'use client'

import { useEditor } from '../../context/EditorContext'

export function CTASectionEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor()

  const block = blocks.find((b) => b.id === selectedBlockId)
  if (!block || block.type !== 'CTASection') return null

  const {
    heading = 'Bereit für unvergessliche Musik?',
    description = 'Fordere jetzt dein unverbindliches Angebot an und mach dein nächstes Firmenevent zu einem Highlight.',
    ctaText = 'Jetzt Angebot anfragen',
  } = block.props

  return (
    <div className="block-editor">
      <h3 className="editor-title">CTA Section</h3>

      <div className="editor-field">
        <label htmlFor="cta-heading">Heading</label>
        <input
          id="cta-heading"
          type="text"
          value={heading}
          onChange={(e) =>
            updateBlock(block.id, { heading: e.target.value })
          }
          className="editor-input"
        />
      </div>

      <div className="editor-field">
        <label htmlFor="cta-description">Description</label>
        <textarea
          id="cta-description"
          value={description}
          onChange={(e) =>
            updateBlock(block.id, { description: e.target.value })
          }
          className="editor-input"
          rows={3}
          placeholder="Short description for the CTA"
        />
      </div>

      <div className="editor-field">
        <label htmlFor="cta-text">Button Text</label>
        <input
          id="cta-text"
          type="text"
          value={ctaText}
          onChange={(e) =>
            updateBlock(block.id, { ctaText: e.target.value })
          }
          className="editor-input"
        />
      </div>

      <div className="editor-hint">
        <p>💡 Tip: This CTA section appears at the bottom of your page to encourage visitors to take action. The button opens the contact modal.</p>
      </div>
    </div>
  )
}
