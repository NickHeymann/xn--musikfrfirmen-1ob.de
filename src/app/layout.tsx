import type { Metadata } from "next";
import { Poppins } from "next/font/google";
import "./globals.css";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import ModalProvider from "@/components/ModalProvider";

const poppins = Poppins({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600", "700"],
  variable: "--font-poppins",
  display: "swap",
});

export const metadata: Metadata = {
  metadataBase: new URL("https://musikfuerfirmen.de"),
  title: {
    default: "musikfürfirmen.de | Livebands, DJs & Technik für Firmenevents",
    template: "%s | musikfürfirmen.de",
  },
  description:
    "Professionelle Livebands, DJs und Veranstaltungstechnik für unvergessliche Firmenevents in Hamburg und deutschlandweit. Rundum-sorglos-Paket mit persönlicher Betreuung. Angebot innerhalb von 24h.",
  keywords: [
    "Liveband buchen",
    "Firmenevent Musik",
    "DJ Firmenfeier",
    "Eventband Hamburg",
    "Partyband buchen",
    "Coverband Firmenevent",
    "Weihnachtsfeier Band",
    "Sommerfest Musik",
    "Gala Band",
    "Veranstaltungstechnik",
    "Event DJ",
    "Firmenfeier Hamburg",
  ],
  authors: [{ name: "Nick Heymann", url: "https://musikfuerfirmen.de" }],
  creator: "musikfürfirmen.de",
  publisher: "musikfürfirmen.de",
  formatDetection: {
    email: true,
    address: true,
    telephone: true,
  },
  openGraph: {
    type: "website",
    locale: "de_DE",
    url: "https://musikfuerfirmen.de",
    siteName: "musikfürfirmen.de",
    title: "musikfürfirmen.de | Livebands, DJs & Technik für Firmenevents",
    description:
      "Professionelle Livebands, DJs und Technik für unvergessliche Firmenevents. Rundum-sorglos-Paket aus Hamburg.",
    images: [
      {
        url: "/images/og-image.jpg",
        width: 1200,
        height: 630,
        alt: "musikfürfirmen.de - Deine Musik für Firmenevents",
      },
    ],
  },
  twitter: {
    card: "summary_large_image",
    title: "musikfürfirmen.de | Livebands, DJs & Technik für Firmenevents",
    description:
      "Professionelle Livebands, DJs und Technik für unvergessliche Firmenevents.",
    images: ["/images/og-image.jpg"],
  },
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      "max-video-preview": -1,
      "max-image-preview": "large",
      "max-snippet": -1,
    },
  },
  alternates: {
    canonical: "https://musikfuerfirmen.de",
  },
  category: "Entertainment",
};

// JSON-LD Structured Data für SEO und AI-Suchmaschinen
const jsonLd = {
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "LocalBusiness",
      "@id": "https://musikfuerfirmen.de/#business",
      name: "musikfürfirmen.de",
      alternateName: "Musik für Firmen",
      description:
        "Professionelle Livebands, DJs und Veranstaltungstechnik für unvergessliche Firmenevents in Hamburg und deutschlandweit.",
      url: "https://musikfuerfirmen.de",
      telephone: "+491741699553",
      email: "kontakt@musikfuerfirmen.de",
      address: {
        "@type": "PostalAddress",
        addressLocality: "Hamburg",
        addressCountry: "DE",
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
        name: "Nick Heymann",
        jobTitle: "Gründer & Projektleitung",
      },
      sameAs: [],
    },
    {
      "@type": "WebSite",
      "@id": "https://musikfuerfirmen.de/#website",
      url: "https://musikfuerfirmen.de",
      name: "musikfürfirmen.de",
      description: "Deine Musik für Firmenevents",
      publisher: {
        "@id": "https://musikfuerfirmen.de/#business",
      },
      inLanguage: "de-DE",
    },
    {
      "@type": "Service",
      "@id": "https://musikfuerfirmen.de/#service-liveband",
      name: "Liveband für Firmenevents",
      description:
        "Professionelle Livebands von Jazz bis Rock für Firmenevents jeder Größe.",
      provider: {
        "@id": "https://musikfuerfirmen.de/#business",
      },
      serviceType: "Entertainment Service",
      areaServed: "Deutschland",
    },
    {
      "@type": "Service",
      "@id": "https://musikfuerfirmen.de/#service-dj",
      name: "DJ für Firmenfeiern",
      description:
        "Erfahrene DJs mit professionellem Equipment für unvergessliche Firmenfeiern.",
      provider: {
        "@id": "https://musikfuerfirmen.de/#business",
      },
      serviceType: "Entertainment Service",
      areaServed: "Deutschland",
    },
    {
      "@type": "FAQPage",
      "@id": "https://musikfuerfirmen.de/#faq",
      mainEntity: [
        {
          "@type": "Question",
          name: "Wie weit im Voraus sollte ich buchen?",
          acceptedAnswer: {
            "@type": "Answer",
            text: "Wir empfehlen, mindestens 4-6 Wochen vor dem Event anzufragen. Bei beliebten Terminen (z.B. Weihnachtsfeiern) solltest du noch früher planen – am besten 2-3 Monate im Voraus.",
          },
        },
        {
          "@type": "Question",
          name: "Welche Musikrichtungen bietet ihr an?",
          acceptedAnswer: {
            "@type": "Answer",
            text: "Von Jazz und Lounge über Pop und Rock bis hin zu elektronischer Musik – wir haben für jeden Geschmack und jedes Event das passende Angebot.",
          },
        },
        {
          "@type": "Question",
          name: "Ist die Technik im Preis enthalten?",
          acceptedAnswer: {
            "@type": "Answer",
            text: "Bei unseren Rundum-sorglos-Paketen ist die komplette Technik (Ton, Licht, ggf. Bühne) bereits inklusive. Du musst dich um nichts kümmern.",
          },
        },
        {
          "@type": "Question",
          name: "Wo seid ihr aktiv?",
          acceptedAnswer: {
            "@type": "Answer",
            text: "Wir sind deutschlandweit für euch da. Unser Netzwerk an Künstlern und Technikern ermöglicht professionelle Events in ganz Deutschland.",
          },
        },
      ],
    },
  ],
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="de" className={poppins.variable}>
      <head>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
        />
      </head>
      <body className="font-sans antialiased">
        <ModalProvider>
          <Header />
          <main>{children}</main>
          <Footer />
        </ModalProvider>
      </body>
    </html>
  );
}
