"use client";

import ServiceCards from '@/components/ServiceCards';

// Destack-compatible wrapper for ServiceCards component
// Makes it draggable and editable in visual editor
export default function ServiceCardsBlock() {
  return <ServiceCards />;
}

// Destack configuration
export const serviceCardsConfig = {
  name: 'ServiceCards',
  label: 'Service Cards (Sticky)',
  category: 'Content',
  icon: '🎵',
  description: 'Sticky service cards showing Livebands, DJs, and Technik',
  thumbnail: '/images/blocks/service-cards.png', // Optional
};
