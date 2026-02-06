import { test, expect } from '@playwright/test';

test.describe('Event Calculator - LocalStorage Consent', () => {
  let consoleLogs = [];
  let consoleErrors = [];

  test.beforeEach(async ({ page }) => {
    // Capture console output
    consoleLogs = [];
    consoleErrors = [];

    page.on('console', (msg) => {
      const text = msg.text();
      consoleLogs.push({ type: msg.type(), text });

      if (msg.type() === 'error') {
        consoleErrors.push(text);
      }
    });

    // Navigate to page and clear localStorage
    await page.goto('/');
    await page.evaluate(() => localStorage.clear());

    // Open calculator
    const calculatorButton = page.locator('text=Jetzt Angebot einholen').first();
    await calculatorButton.click();
    await page.waitForTimeout(500);
  });

  test('Phone field is optional (no validation)', async ({ page }) => {
    console.log('\n🧪 Testing: Phone field optional');

    // Fill Step 1
    await page.fill('input[placeholder="TT"]', '15');
    await page.locator('input[placeholder="MM"]').first().fill('06');
    await page.fill('input[placeholder="YY"]', '26');
    await page.fill('input[id="mff-city"]', 'Berlin');
    await page.fill('input[id="mff-budget"]', '5000');

    // Open guests dropdown via $wire (wire:click doesn't work in Playwright)
    await page.evaluate(() => {
      const button = document.getElementById('mff-guests');
      const alpineData = button.closest('[wire\\:id]')?.__x?.$data;
      if (alpineData && alpineData.$wire) {
        alpineData.$wire.isGuestsDropdownOpen = true;
      }
    });
    await page.waitForTimeout(300);

    // Click dropdown option
    const dropdownOption = page.locator('div[wire\\:click*="selectGuests"]').filter({ hasText: /^100 - 300$/ });
    await dropdownOption.waitFor({ state: 'visible', timeout: 5000 });
    await dropdownOption.click();
    await page.waitForTimeout(300);

    console.log('  Step 1 completed');

    // Go to Step 2
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Nur DJ' }).click();

    console.log('  Step 2 completed');

    // Go to Step 3
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Fill contact form WITHOUT phone
    await page.fill('input[id="mff-firstname"]', 'Max');
    await page.fill('input[id="mff-company"]', 'TestCorp');
    await page.fill('input[id="mff-email"]', 'max@test.de');
    // DO NOT fill phone field
    await page.check('input[id="mff-privacy"]');

    console.log('  Step 3 completed (no phone)');

    // Try to submit
    const submitButton = page.locator('button').filter({ hasText: /Anfrage.*senden/i });
    await submitButton.click();
    await page.waitForTimeout(1000);

    // Check for validation errors
    const validationErrors = await page.locator('text=/Bitte/i').count();
    console.log('  Validation errors found:', validationErrors);

    // Should submit successfully (no phone required)
    const successVisible = await page.locator('text=/Vielen Dank|Erfolgreich/i').isVisible().catch(() => false);
    console.log('  Success message visible:', successVisible);

    expect(validationErrors).toBe(0);
    console.log('✅ Phone field is optional - no validation errors');
  });

  test('Storage consent checkbox controls localStorage behavior', async ({ page }) => {
    console.log('\n🧪 Testing: Storage consent controls localStorage');

    // Fill Step 1
    await page.fill('input[placeholder="TT"]', '20');
    await page.locator('input[placeholder="MM"]').first().fill('08');
    await page.fill('input[placeholder="YY"]', '26');
    await page.fill('input[id="mff-city"]', 'München');
    await page.fill('input[id="mff-budget"]', '8000');

    await page.evaluate(() => {
      const button = document.getElementById('mff-guests');
      const alpineData = button.closest('[wire\\:id]')?.__x?.$data;
      if (alpineData && alpineData.$wire) {
        alpineData.$wire.isGuestsDropdownOpen = true;
      }
    });
    await page.waitForTimeout(300);
    const option300500 = page.locator('div[wire\\:click*="selectGuests"]').filter({ hasText: /^300 - 500$/ });
    await option300500.waitFor({ state: 'visible', timeout: 5000 });
    await option300500.click();
    await page.waitForTimeout(300);

    console.log('  Data entered on Step 1');

    // Go to Step 2
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Full Band' }).click();

    // Go to Step 3
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Check if consent checkbox exists
    const consentCheckbox = page.locator('input[id="mff-storage-consent"]');
    const consentExists = await consentCheckbox.count();
    console.log('  Storage consent checkbox found:', consentExists > 0 ? '✓' : '✗');

    if (consentExists === 0) {
      console.log('❌ Storage consent checkbox not found!');
      expect(consentExists).toBeGreaterThan(0);
      return;
    }

    // Close modal WITHOUT checking consent
    const closeButton = page.locator('button[aria-label="Close"]').first();
    await closeButton.click();
    await page.waitForTimeout(500);

    // Check localStorage - should be EMPTY (no consent)
    let storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    console.log('  LocalStorage without consent:', storedData === null ? 'Empty ✓' : 'Has data ✗');
    expect(storedData).toBeNull();

    // Reopen modal
    await page.locator('text=Jetzt Angebot einholen').first().click();
    await page.waitForTimeout(500);

    // Data should NOT be restored (no consent)
    const cityValue = await page.inputValue('input[id="mff-city"]');
    console.log('  City restored without consent:', cityValue === '' ? 'Empty ✓' : `Has "${cityValue}" ✗`);
    expect(cityValue).toBe('');

    // Now enter data again and CHECK consent
    await page.fill('input[id="mff-city"]', 'Hamburg');
    await page.fill('input[id="mff-budget"]', '3000');

    // Navigate to Step 3 to find consent checkbox
    await page.evaluate(() => {
      const button = document.getElementById('mff-guests');
      const alpineData = button.closest('[wire\\:id]')?.__x?.$data;
      if (alpineData && alpineData.$wire) {
        alpineData.$wire.isGuestsDropdownOpen = true;
      }
    });
    await page.waitForTimeout(300);
    const optionUnter100 = page.locator('div[wire\\:click*="selectGuests"]').filter({ hasText: /^Unter 100$/ });
    await optionUnter100.waitFor({ state: 'visible', timeout: 5000 });
    await optionUnter100.click();
    await page.waitForTimeout(300);

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Nur DJ' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Check consent checkbox
    await page.check('input[id="mff-storage-consent"]');
    await page.waitForTimeout(200);
    console.log('  ✅ Storage consent checked');

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

    console.log('✅ Storage consent controls localStorage correctly');
  });

  test('Modal always opens on Step 1 with restored data', async ({ page }) => {
    console.log('\n🧪 Testing: Modal always opens on Step 1');

    // Fill data and navigate to Step 3
    await page.fill('input[placeholder="TT"]', '10');
    await page.locator('input[placeholder="MM"]').first().fill('12');
    await page.fill('input[placeholder="YY"]', '26');
    await page.fill('input[id="mff-city"]', 'Frankfurt');

    // Open guests dropdown via $wire (wire:click doesn't work in Playwright)
    await page.evaluate(() => {
      const button = document.getElementById('mff-guests');
      const alpineData = button.closest('[wire\\:id]')?.__x?.$data;
      if (alpineData && alpineData.$wire) {
        alpineData.$wire.isGuestsDropdownOpen = true;
      }
    });
    await page.waitForTimeout(300);

    // Click dropdown option
    const dropdownOption = page.locator('div[wire\\:click*="selectGuests"]').filter({ hasText: /^100 - 300$/ });
    await dropdownOption.waitFor({ state: 'visible', timeout: 5000 });
    await dropdownOption.click();
    await page.waitForTimeout(300);

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Full Band + DJ' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Now on Step 3
    const step3Heading = await page.locator('text=Kontaktdaten').isVisible();
    console.log('  Navigated to Step 3:', step3Heading ? '✓' : '✗');

    // Enable storage consent
    await page.check('input[id="mff-storage-consent"]');
    await page.waitForTimeout(200);

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
    console.log('  Data restored:', restoredCity === 'Frankfurt' ? '✓' : '✗');
    expect(restoredCity).toBe('Frankfurt');

    console.log('✅ Modal opens on Step 1 with data intact');
  });

  test('Dark theme privacy checkboxes are visible', async ({ page }) => {
    console.log('\n🧪 Testing: Dark theme privacy checkboxes');

    // Navigate to Step 3
    await page.fill('input[placeholder="TT"]', '15');
    await page.locator('input[placeholder="MM"]').first().fill('06');
    await page.fill('input[placeholder="YY"]', '26');

    await page.evaluate(() => {
      const button = document.getElementById('mff-guests');
      const alpineData = button.closest('[wire\\:id]')?.__x?.$data;
      if (alpineData && alpineData.$wire) {
        alpineData.$wire.isGuestsDropdownOpen = true;
      }
    });
    await page.waitForTimeout(300);
    const optionUnter100 = page.locator('div[wire\\:click*="selectGuests"]').filter({ hasText: /^Unter 100$/ });
    await optionUnter100.waitFor({ state: 'visible', timeout: 5000 });
    await optionUnter100.click();
    await page.waitForTimeout(300);

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Nur DJ' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Check both checkboxes are visible
    const privacyCheckbox = page.locator('input[id="mff-privacy"]');
    const storageCheckbox = page.locator('input[id="mff-storage-consent"]');

    const privacyVisible = await privacyCheckbox.isVisible();
    const storageVisible = await storageCheckbox.isVisible();

    console.log('  Privacy checkbox visible:', privacyVisible ? '✓' : '✗');
    console.log('  Storage consent visible:', storageVisible ? '✓' : '✗');

    expect(privacyVisible).toBeTruthy();
    expect(storageVisible).toBeTruthy();

    // Check labels exist
    const privacyLabel = await page.locator('label[for="mff-privacy"]').textContent();
    const storageLabel = await page.locator('label[for="mff-storage-consent"]').textContent();

    console.log('  Privacy label found:', privacyLabel?.includes('Datenschutz') ? '✓' : '✗');
    console.log('  Storage label found:', storageLabel?.includes('lokal speichern') ? '✓' : '✗');

    expect(privacyLabel).toContain('Datenschutz');
    expect(storageLabel).toContain('lokal speichern');

    console.log('✅ Dark theme checkboxes are visible and labeled');
  });

  test('Console errors check', async ({ page }) => {
    console.log('\n🧪 Checking for Console Errors');

    // Navigate through all steps
    await page.fill('input[placeholder="TT"]', '15');
    await page.locator('input[placeholder="MM"]').first().fill('06');
    await page.fill('input[placeholder="YY"]', '26');
    await page.fill('input[id="mff-city"]', 'Berlin');

    // Open guests dropdown via $wire (wire:click doesn't work in Playwright)
    await page.evaluate(() => {
      const button = document.getElementById('mff-guests');
      const alpineData = button.closest('[wire\\:id]')?.__x?.$data;
      if (alpineData && alpineData.$wire) {
        alpineData.$wire.isGuestsDropdownOpen = true;
      }
    });
    await page.waitForTimeout(300);

    // Click dropdown option
    const dropdownOption = page.locator('div[wire\\:click*="selectGuests"]').filter({ hasText: /^100 - 300$/ });
    await dropdownOption.waitFor({ state: 'visible', timeout: 5000 });
    await dropdownOption.click();
    await page.waitForTimeout(300);

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

    if (consoleErrors.length > 0) {
      console.log('❌ Console errors found:');
      consoleErrors.forEach(error => console.log('  -', error));
    } else {
      console.log('✅ No console errors');
    }

    expect(consoleErrors.length).toBe(0);
  });
});
