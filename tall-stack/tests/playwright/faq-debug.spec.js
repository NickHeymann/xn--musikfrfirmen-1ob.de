import { test, expect } from '@playwright/test';

test.describe('FAQ Accordion Debugging', () => {
  let consoleLogs = [];
  let consoleErrors = [];

  test.beforeEach(async ({ page }) => {
    consoleLogs = [];
    consoleErrors = [];

    page.on('console', (msg) => {
      const text = msg.text();
      consoleLogs.push({ type: msg.type(), text });

      if (msg.type() === 'error') {
        consoleErrors.push(text);
      }
    });

    await page.goto('/');
    
    // Scroll to FAQ section
    await page.locator('#faq').scrollIntoViewIfNeeded();
    await page.waitForTimeout(500);
  });

  test('Phase 1: Component Initialization Check', async ({ page }) => {
    console.log('\n=== PHASE 1: COMPONENT INITIALIZATION ===\n');
    
    // Check Alpine.js
    const alpineExists = await page.evaluate(() => {
      return typeof window.Alpine !== 'undefined';
    });

    console.log('✓ Alpine.js loaded:', alpineExists);
    
    if (!alpineExists) {
      console.error('❌ CRITICAL: Alpine.js not loading');
    }

    const version = await page.evaluate(() => window.Alpine?.version);
    console.log('✓ Alpine.js version:', version);

    expect(alpineExists).toBeTruthy();
  });

  test('Phase 2: FAQ Element Detection', async ({ page }) => {
    console.log('\n=== PHASE 2: FAQ ELEMENT DETECTION ===\n');
    
    const faqItems = page.locator('.faq-item');
    const count = await faqItems.count();

    console.log('✓ FAQ items found:', count);

    if (count === 0) {
      console.error('❌ ISSUE: No FAQ items found');
    }

    expect(count).toBeGreaterThan(0);
  });

  test('Phase 3: Alpine Component State Inspection', async ({ page }) => {
    console.log('\n=== PHASE 3: ALPINE COMPONENT STATE ===\n');

    const state = await page.evaluate(() => {
      const faqContainer = document.querySelector('.faq-container');

      return {
        elementExists: !!faqContainer,
        hasAlpineComponent: !!faqContainer?.__x,
        alpineData: faqContainer?.__x?.$data,
        xDataAttribute: faqContainer?.getAttribute('x-data'),
      };
    });

    console.log('FAQ Container state:', JSON.stringify(state, null, 2));

    if (!state.hasAlpineComponent) {
      console.error('❌ ISSUE: Alpine.js not bound to FAQ container');
      console.log('Possible causes:');
      console.log('  1. x-data attribute missing or malformed');
      console.log('  2. Alpine.start() not called');
      console.log('  3. Syntax error in x-data JSON');
    }

    expect(state.hasAlpineComponent).toBeTruthy();
  });

  test('Phase 4: Event Handler Detection', async ({ page }) => {
    console.log('\n=== PHASE 4: EVENT HANDLER DETECTION ===\n');
    
    const handlers = await page.evaluate(() => {
      const firstButton = document.querySelector('.faq-question');

      return {
        hasClickAttr: firstButton?.hasAttribute('@click') || 
                      firstButton?.hasAttribute('x-on:click'),
        clickContent: firstButton?.getAttribute('@click') || 
                      firstButton?.getAttribute('x-on:click'),
        hasAlpineBinding: !!firstButton?.__x,
      };
    });

    console.log('Event handlers:', JSON.stringify(handlers, null, 2));

    if (!handlers.hasClickAttr) {
      console.error('❌ ISSUE: No click event handler found');
      console.log('Possible causes:');
      console.log('  1. @click directive missing');
      console.log('  2. Alpine.js not processing directives');
    }

    expect(handlers.hasClickAttr).toBeTruthy();
  });

  test('Phase 5: Interaction Test with State Analysis', async ({ page }) => {
    console.log('\n=== PHASE 5: INTERACTION TEST ===\n');
    
    // Expected behavior
    const expected = {
      initialState: { activeIndex: null },
      afterClick: { activeIndex: 0, visible: true },
    };

    // 1. Get initial state
    const initialState = await page.evaluate(() => {
      const faqContainer = document.querySelector('.faq-container');
      const alpineData = faqContainer?.__x?.$data;
      const firstAnswer = document.querySelector('.faq-answer');

      return {
        activeIndex: alpineData?.activeIndex,
        answerVisible: firstAnswer?.style.display !== 'none',
        answerComputedDisplay: window.getComputedStyle(firstAnswer).display,
      };
    });

    console.log('📊 Initial State (Expected):', expected.initialState);
    console.log('📊 Initial State (Actual):', initialState);

    // 2. Click first FAQ button
    const firstButton = page.locator('.faq-question').first();
    await firstButton.click();
    await page.waitForTimeout(500); // Wait for Alpine + x-collapse animation

    // 3. Get state after click
    const afterClickState = await page.evaluate(() => {
      const faqContainer = document.querySelector('.faq-container');
      const alpineData = faqContainer?.__x?.$data;
      const firstAnswer = document.querySelector('.faq-answer');

      return {
        activeIndex: alpineData?.activeIndex,
        answerVisible: firstAnswer?.style.display !== 'none',
        answerComputedDisplay: window.getComputedStyle(firstAnswer).display,
        hasXShow: firstAnswer?.hasAttribute('x-show'),
        xShowValue: firstAnswer?.getAttribute('x-show'),
      };
    });

    console.log('📊 After Click (Expected):', expected.afterClick);
    console.log('📊 After Click (Actual):', afterClickState);

    // 4. Diff Analysis
    console.log('\n🔍 DIFF ANALYSIS:');

    if (initialState.activeIndex !== expected.initialState.activeIndex) {
      console.error('❌ Initial state mismatch!');
      console.log('   Expected activeIndex:', expected.initialState.activeIndex);
      console.log('   Actual activeIndex:', initialState.activeIndex);
    } else {
      console.log('✅ Initial state correct (activeIndex = null)');
    }

    if (afterClickState.activeIndex !== expected.afterClick.activeIndex) {
      console.error('❌ Click state mismatch!');
      console.log('   Expected activeIndex:', expected.afterClick.activeIndex);
      console.log('   Actual activeIndex:', afterClickState.activeIndex);
      console.log('   WHY: Click handler not updating activeIndex');
    } else {
      console.log('✅ activeIndex updated correctly to 0');
    }

    if (!afterClickState.answerVisible) {
      console.error('❌ Visibility issue!');
      console.log('   Answer element style.display:', afterClickState.answerComputedDisplay);
      console.log('   Has x-show:', afterClickState.hasXShow);
      console.log('   x-show value:', afterClickState.xShowValue);
      console.log('   WHY: x-show directive not removing display:none OR x-collapse not working');
    } else {
      console.log('✅ Answer is visible after click');
    }

    // Print console errors if any
    if (consoleErrors.length > 0) {
      console.log('\n⚠️  CONSOLE ERRORS:');
      consoleErrors.forEach(err => console.log('  -', err));
    }

    expect(afterClickState.activeIndex).toBe(0);
    expect(afterClickState.answerVisible).toBe(true);
  });

  test('Phase 6: x-collapse Directive Check', async ({ page }) => {
    console.log('\n=== PHASE 6: X-COLLAPSE DIRECTIVE CHECK ===\n');
    
    const collapseInfo = await page.evaluate(() => {
      const firstAnswer = document.querySelector('.faq-answer');

      return {
        hasXCollapse: firstAnswer?.hasAttribute('x-collapse'),
        hasXShow: firstAnswer?.hasAttribute('x-show'),
        xShowValue: firstAnswer?.getAttribute('x-show'),
        inlineStyle: firstAnswer?.getAttribute('style'),
        computedDisplay: window.getComputedStyle(firstAnswer).display,
      };
    });

    console.log('x-collapse info:', JSON.stringify(collapseInfo, null, 2));

    if (collapseInfo.hasXCollapse && collapseInfo.inlineStyle?.includes('display: none')) {
      console.log('⚠️  WARNING: x-collapse present BUT inline style has display:none');
      console.log('   This may prevent x-collapse from working correctly');
      console.log('   x-collapse manages its own display property');
    }

    expect(collapseInfo.hasXCollapse).toBeTruthy();
    expect(collapseInfo.hasXShow).toBeTruthy();
  });
});
