import type { SiteConfig, NavItem, PackageOption } from "@/types";

// =====================================================
// MUSIKFÜRFIRMEN.DE - SITE CONFIGURATION
// Single Source of Truth für alle Site-weiten Daten
// =====================================================

export const siteConfig: SiteConfig = {
  name: "musikfürfirmen.de",
  domain: "https://xn--musikfrfirmen-1ob.de",
  email: "kontakt@musikfürfirmen.de",
  phone: "+49 174 1699553",
  calComUrl: "https://cal.com/xn--musikfrfirmen-1ob.de/30min",
  address: {
    city: "Hamburg",
    country: "DE",
  },
  founder: {
    name: "Nick Heymann",
    jobTitle: "Gründer & Projektleitung",
  },
};

// Navigation Links
export const navLinks: NavItem[] = [
  { href: "/#waswirbieten", label: "Unsere Leistungen", isAnchor: true },
  { href: "/ueber-uns", label: "Über Uns", isAnchor: false },
  { href: "/#faq", label: "FAQ", isAnchor: true },
];

// Footer Links
export const footerLinks = {
  info: [
    { href: "/#wir", label: "Über uns" },
    { href: "/impressum", label: "Impressum" },
    { href: "/datenschutz", label: "Datenschutz" },
  ],
};

// Package Options für Contact Form
export const packageOptions: PackageOption[] = [
  { value: "dj", label: "Nur DJ" },
  { value: "band", label: "Full Band" },
  { value: "band_dj", label: "Full Band + DJ" },
];

// Guest Count Options
export const guestOptions = [
  { value: "lt100", label: "Unter 100" },
  { value: "100-300", label: "100 - 300" },
  { value: "300-500", label: "300 - 500" },
  { value: "gt500", label: ">500" },
];

// Hero Slider Content
export const heroSliderContent = ["Musik", "Livebands", "Djs", "Technik"];

// Hero Features
export const heroFeatures = [
  "100% unverbindlich",
  "Antwort in 24h",
  "Kostenloses Erstgespräch",
];
