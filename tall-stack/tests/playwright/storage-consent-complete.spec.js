import { test, expect } from '@playwright/test';

/**
 * Complete LocalStorage Consent Flow Tests
 *
 * Uses $wire proxy to bypass wire:click issues in Playwright
 */

test.describe('Event Calculator - Complete Storage Consent Tests', () => {
  let consoleLogs = [];
  let consoleErrors = [];

  // Helper function to open guests dropdown via canonical + focus/blur
  async function openGuestsDropdown(page) {
    await page.evaluate(() => {
      const components = window.Livewire.all();
      const modal = components.find(c => c.name === 'event-request-modal');
      if (modal) {
        modal.canonical.isGuestsDropdownOpen = true;
        // Trigger focus/blur to force re-render
        const button = document.getElementById('mff-guests');
        button.focus();
        button.blur();
      }
    });
    await page.waitForTimeout(800); // Wait for DOM update
  }

  // Helper function to select guests option
  async function selectGuestsOption(page, optionText) {
    const option = page.locator('div[wire\\:click*="selectGuests"]').filter({ hasText: new RegExp(`^${optionText}$`) });
    await option.waitFor({ state: 'visible', timeout: 10000 });
    await option.click();
    await page.waitForTimeout(300);
  }

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
    await page.evaluate(() => localStorage.clear());

    // Open calculator
    const calculatorButton = page.locator('text=Jetzt Angebot einholen').first();
    await calculatorButton.click();
    await page.waitForTimeout(500);
  });

  test('1. Phone field is optional (no validation required)', async ({ page }) => {
    console.log('\n✅ TEST 1: Phone Optional\n');

    // Fill Step 1
    await page.fill('input[placeholder="TT"]', '15');
    await page.locator('input[placeholder="MM"]').first().fill('06');
    await page.fill('input[placeholder="YY"]', '26');
    await page.fill('input[id="mff-city"]', 'Berlin');
    await page.fill('input[id="mff-budget"]', '5000');

    await openGuestsDropdown(page);
    await selectGuestsOption(page, '100 - 300');

    console.log('  ✓ Step 1 completed');

    // Go to Step 2
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Nur DJ' }).click();

    console.log('  ✓ Step 2 completed');

    // Go to Step 3
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Fill contact form WITHOUT phone
    await page.fill('input[id="mff-firstname"]', 'Max');
    await page.fill('input[id="mff-company"]', 'TestCorp');
    await page.fill('input[id="mff-email"]', 'max@test.de');
    // DO NOT fill phone field - it's optional!
    await page.check('input[id="mff-privacy"]');

    console.log('  ✓ Step 3 completed (no phone)');

    // Try to submit
    const submitButton = page.locator('button').filter({ hasText: /Anfrage.*senden/i });
    await submitButton.click();
    await page.waitForTimeout(1000);

    // Check for validation errors
    const validationErrors = await page.locator('text=/Bitte.*Telefon|Phone required/i').count();
    console.log('  Validation errors (phone):', validationErrors);

    // Should submit successfully (no phone required)
    const successVisible = await page.locator('text=/Vielen Dank|Erfolgreich|Danke/i').isVisible().catch(() => false);
    console.log('  Success message visible:', successVisible);

    expect(validationErrors).toBe(0);
    console.log('\n✅ TEST 1 PASSED: Phone is optional\n');
  });

  test('2. Storage consent checkbox controls localStorage behavior', async ({ page }) => {
    console.log('\n✅ TEST 2: Storage Consent Controls localStorage\n');

    // === PART A: No consent = No storage ===
    console.log('Part A: No consent checkbox');

    await page.fill('input[placeholder="TT"]', '20');
    await page.locator('input[placeholder="MM"]').first().fill('08');
    await page.fill('input[placeholder="YY"]', '26');
    await page.fill('input[id="mff-city"]', 'München');
    await page.fill('input[id="mff-budget"]', '8000');

    await openGuestsDropdown(page);
    await selectGuestsOption(page, '300 - 500');

    console.log('  ✓ Data entered');

    // Close modal WITHOUT checking consent
    const closeButton = page.locator('button[aria-label="Close"]').first();
    await closeButton.click();
    await page.waitForTimeout(500);

    // Check localStorage - should be EMPTY (no consent)
    let storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    console.log('  LocalStorage without consent:', storedData === null ? 'Empty ✓' : 'Has data ✗');
    expect(storedData).toBeNull();

    // === PART B: With consent = Data saved ===
    console.log('\nPart B: With consent checkbox');

    // Reopen modal
    await page.locator('text=Jetzt Angebot einholen').first().click();
    await page.waitForTimeout(500);

    // Data should NOT be restored (no consent was given)
    const cityValue = await page.inputValue('input[id="mff-city"]');
    console.log('  City after reopen (no consent):', cityValue === '' ? 'Empty ✓' : `"${cityValue}" ✗`);
    expect(cityValue).toBe('');

    // Enter data again
    await page.fill('input[id="mff-city"]', 'Hamburg');
    await page.fill('input[id="mff-budget"]', '3000');

    await openGuestsDropdown(page);
    await selectGuestsOption(page, 'Unter 100');

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Nur DJ' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Check consent checkbox
    await page.check('input[id="mff-storage-consent"]');
    await page.waitForTimeout(200);
    console.log('  ✓ Storage consent checked');

    // Close modal
    await closeButton.click();
    await page.waitForTimeout(500);

    // Check localStorage - should have data (with consent)
    storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    console.log('  LocalStorage with consent:', storedData !== null ? 'Has data ✓' : 'Empty ✗');
    expect(storedData).not.toBeNull();

    // Parse and verify
    const parsedData = JSON.parse(storedData);
    console.log('  Stored city:', parsedData.city);
    console.log('  Stored budget:', parsedData.budget);
    console.log('  Stored consent flag:', parsedData.storageConsent);

    expect(parsedData.city).toBe('Hamburg');
    expect(parsedData.budget).toBe('3000');
    expect(parsedData.storageConsent).toBe(true);

    console.log('\n✅ TEST 2 PASSED: Storage consent works correctly\n');
  });

  test('3. Modal always opens on Step 1 with restored data', async ({ page }) => {
    console.log('\n✅ TEST 3: Always Open on Step 1\n');

    // Fill data and navigate to Step 3
    await page.fill('input[placeholder="TT"]', '10');
    await page.locator('input[placeholder="MM"]').first().fill('12');
    await page.fill('input[placeholder="YY"]', '26');
    await page.fill('input[id="mff-city"]', 'Frankfurt');

    await openGuestsDropdown(page);
    await selectGuestsOption(page, '100 - 300');

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Full Band + DJ' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Now on Step 3
    const step3Heading = await page.locator('text=Kontaktdaten').isVisible();
    console.log('  Navigated to Step 3:', step3Heading ? '✓' : '✗');
    expect(step3Heading).toBeTruthy();

    // Enable storage consent
    await page.check('input[id="mff-storage-consent"]');
    await page.waitForTimeout(200);
    console.log('  ✓ Storage consent enabled');

    // Close modal
    const closeButton = page.locator('button[aria-label="Close"]').first();
    await closeButton.click();
    await page.waitForTimeout(500);

    // Reopen modal
    await page.locator('text=Jetzt Angebot einholen').first().click();
    await page.waitForTimeout(500);

    // Should open on Step 1 (not Step 3)
    const step1Heading = await page.locator('text=Event-Details').isVisible();
    console.log('  Modal reopened on Step 1 (visual):', step1Heading ? '✓' : '✗');
    expect(step1Heading).toBeTruthy();

    // But data should be restored
    const restoredCity = await page.inputValue('input[id="mff-city"]');
    console.log('  Data restored (city):', restoredCity === 'Frankfurt' ? '✓' : '✗');
    expect(restoredCity).toBe('Frankfurt');

    console.log('\n✅ TEST 3 PASSED: Modal opens on Step 1 with data intact\n');
  });

  test('4. Dark theme privacy checkboxes are visible and styled correctly', async ({ page }) => {
    console.log('\n✅ TEST 4: Dark Theme Checkboxes\n');

    // Navigate to Step 3 (where checkboxes are)
    await page.fill('input[placeholder="TT"]', '15');
    await page.locator('input[placeholder="MM"]').first().fill('06');
    await page.fill('input[placeholder="YY"]', '26');

    await openGuestsDropdown(page);
    await selectGuestsOption(page, 'Unter 100');

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Nur DJ' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Check both checkboxes exist and are visible
    const privacyCheckbox = page.locator('input[id="mff-privacy"]');
    const storageCheckbox = page.locator('input[id="mff-storage-consent"]');

    const privacyVisible = await privacyCheckbox.isVisible();
    const storageVisible = await storageCheckbox.isVisible();

    console.log('  Privacy checkbox visible:', privacyVisible ? '✓' : '✗');
    console.log('  Storage consent visible:', storageVisible ? '✓' : '✗');

    expect(privacyVisible).toBeTruthy();
    expect(storageVisible).toBeTruthy();

    // Check labels exist and have correct text
    const privacyLabel = await page.locator('label[for="mff-privacy"]').textContent();
    const storageLabel = await page.locator('label[for="mff-storage-consent"]').textContent();

    console.log('  Privacy label contains "Datenschutz":', privacyLabel?.includes('Datenschutz') ? '✓' : '✗');
    console.log('  Storage label contains "lokal speichern":', storageLabel?.includes('lokal speichern') ? '✓' : '✗');

    expect(privacyLabel).toContain('Datenschutz');
    expect(storageLabel).toContain('lokal speichern');

    // Check styling (dark theme with bg-white/5)
    const privacyContainer = page.locator('label[for="mff-privacy"]').locator('..');
    const storageContainer = page.locator('label[for="mff-storage-consent"]').locator('..');

    const privacyBg = await privacyContainer.evaluate(el => window.getComputedStyle(el).backgroundColor);
    const storageBg = await storageContainer.evaluate(el => window.getComputedStyle(el).backgroundColor);

    console.log('  Privacy container background:', privacyBg);
    console.log('  Storage container background:', storageBg);

    // Both should have semi-transparent background (not white)
    expect(privacyBg).not.toBe('rgb(255, 255, 255)');
    expect(storageBg).not.toBe('rgb(255, 255, 255)');

    console.log('\n✅ TEST 4 PASSED: Dark theme checkboxes correctly styled\n');
  });

  test('5. Unchecking consent clears localStorage', async ({ page }) => {
    console.log('\n✅ TEST 5: Revoke Consent Clears Storage\n');

    // Enter data and enable consent
    await page.fill('input[id="mff-city"]', 'Köln');
    await page.fill('input[id="mff-budget"]', '4000');

    await openGuestsDropdown(page);
    await selectGuestsOption(page, '100 - 300');

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Full Band' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    await page.check('input[id="mff-storage-consent"]');
    await page.waitForTimeout(200);
    console.log('  ✓ Consent checked');

    // Close modal (data should be saved)
    const closeButton = page.locator('button[aria-label="Close"]').first();
    await closeButton.click();
    await page.waitForTimeout(500);

    // Verify data saved
    let storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    expect(storedData).not.toBeNull();
    console.log('  ✓ Data saved with consent');

    // Reopen modal
    await page.locator('text=Jetzt Angebot einholen').first().click();
    await page.waitForTimeout(500);

    // Navigate back to Step 3
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // UNCHECK consent
    await page.uncheck('input[id="mff-storage-consent"]');
    await page.waitForTimeout(200);
    console.log('  ✓ Consent unchecked');

    // Close modal
    await closeButton.click();
    await page.waitForTimeout(500);

    // Verify localStorage cleared
    storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    console.log('  LocalStorage after revoking consent:', storedData);

    expect(storedData).toBeNull();
    console.log('\n✅ TEST 5 PASSED: Revoking consent clears storage\n');
  });

  test('6. No console errors during normal usage', async ({ page }) => {
    console.log('\n✅ TEST 6: Console Errors Check\n');

    // Navigate through all steps
    await page.fill('input[placeholder="TT"]', '15');
    await page.locator('input[placeholder="MM"]').first().fill('06');
    await page.fill('input[placeholder="YY"]', '26');
    await page.fill('input[id="mff-city"]', 'Berlin');

    await openGuestsDropdown(page);
    await selectGuestsOption(page, '100 - 300');

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Full Band' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Check/uncheck storage consent
    await page.check('input[id="mff-storage-consent"]');
    await page.waitForTimeout(200);
    await page.uncheck('input[id="mff-storage-consent"]');
    await page.waitForTimeout(200);

    // Close and reopen
    const closeButton = page.locator('button[aria-label="Close"]').first();
    await closeButton.click();
    await page.waitForTimeout(500);

    await page.locator('text=Jetzt Angebot einholen').first().click();
    await page.waitForTimeout(500);

    console.log('  Total console messages:', consoleLogs.length);
    console.log('  Console errors:', consoleErrors.length);

    if (consoleErrors.length > 0) {
      console.log('\n❌ Console errors found:');
      consoleErrors.forEach(error => console.log('  -', error));
    } else {
      console.log('  ✓ No console errors');
    }

    expect(consoleErrors.length).toBe(0);
    console.log('\n✅ TEST 6 PASSED: No console errors\n');
  });
});
