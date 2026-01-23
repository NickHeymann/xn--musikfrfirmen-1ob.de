# Contributing to Visual Editor

> Guidelines for contributing to the musikfürfirmen.de visual editor

## Table of Contents

- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Code Standards](#code-standards)
- [Adding New Features](#adding-new-features)
- [Testing Requirements](#testing-requirements)
- [Commit Guidelines](#commit-guidelines)
- [Pull Request Process](#pull-request-process)

---

## Getting Started

### Prerequisites

- Node.js 18+
- npm or yarn
- Laravel backend running on port 8001
- Git

### Setup

```bash
# Clone repository
git clone git@github.com:NickHeymann/musikfuerfirmen.git
cd musikfuerfirmen

# Install dependencies
npm install

# Start development server
npm run dev

# In another terminal, start Laravel backend
cd tall-stack
php artisan serve --port=8001
```

### Project Structure

```
src/visual-editor/
├── components/          # Reusable UI components
├── context/             # React Context (state management)
├── hooks/               # Custom React hooks
├── modes/               # View/Edit modes
├── sidebar/             # Editor sidebar and block editors
├── styles/              # Editor-specific CSS
├── types.ts             # TypeScript interfaces
└── VisualEditor.tsx     # Main entry point
```

---

## Development Workflow

### 1. Create a Feature Branch

```bash
# Feature branch
git checkout -b feature/new-block-editor

# Bug fix branch
git checkout -b fix/validation-error

# Refactor branch
git checkout -b refactor/optimize-rendering
```

### 2. Make Changes

Follow [Code Standards](#code-standards) below.

### 3. Test Locally

```bash
# Run linting
npm run lint

# Run type checking
npx tsc --noEmit

# Test in browser
npm run dev
```

### 4. Commit Changes

Follow [Commit Guidelines](#commit-guidelines).

### 5. Push and Create PR

```bash
git push -u origin feature/new-block-editor
```

Then create a Pull Request on GitHub.

---

## Code Standards

### TypeScript

**✅ DO:**

```typescript
// Use proper types
interface Block {
  id: string;
  type: string;
  props: Record<string, unknown>;
}

// Use type-safe function signatures
function updateBlock(blockId: string, props: Record<string, unknown>): void {
  // ...
}
```

**❌ DON'T:**

```typescript
// Avoid 'any' types
function updateBlock(blockId: string, props: any): void {
  // ❌
  // ...
}

// Avoid implicit any
function processData(data) {
  // ❌
  // ...
}
```

### React Components

**✅ DO:**

```typescript
// Functional components with TypeScript
interface HeroEditorProps {
  blockId: string
  heading: string
}

export function HeroEditor({ blockId, heading }: HeroEditorProps) {
  // ...
}

// Use semantic HTML
<button type="button" onClick={handleClick}>
  Save
</button>
```

**❌ DON'T:**

```typescript
// Avoid class components
class HeroEditor extends React.Component { }  // ❌

// Avoid non-semantic HTML
<div onClick={handleClick}>Save</div>  // ❌
```

### Hooks

**✅ DO:**

```typescript
// Custom hooks start with 'use'
export function useValidation<T>(value: T, rules: ValidationRule[]) {
  // ...
}

// Memoize callbacks
const updateBlock = useCallback(
  (id: string, props: Record<string, unknown>) => {
    // ...
  },
  [dependencies],
);
```

**❌ DON'T:**

```typescript
// Don't call hooks conditionally
if (condition) {
  const data = useState(); // ❌
}

// Don't forget dependencies
useEffect(() => {
  fetchData(id);
}, []); // ❌ Missing 'id' dependency
```

### CSS

**✅ DO:**

```css
/* Use BEM naming */
.editor-sidebar {
  /* ... */
}

.editor-sidebar__header {
  /* ... */
}

.editor-sidebar--collapsed {
  /* ... */
}

/* Use CSS custom properties */
:root {
  --editor-bg: #f5f5f5;
  --editor-text: #333;
}
```

**❌ DON'T:**

```css
/* Avoid inline styles */
<div style={{ color: 'red' }}>  /* ❌ */

/* Avoid !important */
.header {
  color: red !important;  /* ❌ */
}
```

### File Naming

**✅ DO:**

- `HeroEditor.tsx` - PascalCase for components
- `useValidation.ts` - camelCase for hooks
- `editor.css` - kebab-case for CSS
- `types.ts` - lowercase for utilities

**❌ DON'T:**

- `hero-editor.tsx` - Wrong case
- `UseValidation.ts` - Wrong case for hooks
- `Editor.CSS` - Wrong case

---

## Adding New Features

### Adding a New Block Editor

**Step 1: Create Editor Component**

```typescript
// sidebar/editors/NewBlockEditor.tsx
import { useEditor } from '../../context/EditorContext'

export function NewBlockEditor() {
  const { selectedBlockId, updateBlock, blocks } = useEditor()

  const block = blocks.find(b => b.id === selectedBlockId)
  if (!block) return null

  const { title, description } = block.props

  return (
    <div className="editor-panel">
      <h3>New Block Settings</h3>

      <div className="editor-group">
        <label htmlFor="title">Title</label>
        <input
          id="title"
          type="text"
          value={title as string || ''}
          onChange={(e) => updateBlock(selectedBlockId!, { title: e.target.value })}
        />
      </div>

      <div className="editor-group">
        <label htmlFor="description">Description</label>
        <textarea
          id="description"
          value={description as string || ''}
          onChange={(e) => updateBlock(selectedBlockId!, { description: e.target.value })}
        />
      </div>
    </div>
  )
}
```

**Step 2: Register in Sidebar**

```typescript
// sidebar/Sidebar.tsx
import { NewBlockEditor } from "./editors/NewBlockEditor";

const editors: Record<string, FC> = {
  Hero: HeroEditor,
  FAQ: FAQEditor,
  NewBlock: NewBlockEditor, // Add here
};
```

**Step 3: Add to Component Registry**

```typescript
// modes/ViewMode.tsx
import NewBlock from "@/components/NewBlock";

const componentRegistry: Record<string, FC<Record<string, unknown>>> = {
  Hero,
  FAQ,
  NewBlock, // Add here
};
```

**Step 4: Test**

1. Start dev server: `npm run dev`
2. Login to admin: `/admin/login`
3. Edit page: `/admin/pages/home/edit`
4. Select new block
5. Verify editor appears
6. Make changes and save

### Adding a New Hook

**Step 1: Create Hook**

```typescript
// hooks/useNewFeature.ts
import { useState, useCallback } from "react";

/**
 * Hook for new feature
 *
 * @param initialValue - Initial value
 * @returns State and actions
 */
export function useNewFeature<T>(initialValue: T) {
  const [value, setValue] = useState<T>(initialValue);

  const update = useCallback((newValue: T) => {
    setValue(newValue);
  }, []);

  const reset = useCallback(() => {
    setValue(initialValue);
  }, [initialValue]);

  return {
    value,
    update,
    reset,
  };
}
```

**Step 2: Add Tests**

```typescript
// hooks/useNewFeature.test.ts
import { renderHook, act } from "@testing-library/react";
import { useNewFeature } from "./useNewFeature";

describe("useNewFeature", () => {
  it("should initialize with value", () => {
    const { result } = renderHook(() => useNewFeature("initial"));
    expect(result.current.value).toBe("initial");
  });

  it("should update value", () => {
    const { result } = renderHook(() => useNewFeature("initial"));

    act(() => {
      result.current.update("updated");
    });

    expect(result.current.value).toBe("updated");
  });
});
```

**Step 3: Document**

Add JSDoc comments and update README.md if needed.

### Adding a New Validation Rule

**Step 1: Add Rule Type**

```typescript
// hooks/useValidation.ts
export type ValidationRule =
  | { type: "required"; message?: string }
  | { type: "email"; message?: string }; // Add new rule
// ... existing rules
```

**Step 2: Implement Rule**

```typescript
// hooks/useValidation.ts
case 'email': {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (typeof value === 'string' && !emailRegex.test(value)) {
    setError(rule.message || 'Invalid email address')
    return false
  }
  break
}
```

**Step 3: Add Default Message**

```typescript
const defaultMessages = {
  required: "This field is required",
  email: "Invalid email address", // Add here
  // ...
};
```

**Step 4: Test**

```typescript
const { error, validate } = useValidation(email, [
  { type: "required" },
  { type: "email" },
]);
```

---

## Testing Requirements

### Unit Tests

Required for:

- Hooks (useValidation, useAutoSave, etc.)
- Utility functions
- Validation rules

**Example:**

```typescript
// hooks/useValidation.test.ts
import { renderHook } from "@testing-library/react";
import { useValidation } from "./useValidation";

describe("useValidation", () => {
  it("should validate required field", () => {
    const { result } = renderHook(() =>
      useValidation("", [{ type: "required" }]),
    );

    const isValid = result.current.validate();

    expect(isValid).toBe(false);
    expect(result.current.error).toBe("This field is required");
  });
});
```

### Integration Tests

Required for:

- Editor workflows (create, edit, save)
- Drag-and-drop
- Auto-save

### Manual Testing

Before submitting PR, test:

- [ ] All block editors load correctly
- [ ] Validation shows errors
- [ ] Save works (Cmd+S and button)
- [ ] Undo/Redo works
- [ ] Drag-and-drop reorders blocks
- [ ] No console errors
- [ ] No TypeScript errors

---

## Commit Guidelines

### Format

```
type(scope): short description

- Optional detailed description
- Breaking changes (if any)

🤖 Generated with [Claude Code](https://claude.com/claude-code)
Co-Authored-By: Claude Sonnet 4.5 <noreply@anthropic.com>
```

### Types

| Type       | When to Use                              |
| ---------- | ---------------------------------------- |
| `feat`     | New feature                              |
| `fix`      | Bug fix                                  |
| `refactor` | Code refactoring (no functional change)  |
| `docs`     | Documentation only                       |
| `style`    | Formatting, missing semi-colons, etc.    |
| `test`     | Adding or fixing tests                   |
| `chore`    | Maintenance (dependencies, config, etc.) |

### Examples

**Good commits:**

```
feat(editor): add drag-and-drop for blocks

- Implement @dnd-kit for block reordering
- Add visual feedback during drag
- Update history on reorder
```

```
fix(validation): handle empty array validation

- Fix useValidation not checking empty arrays
- Add test for minItems rule
```

```
refactor(hooks): extract auto-save logic to hook

- Move auto-save from EditorContext to useAutoSave
- Add debouncing and error handling
- Update tests
```

**Bad commits:**

```
update stuff  ❌ Too vague

fixed bug  ❌ No context

WIP  ❌ Not descriptive
```

---

## Pull Request Process

### Before Creating PR

1. **Run linting**: `npm run lint`
2. **Check types**: `npx tsc --noEmit`
3. **Test locally**: Manual testing
4. **Update docs**: If adding features
5. **Write tests**: If applicable

### PR Template

```markdown
## Description

Brief description of changes

## Type of Change

- [ ] Bug fix
- [ ] New feature
- [ ] Refactoring
- [ ] Documentation

## Testing

- [ ] Tested locally
- [ ] Added unit tests
- [ ] No TypeScript errors
- [ ] No console errors

## Screenshots (if applicable)

[Add screenshots]

## Checklist

- [ ] Code follows style guidelines
- [ ] Self-reviewed code
- [ ] Commented complex logic
- [ ] Updated documentation
- [ ] No breaking changes (or documented)
```

### Review Process

1. **Create PR** on GitHub
2. **CI checks** run automatically (linting, types)
3. **Code review** by maintainer
4. **Address feedback** if needed
5. **Merge** when approved

---

## Common Pitfalls

### TypeScript Errors

**Issue**: `Property 'X' does not exist on type 'Y'`

**Solution**: Add proper type annotations

```typescript
// ❌ Implicit any
const block = blocks.find((b) => b.id === id);
block.props.title; // Error

// ✅ Proper typing
const block = blocks.find((b) => b.id === id);
if (block) {
  const title = block.props.title as string;
}
```

### React Hooks

**Issue**: "Rendered more hooks than during the previous render"

**Solution**: Don't call hooks conditionally

```typescript
// ❌ Conditional hook
if (condition) {
  const data = useState();
}

// ✅ Unconditional hook
const data = useState();
if (condition) {
  // use data
}
```

### State Updates

**Issue**: State not updating

**Solution**: Use functional updates

```typescript
// ❌ May use stale state
setBlocks(blocks.map(b => ...))

// ✅ Always uses latest state
setBlocks(prev => prev.map(b => ...))
```

---

## Getting Help

### Resources

- [ARCHITECTURE.md](./ARCHITECTURE.md) - System architecture
- [README.md](./README.md) - Quick start guide
- [TESTING.md](./TESTING.md) - Testing documentation
- [TypeScript Docs](https://www.typescriptlang.org/docs/)
- [React Docs](https://react.dev/)

### Contact

- **GitHub Issues**: Report bugs and request features
- **Discussions**: Ask questions and share ideas

---

## Code of Conduct

### Be Respectful

- Respectful communication
- Constructive feedback
- Inclusive language

### Be Collaborative

- Help others learn
- Share knowledge
- Credit contributors

### Be Professional

- Focus on code, not people
- Accept criticism gracefully
- Assume good intentions

---

**Last Updated**: 2026-01-19
**Version**: 1.0.0
