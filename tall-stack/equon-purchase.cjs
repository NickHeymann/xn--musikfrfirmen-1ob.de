const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: false, slowMo: 300 });
  const context = await browser.newContext({ viewport: { width: 1280, height: 900 } });
  const page = await context.newPage();

  // Step 1: Fresh checkout session
  console.log('Step 1: Loading atelier page...');
  await page.goto('https://equon.info/atelier', { waitUntil: 'networkidle' });
  await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight / 2));
  await page.waitForTimeout(800);

  const buyBtn = page.locator('button:has-text("Ich bin dabei")').first();
  await buyBtn.waitFor({ state: 'visible', timeout: 10000 });
  await buyBtn.click();
  await page.waitForURL('**/checkout.stripe.com/**', { timeout: 15000 });
  await page.waitForTimeout(3000);
  console.log('On Stripe checkout');

  // Helper: fill React-controlled input via events
  async function fillReactInput(locator, value) {
    await locator.click({ force: true });
    
    await locator.fill(value);
    // Also trigger React synthetic events
    await page.evaluate((val) => {
      const el = document.activeElement;
      if (!el) return;
      const nativeInputValueSetter = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value').set;
      nativeInputValueSetter.call(el, val);
      el.dispatchEvent(new Event('input', { bubbles: true }));
      el.dispatchEvent(new Event('change', { bubbles: true }));
    }, value);
  }

  // Step 2: Apply promo code NICKTEST100 (uppercase!)
  console.log('\nStep 2: Applying promo code NICKTEST100...');
  const promoInput = page.locator('input[name="promotionCode"]').first();
  await promoInput.waitFor({ state: 'visible', timeout: 5000 });
  await fillReactInput(promoInput, 'NICKTEST100');
  await page.keyboard.press('Enter');
  await page.waitForTimeout(3000);
  await page.screenshot({ path: '/tmp/eq-promo.png' });

  const pageText = await page.locator('body').innerText();
  if (pageText.includes('ungültig') || pageText.includes('invalid')) {
    console.log('ERROR: Promo code still invalid!');
  } else if (pageText.includes('0,00') || pageText.includes('0.00')) {
    console.log('SUCCESS: Promo applied — total is 0!');
  } else {
    console.log('Promo status unclear, continuing...');
  }

  // Step 3: Fill email
  console.log('\nStep 3: Filling contact info...');
  const emailInput = page.locator('input[type="email"]').first();
  if (await emailInput.isVisible({ timeout: 3000 })) {
    await fillReactInput(emailInput, 'test@equon.info');
    console.log('Email filled');
  }

  // Step 4: Cardholder name
  const nameInput = page.locator('input[name="billingName"]').first();
  if (await nameInput.isVisible({ timeout: 2000 })) {
    await fillReactInput(nameInput, 'Test User');
    console.log('Name filled');
  } else {
    // try other selectors
    const nameAlt = page.locator('input[autocomplete="name"], input[placeholder*="Name"]').first();
    if (await nameAlt.isVisible({ timeout: 2000 })) {
      await fillReactInput(nameAlt, 'Test User');
      console.log('Name filled (alt)');
    }
  }

  // Step 5: Card fields in Stripe iframes
  console.log('\nStep 4: Filling card fields...');
  await page.waitForTimeout(1000);

  // Card number
  let cardDone = false;
  for (const frame of [page, ...page.frames()]) {
    for (const sel of ['input[name="number"]', 'input[autocomplete="cc-number"]', 'input[placeholder*="1234"]']) {
      try {
        const el = frame.locator(sel).first();
        if (await el.isVisible({ timeout: 800 })) {
          await el.fill('4242424242424242');
          console.log('Card number filled');
          cardDone = true;
          break;
        }
      } catch (e) {}
    }
    if (cardDone) break;
  }

  for (const frame of [page, ...page.frames()]) {
    for (const sel of ['input[name="expiry"]', 'input[autocomplete="cc-exp"]']) {
      try {
        const el = frame.locator(sel).first();
        if (await el.isVisible({ timeout: 800 })) {
          await el.fill('12 / 29');
          console.log('Expiry filled');
          break;
        }
      } catch (e) {}
    }
  }

  for (const frame of [page, ...page.frames()]) {
    for (const sel of ['input[name="cvc"]', 'input[autocomplete="cc-csc"]']) {
      try {
        const el = frame.locator(sel).first();
        if (await el.isVisible({ timeout: 800 })) {
          await el.fill('123');
          console.log('CVC filled');
          break;
        }
      } catch (e) {}
    }
  }

  await page.screenshot({ path: '/tmp/eq-before-pay.png' });

  // Step 6: Submit
  console.log('\nStep 5: Submitting payment...');
  const submitBtn = page.locator('button[type="submit"]').first();
  await submitBtn.click();

  try {
    await page.waitForURL('**/equon.info/**', { timeout: 25000 });
    console.log(`\nSUCCESS! Redirected to: ${page.url()}`);
  } catch (e) {
    await page.waitForTimeout(10000);
    console.log(`Final URL: ${page.url()}`);
  }

  await page.screenshot({ path: '/tmp/eq-after-pay.png' });

  const finalUrl = page.url();
  const tokenMatch = finalUrl.match(/token=([a-f0-9]+)/);
  if (tokenMatch) {
    console.log(`\nToken: ${tokenMatch[1]}`);
    await page.goto(`https://equon.info/webinar?token=${tokenMatch[1]}`);
    await page.waitForTimeout(8000);
    await page.screenshot({ path: '/tmp/eq-webinar.png' });
    console.log('Webinar screenshot: /tmp/eq-webinar.png');
  } else {
    const text = await page.locator('body').innerText().catch(() => '');
    console.log('Page content (400 chars):\n', text.substring(0, 400));
  }

  await page.waitForTimeout(4000);
  await browser.close();
})();
