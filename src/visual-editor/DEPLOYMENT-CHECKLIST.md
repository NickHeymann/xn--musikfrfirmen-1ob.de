# Deployment Checklist

**Version:** 1.0
**Date:** 2026-01-19
**Target Environment:** Production
**Project:** musikfuerfirmen.de Visual Editor

---

## Pre-Deployment Checklist

### 1. Testing Complete

- [ ] All tests in TESTING.md completed
- [ ] TEST-REPORT.md created and signed off
- [ ] KNOWN-ISSUES.md reviewed and documented
- [ ] No P0 (critical) issues remain
- [ ] All P1 (high) issues resolved or have workarounds

---

### 2. Code Quality

- [ ] TypeScript compilation successful: `npx tsc --noEmit`
- [ ] ESLint passes: `npm run lint`
- [ ] No console errors in dev environment
- [ ] No console warnings (or documented)
- [ ] Code reviewed (if applicable)

---

### 3. Build Verification

```bash
# Run these commands and verify success
npm run build
npm run lint
npx tsc --noEmit
```

- [ ] Production build completes without errors
- [ ] Build output size acceptable (<1MB for editor)
- [ ] No unexpected bundle size increases
- [ ] Source maps generated (for debugging)
- [ ] Environment variables configured

---

### 4. Environment Configuration

#### Production Environment Variables

- [ ] `NEXT_PUBLIC_API_URL` set correctly
- [ ] API endpoints verified (Laravel backend URL)
- [ ] CORS configured on Laravel backend
- [ ] API authentication configured (if required)

**Production API URL:**

```env
NEXT_PUBLIC_API_URL=https://api.musikfuerfirmen.de/api
```

**Verify API is accessible:**

```bash
curl https://api.musikfuerfirmen.de/api/pages/home
```

---

### 5. Backend Verification

- [ ] Laravel backend running on production server
- [ ] API endpoints accessible
- [ ] Database migrations run
- [ ] Media upload directory writable
- [ ] Media upload endpoint tested: `/api/media/upload`
- [ ] Page save endpoint tested: `/api/pages/:slug`
- [ ] CORS headers configured correctly

**Laravel Checklist:**

```bash
# On production server
cd /path/to/laravel
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Test endpoints
curl -X POST https://api.musikfuerfirmen.de/api/pages/home \
  -H "Content-Type: application/json" \
  -d '{"content":{"blocks":[]}}'
```

---

### 6. Performance Verification

- [ ] Lighthouse audit run (Performance >90, Accessibility >95)
- [ ] Bundle size analyzed
- [ ] No memory leaks detected
- [ ] Animations smooth (60fps)
- [ ] Initial load time <2 seconds

**Run Lighthouse:**

```bash
# In Chrome DevTools
# 1. Open /admin/editor/home
# 2. Run Lighthouse audit
# 3. Verify scores meet targets
```

---

### 7. Browser Compatibility

- [ ] Tested in Chrome (latest)
- [ ] Tested in Safari (latest)
- [ ] Tested in Firefox (latest)
- [ ] Mobile Safari tested (optional)
- [ ] No browser-specific bugs

---

### 8. Accessibility

- [ ] Keyboard navigation works
- [ ] Screen reader tested (VoiceOver)
- [ ] ARIA labels present
- [ ] Focus indicators visible
- [ ] Contrast ratios meet WCAG AA

---

### 9. Security

- [ ] No sensitive data in client code
- [ ] API authentication implemented (if required)
- [ ] HTTPS enforced on production
- [ ] CORS configured correctly
- [ ] Rate limiting on API (recommended)
- [ ] File upload validation (size, type, malware scan)

---

### 10. Documentation

- [ ] TESTING.md complete
- [ ] TEST-REPORT.md complete
- [ ] KNOWN-ISSUES.md complete
- [ ] README.md updated (if changes)
- [ ] API documentation up to date
- [ ] User guide created (optional)

---

## Deployment Process

### Step 1: Pre-Deployment Backup

```bash
# Backup current production database
ssh production-server
cd /path/to/laravel
php artisan backup:run

# Create git tag for rollback
git tag pre-deployment-$(date +%Y%m%d-%H%M%S)
git push --tags
```

- [ ] Database backed up
- [ ] Git tag created for rollback
- [ ] Current production code archived

---

### Step 2: Deploy to Staging (Recommended)

```bash
# Deploy to staging environment first
git checkout main
git pull origin main
npm run build

# Deploy to staging server
# (Method depends on hosting - Vercel, manual, Docker, etc.)
```

- [ ] Staging environment deployed
- [ ] Smoke tests passed on staging
- [ ] Visual regression tests passed (if applicable)

---

### Step 3: Deploy to Production

#### For Vercel (Automatic)

```bash
# Merge to main branch
git checkout main
git merge feature/visual-editor
git push origin main

# Vercel will automatically deploy
```

- [ ] Code merged to main
- [ ] Vercel build triggered
- [ ] Build succeeded
- [ ] Deployment completed

#### For Manual Deployment

```bash
# Build production bundle
npm run build

# Copy .next/ folder to production server
scp -r .next/ user@production-server:/path/to/app/

# Restart Next.js process on server
ssh production-server
cd /path/to/app
pm2 restart nextjs-app
```

- [ ] Bundle built
- [ ] Files copied to server
- [ ] Server process restarted

---

### Step 4: Post-Deployment Verification

```bash
# Verify production site is up
curl https://musikfuerfirmen.de/admin/editor/home

# Check API connectivity
curl https://api.musikfuerfirmen.de/api/pages/home
```

- [ ] Production site accessible
- [ ] Editor page loads without errors
- [ ] API calls working
- [ ] Media uploads working
- [ ] Save functionality working

---

### Step 5: Smoke Tests

Perform these quick tests on production:

1. **Load Editor:**
   - [ ] Navigate to `/admin/editor/home`
   - [ ] Page loads without errors
   - [ ] No console errors

2. **Edit Content:**
   - [ ] Switch to Edit Mode
   - [ ] Select Hero block
   - [ ] Edit heading
   - [ ] Preview updates

3. **Save Content:**
   - [ ] Make a small edit
   - [ ] Save (Cmd+S)
   - [ ] Success toast appears
   - [ ] Refresh page
   - [ ] Edit persisted

4. **Media Upload:**
   - [ ] Upload test image
   - [ ] Preview updates
   - [ ] Save
   - [ ] Image persisted

---

### Step 6: Monitoring

- [ ] Error monitoring enabled (Sentry, LogRocket, etc.)
- [ ] Analytics tracking enabled (if applicable)
- [ ] Server logs monitored
- [ ] API response times monitored
- [ ] Alerts configured for errors

**Monitor for 24 hours:**

- [ ] No increase in error rates
- [ ] No performance degradation
- [ ] No user-reported issues

---

## Rollback Plan

If critical issues are discovered after deployment:

### Quick Rollback (Vercel)

```bash
# Revert to previous deployment in Vercel dashboard
# OR
git revert HEAD
git push origin main
```

### Manual Rollback

```bash
# Find previous deployment tag
git tag -l "pre-deployment-*"

# Rollback to previous version
git checkout pre-deployment-YYYYMMDD-HHMMSS
git checkout -b rollback-emergency
git push origin rollback-emergency --force

# Redeploy from rollback branch
npm run build
# ... follow deployment steps
```

### Rollback Checklist

- [ ] Issue severity assessed (P0/P1)
- [ ] Rollback decision made
- [ ] Rollback executed
- [ ] Production verified after rollback
- [ ] Post-mortem scheduled

---

## Post-Deployment Tasks

### Immediate (Within 24 hours)

- [ ] Monitor error logs
- [ ] Monitor API response times
- [ ] Verify no increase in errors
- [ ] User acceptance testing (UAT)
- [ ] Stakeholder notification (deployment complete)

### Short-term (Within 1 week)

- [ ] Gather user feedback
- [ ] Review analytics (if enabled)
- [ ] Update documentation based on feedback
- [ ] Address any minor issues found
- [ ] Plan next iteration

### Long-term (Within 1 month)

- [ ] Review performance metrics
- [ ] Analyze user behavior (if tracking enabled)
- [ ] Identify improvement opportunities
- [ ] Update roadmap for v1.1
- [ ] Retrospective meeting

---

## Communication Plan

### Before Deployment

**Notify:**

- [ ] Stakeholders (Nick Heymann)
- [ ] Development team
- [ ] Content editors (if applicable)

**Message Template:**

```
Subject: Visual Editor Deployment - [Date]

The new visual editor for musikfuerfirmen.de will be deployed on [DATE] at [TIME].

Expected downtime: None (zero-downtime deployment)
New features: Inline editing for all homepage blocks
Documentation: See /admin/editor/home for instructions

Please report any issues to [CONTACT].
```

---

### During Deployment

**Status Updates:**

- [ ] Deployment started (notify team)
- [ ] Build completed
- [ ] Deployment completed
- [ ] Smoke tests passed
- [ ] Deployment successful (notify stakeholders)

---

### After Deployment

**Success Notification:**

```
Subject: Visual Editor Deployed Successfully

The visual editor has been deployed to production successfully.

URL: https://musikfuerfirmen.de/admin/editor/home
Status: All systems operational
Issues: None detected

Monitoring will continue for 24 hours.
```

**Issue Notification (if needed):**

```
Subject: Visual Editor Deployment - Issue Detected

An issue has been detected after deployment:
- Issue: [Brief description]
- Severity: [P0/P1/P2/P3]
- Impact: [User impact]
- Status: [Investigating / Fixing / Rolled back]

Updates will be provided every [INTERVAL].
```

---

## Success Criteria

Deployment is considered successful if:

- ✅ All smoke tests pass
- ✅ No P0 or P1 issues detected in first 24 hours
- ✅ Performance metrics within acceptable range
- ✅ No increase in error rates
- ✅ User feedback positive (or neutral)

---

## Deployment Sign-Off

### Pre-Deployment

**Technical Lead:**

- [ ] All tests passed
- [ ] Code quality verified
- [ ] Build verified

**Signature:** ********\_******** **Date:** ********\_********

---

### Deployment Execution

**DevOps / Deployer:**

- [ ] Backup completed
- [ ] Deployment executed
- [ ] Post-deployment verification completed

**Signature:** ********\_******** **Date:** ********\_********

---

### Post-Deployment

**Product Owner / Stakeholder:**

- [ ] Smoke tests verified
- [ ] User acceptance testing completed
- [ ] Deployment approved

**Signature:** ********\_******** **Date:** ********\_********

---

## Appendix: Deployment Commands

### Full Deployment Script

```bash
#!/bin/bash
# deploy-visual-editor.sh

set -e  # Exit on error

echo "🚀 Starting Visual Editor Deployment"

# 1. Pre-deployment checks
echo "📋 Running pre-deployment checks..."
npm run lint
npx tsc --noEmit
npm run build

# 2. Create backup tag
echo "📦 Creating backup tag..."
TAG="pre-deployment-$(date +%Y%m%d-%H%M%S)"
git tag "$TAG"
git push --tags

# 3. Deploy (adjust for your hosting)
echo "🚢 Deploying to production..."
git push origin main

# 4. Wait for deployment
echo "⏳ Waiting for deployment to complete..."
sleep 30

# 5. Smoke tests
echo "🧪 Running smoke tests..."
curl -f https://musikfuerfirmen.de/admin/editor/home || exit 1
curl -f https://api.musikfuerfirmen.de/api/pages/home || exit 1

echo "✅ Deployment completed successfully!"
```

### Rollback Script

```bash
#!/bin/bash
# rollback-visual-editor.sh

set -e

echo "⏪ Starting Rollback"

# Find latest pre-deployment tag
TAG=$(git tag -l "pre-deployment-*" | sort -r | head -n 1)
echo "📦 Rolling back to: $TAG"

# Checkout tag
git checkout "$TAG"
git checkout -b "rollback-emergency-$(date +%Y%m%d-%H%M%S)"

# Deploy rollback
git push origin HEAD --force

echo "✅ Rollback completed. Please verify production."
```

---

**Last Updated:** 2026-01-19
**Status:** Ready for Use
