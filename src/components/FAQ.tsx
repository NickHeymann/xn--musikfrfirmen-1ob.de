"use client";

import { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";

interface FAQItem {
  question: string;
  answer: string;
}

const faqItems: FAQItem[] = [
  {
    question: "Wie weit im Voraus sollte ich buchen?",
    answer:
      "Wir empfehlen, mindestens 4-6 Wochen vor dem Event anzufragen. Bei beliebten Terminen (z.B. Weihnachtsfeiern) solltest du noch früher planen – am besten 2-3 Monate im Voraus.",
  },
  {
    question: "Welche Musikrichtungen bietet ihr an?",
    answer:
      "Von Jazz und Lounge über Pop und Rock bis hin zu elektronischer Musik – wir haben für jeden Geschmack und jedes Event das passende Angebot. Teile uns einfach deine Wünsche mit.",
  },
  {
    question: "Ist die Technik im Preis enthalten?",
    answer:
      "Bei unseren Rundum-sorglos-Paketen ist die komplette Technik (Ton, Licht, ggf. Bühne) bereits inklusive. Du musst dich um nichts kümmern.",
  },
  {
    question: "Könnt ihr auch kurzfristig buchen?",
    answer:
      "Ja, wir versuchen auch kurzfristige Anfragen möglich zu machen. Kontaktiere uns einfach – wir finden eine Lösung.",
  },
  {
    question: "Wo seid ihr aktiv?",
    answer:
      "Wir sind deutschlandweit für euch da. Unser Netzwerk an Künstlern und Technikern ermöglicht professionelle Events in ganz Deutschland.",
  },
  {
    question: "Was kostet eine Buchung?",
    answer:
      "Die Kosten variieren je nach Event-Größe, Dauer und gewünschten Leistungen. Nutze unseren Kalkulator für eine erste Einschätzung oder fordere ein unverbindliches Angebot an.",
  },
];

export default function FAQ() {
  const [openIndex, setOpenIndex] = useState<number | null>(null);

  const toggleItem = (index: number) => {
    setOpenIndex(openIndex === index ? null : index);
  };

  return (
    <div className="space-y-4">
      {faqItems.map((item, index) => (
        <motion.div
          key={index}
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, margin: "-30px" }}
          transition={{ delay: index * 0.1, duration: 0.4 }}
          className="border border-gray-200 rounded-xl overflow-hidden bg-white"
        >
          <button
            onClick={() => toggleItem(index)}
            className="w-full px-6 py-5 flex items-center justify-between text-left hover:bg-gray-50 transition-colors"
          >
            <span className="font-medium text-gray-900 pr-4">
              {item.question}
            </span>
            <motion.span
              animate={{ rotate: openIndex === index ? 180 : 0 }}
              transition={{ duration: 0.3 }}
              className="flex-shrink-0 text-[#0D7A5F]"
            >
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
                strokeLinejoin="round"
              >
                <path d="M6 9l6 6 6-6" />
              </svg>
            </motion.span>
          </button>

          <AnimatePresence>
            {openIndex === index && (
              <motion.div
                initial={{ height: 0, opacity: 0 }}
                animate={{ height: "auto", opacity: 1 }}
                exit={{ height: 0, opacity: 0 }}
                transition={{ duration: 0.3, ease: "easeInOut" }}
              >
                <div className="px-6 pb-5 text-gray-600 font-light leading-relaxed">
                  {item.answer}
                </div>
              </motion.div>
            )}
          </AnimatePresence>
        </motion.div>
      ))}
    </div>
  );
}
