# Visual Editor Validation System

## Overview

The visual editor includes a comprehensive validation system that provides real-time inline error feedback for required fields.

## Architecture

### 1. `useValidation` Hook

Located in `hooks/useValidation.ts`, this hook provides field-level validation with support for multiple rule types.

**Validation Rules:**

- `required` - Field must not be empty
- `minLength` - Minimum character length
- `maxLength` - Maximum character length
- `minItems` - Minimum array items (for arrays)
- `maxItems` - Maximum array items (for arrays)
- `pattern` - Regex pattern matching
- `custom` - Custom validation function

**Usage Example:**

```typescript
const headingValidation = useValidation(heading, [
  { type: 'required', message: 'Heading is required' },
  { type: 'maxLength', max: 100 },
])

// In JSX
<input
  value={heading}
  onChange={(e) => {
    updateValue(e.target.value)
    headingValidation.clearError() // Clear error on input
  }}
  onBlur={() => headingValidation.validate()} // Validate on blur
  className={`editor-input ${headingValidation.error ? 'input-error' : ''}`}
  aria-invalid={!!headingValidation.error}
/>
{headingValidation.error && (
  <div className="error-message" role="alert">
    <XCircle size={14} />
    {headingValidation.error}
  </div>
)}
```

### 2. `ValidationContext`

Located in `context/ValidationContext.tsx`, this context aggregates validators from all editors and checks them before saving.

**Methods:**

- `registerValidator(id, validator)` - Register a validator function
- `unregisterValidator(id)` - Remove validator when component unmounts
- `validateAll()` - Run all registered validators and return boolean

**Usage in Editor:**

```typescript
const { registerValidator, unregisterValidator } = useValidationContext();

useEffect(() => {
  const validatorId = `hero-${block.id}`;
  registerValidator(validatorId, () => {
    const isValid1 = validation1.validate();
    const isValid2 = validation2.validate();
    return isValid1 && isValid2;
  });

  return () => {
    unregisterValidator(validatorId);
  };
}, [block.id, validation1, validation2]);
```

### 3. Save Button Validation

In `EditorSidebar.tsx`, the save button checks all validators before allowing save:

```typescript
const handleSave = async () => {
  const isValid = validateAll();

  if (!isValid) {
    showToast("warning", "Please fix validation errors before saving");
    return;
  }

  // Proceed with save...
};
```

## Visual Design

### Error States (CSS)

- **Input Error:** Red border (`#FF3B30`) with light red background
- **Error Message:** Red text with XCircle icon below the field
- **Required Indicator:** Red asterisk (\*) after label
- **Focus State:** Red outline when focused on invalid input

### Accessibility

- `aria-invalid="true"` on invalid inputs
- `aria-describedby` linking input to error message
- `role="alert"` and `aria-live="polite"` on error messages
- Semantic HTML with proper label associations

## Validation Timing

- **On Change:** Errors are cleared immediately when user starts typing
- **On Blur:** Validation runs when user leaves the field
- **On Save:** All validators run before API call

This provides non-intrusive validation (following Apple design principles).

## Current Implementations

### Validated Editors

1. **HeroEditor** - headlinePrefix, headlineSuffix, sliderContent, ctaText
2. **CTASectionEditor** - heading, ctaText
3. **ServiceCardsEditor** - title, description, icon (per service)

### Pending Implementations

4. **ProcessStepsEditor** - title, description (per step)
5. **TeamSectionEditor** - name, role (per member)
6. **FAQEditor** - question, answer (per FAQ)

## Adding Validation to a New Editor

1. Import validation hooks:

```typescript
import { useValidation } from "../../hooks/useValidation";
import { useValidationContext } from "../../context/ValidationContext";
import { XCircle } from "lucide-react";
```

2. Create validators for required fields:

```typescript
const titleValidation = useValidation(title, [
  { type: "required", message: "Title is required" },
]);
```

3. Register with context:

```typescript
const { registerValidator, unregisterValidator } = useValidationContext();

useEffect(() => {
  const validatorId = `editor-${block.id}`;
  registerValidator(validatorId, () => {
    return titleValidation.validate();
  });

  return () => {
    unregisterValidator(validatorId);
  };
}, [block.id, titleValidation]);
```

4. Update JSX with error handling:

```typescript
<label className="required">Title</label>
<input
  onChange={(e) => {
    updateValue(e.target.value)
    titleValidation.clearError()
  }}
  onBlur={() => titleValidation.validate()}
  className={`editor-input ${titleValidation.error ? 'input-error' : ''}`}
  aria-invalid={!!titleValidation.error}
/>
{titleValidation.error && (
  <div className="error-message" role="alert">
    <XCircle size={14} />
    {titleValidation.error}
  </div>
)}
```

## Helper Functions

### `validateSliderContent(items: string[])`

Custom validator for animated words in Hero block. Ensures:

- At least 1 word exists
- Each word is 1-5 words long
- No empty words

Usage:

```typescript
const sliderValidation = useValidation(sliderContent, [
  { type: "minItems", min: 1 },
  {
    type: "custom",
    validator: (items) => validateSliderContent(items) === null,
    message: validateSliderContent(sliderContent) || "",
  },
]);
```

## Testing Validation

1. Clear a required field and blur → Error should appear
2. Start typing → Error should clear immediately
3. Click save with errors → Warning toast should appear
4. Fix all errors → Save should proceed normally

---

**Last Updated:** 2026-01-19
**Part of:** Task 19 - Inline Validation System
