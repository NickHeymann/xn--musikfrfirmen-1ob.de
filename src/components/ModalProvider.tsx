"use client";

import { createContext, useContext, useState, ReactNode } from "react";
import ContactModal from "./ContactModal";

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

  return (
    <ModalContext.Provider value={{ openContactModal, closeContactModal }}>
      {children}
      <ContactModal isOpen={isContactModalOpen} onClose={closeContactModal} />
    </ModalContext.Provider>
  );
}
