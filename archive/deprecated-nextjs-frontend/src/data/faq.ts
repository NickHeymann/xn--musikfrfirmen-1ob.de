import type { FAQItem } from "@/types";

// =====================================================
// FAQ DATA - Single Source of Truth
// Wird verwendet in: FAQ.tsx, layout.tsx (JSON-LD)
// =====================================================

export const faqItems: FAQItem[] = [
  {
    question: "Sind Anfragen verbindlich?",
    answer:
      "Nein, Anfragen sind komplett unverbindlich und werden innerhalb von 24 Stunden beantwortet. Gerne bieten wir dir auch ein kostenloses Erstgespräch an.",
  },
  {
    question: "Wie läuft eine Anfrage bei euch ab?",
    answer: `In nur drei Schritten:

1) Klick auf "Unverbindliches Angebot anfragen"
2) Fülle das Formular mit den wichtigsten Infos zu deinem Event aus
3) Drücke auf Absenden.

Innerhalb von 24 Stunden hast du dein individuelles Angebot im Postfach.`,
    hasLink: true,
  },
  {
    question: "Kann ich Songwünsche nennen?",
    answer:
      "Auf jeden Fall! Unsere Bands haben zwar ein festes Repertoire, freuen sich aber über besondere Songwünsche. Erwähne sie einfach im Erstgespräch, so bleibt genug Zeit für die Vorbereitung.",
  },
  {
    question: "Kann man euch deutschlandweit buchen?",
    answer:
      "Absolut! Egal wo in Deutschland dein Event stattfindet, du kannst auf uns zählen. Um Anfahrt, Logistik und Unterkunft kümmern wir uns.",
  },
  {
    question: "Was passiert, wenn die Sängerin/Sänger krank wird?",
    answer:
      "Keine Sorge, dafür sind wir vorbereitet! Für alle unsere Künstler:innen haben wir erfahrene Ersatzleute parat, die kurzfristig einspringen können. Sollte es dazu kommen, melden wir uns natürlich sofort bei dir.",
  },
  {
    question: "Muss ich mich noch um irgendetwas kümmern?",
    answer:
      "Nein! Wir nehmen dir alles ab, was mit Musik zu tun hat: von der Auswahl der passenden Künstler:innen über Equipment und Technik bis hin zu Anfahrt und Übernachtung. Du kannst dich entspannt auf dein Event freuen.",
  },
  {
    question: "Warum sollte ich nicht alles über eine Eventagentur buchen?",
    answer:
      "Gute Frage! Bei den meisten Eventagenturen läuft Musik eher nebenbei. Ob die Band gut ist, wird dann zur Glückssache. Wir sehen das anders: Musik prägt die Stimmung und bleibt in Erinnerung. Deshalb geben wir ihr die Aufmerksamkeit, die sie verdient.",
  },
];
