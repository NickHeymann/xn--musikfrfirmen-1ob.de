"use client";

import { useEffect } from "react";
import { useEditor } from "../../context/EditorContext";
import { useValidationContext } from "../../context/ValidationContext";
import { ArrayInput } from "../../components/ArrayInput";
import { MediaUploader } from "../../components/MediaUploader";
import {
  useValidation,
  validateSliderContent,
} from "../../hooks/useValidation";
import { XCircle } from "lucide-react";

export function HeroEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor();
  const { registerValidator, unregisterValidator } = useValidationContext();

  const block = blocks.find((b) => b.id === selectedBlockId);
  if (!block || block.type !== "Hero") return null;

  // Hero props are enriched from defaultBlockData.ts on load
  // Defaults match actual website content
  const headlinePrefix = (block.props.headlinePrefix as string) || "Deine";
  const sliderContent = (block.props.sliderContent as string[]) || [];
  const headlineSuffix = (block.props.headlineSuffix as string) || "";
  const features = (block.props.features as string[]) || [];
  const ctaText = (block.props.ctaText as string) || "";
  const backgroundVideo = (block.props.backgroundVideo as string) || "";

  // Validation hooks
  const headlinePrefixValidation = useValidation(headlinePrefix, [
    { type: "required", message: "Headline prefix is required" },
    { type: "minLength", min: 1 },
  ]);

  const headlineSuffixValidation = useValidation(headlineSuffix, [
    { type: "required", message: "Headline suffix is required" },
    { type: "minLength", min: 1 },
  ]);

  const sliderContentValidation = useValidation(sliderContent, [
    {
      type: "minItems",
      min: 1,
      message: "At least one animated word is required",
    },
    {
      type: "custom",
      validator: (items) => validateSliderContent(items as string[]) === null,
      message: validateSliderContent(sliderContent as string[]) || "",
    },
  ]);

  const ctaTextValidation = useValidation(ctaText, [
    { type: "required", message: "CTA button text is required" },
    {
      type: "maxLength",
      max: 50,
      message: "CTA text too long (max 50 characters)",
    },
  ]);

  // Register validators with context
  useEffect(() => {
    const validatorId = `hero-${block.id}`;
    registerValidator(validatorId, () => {
      const isHeadlinePrefixValid = headlinePrefixValidation.validate();
      const isHeadlineSuffixValid = headlineSuffixValidation.validate();
      const isSliderContentValid = sliderContentValidation.validate();
      const isCtaTextValid = ctaTextValidation.validate();

      return (
        isHeadlinePrefixValid &&
        isHeadlineSuffixValid &&
        isSliderContentValid &&
        isCtaTextValid
      );
    });

    return () => {
      unregisterValidator(validatorId);
    };
  }, [
    block.id,
    registerValidator,
    unregisterValidator,
    headlinePrefixValidation,
    headlineSuffixValidation,
    sliderContentValidation,
    ctaTextValidation,
  ]);

  return (
    <div className="block-editor">
      <h3 className="editor-title">Hero Block</h3>

      <div className="editor-field">
        <label htmlFor="headlinePrefix" className="required">
          Headline Prefix
        </label>
        <input
          id="headlinePrefix"
          type="text"
          value={headlinePrefix}
          onChange={(e) => {
            updateBlock(block.id, { headlinePrefix: e.target.value });
            headlinePrefixValidation.clearError();
          }}
          onBlur={() => headlinePrefixValidation.validate()}
          className={`editor-input ${headlinePrefixValidation.error ? "input-error" : ""}`}
          aria-invalid={!!headlinePrefixValidation.error}
          aria-describedby={
            headlinePrefixValidation.error ? "headlinePrefix-error" : undefined
          }
        />
        {headlinePrefixValidation.error && (
          <div
            className="error-message"
            id="headlinePrefix-error"
            role="alert"
            aria-live="polite"
          >
            <XCircle size={14} />
            {headlinePrefixValidation.error}
          </div>
        )}
      </div>

      <div className="editor-field">
        <label htmlFor="sliderContent" className="required">
          Animated Words
        </label>
        <ArrayInput
          label=""
          items={sliderContent}
          onChange={(items) => {
            updateBlock(block.id, { sliderContent: items });
            sliderContentValidation.clearError();
          }}
          placeholder="Add word"
          maxItems={6}
        />
        {sliderContentValidation.error && (
          <div
            className="error-message"
            id="sliderContent-error"
            role="alert"
            aria-live="polite"
          >
            <XCircle size={14} />
            {sliderContentValidation.error}
          </div>
        )}
      </div>

      <div className="editor-field">
        <label htmlFor="headlineSuffix" className="required">
          Headline Suffix
        </label>
        <input
          id="headlineSuffix"
          type="text"
          value={headlineSuffix}
          onChange={(e) => {
            updateBlock(block.id, { headlineSuffix: e.target.value });
            headlineSuffixValidation.clearError();
          }}
          onBlur={() => headlineSuffixValidation.validate()}
          className={`editor-input ${headlineSuffixValidation.error ? "input-error" : ""}`}
          aria-invalid={!!headlineSuffixValidation.error}
          aria-describedby={
            headlineSuffixValidation.error ? "headlineSuffix-error" : undefined
          }
        />
        {headlineSuffixValidation.error && (
          <div
            className="error-message"
            id="headlineSuffix-error"
            role="alert"
            aria-live="polite"
          >
            <XCircle size={14} />
            {headlineSuffixValidation.error}
          </div>
        )}
      </div>

      <ArrayInput
        label="Features"
        items={features}
        onChange={(items) => updateBlock(block.id, { features: items })}
        placeholder="Add feature"
        maxItems={5}
      />

      <div className="editor-field">
        <label htmlFor="ctaText" className="required">
          CTA Button Text
        </label>
        <input
          id="ctaText"
          type="text"
          value={ctaText}
          onChange={(e) => {
            updateBlock(block.id, { ctaText: e.target.value });
            ctaTextValidation.clearError();
          }}
          onBlur={() => ctaTextValidation.validate()}
          className={`editor-input ${ctaTextValidation.error ? "input-error" : ""}`}
          aria-invalid={!!ctaTextValidation.error}
          aria-describedby={
            ctaTextValidation.error ? "ctaText-error" : undefined
          }
        />
        {ctaTextValidation.error && (
          <div
            className="error-message"
            id="ctaText-error"
            role="alert"
            aria-live="polite"
          >
            <XCircle size={14} />
            {ctaTextValidation.error}
          </div>
        )}
      </div>

      <MediaUploader
        label="Background Video"
        value={backgroundVideo}
        onChange={(file) => {
          if (file) {
            // Create local preview URL
            // TODO: In production, upload to server and get URL
            const url = URL.createObjectURL(file);
            updateBlock(block.id, { backgroundVideo: url });
          } else {
            updateBlock(block.id, { backgroundVideo: "/videos/hero-background.mp4" });
          }
        }}
        accept="video/*"
        maxSizeMB={50}
        type="video"
      />
    </div>
  );
}
