"use client";

import { motion } from "framer-motion";

interface ServiceCard {
  title: string;
  description: string;
  features: string[];
  icon: React.ReactNode;
}

const services: ServiceCard[] = [
  {
    title: "Livebands",
    description:
      "Von Jazz bis Rock – professionelle Bands für jeden Anlass und jede Firmengröße.",
    features: [
      "Große Bandauswahl",
      "Alle Musikrichtungen",
      "Individuelle Setlists",
      "Professionelles Equipment",
    ],
    icon: (
      <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="1.5"
        className="w-10 h-10"
      >
        <path
          strokeLinecap="round"
          strokeLinejoin="round"
          d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009 15.553z"
        />
      </svg>
    ),
  },
  {
    title: "DJs",
    description:
      "Erfahrene DJs, die genau wissen, wie man die Tanzfläche füllt und die Stimmung hält.",
    features: [
      "Professionelle DJs",
      "Modernes Equipment",
      "Flexible Musikauswahl",
      "Licht & Sound inklusive",
    ],
    icon: (
      <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="1.5"
        className="w-10 h-10"
      >
        <path
          strokeLinecap="round"
          strokeLinejoin="round"
          d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
        <path
          strokeLinecap="round"
          strokeLinejoin="round"
          d="M9 12a3 3 0 106 0 3 3 0 00-6 0z"
        />
      </svg>
    ),
  },
  {
    title: "Technik",
    description:
      "Komplette Veranstaltungstechnik: Licht, Ton und Bühne – alles aus einer Hand.",
    features: [
      "PA-Systeme",
      "Professionelle Beleuchtung",
      "Bühnenaufbau",
      "Technische Betreuung",
    ],
    icon: (
      <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="1.5"
        className="w-10 h-10"
      >
        <path
          strokeLinecap="round"
          strokeLinejoin="round"
          d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"
        />
      </svg>
    ),
  },
];

const cardVariants = {
  hidden: { opacity: 0, y: 40 },
  visible: (i: number) => ({
    opacity: 1,
    y: 0,
    transition: {
      delay: i * 0.15,
      duration: 0.5,
      ease: "easeOut",
    },
  }),
};

export default function ServiceCards() {
  return (
    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
      {services.map((service, index) => (
        <motion.div
          key={service.title}
          custom={index}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, margin: "-50px" }}
          variants={cardVariants}
          whileHover={{ y: -8, transition: { duration: 0.3 } }}
          className="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-100"
        >
          {/* Icon */}
          <div className="mb-6 text-[#0D7A5F] group-hover:text-[#B2EAD8] transition-colors duration-300">
            {service.icon}
          </div>

          {/* Title */}
          <h3 className="text-2xl font-semibold text-gray-900 mb-3">
            {service.title}
          </h3>

          {/* Description */}
          <p className="text-gray-600 font-light mb-6 leading-relaxed">
            {service.description}
          </p>

          {/* Features */}
          <ul className="space-y-2">
            {service.features.map((feature) => (
              <li
                key={feature}
                className="flex items-center gap-2 text-sm text-gray-600 font-light"
              >
                <span className="text-[#0D7A5F] text-lg">✓</span>
                {feature}
              </li>
            ))}
          </ul>

          {/* Hover accent line */}
          <div className="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-[#0D7A5F] to-[#B2EAD8] rounded-b-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
        </motion.div>
      ))}
    </div>
  );
}
