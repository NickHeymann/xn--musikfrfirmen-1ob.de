"use client";

import { motion } from "framer-motion";

interface Step {
  number: string;
  title: string;
  description: string;
}

const steps: Step[] = [
  {
    number: "01",
    title: "Anfrage senden",
    description:
      "Teile uns deine Wünsche mit – Datum, Ort, Gästeanzahl und welche Musik ihr euch vorstellt.",
  },
  {
    number: "02",
    title: "Angebot erhalten",
    description:
      "Innerhalb von 24 Stunden bekommst du ein maßgeschneidertes Angebot mit passenden Optionen.",
  },
  {
    number: "03",
    title: "Event genießen",
    description:
      "Wir kümmern uns um alles – du lehnst dich zurück und feierst mit deinem Team.",
  },
];

export default function ProcessSteps() {
  return (
    <div className="relative">
      {/* Connection line */}
      <div className="hidden md:block absolute top-16 left-[16.666%] right-[16.666%] h-0.5 bg-gradient-to-r from-[#B2EAD8] via-[#0D7A5F] to-[#B2EAD8]" />

      <div className="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-8">
        {steps.map((step, index) => (
          <motion.div
            key={step.number}
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: "-50px" }}
            transition={{ delay: index * 0.2, duration: 0.5 }}
            className="relative text-center"
          >
            {/* Step number circle */}
            <motion.div
              initial={{ scale: 0 }}
              whileInView={{ scale: 1 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.2 + 0.2, duration: 0.4, type: "spring" }}
              className="relative z-10 mx-auto w-32 h-32 rounded-full bg-white border-4 border-[#B2EAD8] flex items-center justify-center mb-6 shadow-lg"
            >
              <span className="text-4xl font-bold text-[#0D7A5F]">
                {step.number}
              </span>
            </motion.div>

            {/* Content */}
            <h3 className="text-xl font-semibold text-gray-900 mb-3">
              {step.title}
            </h3>
            <p className="text-gray-600 font-light leading-relaxed max-w-xs mx-auto">
              {step.description}
            </p>
          </motion.div>
        ))}
      </div>
    </div>
  );
}
