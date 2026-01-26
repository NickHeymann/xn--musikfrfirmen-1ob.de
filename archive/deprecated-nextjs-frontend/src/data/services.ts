import type { ServiceBlock } from "@/types";

// =====================================================
// SERVICES/PROCESS STEPS DATA - Single Source of Truth
// =====================================================

export const serviceBlocks: ServiceBlock[] = [
  {
    id: 1,
    title: "60 Sekunden",
    text: "Schickt uns eure Anfrage innerhalb von ",
    highlight: "60 Sekunden",
    description: " über unser Formular. Schnell, einfach und unkompliziert.",
  },
  {
    id: 2,
    title: "24 Stunden",
    text: "Erhaltet ein kostenloses Angebot innerhalb von ",
    highlight: "24 Stunden",
    description:
      ". Durch das von euch ausgefüllte Formular liefern wir euch ein individuelles Angebot.",
  },
  {
    id: 3,
    title: "Rundum-Service",
    text: "Genießt ",
    highlight: "professionelle Betreuung",
    description:
      " bis zum großen Tag! Wir sind 24/7 erreichbar. Über WhatsApp, Telefon oder E-Mail.",
  },
];
