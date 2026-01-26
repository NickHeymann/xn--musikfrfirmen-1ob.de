"use client";

import { createContext, useContext, useState, ReactNode, useEffect } from "react";
import { ContactModal } from "./contact";

interface ModalContextType {
  openContactModal: () => void;
  closeContactModal: () => void;
}

const ModalContext = createContext<ModalContextType | undefined>(undefined);

export function useModal() {
  const context = useContext(ModalContext);
  if (!context) {
    throw new Error("useModal must be used within a ModalProvider");
  }
  return context;
}

interface ModalProviderProps {
  children: ReactNode;
}

export default function ModalProvider({ children }: ModalProviderProps) {
  const [isContactModalOpen, setIsContactModalOpen] = useState(false);

  const openContactModal = () => setIsContactModalOpen(true);
  const closeContactModal = () => setIsContactModalOpen(false);

  // Listen for custom event from Hero component
  useEffect(() => {
    const handleOpenCalculator = () => {
      openContactModal();
    };

    window.addEventListener("openMFFCalculator", handleOpenCalculator);
    return () => window.removeEventListener("openMFFCalculator", handleOpenCalculator);
  }, []);

  // Handle ESC key to close modal
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape" && isContactModalOpen) {
        closeContactModal();
      }
    };

    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [isContactModalOpen]);

  return (
    <ModalContext.Provider value={{ openContactModal, closeContactModal }}>
      {children}
      <ContactModal isOpen={isContactModalOpen} onClose={closeContactModal} />
    </ModalContext.Provider>
  );
}
