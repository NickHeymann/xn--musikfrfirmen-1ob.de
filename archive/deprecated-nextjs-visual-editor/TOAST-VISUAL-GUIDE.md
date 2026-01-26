# Toast Notification System - Visual Guide

## Live Preview

When you run the visual editor, toasts appear in the **top-right corner** of the screen.

## Toast Appearance

### Success Toast
```
┌─────────────────────────────────────────┐
│ ✓  Changes saved successfully!       × │ ← Light green background (#D1F4E0)
├─────────────────────────────────────────┤ ← Green border-left (#00C853)
│ ████████████████                        │ ← Green progress bar
└─────────────────────────────────────────┘
```

**When to use:**
- Save operations succeed
- Upload completes successfully
- Actions complete without errors

### Error Toast
```
┌─────────────────────────────────────────┐
│ ✕  Failed to save: Network error     × │ ← Light red background (#FFE5E5)
├─────────────────────────────────────────┤ ← Red border-left (#FF3B30)
│ ████████████████                        │ ← Red progress bar
└─────────────────────────────────────────┘
```

**When to use:**
- Save operations fail
- Upload errors occur
- Network connection issues
- Any critical errors

### Warning Toast
```
┌─────────────────────────────────────────┐
│ ⚠  Please fill all required fields   × │ ← Light yellow background (#FFF4E5)
├─────────────────────────────────────────┤ ← Yellow border-left (#FF9500)
│ ████████████████                        │ ← Yellow progress bar
└─────────────────────────────────────────┘
```

**When to use:**
- Validation issues
- Missing required fields
- Non-critical issues
- User should take action

### Info Toast
```
┌─────────────────────────────────────────┐
│ ℹ  Preview updating...                × │ ← Light blue background (#E5F3FF)
├─────────────────────────────────────────┤ ← Blue border-left (#007AFF)
│ ████████████████                        │ ← Blue progress bar
└─────────────────────────────────────────┘
```

**When to use:**
- Informational messages
- Status updates
- Non-urgent notifications

## Interaction Examples

### 1. Single Toast
```
Screen:
┌────────────────────────────────────────────────────────────┐
│                                                            │
│                              ┌────────────────────────┐    │
│                              │ ✓ Saved!            × │    │ ← Top-right
│                              ├────────────────────────┤    │
│   [Your Editor Content]      │ ███████               │    │
│                              └────────────────────────┘    │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

### 2. Multiple Toasts (Stacked)
```
Screen:
┌────────────────────────────────────────────────────────────┐
│                                                            │
│                              ┌────────────────────────┐    │
│                              │ ✓ Image uploaded    × │    │ ← Oldest (top)
│                              └────────────────────────┘    │
│                              ┌────────────────────────┐    │
│                              │ ✓ Changes saved     × │    │
│                              └────────────────────────┘    │
│   [Your Editor Content]      ┌────────────────────────┐    │
│                              │ ℹ Preview updating  × │    │ ← Newest (bottom)
│                              └────────────────────────┘    │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

### 3. Animation Sequence

**Step 1: Toast appears (slide in)**
```
                    →→→ ┌────────────────┐
                        │ ✓ Saved!    × │
                        └────────────────┘
```

**Step 2: Toast visible (progress bar animates)**
```
                        ┌────────────────┐
                        │ ✓ Saved!    × │
                        ├────────────────┤
                        │ █████▒▒▒▒▒▒▒▒▒│ ← Progress bar shrinking
                        └────────────────┘
```

**Step 3: Toast disappears (slide out)**
```
                        ┌────────────────┐ →→→
                        │ ✓ Saved!    × │
                        └────────────────┘
```

## Hover Behavior

### Before Hover
```
┌────────────────────────┐
│ ✓ Saved!            × │
├────────────────────────┤
│ ████▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒│ ← Progress bar shrinking
└────────────────────────┘
```

### During Hover (Paused)
```
┌────────────────────────┐
│ ✓ Saved!            × │ ← Cursor over toast
├────────────────────────┤
│ ████▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒│ ← Progress PAUSED
└────────────────────────┘
```

### After Hover (Resumed)
```
┌────────────────────────┐
│ ✓ Saved!            × │
├────────────────────────┤
│ ████▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒│ ← Progress continues
└────────────────────────┘
```

## Mobile View

On mobile devices (< 768px), toasts span full width with margins:

```
Mobile Screen:
┌──────────────────────────┐
│ [16px]               [16px] │
│   ┌──────────────────┐   │
│   │ ✓ Saved!      × │   │ ← Full width
│   └──────────────────┘   │
│                          │
│   [Your Editor Content]  │
│                          │
└──────────────────────────┘
```

## Real-World Usage Flow

### Scenario: User saves changes

1. **User clicks "Save" button**
   ```
   [Save Button] ← clicked
   ```

2. **Save in progress** (button shows loading)
   ```
   [⟳ Saving...] ← disabled
   ```

3. **Success toast appears**
   ```
   ┌────────────────────────────┐
   │ ✓ Changes saved!        × │ ← Slides in from right
   └────────────────────────────┘
   ```

4. **Toast auto-dismisses after 3 seconds**
   ```
   (toast fades out and slides right) →→→
   ```

### Scenario: Save fails

1. **Network error occurs**

2. **Error toast appears**
   ```
   ┌──────────────────────────────────┐
   │ ✕ Failed to save: Network     × │ ← Red
   │    error                          │
   └──────────────────────────────────┘
   ```

3. **User can read error message**
   - Hover to pause auto-dismiss
   - Read full error
   - Click X to dismiss manually

## Typography & Spacing

### Toast Anatomy
```
┌─────────────────────────────────────────┐
│ [Icon] [Message]                    [X] │
│  20px   14px (500 weight)           16px│
│                                          │
│  12px padding left/right                │
│  14px padding top/bottom                │
├─────────────────────────────────────────┤
│ ████████████████ Progress bar (3px)     │
└─────────────────────────────────────────┘
```

### Spacing Between Stacked Toasts
```
Toast 1
       ↕ 12px gap
Toast 2
       ↕ 12px gap
Toast 3
```

## Color Reference

### Success (Green)
- Background: `#D1F4E0` (very light green)
- Border: `#00C853` (Material Green 500)
- Icon: `#00C853` (CheckCircle)
- Progress: `#00C853`

### Error (Red)
- Background: `#FFE5E5` (very light red)
- Border: `#FF3B30` (Apple Red)
- Icon: `#FF3B30` (XCircle)
- Progress: `#FF3B30`

### Warning (Yellow)
- Background: `#FFF4E5` (very light yellow)
- Border: `#FF9500` (Apple Orange)
- Icon: `#FF9500` (AlertCircle)
- Progress: `#FF9500`

### Info (Blue)
- Background: `#E5F3FF` (very light blue)
- Border: `#007AFF` (Apple Blue)
- Icon: `#007AFF` (InfoCircle)
- Progress: `#007AFF`

## Accessibility Features

### Screen Reader Announcement
When toast appears, screen reader announces:
```
"Alert: Changes saved successfully!"
```

### Keyboard Navigation
- **Tab**: Focus on dismiss button (X)
- **Enter/Space**: Dismiss toast
- **Escape**: Dismiss focused toast (future enhancement)

### ARIA Attributes
```html
<div role="alert" aria-live="polite" aria-atomic="true">
  Changes saved successfully!
</div>
```

## Implementation in Code

### Show a Toast
```tsx
import { useToast } from '@/visual-editor/context/ToastContext'

function MyComponent() {
  const { showToast } = useToast()

  return (
    <button onClick={() => showToast('success', 'Action complete!')}>
      Do Action
    </button>
  )
}
```

### Toast Types
```tsx
// Success (green checkmark)
showToast('success', 'Saved!')

// Error (red X)
showToast('error', 'Failed to save')

// Warning (yellow alert)
showToast('warning', 'Missing fields')

// Info (blue info icon)
showToast('info', 'Processing...')
```

### Custom Duration
```tsx
// Default: 3000ms (3 seconds)
showToast('success', 'Quick message')

// Custom: 5000ms (5 seconds)
showToast('info', 'Important message', 5000)

// Very brief: 1500ms (1.5 seconds)
showToast('success', 'Done!', 1500)
```

## Best Practices

### ✅ Do
- Use success for completed actions
- Use error for failures with specific message
- Use warning for validation issues
- Use info for status updates
- Keep messages concise (< 60 characters)
- Provide actionable error messages

### ❌ Don't
- Don't show toast for every minor action
- Don't use long technical error messages
- Don't stack more than 3 toasts
- Don't use toasts for critical warnings (use modals)
- Don't rely only on color (icons help accessibility)

## Design Philosophy

This toast system follows **Apple's Human Interface Guidelines**:

1. **Non-intrusive**: Appears in corner, doesn't block content
2. **Clear hierarchy**: Icon → Message → Dismiss
3. **Feedback**: Progress bar shows time remaining
4. **User control**: Hover to pause, click to dismiss
5. **Accessibility**: ARIA live regions for screen readers
6. **Physics-based animation**: Spring animations feel natural
7. **Minimal design**: Clean, no unnecessary decorations

---

**Next Steps**: Use the toast system throughout the visual editor for all user feedback!
