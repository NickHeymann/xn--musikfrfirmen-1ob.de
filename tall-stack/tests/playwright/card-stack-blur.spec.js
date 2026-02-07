import { test, expect } from '@playwright/test';

/**
 * Card Stack Blur/Darken Animation Tests
 *
 * Rules:
 * 1. A section must NOT blur/darken before the next section starts covering it
 * 2. Once the next section starts covering, blur/darken ramps from zero
 * 3. The ramp must be linear — no sudden jumps
 * 4. Each section's ramp duration is proportional to its own height
 *
 * The production code uses live getBoundingClientRect() on every frame:
 * - coverAmount = (sec.top + sec.height) - next.top
 * - blur starts when coverAmount > 0 (next section overlaps current section)
 */

const BASE = 'http://localhost:8080';

/** Run a full progressive scroll inside the browser (fast, single evaluate) */
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

  test('sections must have zero blur BEFORE the next section starts covering them', async ({ page }) => {
    const data = await progressiveScan(page);
    const errors = [];

    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent) continue;
        const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
        if (!nextSec) continue;

        // Next section hasn't started covering this one yet
        const secBottom = sec.top + sec.height;
        const coverAmount = secBottom - nextSec.top;

        if (coverAmount <= 0) {
          // No overlap — blur should be zero
          if (sec.blur > 0.1) {
            errors.push(`Section ${sec.idx} blur=${sec.blur} at scroll=${entry.scrollY} but no overlap (gap=${-coverAmount}px)`);
          }
          if (sec.darken > 0.01) {
            errors.push(`Section ${sec.idx} darken=${sec.darken} at scroll=${entry.scrollY} but no overlap`);
          }
        }
      }
    }

    if (errors.length > 0) {
      console.log('\n=== BLUR BEFORE COVER ERRORS ===');
      errors.slice(0, 10).forEach(e => console.log('  FAIL:', e));
      if (errors.length > 10) console.log(`  ... and ${errors.length - 10} more`);
    }
    expect(errors, 'Sections must not blur before they are covered').toHaveLength(0);
  });

  test('sections must START blurring once the next section covers them', async ({ page }) => {
    const data = await progressiveScan(page);
    const errors = [];

    // For each content section, find the first scroll point where coverAmount > 50px
    // and verify blur has started
    const sectionBlurChecked = new Set();

    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent || sectionBlurChecked.has(sec.idx)) continue;
        const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
        if (!nextSec) continue;

        const secBottom = sec.top + sec.height;
        const coverAmount = secBottom - nextSec.top;

        // Check 100px into the cover zone
        if (coverAmount >= 100) {
          sectionBlurChecked.add(sec.idx);
          if (sec.blur < 0.1) {
            errors.push(`Section ${sec.idx} blur=${sec.blur} at scroll=${entry.scrollY} with coverAmount=${coverAmount}px — should be blurring`);
          }
        }
      }
    }

    if (errors.length > 0) {
      console.log('\n=== BLUR NOT STARTING ERRORS ===');
      errors.forEach(e => console.log('  FAIL:', e));
    }
    expect(errors, 'Sections must start blurring once covered').toHaveLength(0);
  });

  test('blur ramp must be linear — no sudden jumps', async ({ page }) => {
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
          darken: sec.darken,
        });
      }
    }

    for (const [idx, samples] of Object.entries(sectionSamples)) {
      if (samples.length < 3) continue;

      // Check for non-decreasing blur (allowing tolerance for sticky layout jitter)
      for (let i = 1; i < samples.length; i++) {
        const delta = samples[i].blur - samples[i - 1].blur;
        if (delta < -0.6) {
          errors.push(`Section ${idx}: blur DECREASED from ${samples[i-1].blur} to ${samples[i].blur} at scroll=${samples[i].scrollY}`);
        }
      }

      // Check for sudden jumps (>3x average delta in the middle of the ramp)
      const totalDelta = samples[samples.length - 1].blur - samples[0].blur;
      const avgDelta = totalDelta / (samples.length - 1);
      if (avgDelta > 0.05) {
        for (let i = 2; i < samples.length - 1; i++) {
          const delta = samples[i].blur - samples[i - 1].blur;
          if (delta > avgDelta * 4) {
            errors.push(`Section ${idx}: blur JUMPED by ${delta.toFixed(2)} at scroll=${samples[i].scrollY} (avg=${avgDelta.toFixed(2)})`);
          }
        }
      }

      // Print ramp for inspection
      console.log(`\n  Section ${idx} blur ramp (${samples.length} samples):`);
      const step = Math.max(1, Math.floor(samples.length / 12));
      for (let i = 0; i < samples.length; i += step) {
        const s = samples[i];
        const bar = '█'.repeat(Math.round(s.blur * 5));
        console.log(`    y=${s.scrollY} blur=${s.blur.toFixed(2)} dark=${s.darken.toFixed(3)} ${bar}`);
      }
      if (samples.length > 0) {
        const last = samples[samples.length - 1];
        console.log(`    y=${last.scrollY} blur=${last.blur.toFixed(2)} dark=${last.darken.toFixed(3)} (final)`);
      }
    }

    if (errors.length > 0) {
      console.log('\n=== LINEARITY ERRORS ===');
      errors.forEach(e => console.log('  FAIL:', e));
    }
    expect(errors, 'Blur ramp must be smooth and linear').toHaveLength(0);
  });

  test('no section must blur when not being covered', async ({ page }) => {
    const data = await progressiveScan(page);
    const errors = [];

    for (const entry of data) {
      for (const sec of entry.sections) {
        if (!sec.hasContent) continue;
        const nextSec = entry.sections.find(s => s.idx === sec.idx + 1);
        if (!nextSec) continue;

        // Check: is the next section's top still below the current section's bottom?
        const secBottom = sec.top + sec.height;
        const gap = nextSec.top - secBottom;

        if (gap > 5 && sec.blur > 0.1) {
          errors.push(`Section ${sec.idx} (scroll=${entry.scrollY}): blur=${sec.blur} but next section is ${gap}px below (not covering)`);
        }
        if (gap > 5 && sec.darken > 0.01) {
          errors.push(`Section ${sec.idx} (scroll=${entry.scrollY}): darken=${sec.darken} but next section is ${gap}px below`);
        }
      }
    }

    if (errors.length > 0) {
      console.log('\n=== UNCOVERED BLUR ERRORS ===');
      errors.slice(0, 10).forEach(e => console.log('  FAIL:', e));
      if (errors.length > 10) console.log(`  ... and ${errors.length - 10} more`);
    }
    expect(errors, 'Sections must not blur when not being covered').toHaveLength(0);
  });
});
