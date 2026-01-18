'use client'

import type { FC } from 'react'
import { useEditor } from '../context/EditorContext'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import Hero from '@/components/Hero'
import ServiceCards from '@/components/ServiceCards'
import ProcessSteps from '@/components/ProcessSteps'
import TeamSection from '@/components/TeamSection'
import FAQ from '@/components/FAQ'
import CTASection from '@/components/CTASection'

const componentRegistry: Record<string, FC<any>> = {
  Hero,
  ServiceCards,
  ProcessSteps,
  TeamSection,
  FAQ,
  CTASection,
}

export function ViewMode() {
  const { blocks } = useEditor()

  return (
    <div className="view-mode">
      <Header />

      {blocks.map((block) => {
        const Component = componentRegistry[block.type]
        if (!Component) {
          console.warn(`Unknown component: ${block.type}`)
          return null
        }
        return <Component key={block.id} {...block.props} />
      })}

      <Footer />
    </div>
  )
}
