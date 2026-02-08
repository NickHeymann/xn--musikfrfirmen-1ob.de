import { test, expect } from '@playwright/test';

/**
 * Card Stack Blur/Darken Animation Tests
 *
 * Rules:
 * 1. Blur starts when the NEXT section's top is within 200px of its stickyTop
 *    (i.e. rectTop < stickyTop + 200)
 * 2. Blur ramps from 0 to max(6px) with an ease-in curve over that 200px
 * 3. No blur when the next section is more than 200px away from pinning
 * 4. FAQ (last card) must never blur — it has no next card-index section
 * 5. Footer must NOT participate in the card stack (no data-card-index)
 */

const BASE = 'http://localhost:8080';
const TRAVEL = 200; // blur travel window in px

/** Run a full progressive scroll inside the browser */
async function progressiveScan(page) {
  return page.evaluate(async () => {
    const secs = Array.from(document.querySelectorAll('[data-card-index]'))
      .sort((a, b) => parseInt(a.dataset.cardIndex) - parseInt(b.dataset.cardIndex));

    const data = [];
    let currentPos = 0;
    const maxIter = 600;

    for (let iter = 0; iter < maxIter; iter++) {
      window.scrollTo(0, currentPos);
      await new Promise(r => requestAnimationFrame(() => requestAnimationFrame(() => setTimeout(r, 10))));

      const scrollY = Math.round(window.scrollY);
      const maxScroll = document.documentElement.scrollHeight - window.innerHeight;

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
        });
      }
      data.push(entry);

      currentPos = scrollY + 25;
      if (currentPos > maxScroll + 50) break;
    }
    return data;
  });
}

test.describe('Card Stack Blur Animation', () => {

  test.beforeEach(async ({ page }) => {
    await page.goto(BASE, { waitUntil: 'networkidle' });
    await page.waitForTimeout(500);
  });

  test('no blur when next section is far from header (> 200px above stickyTop)', async ({ page }) => {
    const data = await progressiveScan(page);
    const errors = [];

    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent) continue;
        const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
        if (!nextSec) continue;

        // Next section is far from pinning — more than 200px above stickyTop
        const nextFarAway = nextSec.top > nextSec.stickyTop + TRAVEL + 10;

        if (nextFarAway && sec.blur > 0.1) {
          errors.push(`Section ${sec.idx} blur=${sec.blur} at scroll=${entry.scrollY} but next section at ${nextSec.top}px (far from header)`);
        }
      }
    }

    if (errors.length > 0) {
      console.log('\n=== PREMATURE BLUR ERRORS ===');
      errors.slice(0, 10).forEach(e => console.log('  FAIL:', e));
    }
    expect(errors, 'No blur when next section is far from header').toHaveLength(0);
  });

  test('blur starts when next section approaches header (within 200px)', async ({ page }) => {
    const data = await progressiveScan(page);

    // For each section with content, find a scroll frame where the next
    // section is within the 200px travel zone and check blur has started
    const found = {};
    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent || found[sec.idx]) continue;
        const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
        if (!nextSec) continue;

        // Next section is in the blur zone: between stickyTop and stickyTop+200
        // Check when it's past the midpoint (100px) to ensure blur has ramped
        const inBlurZone = nextSec.top > nextSec.stickyTop &&
                          nextSec.top < nextSec.stickyTop + TRAVEL * 0.5;

        if (inBlurZone && sec.blur > 0.1) {
          found[sec.idx] = { scrollY: entry.scrollY, blur: sec.blur, nextTop: nextSec.top };
        }
      }
    }

    console.log('\n  Blur zone detections:', JSON.stringify(found, null, 2));

    // At least some sections should show blur during the travel zone
    const sectionsWithContent = new Set();
    for (const entry of data) {
      for (const sec of entry.sections) {
        if (sec.hasContent) {
          const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
          if (nextSec) sectionsWithContent.add(sec.idx);
        }
      }
    }

    const foundCount = Object.keys(found).length;
    expect(foundCount, `Expected blur in travel zone for some sections (found ${foundCount}/${sectionsWithContent.size})`).toBeGreaterThan(0);
  });

  test('blur ramp must be smooth — no sudden jumps', async ({ page }) => {
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
        const bar = '█'.repeat(Math.round(s.blur * 5));
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
