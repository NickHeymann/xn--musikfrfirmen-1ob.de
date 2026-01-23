/**
 * Default Block Data - Single Source of Truth
 * 
 * Loads default content from actual component files to ensure
 * the editor shows the same data as the live website.
 */

import { faqItems } from '@/data/faq';
import type { Block } from '../types';

// Import default services from ServiceCards component
const defaultServices = [
  {
    number: "01",
    title: "Livebands",
    image: "/images/services/liveband.jpg",
    colorOverlay: "from-emerald-400/65 to-teal-500/75",
    texts: [
      "Unser absolutes Alleinstellungsmerkmal. Wir arbeiten mit einer festen Stammband, die wir persönlich kennen und die wir je nach Bedarf individuell für euch zusammenstellen und auf euer Event abstimmen.",
      "Viele Agenturen vermitteln nur Kontakte, die Bands kennen sie kaum persönlich. Bei musikfürfirmen.de läuft das anders. Wir arbeiten mit einer eingespielten Stammband, die je nach Eventgröße flexibel in unterschiedlichen Besetzungen auftritt.",
      "Alle Musiker:innen haben wir persönlich ausgewählt und zu einem harmonischen Team geformt. So garantieren wir absolute Spitzenqualität und eine sorgenfreie Zusammenarbeit.",
    ],
    icon: "Music",
  },
  {
    number: "02",
    title: "DJ's",
    image: "/images/services/dj.jpg",
    colorOverlay: "from-cyan-400/65 to-emerald-500/75",
    texts: [
      "Unsere DJs liefern euch den perfekten Mix aus Klassikern und aktuellen Hits, maßgeschneidert für euer Event und perfekt abgestimmt auf eure individuellen Musikwünsche.",
      "Ob entspannte Afterwork-Party oder festliches Firmenjubiläum: Wir finden über unser Netzwerk genau den richtigen DJ für euren Anlass. DJs lassen sich hervorragend mit einer Liveband kombinieren.",
      "Auf Wunsch erweitern wir das Setup um live auftretende Sänger:innen oder Saxofonist:innen.",
    ],
    icon: "Disc",
  },
  {
    number: "03",
    title: "Technik",
    image: "/images/services/technik.jpg",
    colorOverlay: "from-teal-500/70 to-emerald-600/80",
    texts: [
      "Mit Musik- und Lichttechnik im Wert von über 100.000 € stellen wir für jede Eventgröße die perfekte Ausstattung damit unsere Künstler:innen ihre Performance optimal präsentieren können.",
      "Unser umfangreiches Equipment ermöglicht es uns, für Events jeder Größenordnung die ideale Sound- und Lichtausstattung bereitzustellen.",
      "Das Ergebnis: Ihr seid begeistert von der professionellen Darbietung, und unsere Musiker:innen können sich im besten Licht präsentieren.",
    ],
    icon: "Speaker",
  },
];

// Process Steps from services.ts
const defaultProcessSteps = [
  {
    number: 1,
    title: "60 Sekunden",
    description: "Schickt uns eure Anfrage innerhalb von 60 Sekunden über unser Formular. Schnell, einfach und unkompliziert.",
  },
  {
    number: 2,
    title: "24 Stunden",
    description: "Erhaltet ein kostenloses Angebot innerhalb von 24 Stunden. Durch das von euch ausgefüllte Formular liefern wir euch ein individuelles Angebot.",
  },
  {
    number: 3,
    title: "Rundum-Service",
    description: "Genießt professionelle Betreuung bis zum großen Tag! Wir sind 24/7 erreichbar. Über WhatsApp, Telefon oder E-Mail.",
  },
];

// Team members
const defaultTeamMembers = [
  {
    name: "Jonas Glamann",
    role: "Direkter Kontakt vor Ort",
    roleSecondLine: "Koordination von Band + Technik, Gitarrist",
    image: "/images/team/jonas.png",
    bioTitle: "Der Kreative",
    bioText: "Hi, ich bin Jonas. Ich bin euer direkter Kontakt vor Ort. Mit 7 Jahren habe ich angefangen Gitarre zu spielen und stehe seitdem auf der Bühne. Ich bin selbst Teil der Band und koordiniere diese, sowie alles rund um Technik. Ich halte die Abläufe zusammen. Vor Musikfürfirmen.de habe ich selbst in vielen Coverbands gespielt. Parallel dazu habe ich als Techniker Bands wie Revolverheld und Adel Tawil auf Tour begleitet. Ich bin dadurch mit beiden Seiten von Events vertraut und weiß genau, wie ich mit allen Beteiligten kommunizieren muss.",
    imageClass: "img1",
    position: "left" as const,
  },
  {
    name: "Nick Heymann",
    role: "Marketingspezialist",
    roleSecondLine: "Ansprechpartner und Videograf",
    image: "/images/team/nick.png",
    bioTitle: "Der Macher",
    bioText: "Hi, ich bin Nick. Ich bin euer allgemeiner Ansprechpartner und kümmere ich mich um das Marketing und den visuellen Auftritt bei Musikfürfirmen.de. Nach meinem Musikstudium habe ich mich als Videograf und Marketingberater selbständig gemacht. Meine Videos von verschiedenen Events sammelten über 100 Millionen Aufrufe auf Social Media. Auf Wunsch begleite ich euer Event videographisch und liefere Material für interne Kommunikation oder Socials und eure Website.",
    imageClass: "img2",
    position: "right" as const,
  },
];;

// Hero defaults
const defaultHeroProps = {
  headlinePrefix: "Deine",
  sliderContent: ["Musik", "Livebands", "DJs", "Technik"],
  headlineSuffix: "für Firmenevents!",
  features: [
    "Musik für jedes Firmenevent",
    "Rundum-sorglos-Paket",
    "Angebot innerhalb von 24 Stunden",
  ],
  ctaText: "Unverbindliches Angebot anfragen",
  backgroundVideo: "/videos/hero-background.mp4",
};

/**
 * Enriches blocks with default content if props are missing
 * 
 * This ensures the editor shows the same content as the live website
 * when the Laravel API doesn't have stored overrides.
 */
export function enrichBlocksWithDefaults(blocks: Block[]): Block[] {
  return blocks.map((block) => {
    switch (block.type) {
      case 'ServiceCards':
        return {
          ...block,
          props: {
            ...block.props,
            services: block.props.services || defaultServices,
          },
        };

      case 'FAQ':
        return {
          ...block,
          props: {
            title: "FAQ",
            ...block.props,
            items: block.props.items || faqItems,
          },
        };

      case 'ProcessSteps':
        return {
          ...block,
          props: {
            title: "Musik und Technik? Läuft.",
            subtitle: "Von uns geplant. Von euch gefeiert.",
            ...block.props,
            steps: block.props.steps || defaultProcessSteps,
          },
        };

      case 'TeamSection':
        return {
          ...block,
          props: {
            ...block.props,
            teamMembers: block.props.teamMembers || defaultTeamMembers,
          },
        };

      case 'Hero':
        return {
          ...block,
          props: {
            ...defaultHeroProps,
            ...block.props, // Override with any stored props
          },
        };

      default:
        return block;
    }
  });
}
