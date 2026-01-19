'use client'

import { useState } from 'react'
import { useEditor } from '../../context/EditorContext'
import { ChevronDown, ChevronUp, Trash2, Plus } from 'lucide-react'

interface ProcessStep {
  number: number
  title: string
  description: string
}

export function ProcessStepsEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor()

  const block = blocks.find((b) => b.id === selectedBlockId)
  if (!block || block.type !== 'ProcessSteps') return null

  const steps: ProcessStep[] = block.props.steps || [
    { number: 1, title: 'Anfrage senden', description: 'Kontaktieren Sie uns mit Ihren Event-Details' },
    { number: 2, title: 'Beratung', description: 'Wir besprechen Ihre Wünsche und erstellen ein Angebot' },
    { number: 3, title: 'Event durchführen', description: 'Wir sorgen für unvergessliche Musik an Ihrem Event' },
  ]

  const [expandedIndex, setExpandedIndex] = useState<number | null>(null)

  const handleStepChange = (index: number, field: keyof Omit<ProcessStep, 'number'>, value: string) => {
    const updated = [...steps]
    updated[index] = { ...updated[index], [field]: value }
    updateBlock(block.id, { steps: updated })
  }

  const handleAddStep = () => {
    const newStep: ProcessStep = {
      number: steps.length + 1,
      title: 'New Step',
      description: 'Step description',
    }
    updateBlock(block.id, { steps: [...steps, newStep] })
    setExpandedIndex(steps.length) // Auto-expand new step
  }

  const handleRemoveStep = (index: number) => {
    const updated = steps.filter((_, i) => i !== index)
    // Renumber remaining steps
    const renumbered = updated.map((step, i) => ({
      ...step,
      number: i + 1,
    }))
    updateBlock(block.id, { steps: renumbered })
    if (expandedIndex === index) setExpandedIndex(null)
  }

  const toggleExpand = (index: number) => {
    setExpandedIndex(expandedIndex === index ? null : index)
  }

  return (
    <div className="block-editor">
      <h3 className="editor-title">Process Steps</h3>

      <div className="steps-list">
        {steps.map((step, index) => {
          const isExpanded = expandedIndex === index

          return (
            <div key={index} className="step-card-item">
              <div className="step-header" onClick={() => toggleExpand(index)}>
                <div className="step-header-left">
                  <span className="step-number-badge">{step.number}</span>
                  <span className="step-title-preview">{step.title}</span>
                </div>
                <div className="step-actions">
                  <button
                    type="button"
                    onClick={(e) => {
                      e.stopPropagation()
                      handleRemoveStep(index)
                    }}
                    className="icon-button-small danger"
                    title="Remove step"
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
                <div className="step-fields">
                  <div className="editor-field">
                    <label htmlFor={`step-title-${index}`}>Title</label>
                    <input
                      id={`step-title-${index}`}
                      type="text"
                      value={step.title}
                      onChange={(e) =>
                        handleStepChange(index, 'title', e.target.value)
                      }
                      className="editor-input"
                    />
                  </div>

                  <div className="editor-field">
                    <label htmlFor={`step-description-${index}`}>Description</label>
                    <textarea
                      id={`step-description-${index}`}
                      value={step.description}
                      onChange={(e) =>
                        handleStepChange(index, 'description', e.target.value)
                      }
                      className="editor-textarea"
                      rows={3}
                    />
                  </div>
                </div>
              )}
            </div>
          )
        })}
      </div>

      <button
        type="button"
        onClick={handleAddStep}
        className="add-step-button"
      >
        <Plus size={16} />
        <span>Add Step</span>
      </button>
    </div>
  )
}
