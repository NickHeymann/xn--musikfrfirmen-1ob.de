'use client'

import { useEditor } from '../../context/EditorContext'
import { ArrayInput } from '../../components/ArrayInput'

export function HeroEditor() {
  const { blocks, selectedBlockId, updateBlock } = useEditor()

  const block = blocks.find((b) => b.id === selectedBlockId)
  if (!block || block.type !== 'Hero') return null

  const {
    headlinePrefix = 'Deine',
    sliderContent = ['Musik', 'Livebands', 'DJs', 'Technik'],
    headlineSuffix = 'für Firmenevents!',
    features = [
      'Professionelle Bands und DJs',
      'Technik vom Feinsten',
      'Rundum-Service',
    ],
    ctaText = 'Jetzt anfragen',
  } = block.props

  return (
    <div className="block-editor">
      <h3 className="editor-title">Hero Block</h3>

      <div className="editor-field">
        <label htmlFor="headlinePrefix">Headline Prefix</label>
        <input
          id="headlinePrefix"
          type="text"
          value={headlinePrefix}
          onChange={(e) =>
            updateBlock(block.id, { headlinePrefix: e.target.value })
          }
          className="editor-input"
        />
      </div>

      <ArrayInput
        label="Animated Words"
        items={sliderContent}
        onChange={(items) => updateBlock(block.id, { sliderContent: items })}
        placeholder="Add word"
        maxItems={6}
      />

      <div className="editor-field">
        <label htmlFor="headlineSuffix">Headline Suffix</label>
        <input
          id="headlineSuffix"
          type="text"
          value={headlineSuffix}
          onChange={(e) =>
            updateBlock(block.id, { headlineSuffix: e.target.value })
          }
          className="editor-input"
        />
      </div>

      <ArrayInput
        label="Features"
        items={features}
        onChange={(items) => updateBlock(block.id, { features: items })}
        placeholder="Add feature"
        maxItems={5}
      />

      <div className="editor-field">
        <label htmlFor="ctaText">CTA Button Text</label>
        <input
          id="ctaText"
          type="text"
          value={ctaText}
          onChange={(e) =>
            updateBlock(block.id, { ctaText: e.target.value })
          }
          className="editor-input"
        />
      </div>
    </div>
  )
}
