"use client";

import {
  createContext,
  useContext,
  useState,
  useCallback,
  ReactNode,
  useRef,
} from "react";

type ValidatorFunction = () => boolean;

interface ValidationContextType {
  registerValidator: (id: string, validator: ValidatorFunction) => void;
  unregisterValidator: (id: string) => void;
  validateAll: () => boolean;
}

const ValidationContext = createContext<ValidationContextType | null>(null);

export function ValidationProvider({ children }: { children: ReactNode }) {
  const validatorsRef = useRef<Map<string, ValidatorFunction>>(new Map());

  const registerValidator = useCallback(
    (id: string, validator: ValidatorFunction) => {
      validatorsRef.current.set(id, validator);
    },
    [],
  );

  const unregisterValidator = useCallback((id: string) => {
    validatorsRef.current.delete(id);
  }, []);

  const validateAll = useCallback((): boolean => {
    let allValid = true;

    // Run all registered validators
    validatorsRef.current.forEach((validator) => {
      const isValid = validator();
      if (!isValid) {
        allValid = false;
      }
    });

    return allValid;
  }, []);

  return (
    <ValidationContext.Provider
      value={{ registerValidator, unregisterValidator, validateAll }}
    >
      {children}
    </ValidationContext.Provider>
  );
}

export function useValidationContext() {
  const context = useContext(ValidationContext);
  if (!context) {
    throw new Error(
      "useValidationContext must be used within ValidationProvider",
    );
  }
  return context;
}
