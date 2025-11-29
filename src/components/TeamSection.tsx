"use client";

import { useState } from "react";
import { motion } from "framer-motion";
import Image from "next/image";

interface TeamMember {
  name: string;
  role: string;
  image: string;
  alt: string;
  description: string;
}

const team: TeamMember[] = [
  {
    name: "Nick Heymann",
    role: "Gründer & Projektleitung",
    image: "/images/team/nick.png",
    alt: "Nick Heymann - Gründer von musikfürfirmen.de, Eventmanager und DJ aus Hamburg",
    description:
      "Seit über 10 Jahren in der Eventbranche. Kümmert sich darum, dass euer Event perfekt wird.",
  },
  {
    name: "Jonas",
    role: "Künstlerbetreuung & Booking",
    image: "/images/team/jonas.png",
    alt: "Jonas - Künstlerbetreuung und Booking bei musikfürfirmen.de Hamburg",
    description:
      "Findet die perfekte Band oder den passenden DJ für jedes Firmenevent.",
  },
];

function TeamCard({ member, index }: { member: TeamMember; index: number }) {
  const [imageError, setImageError] = useState(false);

  return (
    <motion.article
      initial={{ opacity: 0, y: 30 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, margin: "-50px" }}
      transition={{ delay: index * 0.2, duration: 0.5 }}
      className="text-center"
      itemScope
      itemType="https://schema.org/Person"
    >
      {/* Image */}
      <div className="relative w-48 h-48 mx-auto mb-6 rounded-full overflow-hidden bg-gray-100">
        {!imageError ? (
          <Image
            src={member.image}
            alt={member.alt}
            fill
            className="object-cover object-top"
            sizes="(max-width: 768px) 192px, 192px"
            onError={() => setImageError(true)}
            loading="lazy"
            itemProp="image"
          />
        ) : (
          <div className="absolute inset-0 flex items-center justify-center text-gray-300">
            <svg className="w-20 h-20" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </div>
        )}
      </div>

      {/* Info */}
      <h3 className="text-xl font-semibold text-gray-900 mb-1" itemProp="name">
        {member.name}
      </h3>
      <p className="text-[#0D7A5F] font-medium text-sm mb-3" itemProp="jobTitle">
        {member.role}
      </p>
      <p className="text-gray-600 font-light leading-relaxed max-w-sm mx-auto" itemProp="description">
        {member.description}
      </p>
      <meta itemProp="worksFor" content="musikfürfirmen.de" />
    </motion.article>
  );
}

export default function TeamSection() {
  return (
    <div
      className="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 max-w-4xl mx-auto"
      role="list"
      aria-label="Unser Team"
    >
      {team.map((member, index) => (
        <TeamCard key={member.name} member={member} index={index} />
      ))}
    </div>
  );
}
