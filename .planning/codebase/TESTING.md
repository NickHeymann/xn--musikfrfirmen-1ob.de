# Testing Patterns

**Analysis Date:** 2026-01-26

## Test Framework

**Runner:**
- Playwright (End-to-End testing)
- Version: Latest (defined in package.json as `@playwright/test`)
- Config: `playwright.config.js`

**Assertion Library:**
- Playwright's native assertions: `expect(...)`
- Example: `expect(hasError).toBeFalsy()`, `expect(canvasExists).toBeTruthy()`

**Run Commands:**
```bash
npm run test:ui              # Run all E2E tests (headed mode)
npm run test:ui:headed       # Run with browser visible
npm run test:ui:debug        # Debug mode with inspector
npm run test:ui:ui           # Playwright UI runner
npm run test:ui:report       # View HTML test report
```

## Test File Organization

**Location:**
- `tests/playwright/` directory
- Separate from application code (not co-located)

**Naming:**
- Pattern: `*.spec.js` (e.g., `visual-editor.spec.js`)
- Feature-based naming: Test files named after feature being tested

**Structure:**
```
tests/
└── playwright/
    └── visual-editor.spec.js
```

## Test Structure

**Suite Organization:**

```javascript
test.describe('Visual Editor - Comprehensive Testing', () => {
  let consoleLogs = [];
  let consoleErrors = [];

  test.beforeEach(async ({ page }) => {
    // Capture all console output
    consoleLogs = [];
    consoleErrors = [];

    page.on('console', (msg) => {
      const text = msg.text();
      consoleLogs.push({ type: msg.type(), text });

      if (msg.type() === 'error') {
        consoleErrors.push(text);
      }
    });

    // Navigate to visual editor
    await page.goto('http://localhost:3000/admin/editor/home');
  });

  test('should load visual editor without errors', async ({ page }) => {
    // Test implementation
  });
});
```

**Patterns:**

**Setup Pattern (beforeEach):**
- Clear state arrays: `consoleLogs = []`, `consoleErrors = []`
- Register console listener: `page.on('console', (msg) => { ... })`
- Navigate to test URL: `await page.goto('http://localhost:3000/admin/editor/home')`
- Wait for network idle: `await page.waitForLoadState('networkidle', { timeout: 10000 })`

**Teardown Pattern:**
- Not explicitly used; Playwright auto-closes contexts after each test
- No cleanup needed for console listeners (tied to page lifecycle)

**Assertion Pattern:**
```typescript
const errorMessage = page.locator('text=Error Loading Page');
const hasError = await errorMessage.isVisible().catch(() => false);

if (hasError) {
  console.error('❌ Error message displayed on page');
}

expect(hasError).toBeFalsy();
expect(consoleErrors.length).toBe(0);
```

## Mocking

**Framework:** Playwright's native response mocking (not used in current tests)

**Patterns in Existing Tests:**
- No explicit mocking; tests hit real backends
- Backend requirements documented in config:
  ```javascript
  // Note: Servers must be running separately
  // Laravel: php artisan serve --port=8001
  // Next.js: npm run dev
  ```

**What NOT to Mock:**
- API responses (tests validate real API integration)
- Database calls (use real database with test data)

**Network Inspection Pattern:**
```javascript
const apiResponse = await page.waitForResponse(
  response => response.url().includes('/api/pages/home'),
  { timeout: 5000 }
).catch(() => null);

if (apiResponse) {
  console.log('✓ API Status:', apiResponse.status());
  const data = await apiResponse.json().catch(() => ({}));
  console.log('✓ API Response:', JSON.stringify(data, null, 2));
}
```

## Fixtures and Factories

**Test Data:**
- No explicit test fixtures or factories used
- Tests assume specific test data exists on backend (e.g., page slug "home")
- Example requirement: `http://localhost:3000/admin/editor/home` must exist in database

**Location:**
- N/A (no test fixtures in current structure)
- Could be added in `tests/fixtures/` if needed

## Coverage

**Requirements:** Not enforced

**Current Coverage:**
- Limited (E2E tests only, no unit tests)
- Covers: Visual editor page load, API connectivity, component palette visibility
- No code coverage metrics configured

## Test Types

**Unit Tests:**
- Not implemented
- Opportunity: Test hooks like `useContactForm.ts`, `useEditorAuth.ts` with isolated state changes

**Integration Tests:**
- Not explicitly separated
- Playwright tests function as E2E/integration tests

**E2E Tests:**
- Framework: Playwright
- Configuration: `playwright.config.js`
- Reporters: List (console) + HTML report
- Screenshots: Only on failure (`screenshot: 'only-on-failure'`)
- Videos: Retained on failure (`video: 'retain-on-failure'`)

**Playwright Config Details:**
```javascript
export default defineConfig({
  testDir: './tests/playwright',
  timeout: 30 * 1000,                    // 30s per test
  fullyParallel: false,                  // Sequential for debugging
  forbidOnly: !!process.env.CI,          // Fail if .only() left in code
  retries: process.env.CI ? 2 : 0,       // Retry twice on CI
  workers: 1,                            // Single worker (sequential)

  reporter: [
    ['list'],                            // Console output
    ['html', { outputFolder: 'playwright-report' }]  // HTML report
  ],

  use: {
    baseURL: 'http://localhost:3000',
    trace: 'on-first-retry',             // Trace only when retrying
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    viewport: { width: 1280, height: 720 },
    actionTimeout: 5000,                 // 5s for individual actions
  },

  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
  ],
});
```

## Common Patterns

**Async Testing:**
```javascript
test('should successfully connect to Laravel API', async ({ page }) => {
  await page.waitForLoadState('networkidle');

  // Check if EditorCanvas loaded (indicates API success)
  const canvas = page.locator('.flex-1.overflow-y-auto');
  const canvasExists = await canvas.isVisible({ timeout: 5000 }).catch(() => false);

  expect(canvasExists).toBeTruthy();
});
```

**Error Testing:**
```javascript
test('should load visual editor without errors', async ({ page }) => {
  // Check for UI error message
  const errorMessage = page.locator('text=Error Loading Page');
  const hasError = await errorMessage.isVisible().catch(() => false);

  // Check for console errors
  console.log('Console errors:', consoleErrors.length);

  expect(hasError).toBeFalsy();
  expect(consoleErrors.length).toBe(0);
});
```

**Logging Pattern for Debugging:**
- Tests use emoji prefixes: `console.log('\n🧪 TEST: [Name]')`
- Organized by phase: `// ===== Phase 1: Page Load & API Connection =====`
- Helpful for reading test output

**Element Locators:**
- Text selectors: `page.locator('text=Error Loading Page')`
- CSS selectors: `page.locator('.flex-1.overflow-y-auto')`
- Attribute selectors: `page.locator('[data-attribute]')`

**Conditional Error Handling:**
```javascript
const hasError = await elementLocator.isVisible().catch(() => false);
```

## Test Execution Context

**Environment:**
- Headless by default (can run headed with `--headed`)
- Browser: Chromium only (can add Firefox/WebKit in config)
- Viewport: 1280x720 (desktop)
- Action timeout: 5000ms
- Test timeout: 30000ms

**Debugging Features:**
- Traces on first retry: `trace: 'on-first-retry'`
- Screenshots on failure: Auto-captured
- Videos on failure: Retained for analysis
- HTML report: `playwright-report/` directory (view with `npm run test:ui:report`)

## Current Test Suite

**Visual Editor Tests (`tests/playwright/visual-editor.spec.js`):**

Phases covered:
1. **Page Load & API Connection** - Loads editor, checks for error messages, verifies console is clean
2. **Component Palette & Blocks** - Checks left sidebar visibility
3. *Additional phases implied but not yet implemented*

**Known Limitations:**
- Tests require BOTH servers running (hardcoded):
  - Laravel backend on port 8001 (for `/api/pages/home`)
  - Next.js frontend on port 3000
- Not executable in isolated CI without manual server startup
- No database setup documented
- No test data seeding/cleanup

**Example Test Scenarios:**
```javascript
test('should load visual editor without errors', async ({ page }) => {
  // Phase: Page Load
  // Asserts:
  //   - No error message displayed
  //   - No console errors

  test('should successfully connect to Laravel API', async ({ page }) => {
    // Phase: API Connection
    // Asserts:
    //   - Editor canvas visible (API responded)
    //   - API returned successful status
    //   - Response JSON can be parsed
});
```

## Future Testing Opportunities

**Unit Tests:**
- `useContactForm.ts` - Test form validation, field updates, step progression
- `useEditorAuth.ts` - Test authentication state management
- `useKeyboardShortcuts.ts` - Test keyboard event handling
- `error-logger.ts` - Test error capture, log export, limits

**Integration Tests:**
- Contact form submission flow (form → validation → email)
- Page loading: Load page → API request → render content → save changes
- Editor workflows: Load page → edit content → save → verify API call

**Component Tests:**
- Snapshot tests for Hero, Header, Footer components
- Visual regression testing with Playwright's visual assertions

---

*Testing analysis: 2026-01-26*
