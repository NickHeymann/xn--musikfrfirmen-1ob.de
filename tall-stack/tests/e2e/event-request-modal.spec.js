import { test, expect } from '@playwright/test';

test.describe('Event Request Modal', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('/');
    await page.waitForLoadState('networkidle');
  });

  test('should open modal when clicking "Unverbindliches Angebot anfragen"', async ({ page }) => {
    // Find and click the trigger button
    const triggerButton = page.locator('text=Unverbindliches Angebot anfragen').first();
    await expect(triggerButton).toBeVisible();
    await triggerButton.click();

    // Wait for Livewire to process
    await page.waitForTimeout(1000);

    // Check if we're on step 1
    const step1Title = page.locator('text=Event-Details');
    await expect(step1Title).toBeVisible();

    const dateLabel = page.locator('text=Datum');
    await expect(dateLabel).toBeVisible();
  });

  test('should complete full form submission flow', async ({ page }) => {
    // Open modal
    await page.locator('text=Unverbindliches Angebot anfragen').first().click();
    await page.waitForTimeout(1000);

    // Step 1: Event Details
    await page.locator('input[type="date"]').fill('2026-03-15');
    await page.locator('input[placeholder*="Hamburg"]').fill('Hamburg');
    await page.locator('select').first().selectOption('100-300');

    // Click "Weiter"
    await page.locator('button:has-text("Weiter")').click();
    await page.waitForTimeout(1500);

    // Step 2: Package Selection - look for the Package step title
    await expect(page.locator('text=Paket-Auswahl')).toBeVisible({ timeout: 10000 });

    // Click on Full Band + DJ package (via label click)
    const bandDjLabel = page.locator('label[for="package-band_dj"]');
    await expect(bandDjLabel).toBeVisible();
    await bandDjLabel.click();
    await page.waitForTimeout(1000);

    await page.locator('button:has-text("Weiter")').click();
    await page.waitForTimeout(1500);

    // Step 3: Contact Details
    await expect(page.locator('text=Kontaktdaten')).toBeVisible({ timeout: 10000 });

    await page.locator('input[placeholder*="Name"]').fill('Playwright Test');
    await page.locator('input[type="email"]').fill('playwright@test.de');
    await page.locator('input[type="tel"]').fill('+49 170 9999999');
    await page.locator('textarea').fill('Automatisierter Playwright Test');

    // Accept privacy checkbox
    await page.locator('input[type="checkbox"]').check();

    // Submit form
    await page.locator('button:has-text("Anfrage absenden")').click();

    // Wait for success message
    await expect(page.locator('text=Anfrage gesendet!')).toBeVisible({ timeout: 10000 });
  });

  test('should validate required fields on step 1', async ({ page }) => {
    // Open modal
    await page.locator('text=Unverbindliches Angebot anfragen').first().click();
    await page.waitForSelector('[x-show="showModal"]', { state: 'visible' });

    // Try to proceed without filling fields
    await page.locator('button:has-text("Weiter")').click();

    // Check for validation errors
    await page.waitForSelector('text=Bitte wähle ein Datum in der Zukunft', { timeout: 3000 });
    const dateError = page.locator('text=Bitte wähle ein Datum in der Zukunft');
    await expect(dateError).toBeVisible();
  });

  test('should allow navigation back to previous steps', async ({ page }) => {
    // Open modal
    await page.locator('text=Unverbindliches Angebot anfragen').first().click();
    await page.waitForSelector('[x-show="showModal"]', { state: 'visible' });

    // Fill step 1 and proceed
    await page.fill('input[wire\\:model="date"]', '2026-03-15');
    await page.fill('input[wire\\:model="city"]', 'Berlin');
    await page.selectOption('select[wire\\:model="guests"]', '100-300');
    await page.locator('button:has-text("Weiter")').click();
    await page.waitForTimeout(500);

    // Verify we're on step 2
    await page.waitForSelector('text=Paket-Auswahl');

    // Go back (back button is an arrow SVG, use wire:click)
    await page.locator('button[wire\\:click="prevStep"]').click();
    await page.waitForTimeout(500);

    // Verify we're back on step 1
    const step1Content = page.locator('text=Event-Details');
    await expect(step1Content).toBeVisible();

    // Check that data is preserved
    const cityInput = page.locator('input[wire\\:model="city"]');
    await expect(cityInput).toHaveValue('Berlin');
  });

  test('should close modal when clicking close button', async ({ page }) => {
    // Open modal
    await page.locator('text=Unverbindliches Angebot anfragen').first().click();
    await page.waitForSelector('[x-show="showModal"]', { state: 'visible' });

    // Click close button (X icon)
    const closeButton = page.locator('button[wire\\:click="closeModal"]');
    await closeButton.click();

    // Wait for modal to close
    await page.waitForTimeout(500);

    // Modal should not be visible (check overlay)
    const modal = page.locator('.fixed.inset-0.z-\\[2147483647\\]');
    await expect(modal).not.toBeVisible();
  });
});
