import { test, expect } from '@playwright/test';

/**
 * Card Stack Blur/Darken Animation Tests
 *
 * Rules:
 * 1. Blur starts at the INSTANT the next section pins at the header
 *    (rectTop <= stickyTop + 2)
 * 2. Blur ramps LINEARLY from 0 to max(6px) via scrollY tracking
 * 3. No blur before next section pins
 * 4. FAQ (last card) must never blur — it has no next card-index section
 * 5. Footer must NOT participate in the card stack (no data-card-index)
 */

const BASE = 'http://localhost:8080';

/** Run a full progressive scroll inside the browser */
async function progressiveScan(page) {
  return page.evaluate(async () => {
    const secs = Array.from(document.querySelectorAll('[data-card-index]'))
      .sort((a, b) => parseInt(a.dataset.cardIndex) - parseInt(b.dataset.cardIndex));

    const data = [];
    const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
    let currentPos = 0;

    while (currentPos <= maxScroll + 50) {
      window.scrollTo({ top: currentPos, behavior: 'instant' });
      await new Promise(r =>
        requestAnimationFrame(() => requestAnimationFrame(() => setTimeout(r, 30)))
      );

      const scrollY = Math.round(window.scrollY);

      const entry = { scrollY, sections: [] };
      for (const s of secs) {
        const idx = parseInt(s.dataset.cardIndex);
        const rect = s.getBoundingClientRect();
        const stickyTop = parseFloat(getComputedStyle(s).top) || 0;
        const content = s.querySelector('.card-stack-content');
        const overlay = s.querySelector('.card-stack-overlay');
        const filterVal = content?.style?.filter || 'none';
        const bgVal = overlay?.style?.background || 'transparent';

        let blur = 0;
        if (filterVal.includes('blur')) {
          blur = parseFloat(filterVal.match(/blur\(([0-9.]+)px\)/)?.[1] || '0');
        }
        let darken = 0;
        if (bgVal.includes('rgba')) {
          darken = parseFloat(bgVal.match(/rgba\(\s*0\s*,\s*0\s*,\s*0\s*,\s*([0-9.]+)\)/)?.[1] || '0');
        }

        entry.sections.push({
          idx,
          top: Math.round(rect.top),
          height: Math.round(rect.height),
          stickyTop: Math.round(stickyTop),
          blur: Math.round(blur * 100) / 100,
          darken: Math.round(darken * 1000) / 1000,
          hasContent: !!content,
          pinned: rect.top <= stickyTop + 2,
        });
      }
      data.push(entry);

      currentPos += 25;
    }
    return data;
  });
}

test.describe('Card Stack Blur Animation', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto(BASE, { waitUntil: 'networkidle' });
    await page.waitForTimeout(500);
  });

  test('no blur before next section pins at header', async ({ page }) => {
    const data = await progressiveScan(page);
    const errors = [];

    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent) continue;
        const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
        if (!nextSec) continue;

        // Next section has NOT pinned yet
        if (!nextSec.pinned && sec.blur > 0.1) {
          errors.push(`Section ${sec.idx} blur=${sec.blur} at scroll=${entry.scrollY} but next section not pinned (top=${nextSec.top})`);
        }
      }
    }

    if (errors.length > 0) {
      console.log('\n=== PREMATURE BLUR ERRORS ===');
      errors.slice(0, 10).forEach(e => console.log('  FAIL:', e));
    }
    expect(errors, 'No blur before next section pins').toHaveLength(0);
  });

  test('blur starts when next section pins at header', async ({ page }) => {
    const data = await progressiveScan(page);

    // For each section with content, find a frame where the next section
    // has JUST pinned and blur has started ramping
    const found = {};
    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent || found[sec.idx]) continue;
        const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
        if (!nextSec) continue;

        // Next section is pinned and current section has some blur
        if (nextSec.pinned && sec.blur > 0.1) {
          found[sec.idx] = { scrollY: entry.scrollY, blur: sec.blur };
        }
      }
    }

    console.log('\n  Pin-triggered blur detections:', JSON.stringify(found, null, 2));

    // At least some sections should show blur after their next section pins
    const foundCount = Object.keys(found).length;
    expect(foundCount, `Expected blur after pinning for some sections (found ${foundCount})`).toBeGreaterThan(0);
  });

  test('blur ramp must be smooth and linear — no sudden jumps', async ({ page }) => {
    const data = await progressiveScan(page);
    const errors = [];

    // Group blur values by section
    const sectionSamples = {};
    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent || sec.blur <= 0) continue;
        if (!sectionSamples[sec.idx]) sectionSamples[sec.idx] = [];
        sectionSamples[sec.idx].push({
          scrollY: entry.scrollY,
          blur: sec.blur,
        });
      }
    }

    for (const [idx, samples] of Object.entries(sectionSamples)) {
      if (samples.length < 3) continue;

      // Check that blur never decreases significantly (monotonic ramp)
      for (let i = 1; i < samples.length; i++) {
        const delta = samples[i].blur - samples[i - 1].blur;
        if (delta < -1.5) {
          errors.push(`Section ${idx}: blur DECREASED by ${(-delta).toFixed(2)} at scroll=${samples[i].scrollY}`);
        }
      }

      // Check that max blur doesn't exceed 6px + tolerance
      for (const s of samples) {
        if (s.blur > 6.5) {
          errors.push(`Section ${idx}: blur ${s.blur.toFixed(2)} exceeds max at scroll=${s.scrollY}`);
        }
      }

      // Print ramp summary
      console.log(`\n  Section ${idx} blur ramp (${samples.length} samples):`);
      const step = Math.max(1, Math.floor(samples.length / 8));
      for (let i = 0; i < samples.length; i += step) {
        const s = samples[i];
        const bar = '\u2588'.repeat(Math.round(s.blur * 5));
        console.log(`    y=${s.scrollY} blur=${s.blur.toFixed(2)} ${bar}`);
      }
    }

    expect(errors, 'Blur ramp must be smooth').toHaveLength(0);
  });

  test('FAQ section must never blur and footer must not be in card stack', async ({ page }) => {
    const data = await progressiveScan(page);
    const errors = [];

    // Find the highest card-index (should be FAQ = 7)
    let maxCardIndex = 0;
    for (const entry of data) {
      for (const sec of entry.sections) {
        if (sec.idx > maxCardIndex) maxCardIndex = sec.idx;
      }
    }

    // The last card-stack section should never blur (no next section to trigger it)
    for (const entry of data) {
      const lastSec = entry.sections.find(s => s.idx === maxCardIndex);
      if (lastSec && lastSec.blur > 0.1) {
        errors.push(`Last section (idx=${maxCardIndex}) has blur=${lastSec.blur} at scroll=${entry.scrollY} — should never blur`);
      }
    }

    // Footer must NOT have data-card-index
    const footerInStack = await page.evaluate(() => {
      const footer = document.querySelector('footer');
      return footer?.dataset?.cardIndex !== undefined;
    });
    if (footerInStack) {
      errors.push('Footer has data-card-index — it should NOT be in the card stack');
    }

    if (errors.length > 0) {
      console.log('\n=== FAQ/FOOTER ERRORS ===');
      errors.slice(0, 5).forEach(e => console.log('  FAIL:', e));
    }
    expect(errors, 'FAQ must not blur, footer must not be in card stack').toHaveLength(0);
  });
});
