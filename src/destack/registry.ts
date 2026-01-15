import ServiceCardsBlock, { serviceCardsConfig } from './components/ServiceCardsBlock';

// Component registry for Destack visual editor
// Add all draggable components here
export const componentRegistry = [
  {
    component: ServiceCardsBlock,
    config: serviceCardsConfig,
  },
  // More components will be added here
];

export type ComponentConfig = typeof componentRegistry[number];
