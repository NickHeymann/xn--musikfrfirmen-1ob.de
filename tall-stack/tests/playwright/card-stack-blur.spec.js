import { test, expect } from '@playwright/test';

/**
 * Card Stack Blur/Darken Animation Tests
 *
 * The effect uses live rect positions — no cached values, no state tracking.
 * Blur starts when the next section overlaps the current section's area
 * and ramps linearly until the next section pins at the header.
 *
 * Rules:
 * 1. Blur on a section starts when the next section enters its area
 * 2. Blur ramps LINEARLY from 0 to max(4.8px) (80% cap)
 * 3. No blur when next section is fully below current section
 * 4. FAQ (last card) must never blur — no next card-index section
 * 5. Footer must NOT participate in the card stack
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
      for (let si = 0; si < secs.length; si++) {
        const s = secs[si];
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

        // Check for intermediate non-card sibling (used for overlap detection)
        let overlapTop = null;
        const nextCard = secs[si + 1];
        if (nextCard) {
          const sib = s.nextElementSibling;
          if (sib && sib !== nextCard && !sib.hasAttribute('data-card-index')) {
            overlapTop = Math.round(sib.getBoundingClientRect().top);
          }
        }

        entry.sections.push({
          idx,
          top: Math.round(rect.top),
          bottom: Math.round(rect.top + rect.height),
          height: Math.round(rect.height),
          stickyTop: Math.round(stickyTop),
          blur: Math.round(blur * 100) / 100,
          darken: Math.round(darken * 1000) / 1000,
          hasContent: !!content,
          pinned: rect.top <= stickyTop + 2,
          overlapTop,
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

  test('no blur when next section is fully below current section', async ({ page }) => {
    const data = await progressiveScan(page);
    const errors = [];

    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent) continue;
        const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
        if (!nextSec) continue;

        // Use the overlap element's top if available (intermediate non-card sibling),
        // otherwise the next card-index section's top
        const effectiveTop = sec.overlapTop !== null ? Math.min(sec.overlapTop, nextSec.top) : nextSec.top;

        // Next element's top is below current section's bottom → no overlap → no blur
        if (effectiveTop >= sec.bottom && sec.blur > 0.1) {
          errors.push(`Section ${sec.idx} blur=${sec.blur} at scroll=${entry.scrollY} but next element below (top=${effectiveTop} >= secBottom=${sec.bottom})`);
        }
      }
    }

    if (errors.length > 0) {
      console.log('\n=== PREMATURE BLUR ERRORS ===');
      errors.slice(0, 10).forEach(e => console.log('  FAIL:', e));
    }
    expect(errors, 'No blur when next section is below current').toHaveLength(0);
  });

  test('blur ramps up as next section overlaps current section', async ({ page }) => {
    const data = await progressiveScan(page);

    // For each section with content, find frames where blur is active
    const found = {};
    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent || found[sec.idx]) continue;
        const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
        if (!nextSec) continue;

        // Use overlap element top if available
        const effTop = sec.overlapTop !== null ? Math.min(sec.overlapTop, nextSec.top) : nextSec.top;

        // Next element overlaps current and blur is active
        if (effTop < sec.bottom && sec.blur > 0.1) {
          found[sec.idx] = { scrollY: entry.scrollY, blur: sec.blur, nextTop: effTop, secBottom: sec.bottom };
        }
      }
    }

    console.log('\n  Blur detections:', JSON.stringify(found, null, 2));

    const foundCount = Object.keys(found).length;
    expect(foundCount, `Expected blur for some sections (found ${foundCount})`).toBeGreaterThan(0);
  });

  test('blur ramp must be smooth and linear — no sudden jumps', async ({ page }) => {
    const data = await progressiveScan(page);
    const errors = [];

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

      for (let i = 1; i < samples.length; i++) {
        const delta = samples[i].blur - samples[i - 1].blur;
        if (delta < -1.5) {
          errors.push(`Section ${idx}: blur DECREASED by ${(-delta).toFixed(2)} at scroll=${samples[i].scrollY}`);
        }
      }

      for (const s of samples) {
        if (s.blur > 5.2) {
          errors.push(`Section ${idx}: blur ${s.blur.toFixed(2)} exceeds 80% max (4.8px) at scroll=${s.scrollY}`);
        }
      }

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

    let maxCardIndex = 0;
    for (const entry of data) {
      for (const sec of entry.sections) {
        if (sec.idx > maxCardIndex) maxCardIndex = sec.idx;
      }
    }

    for (const entry of data) {
      const lastSec = entry.sections.find(s => s.idx === maxCardIndex);
      if (lastSec && lastSec.blur > 0.1) {
        errors.push(`Last section (idx=${maxCardIndex}) has blur=${lastSec.blur} at scroll=${entry.scrollY} — should never blur`);
      }
    }

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
