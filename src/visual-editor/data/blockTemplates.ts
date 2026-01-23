/**
 * Block Templates System - Default Template Definitions
 * 
 * Pre-configured block layouts that users can insert with one click.
 */

import type { BlockTemplate } from '../types/blockTemplate';

/**
 * Default block templates available in the system
 */
export const BLOCK_TEMPLATES: BlockTemplate[] = [
  // 1. Hero Section with Headline + CTA
  {
    id: 'hero-with-cta',
    name: 'Hero Section',
    description: 'Full-width hero section with headline, subheading, and call-to-action button',
    category: 'hero',
    preview: '/templates/hero-with-cta.jpg',
    blocks: [
      {
        id: 'temp-hero-1',
        type: 'hero',
        props: {
          title: 'Your Headline Here',
          subtitle: 'Compelling subheading that describes your service',
          ctaText: 'Get Started',
          ctaLink: '#contact',
          backgroundImage: '',
        },
      },
    ],
    metadata: {
      createdAt: new Date().toISOString(),
      createdBy: 'system',
    },
  },

  // 2. Feature Grid (3 columns)
  {
    id: 'feature-grid-3col',
    name: 'Feature Grid',
    description: 'Three-column grid showcasing key features or services',
    category: 'features',
    preview: '/templates/feature-grid.jpg',
    blocks: [
      {
        id: 'temp-features-1',
        type: 'service-cards',
        props: {
          title: 'Our Services',
          services: [
            {
              title: 'Feature One',
              description: 'Description of your first key feature or service',
              icon: 'star',
            },
            {
              title: 'Feature Two',
              description: 'Description of your second key feature or service',
              icon: 'check',
            },
            {
              title: 'Feature Three',
              description: 'Description of your third key feature or service',
              icon: 'heart',
            },
          ],
        },
      },
    ],
    metadata: {
      createdAt: new Date().toISOString(),
      createdBy: 'system',
    },
  },

  // 3. Testimonial Section
  {
    id: 'testimonial-section',
    name: 'Testimonials',
    description: 'Customer testimonials section with quotes and attribution',
    category: 'testimonials',
    preview: '/templates/testimonials.jpg',
    blocks: [
      {
        id: 'temp-team-1',
        type: 'team-section',
        props: {
          title: 'What Our Clients Say',
          members: [
            {
              name: 'Client Name',
              role: 'Company, Position',
              bio: '"Testimonial quote highlighting the positive experience with your service."',
              image: '/placeholder-avatar.jpg',
            },
            {
              name: 'Client Name',
              role: 'Company, Position',
              bio: '"Another testimonial showcasing customer satisfaction and results."',
              image: '/placeholder-avatar.jpg',
            },
          ],
        },
      },
    ],
    metadata: {
      createdAt: new Date().toISOString(),
      createdBy: 'system',
    },
  },

  // 4. CTA Banner
  {
    id: 'cta-banner',
    name: 'Call-to-Action Banner',
    description: 'Eye-catching banner with headline and prominent call-to-action',
    category: 'cta',
    preview: '/templates/cta-banner.jpg',
    blocks: [
      {
        id: 'temp-cta-1',
        type: 'cta-section',
        props: {
          headline: 'Ready to Get Started?',
          subheadline: 'Contact us today for a free consultation',
          ctaText: 'Contact Us',
          ctaLink: '#contact',
          backgroundColor: '#007bff',
        },
      },
    ],
    metadata: {
      createdAt: new Date().toISOString(),
      createdBy: 'system',
    },
  },

  // 5. Two-Column Layout
  {
    id: 'two-column-layout',
    name: 'Two-Column Content',
    description: 'Balanced two-column layout with text and image',
    category: 'features',
    preview: '/templates/two-column.jpg',
    blocks: [
      {
        id: 'temp-process-1',
        type: 'process-steps',
        props: {
          title: 'How It Works',
          steps: [
            {
              title: 'Step One',
              description: 'First step in your process or explanation',
            },
            {
              title: 'Step Two',
              description: 'Second step continuing the flow',
            },
          ],
        },
      },
    ],
    metadata: {
      createdAt: new Date().toISOString(),
      createdBy: 'system',
    },
  },
];

/**
 * Get template by ID
 */
export function getTemplateById(id: string): BlockTemplate | undefined {
  return BLOCK_TEMPLATES.find((template) => template.id === id);
}

/**
 * Get templates by category
 */
export function getTemplatesByCategory(
  category: BlockTemplate['category']
): BlockTemplate[] {
  return BLOCK_TEMPLATES.filter((template) => template.category === category);
}

/**
 * Search templates by name or description
 */
export function searchTemplates(query: string): BlockTemplate[] {
  const lowerQuery = query.toLowerCase();
  return BLOCK_TEMPLATES.filter(
    (template) =>
      template.name.toLowerCase().includes(lowerQuery) ||
      template.description.toLowerCase().includes(lowerQuery)
  );
}
