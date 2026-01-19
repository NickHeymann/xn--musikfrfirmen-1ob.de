'use client'

import { useState } from 'react'
import { useEditor } from '../../context/EditorContext'
import { ChevronDown, ChevronUp, Trash2, Plus, HelpCircle } from 'lucide-react'

interface FAQItem {
  question: string
  answer: string
}

export function FAQEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor()

  const block = blocks.find((b) => b.id === selectedBlockId)
  if (!block || block.type !== 'FAQ') return null

  const items: FAQItem[] = block.props.items || [
    {
      question: 'Wie lange im Voraus sollte ich buchen?',
      answer: 'Wir empfehlen eine Buchung mindestens 3-6 Monate im Voraus.',
    },
    {
      question: 'Welche Technik ist im Preis enthalten?',
      answer: 'Alle notwendige Technik wie PA-Anlage, Mikrofone und Licht ist inklusive.',
    },
    {
      question: 'Können Sie auch im Freien spielen?',
      answer: 'Ja, wir haben Erfahrung mit Outdoor-Events und die passende Technik.',
    },
  ]

  const [expandedIndex, setExpandedIndex] = useState<number | null>(null)

  const handleItemChange = (index: number, field: keyof FAQItem, value: string) => {
    const updated = [...items]
    updated[index] = { ...updated[index], [field]: value }
    updateBlock(block.id, { items: updated })
  }

  const handleAddItem = () => {
    const newItem: FAQItem = {
      question: 'New Question?',
      answer: 'Answer to the question.',
    }
    updateBlock(block.id, { items: [...items, newItem] })
    setExpandedIndex(items.length) // Auto-expand new item
  }

  const handleRemoveItem = (index: number) => {
    const updated = items.filter((_, i) => i !== index)
    updateBlock(block.id, { items: updated })
    if (expandedIndex === index) setExpandedIndex(null)
  }

  const toggleExpand = (index: number) => {
    setExpandedIndex(expandedIndex === index ? null : index)
  }

  return (
    <div className="block-editor">
      <h3 className="editor-title">FAQ</h3>

      <div className="faq-list">
        {items.map((item, index) => {
          const isExpanded = expandedIndex === index

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
                      e.stopPropagation()
                      handleRemoveItem(index)
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
                        handleItemChange(index, 'question', e.target.value)
                      }
                      className="editor-input"
                    />
                  </div>

                  <div className="editor-field">
                    <label htmlFor={`faq-answer-${index}`}>Answer</label>
                    <textarea
                      id={`faq-answer-${index}`}
                      value={item.answer}
                      onChange={(e) =>
                        handleItemChange(index, 'answer', e.target.value)
                      }
                      className="editor-textarea"
                      rows={4}
                    />
                  </div>
                </div>
              )}
            </div>
          )
        })}
      </div>

      <button type="button" onClick={handleAddItem} className="add-faq-button">
        <Plus size={16} />
        <span>Add FAQ Item</span>
      </button>
    </div>
  )
}
