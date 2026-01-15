import ServiceCardsBlock, { serviceCardsConfig } from './components/ServiceCardsBlock';
import TeamMemberCardBlock, { teamMemberCardConfig } from './components/TeamMemberCardBlock';

// Component registry for Destack visual editor
// Add all draggable components here
export const componentRegistry = [
  {
    component: ServiceCardsBlock,
    config: serviceCardsConfig,
  },
  {
    component: TeamMemberCardBlock,
    config: teamMemberCardConfig,
  },
];

export type ComponentConfig = typeof componentRegistry[number];
