'use client'

import { useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import { useEditor } from '../context/EditorContext'
import { EditorSidebar } from '../sidebar/EditorSidebar'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import Hero from '@/components/Hero'
import ServiceCards from '@/components/ServiceCards'
import ProcessSteps from '@/components/ProcessSteps'
import TeamSection from '@/components/TeamSection'
import FAQ from '@/components/FAQ'
import CTASection from '@/components/CTASection'

const componentRegistry: Record<string, any> = {
  Hero,
  ServiceCards,
  ProcessSteps,
  TeamSection,
  FAQ,
  CTASection,
}

export function EditMode() {
  const { blocks, selectBlock, selectedBlockId } = useEditor()
  const [hoveredBlockId, setHoveredBlockId] = useState<string | null>(null)

  return (
    <div className="edit-mode-container">
      {/* Preview (70%) */}
      <div className="edit-mode-preview">
        <Header />

        {blocks.map((block) => {
          const Component = componentRegistry[block.type]
          if (!Component) return null

          const isHovered = hoveredBlockId === block.id
          const isSelected = selectedBlockId === block.id

          return (
            <div
              key={block.id}
              className={`editable-block ${isSelected ? 'selected' : ''}`}
              onMouseEnter={() => setHoveredBlockId(block.id)}
              onMouseLeave={() => setHoveredBlockId(null)}
              onClick={() => selectBlock(block.id)}
            >
              <Component {...block.props} />

              <AnimatePresence>
                {isHovered && !isSelected && (
                  <motion.button
                    className="block-edit-button"
                    initial={{ opacity: 0, y: -10 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -10 }}
                    transition={{ duration: 0.15 }}
                    onClick={(e) => {
                      e.stopPropagation()
                      selectBlock(block.id)
                    }}
                  >
                    Edit
                  </motion.button>
                )}
              </AnimatePresence>
            </div>
          )
        })}

        <Footer />
      </div>

      {/* Sidebar (30%) */}
      <EditorSidebar />
    </div>
  )
}
