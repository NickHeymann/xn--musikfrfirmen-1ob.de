import { useState, useCallback } from "react";

// Validation rule types
export type ValidationRule =
  | { type: "required"; message?: string }
  | { type: "minLength"; min: number; message?: string }
  | { type: "maxLength"; max: number; message?: string }
  | { type: "minItems"; min: number; message?: string }
  | { type: "maxItems"; max: number; message?: string }
  | { type: "pattern"; pattern: RegExp; message?: string }
  | { type: "custom"; validator: (value: any) => boolean; message: string };

// Default error messages
const defaultMessages = {
  required: "This field is required",
  minLength: (min: number) => `At least ${min} characters required`,
  maxLength: (max: number) => `Maximum ${max} characters allowed`,
  minItems: (min: number) => `At least ${min} items required`,
  maxItems: (max: number) => `Maximum ${max} items allowed`,
  pattern: "Invalid format",
};

/**
 * Validation hook with rules engine
 *
 * @param value - The value to validate
 * @param rules - Array of validation rules
 * @returns error message (or null) and validate function
 *
 * @example
 * const { error, validate, clearError } = useValidation(heading, [
 *   { type: 'required', message: 'Heading is required' },
 *   { type: 'maxLength', max: 100 },
 * ])
 */
export function useValidation<T>(
  value: T,
  rules: ValidationRule[],
): {
  error: string | null;
  validate: () => boolean;
  clearError: () => void;
} {
  const [error, setError] = useState<string | null>(null);

  const validate = useCallback((): boolean => {
    // Run through all rules
    for (const rule of rules) {
      switch (rule.type) {
        case "required": {
          const isEmpty =
            value === null ||
            value === undefined ||
            (typeof value === "string" && value.trim() === "") ||
            (Array.isArray(value) && value.length === 0);

          if (isEmpty) {
            setError(rule.message || defaultMessages.required);
            return false;
          }
          break;
        }

        case "minLength": {
          if (typeof value === "string" && value.length < rule.min) {
            setError(rule.message || defaultMessages.minLength(rule.min));
            return false;
          }
          break;
        }

        case "maxLength": {
          if (typeof value === "string" && value.length > rule.max) {
            setError(rule.message || defaultMessages.maxLength(rule.max));
            return false;
          }
          break;
        }

        case "minItems": {
          if (Array.isArray(value) && value.length < rule.min) {
            setError(rule.message || defaultMessages.minItems(rule.min));
            return false;
          }
          break;
        }

        case "maxItems": {
          if (Array.isArray(value) && value.length > rule.max) {
            setError(rule.message || defaultMessages.maxItems(rule.max));
            return false;
          }
          break;
        }

        case "pattern": {
          if (typeof value === "string" && !rule.pattern.test(value)) {
            setError(rule.message || defaultMessages.pattern);
            return false;
          }
          break;
        }

        case "custom": {
          if (!rule.validator(value)) {
            setError(rule.message);
            return false;
          }
          break;
        }
      }
    }

    // All rules passed
    setError(null);
    return true;
  }, [value, rules]);

  const clearError = useCallback(() => {
    setError(null);
  }, []);

  return {
    error,
    validate,
    clearError,
  };
}

/**
 * Helper to validate slider content (animated words)
 * Words should be 1-5 words each, and array should have at least 1 item
 */
export function validateSliderContent(items: string[]): string | null {
  if (items.length === 0) {
    return "At least one word is required";
  }

  for (let i = 0; i < items.length; i++) {
    const item = items[i];
    const wordCount = item.trim().split(/\s+/).length;

    if (wordCount === 0) {
      return `Item ${i + 1} cannot be empty`;
    }

    if (wordCount > 5) {
      return `Item ${i + 1} has too many words (max 5)`;
    }
  }

  return null;
}
