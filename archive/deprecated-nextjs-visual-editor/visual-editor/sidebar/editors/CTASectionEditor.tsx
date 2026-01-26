"use client";

import { useEffect } from "react";
import { useEditor } from "../../context/EditorContext";
import { useValidationContext } from "../../context/ValidationContext";
import { useValidation } from "../../hooks/useValidation";
import { XCircle } from "lucide-react";

export function CTASectionEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor();
  const { registerValidator, unregisterValidator } = useValidationContext();

  const block = blocks.find((b) => b.id === selectedBlockId);
  if (!block || block.type !== "CTASection") return null;

  const {
    heading = "Bereit für unvergessliche Musik?",
    description = "Fordere jetzt dein unverbindliches Angebot an und mach dein nächstes Firmenevent zu einem Highlight.",
    ctaText = "Jetzt Angebot anfragen",
  } = block.props;

  // Validation
  const headingValidation = useValidation(heading, [
    { type: "required", message: "Heading is required" },
  ]);

  const ctaTextValidation = useValidation(ctaText, [
    { type: "required", message: "Button text is required" },
    {
      type: "maxLength",
      max: 50,
      message: "Button text too long (max 50 characters)",
    },
  ]);

  // Register validators
  useEffect(() => {
    const validatorId = `cta-${block.id}`;
    registerValidator(validatorId, () => {
      const isHeadingValid = headingValidation.validate();
      const isCtaTextValid = ctaTextValidation.validate();
      return isHeadingValid && isCtaTextValid;
    });

    return () => {
      unregisterValidator(validatorId);
    };
  }, [
    block.id,
    registerValidator,
    unregisterValidator,
    headingValidation,
    ctaTextValidation,
  ]);

  return (
    <div className="block-editor">
      <h3 className="editor-title">CTA Section</h3>

      <div className="editor-field">
        <label htmlFor="cta-heading" className="required">
          Heading
        </label>
        <input
          id="cta-heading"
          type="text"
          value={heading as string}
          onChange={(e) => {
            updateBlock(block.id, { heading: e.target.value });
            headingValidation.clearError();
          }}
          onBlur={() => headingValidation.validate()}
          className={`editor-input ${headingValidation.error ? "input-error" : ""}`}
          aria-invalid={!!headingValidation.error}
          aria-describedby={
            headingValidation.error ? "cta-heading-error" : undefined
          }
        />
        {headingValidation.error && (
          <div
            className="error-message"
            id="cta-heading-error"
            role="alert"
            aria-live="polite"
          >
            <XCircle size={14} />
            {headingValidation.error}
          </div>
        )}
      </div>

      <div className="editor-field">
        <label htmlFor="cta-description">Description</label>
        <textarea
          id="cta-description"
          value={description as string}
          onChange={(e) =>
            updateBlock(block.id, { description: e.target.value })
          }
          className="editor-input"
          rows={3}
          placeholder="Short description for the CTA"
        />
      </div>

      <div className="editor-field">
        <label htmlFor="cta-text" className="required">
          Button Text
        </label>
        <input
          id="cta-text"
          type="text"
          value={ctaText as string}
          onChange={(e) => {
            updateBlock(block.id, { ctaText: e.target.value });
            ctaTextValidation.clearError();
          }}
          onBlur={() => ctaTextValidation.validate()}
          className={`editor-input ${ctaTextValidation.error ? "input-error" : ""}`}
          aria-invalid={!!ctaTextValidation.error}
          aria-describedby={
            ctaTextValidation.error ? "cta-text-error" : undefined
          }
        />
        {ctaTextValidation.error && (
          <div
            className="error-message"
            id="cta-text-error"
            role="alert"
            aria-live="polite"
          >
            <XCircle size={14} />
            {ctaTextValidation.error}
          </div>
        )}
      </div>

      <div className="editor-hint">
        <p>
          💡 Tip: This CTA section appears at the bottom of your page to
          encourage visitors to take action. The button opens the contact modal.
        </p>
      </div>
    </div>
  );
}
