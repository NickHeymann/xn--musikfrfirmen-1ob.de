// =====================================================
// MUSIKFÜRFIRMEN.DE - TYPE DEFINITIONS
// =====================================================

// FAQ Types
export interface FAQItem {
  question: string;
  answer: string;
  hasLink?: boolean;
}

// Team Types
export interface TeamMember {
  name: string;
  role: string;
  roleSecondLine: string;
  image: string;
  bioTitle: string;
  bioText: string;
  imageClass: string;
  position: "left" | "right";
}

// Service/Process Types
export interface ServiceBlock {
  id: number;
  title: string;
  text: string;
  highlight: string;
  description: string;
}

// Contact Form Types
export interface ContactFormData {
  date: string;
  time: string;
  city: string;
  budget: string;
  guests: string;
  package: string;
  name: string;
  company: string;
  email: string;
  phone: string;
  message: string;
  privacy: boolean;
}

export interface CityResult {
  city: string;
  state: string;
}

export type PackageType = "dj" | "band" | "band_dj";

export interface PackageOption {
  value: PackageType;
  label: string;
}

// Navigation Types
export interface NavItem {
  href: string;
  label: string;
  isAnchor: boolean;
}

// Site Config Types
export interface SiteConfig {
  name: string;
  domain: string;
  email: string;
  phone: string;
  calComUrl: string;
  address: {
    city: string;
    country: string;
  };
  founder: {
    name: string;
    jobTitle: string;
  };
}

// Destack Editor Types
export interface ContentNode {
  type: 'component';
  name: string;
  id: string;
  props?: Record<string, unknown>;
}

export interface RootContent {
  type: 'root';
  children: ContentNode[];
}
