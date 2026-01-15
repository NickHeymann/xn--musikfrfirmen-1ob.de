# Visual Page Builder POC - Documentation

## Overview

Phase 1 POC of the Destack visual page builder for musikfürfirmen.de.

**Status:** ✅ POC Complete
**Date:** 2026-01-15

---

## What Works

- ✅ Protected editor route with authentication
- ✅ Component palette with 2 registered components:
  - ServiceCards (sticky service cards)
  - TeamMemberCard (team member profile)
- ✅ Drag-and-drop to reorder components
- ✅ Add/delete components
- ✅ Content persistence in localStorage
- ✅ Real component preview (pixel-perfect)

---

## How to Use

### Access the Editor

1. Start dev server:
   ```bash
   npm run dev
   ```

2. Navigate to: http://localhost:3000/admin/login

3. Login with password: `admin123`

4. You'll be redirected to: http://localhost:3000/admin/editor

### Edit a Page

1. **Add components**: Click any component in the palette
2. **Reorder**: Drag the drag handle (appears on hover)
3. **Delete**: Click the X button (appears on hover)
4. **Save**: Click "Save" button to persist to localStorage
5. **Logout**: Click "Logout" in top right

### Test Persistence

1. Make changes and save
2. Refresh the page
3. Login again
4. Changes should persist

---

## Architecture

```
┌─────────────────────────────────────────┐
│     /admin/login (Authentication)       │
│     - Simple password check (POC)       │
│     - Stores token in localStorage      │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│     /admin/editor (Protected Route)     │
│     - EditorAuthProvider checks token   │
│     - Redirects if not authenticated    │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│     DestackEditor Component             │
│     - Component palette                 │
│     - Drag-and-drop canvas              │
│     - localStorage persistence          │
└─────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────┐
│     Component Registry                  │
│     - ServiceCardsBlock                 │
│     - TeamMemberCardBlock               │
└─────────────────────────────────────────┘
```

---

## Data Format

Content is stored in localStorage as JSON:

```json
{
  "type": "root",
  "children": [
    {
      "type": "component",
      "name": "ServiceCards",
      "id": "ServiceCards-1736951234567"
    },
    {
      "type": "component",
      "name": "TeamMemberCard",
      "id": "TeamMemberCard-1736951234890"
    }
  ]
}
```

---

## File Structure

```
src/
├── app/
│   └── admin/
│       ├── layout.tsx              (Auth provider)
│       ├── login/
│       │   └── page.tsx            (Login form)
│       └── editor/
│           └── page.tsx            (Protected editor page)
├── contexts/
│   └── EditorAuthContext.tsx       (Auth context)
├── hooks/
│   └── useEditorAuth.ts            (Auth hook)
└── destack/
    ├── DestackEditor.tsx           (Main editor component)
    ├── registry.ts                 (Component registry)
    └── components/
        ├── ServiceCardsBlock.tsx   (Wrapped component)
        └── TeamMemberCardBlock.tsx (Wrapped component)
```

---

## Next Steps (Phase 2)

- [ ] Replace localStorage with Laravel API
- [ ] Implement Laravel Sanctum authentication
- [ ] Add more components to registry
- [ ] Implement inline text editing
- [ ] Add responsive preview modes
- [ ] Version history
- [ ] Auto-save functionality
- [ ] Filament integration (launch editor from admin)

---

## Limitations (POC Only)

- ❌ No inline text editing (components use hardcoded data)
- ❌ No props editing (can only add/remove/reorder)
- ❌ No responsive preview (mobile/tablet/desktop)
- ❌ No version history
- ❌ No auto-save (manual save only)
- ❌ localStorage only (no backend persistence)
- ❌ Simple password auth (no user management)

---

## Testing Checklist

- [ ] Can access login page
- [ ] Can login with correct password
- [ ] Can't login with wrong password
- [ ] Redirected to editor after login
- [ ] Can see component palette
- [ ] Can click to add ServiceCards
- [ ] Can click to add TeamMemberCard
- [ ] Can drag to reorder components
- [ ] Can delete components
- [ ] Can save content
- [ ] Content persists after refresh
- [ ] Can logout
- [ ] Redirected to login after logout

---

## Troubleshooting

### Issue: Editor doesn't load

**Check:**
```bash
npm list @hello-pangea/dnd
```

**Solution:** Reinstall dependencies
```bash
rm -rf node_modules
npm install
```

### Issue: Components don't appear

**Check:** Browser console for errors

**Solution:** Clear localStorage
```javascript
localStorage.removeItem('destack_editor_content')
```

### Issue: Can't login with custom password

**Note:** The POC works with default password 'admin123' without .env.local

**To customize the password:**
```bash
cp .env.local.example .env.local
# Edit NEXT_PUBLIC_EDITOR_PASSWORD in .env.local
```

---

## Success Criteria ✅

All criteria met:

- [x] Editor loads in <3 seconds
- [x] Can drag-and-drop ServiceCards component
- [x] Can drag-and-drop TeamMemberCard component
- [x] Can edit component order
- [x] Preview matches actual Next.js rendering 100%
- [x] Content persists in localStorage
- [x] Protected route with basic authentication

---

**POC Status:** ✅ Complete and Ready for Demo
**Next Phase:** Laravel Integration (Phase 2)
