import type { Metadata } from "next";
import { Poppins } from "next/font/google";
import "./globals.css";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import ModalProvider from "@/components/ModalProvider";
import { DebugPanel } from "@/components/DebugPanel";
import { ErrorLoggerInit } from "@/components/ErrorLoggerInit";
import { siteConfig } from "@/config/site";
import { generateJsonLd } from "@/data/jsonLd";

const poppins = Poppins({
  subsets: ["latin"],
  weight: ["300", "400", "500", "600", "700"],
  variable: "--font-poppins",
  display: "swap",
});

export const metadata: Metadata = {
  metadataBase: new URL(siteConfig.domain),
  title: {
    default: `${siteConfig.name} | Livebands, DJs & Technik für Firmenevents`,
    template: `%s | ${siteConfig.name}`,
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
  authors: [{ name: siteConfig.founder.name, url: siteConfig.domain }],
  creator: siteConfig.name,
  publisher: siteConfig.name,
  formatDetection: {
    email: true,
    address: true,
    telephone: true,
  },
  openGraph: {
    type: "website",
    locale: "de_DE",
    url: siteConfig.domain,
    siteName: siteConfig.name,
    title: `${siteConfig.name} | Livebands, DJs & Technik für Firmenevents`,
    description:
      "Professionelle Livebands, DJs und Technik für unvergessliche Firmenevents. Rundum-sorglos-Paket aus Hamburg.",
    images: [
      {
        url: "/images/og-image.jpg",
        width: 1200,
        height: 630,
        alt: `${siteConfig.name} - Deine Musik für Firmenevents`,
      },
    ],
  },
  twitter: {
    card: "summary_large_image",
    title: `${siteConfig.name} | Livebands, DJs & Technik für Firmenevents`,
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
    canonical: siteConfig.domain,
  },
  category: "Entertainment",
  icons: {
    icon: "/favicon.svg",
    apple: "/apple-touch-icon.png",
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const jsonLd = generateJsonLd();

  return (
    <html lang="de" className={poppins.variable}>
      <head>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
        />
      </head>
      <body className="font-sans antialiased">
        <ErrorLoggerInit />
        <ModalProvider>
          <Header />
          <main>{children}</main>
          <Footer />
        </ModalProvider>
        <DebugPanel />
      </body>
    </html>
  );
}
