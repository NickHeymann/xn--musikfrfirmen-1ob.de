"use client";

import TeamMemberCard from '@/components/TeamMemberCard';

// Destack-compatible wrapper with sample data for POC
export default function TeamMemberCardBlock() {
  const sampleData = {
    name: "Sample Team Member",
    title: "Position",
    subtitle: "Role",
    image: "/images/team/sample.jpg",
    bio: "Sample bio text",
    tags: ["Tag1", "Tag2"],
    stats: [
      { value: "100+", label: "Metric" }
    ],
    timeline: [
      {
        year: "2020",
        title: "Milestone",
        description: "Description",
        image: "/images/timeline/sample.jpg"
      }
    ]
  };

  return <TeamMemberCard {...sampleData} />;
}

// Destack configuration
export const teamMemberCardConfig = {
  name: 'TeamMemberCard',
  label: 'Team Member Card',
  category: 'People',
  icon: '👤',
  description: 'Team member profile card with timeline and stats',
};
