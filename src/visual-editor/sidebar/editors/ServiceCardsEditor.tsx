"use client";

import { useState, useEffect } from "react";
import { useEditor } from "../../context/EditorContext";
import { useValidationContext } from "../../context/ValidationContext";
import { ChevronDown, ChevronUp, Trash2, Plus, XCircle } from "lucide-react";
import { MediaUploader } from "../../components/MediaUploader";
import { RichTextEditor } from "../../components/RichTextEditor";

interface ServiceCard {
  title: string;
  description: string;
  icon: string;
  number?: string;
  image?: string;
  colorOverlay?: string;
}

export function ServiceCardsEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor();
  const { registerValidator, unregisterValidator } = useValidationContext();
  const [errors, setErrors] = useState<{ [key: string]: string }>({});

  const block = blocks.find((b) => b.id === selectedBlockId);
  if (!block || block.type !== "ServiceCards") return null;

  // Services are enriched from defaultBlockData.ts on load
  // This ensures we always have the actual website content
  const services: ServiceCard[] = (block.props.services as ServiceCard[]) || [];

  const [expandedIndex, setExpandedIndex] = useState<number | null>(null);

  const handleServiceChange = (
    index: number,
    field: keyof ServiceCard,
    value: string | string[],
  ) => {
    const updated = [...services];
    updated[index] = { ...updated[index], [field]: value };
    updateBlock(block.id, { services: updated });
  };

  const handleAddService = () => {
    const newService: ServiceCard = {
      title: "New Service",
      description: "Service description",
      icon: "Star",
      number: `0${services.length + 1}`,
    };
    updateBlock(block.id, { services: [...services, newService] });
    setExpandedIndex(services.length); // Auto-expand new service
  };

  const handleRemoveService = (index: number) => {
    const updated = services.filter((_, i) => i !== index);
    updateBlock(block.id, { services: updated });
    if (expandedIndex === index) setExpandedIndex(null);
  };

  const toggleExpand = (index: number) => {
    setExpandedIndex(expandedIndex === index ? null : index);
  };

  const validateField = (index: number, field: keyof ServiceCard) => {
    const value = services[index][field];
    const errorKey = `${index}-${field}`;

    // Handle string validation
    if (typeof value === "string") {
      if (!value || value.trim() === "") {
        setErrors((prev) => ({
          ...prev,
          [errorKey]: `${field.charAt(0).toUpperCase() + field.slice(1)} is required`,
        }));
        return false;
      }
    }

    // Clear error if validation passes
    setErrors((prev) => {
      const newErrors = { ...prev };
      delete newErrors[errorKey];
      return newErrors;
    });
    return true;
  };

  const clearError = (index: number, field: keyof ServiceCard) => {
    const errorKey = `${index}-${field}`;
    setErrors((prev) => {
      const newErrors = { ...prev };
      delete newErrors[errorKey];
      return newErrors;
    });
  };

  // Register validator
  useEffect(() => {
    const validatorId = `services-${block.id}`;
    registerValidator(validatorId, () => {
      let allValid = true;
      const newErrors: { [key: string]: string } = {};

      services.forEach((service, index) => {
        if (!service.title || service.title.trim() === "") {
          newErrors[`${index}-title`] = "Title is required";
          allValid = false;
        }
        if (!service.description || service.description.trim() === "") {
          newErrors[`${index}-description`] = "Description is required";
          allValid = false;
        }
        if (!service.icon || service.icon.trim() === "") {
          newErrors[`${index}-icon`] = "Icon is required";
          allValid = false;
        }
      });

      setErrors(newErrors);
      return allValid;
    });

    return () => {
      unregisterValidator(validatorId);
    };
  }, [block.id, services, registerValidator, unregisterValidator]);

  return (
    <div className="block-editor">
      <h3 className="editor-title">Service Cards</h3>

      <div className="services-list">
        {services.map((service, index) => {
          const isExpanded = expandedIndex === index;

          return (
            <div key={index} className="service-card-item">
              <div
                className="service-header"
                onClick={() => toggleExpand(index)}
              >
                <span className="service-title-preview">{service.title}</span>
                <div className="service-actions">
                  <button
                    type="button"
                    onClick={(e) => {
                      e.stopPropagation();
                      handleRemoveService(index);
                    }}
                    className="icon-button-small danger"
                    title="Remove service"
                  >
                    <Trash2 size={14} />
                  </button>
                  {isExpanded ? (
                    <ChevronUp size={16} />
                  ) : (
                    <ChevronDown size={16} />
                  )}
                </div>
              </div>

              {isExpanded && (
                <div className="service-fields">
                  {/* Service Number */}
                  <div className="editor-field">
                    <label htmlFor={`service-number-${index}`}>
                      Service Number
                    </label>
                    <input
                      id={`service-number-${index}`}
                      type="text"
                      value={service.number || `0${index + 1}`}
                      onChange={(e) =>
                        handleServiceChange(index, "number", e.target.value)
                      }
                      placeholder="01"
                      maxLength={2}
                      className="editor-input"
                    />
                  </div>

                  {/* Title */}
                  <div className="editor-field">
                    <label
                      htmlFor={`service-title-${index}`}
                      className="required"
                    >
                      Title
                    </label>
                    <input
                      id={`service-title-${index}`}
                      type="text"
                      value={service.title}
                      onChange={(e) => {
                        handleServiceChange(index, "title", e.target.value);
                        clearError(index, "title");
                      }}
                      onBlur={() => validateField(index, "title")}
                      className={`editor-input ${errors[`${index}-title`] ? "input-error" : ""}`}
                      aria-invalid={!!errors[`${index}-title`]}
                      aria-describedby={
                        errors[`${index}-title`]
                          ? `service-title-${index}-error`
                          : undefined
                      }
                    />
                    {errors[`${index}-title`] && (
                      <div
                        className="error-message"
                        id={`service-title-${index}-error`}
                        role="alert"
                        aria-live="polite"
                      >
                        <XCircle size={14} />
                        {errors[`${index}-title`]}
                      </div>
                    )}
                  </div>

                  {/* Description (Rich Text) */}
                  <div className="editor-field">
                    <RichTextEditor
                      label="Description"
                      value={service.description || ""}
                      onChange={(html) => {
                        handleServiceChange(index, "description", html);
                        clearError(index, "description");
                      }}
                      placeholder="Enter the service description with formatting..."
                    />
                    {errors[`${index}-description`] && (
                      <div className="error-message" role="alert">
                        <XCircle size={14} />
                        {errors[`${index}-description`]}
                      </div>
                    )}
                  </div>

                  {/* Icon */}
                  <div className="editor-field">
                    <label
                      htmlFor={`service-icon-${index}`}
                      className="required"
                    >
                      Icon
                    </label>
                    <select
                      id={`service-icon-${index}`}
                      value={service.icon}
                      onChange={(e) => {
                        handleServiceChange(index, "icon", e.target.value);
                        clearError(index, "icon");
                      }}
                      onBlur={() => validateField(index, "icon")}
                      className={`editor-select ${errors[`${index}-icon`] ? "input-error" : ""}`}
                      aria-invalid={!!errors[`${index}-icon`]}
                      aria-describedby={
                        errors[`${index}-icon`]
                          ? `service-icon-${index}-error`
                          : undefined
                      }
                    >
                      <option value="Music">Music (🎵)</option>
                      <option value="Disc">Disc (💿)</option>
                      <option value="Speaker">Speaker (🔊)</option>
                      <option value="Mic">Microphone (🎤)</option>
                      <option value="Guitar">Guitar (🎸)</option>
                      <option value="Headphones">Headphones (🎧)</option>
                      <option value="Radio">Radio (📻)</option>
                      <option value="Star">Star (⭐)</option>
                      <option value="Sparkles">Sparkles (✨)</option>
                      <option value="Heart">Heart (❤️)</option>
                    </select>
                    {errors[`${index}-icon`] && (
                      <div
                        className="error-message"
                        id={`service-icon-${index}-error`}
                        role="alert"
                        aria-live="polite"
                      >
                        <XCircle size={14} />
                        {errors[`${index}-icon`]}
                      </div>
                    )}
                  </div>

                  {/* Service Image */}
                  <MediaUploader
                    label="Service Image"
                    value={service.image}
                    onChange={(file) => {
                      if (file) {
                        const url = URL.createObjectURL(file);
                        handleServiceChange(index, "image", url);
                      } else {
                        handleServiceChange(index, "image", "");
                      }
                    }}
                    accept="image/*"
                    maxSizeMB={5}
                    type="image"
                  />

                  {/* Color Overlay */}
                  <div className="editor-field">
                    <label htmlFor={`service-color-${index}`}>
                      Color Overlay (CSS)
                    </label>
                    <input
                      id={`service-color-${index}`}
                      type="text"
                      value={service.colorOverlay || ""}
                      onChange={(e) =>
                        handleServiceChange(
                          index,
                          "colorOverlay",
                          e.target.value,
                        )
                      }
                      placeholder="linear-gradient(...)"
                      className="editor-input"
                    />
                    <p className="field-hint">
                      Optional CSS gradient or color overlay for the service
                      image
                    </p>
                  </div>
                </div>
              )}
            </div>
          );
        })}
      </div>

      <button
        type="button"
        onClick={handleAddService}
        className="add-service-button"
      >
        <Plus size={16} />
        <span>Add Service</span>
      </button>
    </div>
  );
}
