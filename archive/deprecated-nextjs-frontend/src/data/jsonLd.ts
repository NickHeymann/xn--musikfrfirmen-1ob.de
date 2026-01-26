import { siteConfig } from "@/config/site";
import { faqItems } from "./faq";

// =====================================================
// JSON-LD SCHEMA.ORG DATA
// Dynamisch generiert aus zentralen Datenquellen
// =====================================================

export function generateJsonLd() {
  return {
    "@context": "https://schema.org",
    "@graph": [
      // LocalBusiness
      {
        "@type": "LocalBusiness",
        "@id": `${siteConfig.domain}/#business`,
        name: siteConfig.name,
        alternateName: "Musik für Firmen",
        description:
          "Professionelle Livebands, DJs und Veranstaltungstechnik für unvergessliche Firmenevents in Hamburg und deutschlandweit.",
        url: siteConfig.domain,
        telephone: siteConfig.phone.replace(/\s/g, ""),
        email: siteConfig.email,
        address: {
          "@type": "PostalAddress",
          addressLocality: siteConfig.address.city,
          addressCountry: siteConfig.address.country,
        },
        geo: {
          "@type": "GeoCoordinates",
          latitude: 53.5511,
          longitude: 9.9937,
        },
        areaServed: {
          "@type": "Country",
          name: "Deutschland",
        },
        serviceType: [
          "Liveband für Firmenevents",
          "DJ für Firmenfeiern",
          "Veranstaltungstechnik",
          "Event-Entertainment",
        ],
        priceRange: "€€-€€€",
        openingHoursSpecification: {
          "@type": "OpeningHoursSpecification",
          dayOfWeek: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
          opens: "09:00",
          closes: "18:00",
        },
        founder: {
          "@type": "Person",
          name: siteConfig.founder.name,
          jobTitle: siteConfig.founder.jobTitle,
        },
        sameAs: [],
      },
      // WebSite
      {
        "@type": "WebSite",
        "@id": `${siteConfig.domain}/#website`,
        url: siteConfig.domain,
        name: siteConfig.name,
        description: "Deine Musik für Firmenevents",
        publisher: {
          "@id": `${siteConfig.domain}/#business`,
        },
        inLanguage: "de-DE",
      },
      // Services
      {
        "@type": "Service",
        "@id": `${siteConfig.domain}/#service-liveband`,
        name: "Liveband für Firmenevents",
        description:
          "Professionelle Livebands von Jazz bis Rock für Firmenevents jeder Größe.",
        provider: {
          "@id": `${siteConfig.domain}/#business`,
        },
        serviceType: "Entertainment Service",
        areaServed: "Deutschland",
      },
      {
        "@type": "Service",
        "@id": `${siteConfig.domain}/#service-dj`,
        name: "DJ für Firmenfeiern",
        description:
          "Erfahrene DJs mit professionellem Equipment für unvergessliche Firmenfeiern.",
        provider: {
          "@id": `${siteConfig.domain}/#business`,
        },
        serviceType: "Entertainment Service",
        areaServed: "Deutschland",
      },
      // FAQ Page
      {
        "@type": "FAQPage",
        "@id": `${siteConfig.domain}/#faq`,
        mainEntity: faqItems.map((item) => ({
          "@type": "Question",
          name: item.question,
          acceptedAnswer: {
            "@type": "Answer",
            text: item.answer,
          },
        })),
      },
    ],
  };
}
