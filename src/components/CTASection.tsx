"use client";

import { motion } from "framer-motion";
import { useModal } from "./ModalProvider";

interface CTASectionProps {
  heading?: string;
  description?: string;
  ctaText?: string;
}

export default function CTASection({
  heading = "Bereit für unvergessliche Musik?",
  description = "Fordere jetzt dein unverbindliches Angebot an und mach dein nächstes Firmenevent zu einem Highlight.",
  ctaText = "Jetzt Angebot anfragen"
}: CTASectionProps = {}) {
  const { openContactModal } = useModal();
  return (
    <section className="py-24 bg-gradient-to-br from-[#0D7A5F] to-[#0a5c47]">
      <div className="max-w-4xl mx-auto px-4 text-center">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="text-white mb-6"
        >
          {heading}
        </motion.h2>
        <motion.p
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ delay: 0.1, duration: 0.5 }}
          className="text-white/90 font-light mb-10 text-lg max-w-2xl mx-auto"
        >
          {description}
        </motion.p>
        <motion.button
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ delay: 0.2, duration: 0.5 }}
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.98 }}
          onClick={openContactModal}
          className="bg-white text-[#0D7A5F] px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 transition-colors shadow-lg"
        >
          {ctaText}
        </motion.button>
      </div>
    </section>
  );
}
