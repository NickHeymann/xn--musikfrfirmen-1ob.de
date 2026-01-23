/**
 * Toast Notification System - Usage Examples
 *
 * This file demonstrates how to use the toast notification system
 * in the visual editor.
 */

import { useToast } from "../context/ToastContext";

/**
 * Example 1: Success Toast
 * Use for successful operations like saving, uploading, etc.
 */
function SaveExample() {
  const { showToast } = useToast();

  const handleSave = async () => {
    try {
      // ... save logic ...
      showToast("success", "Changes saved successfully!");
    } catch (error) {
      // Error handling in Example 2
    }
  };

  return <button onClick={handleSave}>Save</button>;
}

/**
 * Example 2: Error Toast
 * Use for failed operations with error details
 */
function ErrorExample() {
  const { showToast } = useToast();

  const handleSave = async () => {
    try {
      // ... save logic ...
      throw new Error("Network connection failed");
    } catch (error) {
      const message = error instanceof Error ? error.message : "Unknown error";
      showToast("error", `Failed to save: ${message}`);
    }
  };

  return <button onClick={handleSave}>Save</button>;
}

/**
 * Example 3: Warning Toast
 * Use for non-critical issues or warnings
 */
function ValidationExample() {
  const { showToast } = useToast();

  const handleSubmit = () => {
    const hasErrors = true; // validation check
    if (hasErrors) {
      showToast("warning", "Please fill all required fields");
      return;
    }
    // ... submit logic ...
  };

  return <button onClick={handleSubmit}>Submit</button>;
}

/**
 * Example 4: Info Toast
 * Use for informational messages
 */
function InfoExample() {
  const { showToast } = useToast();

  const handleAction = () => {
    showToast("info", "Preview updating...");
    // ... action logic ...
  };

  return <button onClick={handleAction}>Action</button>;
}

/**
 * Example 5: Custom Duration
 * By default toasts auto-dismiss after 3 seconds.
 * You can customize the duration:
 */
function CustomDurationExample() {
  const { showToast } = useToast();

  const handleAction = () => {
    // Show for 5 seconds instead of default 3
    showToast("success", "Important message", 5000);
  };

  return <button onClick={handleAction}>Show Long Toast</button>;
}

/**
 * Example 6: Manual Dismiss
 * Users can always manually dismiss toasts by clicking the X button
 * or toasts will auto-dismiss after the duration expires.
 *
 * Hovering over a toast pauses the auto-dismiss timer.
 */

/**
 * Real-World Integration Examples
 */

// In EditorSidebar.tsx
function EditorSidebarExample() {
  const { showToast } = useToast();

  const handleSave = async () => {
    try {
      // Simulated save function
      const saveDraft = async () => {
        // ... save logic ...
      };
      await saveDraft();
      showToast("success", "Changes saved successfully!");
    } catch (error) {
      const message = error instanceof Error ? error.message : "Unknown error";
      showToast("error", `Failed to save: ${message}`);
    }
  };

  return <button onClick={handleSave}>Save</button>;
}

// In MediaUploader.tsx (future integration)
function MediaUploaderExample() {
  const { showToast } = useToast();

  const handleUpload = async (file: File) => {
    try {
      // ... upload logic ...
      showToast("success", "Image uploaded!");
    } catch (error) {
      showToast("error", "Upload failed: File too large");
    }
  };

  return (
    <input
      type="file"
      onChange={(e) => {
        const file = e.target.files?.[0];
        if (file) handleUpload(file);
      }}
    />
  );
}

/**
 * Toast Types & Colors:
 *
 * SUCCESS (green):
 * - Background: #D1F4E0
 * - Border: #00C853
 * - Icon: CheckCircle (green)
 *
 * ERROR (red):
 * - Background: #FFE5E5
 * - Border: #FF3B30
 * - Icon: XCircle (red)
 *
 * WARNING (yellow):
 * - Background: #FFF4E5
 * - Border: #FF9500
 * - Icon: AlertCircle (yellow)
 *
 * INFO (blue):
 * - Background: #E5F3FF
 * - Border: #007AFF
 * - Icon: InfoCircle (blue)
 */

/**
 * Features:
 *
 * ✓ Auto-dismiss after configurable duration (default 3s)
 * ✓ Manual dismiss with X button
 * ✓ Hover to pause auto-dismiss
 * ✓ Progress bar shows time remaining
 * ✓ Stacked toasts (max 3 visible)
 * ✓ Smooth slide-in/out animations (Framer Motion)
 * ✓ Accessible (ARIA live region)
 * ✓ Top-right positioning
 * ✓ Mobile responsive
 */

export {};
