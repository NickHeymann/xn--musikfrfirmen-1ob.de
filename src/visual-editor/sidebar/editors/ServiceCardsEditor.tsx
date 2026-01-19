'use client'

import { useState } from 'react'
import { useEditor } from '../../context/EditorContext'
import { ChevronDown, ChevronUp, Trash2, Plus } from 'lucide-react'

interface ServiceCard {
  title: string
  description: string
  icon: string
}

export function ServiceCardsEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor()

  const block = blocks.find((b) => b.id === selectedBlockId)
  if (!block || block.type !== 'ServiceCards') return null

  const services: ServiceCard[] = block.props.services || [
    { title: 'Livebands', description: 'Professionelle Live-Musik', icon: 'Music' },
    { title: 'DJs', description: 'Moderne DJ-Sets', icon: 'Disc' },
    { title: 'Technik', description: 'Professionelle Event-Technik', icon: 'Speaker' },
  ]

  const [expandedIndex, setExpandedIndex] = useState<number | null>(null)

  const handleServiceChange = (index: number, field: keyof ServiceCard, value: string) => {
    const updated = [...services]
    updated[index] = { ...updated[index], [field]: value }
    updateBlock(block.id, { services: updated })
  }

  const handleAddService = () => {
    const newService: ServiceCard = {
      title: 'New Service',
      description: 'Service description',
      icon: 'Star',
    }
    updateBlock(block.id, { services: [...services, newService] })
    setExpandedIndex(services.length) // Auto-expand new service
  }

  const handleRemoveService = (index: number) => {
    const updated = services.filter((_, i) => i !== index)
    updateBlock(block.id, { services: updated })
    if (expandedIndex === index) setExpandedIndex(null)
  }

  const toggleExpand = (index: number) => {
    setExpandedIndex(expandedIndex === index ? null : index)
  }

  return (
    <div className="block-editor">
      <h3 className="editor-title">Service Cards</h3>

      <div className="services-list">
        {services.map((service, index) => {
          const isExpanded = expandedIndex === index

          return (
            <div key={index} className="service-card-item">
              <div className="service-header" onClick={() => toggleExpand(index)}>
                <span className="service-title-preview">{service.title}</span>
                <div className="service-actions">
                  <button
                    type="button"
                    onClick={(e) => {
                      e.stopPropagation()
                      handleRemoveService(index)
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
                  <div className="editor-field">
                    <label htmlFor={`service-title-${index}`}>Title</label>
                    <input
                      id={`service-title-${index}`}
                      type="text"
                      value={service.title}
                      onChange={(e) =>
                        handleServiceChange(index, 'title', e.target.value)
                      }
                      className="editor-input"
                    />
                  </div>

                  <div className="editor-field">
                    <label htmlFor={`service-description-${index}`}>Description</label>
                    <textarea
                      id={`service-description-${index}`}
                      value={service.description}
                      onChange={(e) =>
                        handleServiceChange(index, 'description', e.target.value)
                      }
                      className="editor-textarea"
                      rows={3}
                    />
                  </div>

                  <div className="editor-field">
                    <label htmlFor={`service-icon-${index}`}>Icon</label>
                    <select
                      id={`service-icon-${index}`}
                      value={service.icon}
                      onChange={(e) =>
                        handleServiceChange(index, 'icon', e.target.value)
                      }
                      className="editor-select"
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
                  </div>
                </div>
              )}
            </div>
          )
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
  )
}
