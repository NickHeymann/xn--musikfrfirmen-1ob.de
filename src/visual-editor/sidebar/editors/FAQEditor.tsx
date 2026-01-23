"use client";

import { useState } from "react";
import { useEditor } from "../../context/EditorContext";
import { ChevronDown, ChevronUp, Trash2, Plus, HelpCircle } from "lucide-react";
import { RichTextEditor } from "../../components/RichTextEditor";

interface FAQItem {
  question: string;
  answer: string;
  hasLink?: boolean; // NEW: Flag for special link behavior
}

export function FAQEditor() {
  const { blocks, selectedBlockId, updateBlock, expandedFAQIndex, setExpandedFAQIndex } = useEditor();

  const block = blocks.find((b) => b.id === selectedBlockId);
  if (!block || block.type !== "FAQ") return null;

  // FAQ items are enriched from defaultBlockData.ts on load
  // This ensures we show all 7 actual FAQ items from the website
  const items: FAQItem[] = (block.props.items as FAQItem[]) || [];

  const handleItemChange = (
    index: number,
    field: keyof FAQItem,
    value: string | boolean,
  ) => {
    const updated = [...items];
    updated[index] = { ...updated[index], [field]: value };
    updateBlock(block.id, { items: updated });
  };

  const handleAddItem = () => {
    const newItem: FAQItem = {
      question: "New Question?",
      answer: "Answer to the question.",
    };
    updateBlock(block.id, { items: [...items, newItem] });
    setExpandedFAQIndex(items.length); // Auto-expand new item
  };

  const handleRemoveItem = (index: number) => {
    const updated = items.filter((_, i) => i !== index);
    updateBlock(block.id, { items: updated });
    if (expandedFAQIndex === index) setExpandedFAQIndex(null);
  };

  const toggleExpand = (index: number) => {
    setExpandedFAQIndex(expandedFAQIndex === index ? null : index);
  };

  return (
    <div className="block-editor">
      <h3 className="editor-title">FAQ</h3>

      <div className="faq-list">
        {items.map((item, index) => {
          const isExpanded = expandedFAQIndex === index;

          return (
            <div key={index} className="faq-card-item">
              <div className="faq-header" onClick={() => toggleExpand(index)}>
                <div className="faq-header-left">
                  <HelpCircle size={20} className="faq-icon" />
                  <span className="faq-question-preview">{item.question}</span>
                </div>
                <div className="faq-actions">
                  <button
                    type="button"
                    onClick={(e) => {
                      e.stopPropagation();
                      handleRemoveItem(index);
                    }}
                    className="icon-button-small danger"
                    title="Remove FAQ item"
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
                <div className="faq-fields">
                  <div className="editor-field">
                    <label htmlFor={`faq-question-${index}`}>Question</label>
                    <input
                      id={`faq-question-${index}`}
                      type="text"
                      value={item.question}
                      onChange={(e) =>
                        handleItemChange(index, "question", e.target.value)
                      }
                      className="editor-input"
                    />
                  </div>

                  <div className="editor-field">
                    <RichTextEditor
                      label="Answer"
                      value={item.answer}
                      onChange={(html) => handleItemChange(index, "answer", html)}
                      placeholder="Enter the answer with formatting..."
                    />
                  </div>
                </div>
              )}
            </div>
          );
        })}
      </div>

      <button type="button" onClick={handleAddItem} className="add-faq-button">
        <Plus size={16} />
        <span>Add FAQ Item</span>
      </button>
    </div>
  );
}
