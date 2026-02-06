import { test, expect } from '@playwright/test';

test.describe('Event Calculator - LocalStorage Consent Flow', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
    // Clear localStorage before each test
    await page.evaluate(() => localStorage.clear());
  });

  test('should NOT save data without consent checkbox checked', async ({ page }) => {
    console.log('\n🧪 Testing: Data NOT saved without consent');

    // Open calculator
    const openButton = page.locator('text=Jetzt Angebot einholen').first();
    await openButton.click();
    await page.waitForTimeout(500);

    // Enter data on Step 1
    await page.fill('input[placeholder="TT"]', '15');
    await page.locator('input[placeholder="MM"]').first().fill('06');
    await page.fill('input[placeholder="YY"]', '26');
    await page.fill('input[id="mff-city"]', 'Berlin');
    await page.fill('input[id="mff-budget"]', '5000');

    // Click guests dropdown and select option
    await page.locator('button#mff-guests').click();
    await page.waitForTimeout(200);
    await page.locator('button').filter({ hasText: '100 - 300' }).click();

    console.log('  Step 1 data entered');

    // Close modal (without consent)
    const closeButton = page.locator('button[aria-label="Close"]').first();
    await closeButton.click();
    await page.waitForTimeout(500);

    // Check localStorage - should be EMPTY
    const storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    console.log('  LocalStorage after close (no consent):', storedData);

    expect(storedData).toBeNull();
    console.log('✅ Data NOT saved without consent');
  });

  test('should save and restore data WITH consent checkbox checked', async ({ page }) => {
    console.log('\n🧪 Testing: Data saved and restored WITH consent');

    // Open calculator
    const openButton = page.locator('text=Jetzt Angebot einholen').first();
    await openButton.click();
    await page.waitForTimeout(500);

    // Enter data on Step 1
    await page.fill('input[placeholder="TT"]', '20');
    await page.locator('input[placeholder="MM"]').first().fill('08');
    await page.fill('input[placeholder="YY"]', '26');
    await page.locator('#mff-city').fill('München');
    await page.locator('#mff-budget').fill('8000');

    // Click guests dropdown and select option
    await page.locator('button#mff-guests').click();
    await page.waitForTimeout(200);
    await page.locator('button').filter({ hasText: '300 - 500' }).click();

    console.log('  Step 1 data entered');

    // Go to Step 2 and select package
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Full Band' }).click();

    console.log('  Step 2: Package selected');

    // Go to Step 3 and enter contact data
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('#mff-firstname').fill('Max');
    await page.locator('#mff-company').fill('Testfirma GmbH');
    await page.locator('#mff-email').fill('max@test.de');

    console.log('  Step 3: Contact data entered');

    // ✅ CHECK THE CONSENT CHECKBOX
    await page.locator('#mff-storage-consent').check();
    await page.waitForTimeout(200);

    console.log('  ✅ Storage consent checkbox checked');

    // Close modal
    const closeButton = page.locator('button[aria-label="Close"]').first();
    await closeButton.click();
    await page.waitForTimeout(500);

    // Check localStorage - should contain data
    const storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    console.log('  LocalStorage after close (with consent):', storedData ? 'Data saved ✓' : 'Empty ✗');

    expect(storedData).not.toBeNull();

    // Parse and verify stored data
    const parsedData = JSON.parse(storedData);
    expect(parsedData.city).toBe('München');
    expect(parsedData.budget).toBe('8000');
    expect(parsedData.guests).toBe('300-500');
    expect(parsedData.package).toBe('band');
    expect(parsedData.firstname).toBe('Max');
    expect(parsedData.company).toBe('Testfirma GmbH');
    expect(parsedData.email).toBe('max@test.de');
    expect(parsedData.storageConsent).toBe(true);

    console.log('  ✅ All data fields saved correctly');

    // Reopen modal
    await openButton.click();
    await page.waitForTimeout(500);

    // Verify modal opens on Step 1 (user reassurance)
    const step1Visible = await page.locator('text=Event-Details').isVisible();
    console.log('  Modal reopened on Step 1:', step1Visible ? '✓' : '✗');
    expect(step1Visible).toBeTruthy();

    // Verify data was restored
    const restoredCity = await page.locator('#mff-city').inputValue();
    const restoredBudget = await page.locator('#mff-budget').inputValue();
    const restoredFirstname = await page.locator('#mff-firstname').inputValue();
    const consentChecked = await page.locator('#mff-storage-consent').isChecked();

    console.log('  Restored data:');
    console.log('    City:', restoredCity);
    console.log('    Budget:', restoredBudget);
    console.log('    Firstname:', restoredFirstname);
    console.log('    Consent:', consentChecked);

    expect(restoredCity).toBe('München');
    expect(restoredBudget).toBe('8000');
    expect(restoredFirstname).toBe('Max');
    expect(consentChecked).toBeTruthy();

    console.log('✅ Data saved, restored, and modal opened on Step 1');
  });

  test('should clear storage when consent is unchecked', async ({ page }) => {
    console.log('\n🧪 Testing: Unchecking consent clears storage');

    // Open calculator
    const openButton = page.locator('text=Jetzt Angebot einholen').first();
    await openButton.click();
    await page.waitForTimeout(500);

    // Enter some data
    await page.locator('#mff-city').fill('Hamburg');
    await page.locator('#mff-budget').fill('3000');

    // Check consent
    await page.locator('#mff-storage-consent').check();
    await page.waitForTimeout(200);

    // Close modal (data should be saved)
    const closeButton = page.locator('button[aria-label="Close"]').first();
    await closeButton.click();
    await page.waitForTimeout(500);

    // Verify data saved
    let storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    expect(storedData).not.toBeNull();
    console.log('  Data saved with consent ✓');

    // Reopen modal
    await openButton.click();
    await page.waitForTimeout(500);

    // UNCHECK consent
    await page.locator('#mff-storage-consent').uncheck();
    await page.waitForTimeout(200);

    console.log('  ✅ Consent checkbox unchecked');

    // Close modal
    await closeButton.click();
    await page.waitForTimeout(500);

    // Verify localStorage cleared
    storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    console.log('  LocalStorage after unchecking consent:', storedData);

    expect(storedData).toBeNull();
    console.log('✅ Storage cleared when consent unchecked');
  });

  test('should clear storage after successful form submission', async ({ page }) => {
    console.log('\n🧪 Testing: Storage cleared after submit');

    // Open calculator
    const openButton = page.locator('text=Jetzt Angebot einholen').first();
    await openButton.click();
    await page.waitForTimeout(500);

    // Enter complete form data with consent
    await page.fill('input[placeholder="TT"]', '10');
    await page.locator('input[placeholder="MM"]').first().fill('12');
    await page.fill('input[placeholder="YY"]', '26');
    await page.locator('#mff-city').fill('Frankfurt');
    await page.locator('#mff-budget').fill('6000');

    await page.locator('button#mff-guests').click();
    await page.waitForTimeout(200);
    await page.locator('button').filter({ hasText: '100 - 300' }).click();

    // Check storage consent
    await page.locator('#mff-storage-consent').check();
    await page.waitForTimeout(200);

    // Go to Step 2
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Nur DJ' }).click();

    // Go to Step 3
    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    await page.locator('#mff-firstname').fill('Anna');
    await page.locator('#mff-company').fill('TestCorp');
    await page.locator('#mff-email').fill('anna@testcorp.de');
    await page.locator('#mff-privacy').check();

    console.log('  Form completed');

    // Submit form
    await page.locator('button').filter({ hasText: /Anfrage.*senden/i }).click();
    await page.waitForTimeout(1000);

    // Verify localStorage cleared after successful submit
    const storedData = await page.evaluate(() => localStorage.getItem('mff-calculator-data'));
    console.log('  LocalStorage after submit:', storedData);

    expect(storedData).toBeNull();
    console.log('✅ Storage cleared after successful submission');
  });

  test('should always open on Step 1 even if user was on Step 3', async ({ page }) => {
    console.log('\n🧪 Testing: Always opens on Step 1 (user reassurance)');

    // Open calculator
    const openButton = page.locator('text=Jetzt Angebot einholen').first();
    await openButton.click();
    await page.waitForTimeout(500);

    // Navigate to Step 3
    await page.fill('input[placeholder="TT"]', '05');
    await page.locator('input[placeholder="MM"]').first().fill('07');
    await page.fill('input[placeholder="YY"]', '26');
    await page.locator('#mff-city').fill('Köln');

    await page.locator('button#mff-guests').click();
    await page.waitForTimeout(200);
    await page.locator('button').filter({ hasText: 'Unter 100' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);
    await page.locator('label').filter({ hasText: 'Full Band + DJ' }).click();

    await page.locator('button').filter({ hasText: 'Weiter' }).click();
    await page.waitForTimeout(300);

    // Now on Step 3
    const step3Visible = await page.locator('text=Kontaktdaten').isVisible();
    console.log('  Navigated to Step 3:', step3Visible ? '✓' : '✗');
    expect(step3Visible).toBeTruthy();

    // Enable storage consent
    await page.locator('#mff-storage-consent').check();
    await page.waitForTimeout(200);

    // Close modal
    const closeButton = page.locator('button[aria-label="Close"]').first();
    await closeButton.click();
    await page.waitForTimeout(500);

    // Reopen modal
    await openButton.click();
    await page.waitForTimeout(500);

    // MUST open on Step 1 (not Step 3)
    const step1Visible = await page.locator('text=Event-Details').isVisible();
    const step1Active = await page.evaluate(() => {
      const header = document.querySelector('.header');
      return header?.__x?.$data?.step === 1;
    });

    console.log('  Modal reopened on Step 1 (visual):', step1Visible ? '✓' : '✗');
    console.log('  Modal reopened on Step 1 (Alpine state):', step1Active ? '✓' : '✗');

    expect(step1Visible).toBeTruthy();
    expect(step1Active).toBeTruthy();

    // But data should still be there
    const restoredCity = await page.locator('#mff-city').inputValue();
    expect(restoredCity).toBe('Köln');

    console.log('✅ Modal always opens on Step 1 with data intact');
  });
});
