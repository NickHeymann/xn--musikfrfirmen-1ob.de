"use client";

import { useState, useEffect } from "react";
import Link from "next/link";
import { motion, AnimatePresence } from "framer-motion";
import { useModal } from "./ModalProvider";

const navItems = [
  { href: "/#leistungen", label: "Unsere Leistungen" },
  { href: "/#wir", label: "Über Uns" },
  { href: "/#faq", label: "FAQ" },
];

export default function Header() {
  const [isScrolled, setIsScrolled] = useState(false);
  const [isVisible, setIsVisible] = useState(true);
  const [lastScrollY, setLastScrollY] = useState(0);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const { openContactModal } = useModal();

  useEffect(() => {
    const handleScroll = () => {
      const currentScrollY = window.scrollY;

      // Header sichtbar/versteckt basierend auf Scroll-Richtung
      if (currentScrollY <= 10) {
        setIsVisible(true);
        setIsScrolled(false);
      } else if (currentScrollY > lastScrollY + 5) {
        // Scrolling down
        setIsVisible(false);
      } else if (currentScrollY < lastScrollY - 5) {
        // Scrolling up
        setIsVisible(true);
        setIsScrolled(true);
      }

      setLastScrollY(currentScrollY);
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    return () => window.removeEventListener("scroll", handleScroll);
  }, [lastScrollY]);

  return (
    <>
      <motion.header
        initial={{ y: 0 }}
        animate={{ y: isVisible ? 0 : -100 }}
        transition={{ duration: 0.3, ease: "easeInOut" }}
        className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
          isScrolled
            ? "bg-white/95 backdrop-blur-md shadow-sm"
            : "bg-white"
        }`}
      >
        <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-[78px]">
            {/* Logo */}
            <Link
              href="/"
              className="text-lg font-semibold text-gray-900 hover:opacity-70 transition-opacity"
            >
              musikfürfirmen.de
            </Link>

            {/* Desktop Navigation */}
            <div className="hidden md:flex items-center gap-8">
              {navItems.map((item) => (
                <Link
                  key={item.href}
                  href={item.href}
                  className="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors"
                >
                  {item.label}
                </Link>
              ))}
              <button
                onClick={openContactModal}
                className="btn-primary text-sm py-2 px-5"
              >
                Anfrage
              </button>
            </div>

            {/* Mobile Menu Button */}
            <button
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
              className="md:hidden p-2 text-gray-700"
              aria-label={isMobileMenuOpen ? "Menü schließen" : "Menü öffnen"}
            >
              <div className="w-6 h-5 relative flex flex-col justify-between">
                <span
                  className={`w-full h-[1px] bg-current transform transition-all duration-300 ${
                    isMobileMenuOpen ? "rotate-45 translate-y-2" : ""
                  }`}
                />
                <span
                  className={`w-full h-[1px] bg-current transition-opacity duration-300 ${
                    isMobileMenuOpen ? "opacity-0" : ""
                  }`}
                />
                <span
                  className={`w-full h-[1px] bg-current transform transition-all duration-300 ${
                    isMobileMenuOpen ? "-rotate-45 -translate-y-2" : ""
                  }`}
                />
              </div>
            </button>
          </div>
        </nav>

        {/* Border */}
        <div className="h-[1px] bg-gray-100" />
      </motion.header>

      {/* Mobile Menu Overlay */}
      <AnimatePresence>
        {isMobileMenuOpen && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.2 }}
            className="fixed inset-0 z-40 md:hidden"
          >
            {/* Backdrop */}
            <div
              className="absolute inset-0 bg-black/20 backdrop-blur-sm"
              onClick={() => setIsMobileMenuOpen(false)}
            />

            {/* Menu */}
            <motion.nav
              initial={{ x: "100%" }}
              animate={{ x: 0 }}
              exit={{ x: "100%" }}
              transition={{ duration: 0.3, ease: "easeInOut" }}
              className="absolute top-[79px] right-0 w-full bg-white shadow-lg"
            >
              <div className="py-4 px-6 space-y-1">
                {navItems.map((item) => (
                  <Link
                    key={item.href}
                    href={item.href}
                    onClick={() => setIsMobileMenuOpen(false)}
                    className="block py-3 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-lg px-3 transition-colors"
                  >
                    {item.label}
                  </Link>
                ))}
                <button
                  onClick={() => {
                    setIsMobileMenuOpen(false);
                    openContactModal();
                  }}
                  className="w-full mt-4 btn-primary"
                >
                  Anfrage senden
                </button>
              </div>
            </motion.nav>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Spacer für fixed Header */}
      <div className="h-[78px]" />
    </>
  );
}
