# Visual Editor Data Loading Fix

## Problem

The visual editor was not showing actual website content:
- **ServiceCards**: Missing full "Alleinstellungsmerkmal..." text (3 paragraphs)
- **FAQ**: Showing only 3 items instead of 7
- **Other blocks**: Using incorrect default data

## Root Cause Analysis

### Architecture Discovery

The project uses a **dual-source data architecture**:

1. **Static TypeScript Files** (`src/data/*.ts`, component defaults)
   - Contains actual website content
   - Versioned in git
   - Used by live website components

2. **Laravel Database** (`pages` table, `content` JSON column)
   - Stores only block structure and layout props
   - Content props are NOT stored (database shows empty)
   - Used for editor overrides only

3. **Editor Components** (`src/visual-editor/sidebar/editors/`)
   - Had HARDCODED fallback data
   - Fallbacks didn't match actual website content
   - Used when API returned blocks without content props

### Why This Happened

When the Laravel API returns blocks, it only returns:
```json
{
  "id": "services-1",
  "type": "ServiceCards",
  "props": {
    "sectionId": "waswirbieten",
    "paddingTop": "187px"
    // NO "services" array here!
  }
}
```

The editor components would then use their hardcoded fallbacks instead of the actual component defaults.

## Solution

### 1. Created Data Enrichment System

**File**: `src/visual-editor/lib/defaultBlockData.ts`

This file:
- Imports actual default data from components and data files
- Provides `enrichBlocksWithDefaults()` function
- Merges default content into blocks when props are missing

**Key Defaults Loaded**:
- `ServiceCards`: 3 services with full "Alleinstellungsmerkmal" paragraphs
- `FAQ`: All 7 FAQ items from `src/data/faq.ts`
- `ProcessSteps`: 3 steps from `src/data/services.ts`
- `TeamSection`: Jonas & Nick team member data
- `Hero`: Full headline, slider content, features

### 2. Integrated Into Page Loading

**File**: `src/app/admin/editor/[slug]/page.tsx`

Modified the API data loading to enrich blocks:

```typescript
const data = await api.pages.get(slug);

// Enrich blocks with default content from component files
const enrichedData = {
  ...data,
  content: {
    ...data.content,
    blocks: enrichBlocksWithDefaults(data.content.blocks),
  },
};

setPageData(enrichedData);
```

### 3. Removed Hardcoded Editor Fallbacks

Updated all editor components to use empty fallbacks:

**Before**:
```typescript
const services = block.props.services || [
  { title: "Livebands", texts: ["Professionelle Live-Musik"], ... },
  { title: "DJs", texts: ["Moderne DJ-Sets"], ... },
  // Wrong data!
];
```

**After**:
```typescript
// Services are enriched from defaultBlockData.ts on load
const services = block.props.services || [];
```

**Files Updated**:
- `ServiceCardsEditor.tsx` - Removed 3 hardcoded services
- `FAQEditor.tsx` - Removed 3 hardcoded FAQ items
- `ProcessStepsEditor.tsx` - Removed 3 hardcoded steps
- `TeamSectionEditor.tsx` - Removed 2 hardcoded team members
- `HeroEditor.tsx` - Removed all hardcoded hero props

## Benefits

### Correct Content Display
✅ Editor now shows exactly what users see on the live website
✅ All 7 FAQ items visible and editable
✅ Full "Alleinstellungsmerkmal" text (3 paragraphs) appears
✅ All service details, team bios, process steps match production

### Single Source of Truth
✅ Content lives in component files (git-versioned)
✅ Database stores only user overrides
✅ No content duplication between code and database
✅ Easy to update default content (just edit component files)

### Maintainability
✅ Clear separation: layout in DB, content in code
✅ New blocks automatically get proper defaults
✅ No risk of stale hardcoded data in editors

## Architecture Pattern

This follows a smart **Content-as-Code + Overrides-in-DB** pattern:

```
Default Content (Code)
  ↓
Merged with API Data
  ↓
Editor Display
  ↓
User Edits (Optional)
  ↓
Saved to Database
  ↓
Future loads use saved overrides
```

**Example Flow**:

1. **Initial Load**: API returns block with no `services` prop
2. **Enrichment**: `enrichBlocksWithDefaults()` adds default services from code
3. **Editor Shows**: Full "Alleinstellungsmerkmal" text visible
4. **User Edits**: Changes "Livebands" text
5. **Save**: Only edited service saved to database
6. **Next Load**: API returns edited service, others stay default

## Testing

**Manual Verification**:

1. Open editor: http://localhost:3000/admin/editor/home
2. Switch to Edit Mode
3. Click "ServiceCards" block in BLOCKS tab
4. Properties sidebar should show:
   - ✅ 3 services (not just titles)
   - ✅ Full "Alleinstellungsmerkmal" paragraph visible
   - ✅ All 3 paragraphs per service editable
5. Click "FAQ" block
6. Properties should show:
   - ✅ All 7 FAQ items
   - ✅ Full question text
   - ✅ Full answer text

**Build Verification**:
```bash
npm run build
# Should compile without errors
```

## Files Modified

### New Files
- `src/visual-editor/lib/defaultBlockData.ts` - Data enrichment system

### Modified Files
- `src/app/admin/editor/[slug]/page.tsx` - Integrated enrichment on load
- `src/visual-editor/sidebar/editors/ServiceCardsEditor.tsx` - Removed fallbacks
- `src/visual-editor/sidebar/editors/FAQEditor.tsx` - Removed fallbacks
- `src/visual-editor/sidebar/editors/ProcessStepsEditor.tsx` - Removed fallbacks
- `src/visual-editor/sidebar/editors/TeamSectionEditor.tsx` - Removed fallbacks
- `src/visual-editor/sidebar/editors/HeroEditor.tsx` - Removed fallbacks

## Future Considerations

### Adding New Block Types

When adding new block types, follow this pattern:

1. Define default data in component file
2. Add case to `enrichBlocksWithDefaults()` in `defaultBlockData.ts`
3. Editor component uses `block.props.field || []` (empty fallback)

**Example**:
```typescript
// In defaultBlockData.ts
case 'Testimonials':
  return {
    ...block,
    props: {
      ...block.props,
      testimonials: block.props.testimonials || defaultTestimonials,
    },
  };
```

### Updating Default Content

To change website content:

1. Edit the component file or data file (e.g., `src/data/faq.ts`)
2. Restart dev server (defaults are imported at build time)
3. Editor automatically shows updated content
4. No database changes needed (unless user had overrides)

### Database Migration

If you need to populate the database with current defaults:

```typescript
// Run this script to seed database with code defaults
import { enrichBlocksWithDefaults } from '@/visual-editor/lib/defaultBlockData';

async function seedDefaults() {
  const pages = await db.pages.findAll();
  
  for (const page of pages) {
    const enriched = enrichBlocksWithDefaults(page.content.blocks);
    await db.pages.update(page.id, { content: { blocks: enriched } });
  }
}
```

## Related Documentation

- `/docs/plans/2026-01-18-visual-editor-implementation.md` - Original implementation plan
- `src/visual-editor/README.md` - Editor architecture overview
- `CLAUDE.md` - Project coding standards

---

**Last Updated**: 2026-01-19
**Status**: ✅ Fixed and Tested
**Build**: ✅ Passing
