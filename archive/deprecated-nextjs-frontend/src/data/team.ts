import type { TeamMember } from "@/types";

// =====================================================
// TEAM DATA - Single Source of Truth
// =====================================================

export const teamMembers: TeamMember[] = [
  {
    name: "Jonas Glamann",
    role: "Direkter Kontakt vor Ort",
    roleSecondLine: "Koordination von Band + Technik, Gitarrist",
    image: "/images/team/jonas.png",
    bioTitle: "Der Kreative",
    bioText: `Hi, ich bin Jonas. Ich bin euer direkter Kontakt vor Ort. Mit 7 Jahren habe ich angefangen Gitarre zu spielen und stehe seitdem auf der Bühne. Ich bin selbst Teil der Band und koordiniere diese, sowie alles rund um Technik. Ich halte die Abläufe zusammen.

Vor Musikfürfirmen.de habe ich selbst in vielen Coverbands gespielt. Parallel dazu habe ich als Techniker Bands wie Revolverheld und Adel Tawil auf Tour begleitet. Ich bin dadurch mit beiden Seiten von Events vertraut und weiß genau, wie ich mit allen Beteiligten kommunizieren muss.`,
    imageClass: "img1",
    position: "left",
  },
  {
    name: "Nick Heymann",
    role: "Marketingspezialist",
    roleSecondLine: "Ansprechpartner und Videograf",
    image: "/images/team/nick.png",
    bioTitle: "Der Macher",
    bioText: `Hi, ich bin Nick. Ich bin euer allgemeiner Ansprechpartner und kümmere ich mich um das Marketing und den visuellen Auftritt bei Musikfürfirmen.de.

Nach meinem Musikstudium habe ich mich als Videograf und Marketingberater selbständig gemacht. Meine Videos von verschiedenen Events sammelten über 100 Millionen Aufrufe auf Social Media.

Auf Wunsch begleite ich euer Event videographisch und liefere Material für interne Kommunikation oder Socials und eure Website.`,
    imageClass: "img2",
    position: "right",
  },
];
