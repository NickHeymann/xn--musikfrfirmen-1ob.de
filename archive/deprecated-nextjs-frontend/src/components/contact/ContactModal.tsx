"use client";

import { useEffect } from "react";
import { CloseIcon, ChevronLeftIcon } from "@/components/icons";
import { useContactForm, useCityAutocomplete } from "./useContactForm";
import ContactStep1 from "./ContactStep1";
import ContactStep2 from "./ContactStep2";
import ContactStep3 from "./ContactStep3";
import ContactSuccess from "./ContactSuccess";

interface ContactModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export default function ContactModal({ isOpen, onClose }: ContactModalProps) {
  const {
    step,
    formData,
    errors,
    isSubmitting,
    submitStatus,
    updateField,
    handleNext,
    handlePrev,
    handleSubmit,
    reset,
  } = useContactForm();

  const {
    suggestions: citySuggestions,
    showSuggestions,
    activeIndex,
    fetchSuggestions: fetchCitySuggestions,
    hideSuggestions,
    setSuggestions,
    setShowSuggestions,
  } = useCityAutocomplete();

  // Reset on close
  useEffect(() => {
    if (!isOpen) {
      setTimeout(reset, 300);
    }
  }, [isOpen, reset]);

  // Prevent body scroll when modal is open
  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [isOpen]);

  const handleSelectCity = (city: { city: string; state: string }) => {
    updateField("city", city.city);
    setSuggestions([]);
    setShowSuggestions(false);
  };

  if (!isOpen) return null;

  return (
    <div
      className={`mff-modal-overlay fixed inset-0 z-[2147483647] flex items-center justify-center p-[10px] transition-all duration-300 ${
        isOpen ? "opacity-100 visible" : "opacity-0 invisible"
      }`}
      style={{ background: "rgba(0, 0, 0, 0)", backdropFilter: "blur(8px)" }}
      onClick={(e) => {
        if (e.target === e.currentTarget) onClose();
      }}
    >
      <div
        className={`mff-modal-content relative bg-white rounded-2xl max-w-[800px] w-full max-h-[90vh] min-h-[300px] overflow-y-auto shadow-[0_20px_60px_rgba(0,0,0,0.3)] transform transition-transform duration-300 ${
          isOpen ? "scale-100 translate-y-0" : "scale-90 translate-y-5"
        }`}
        style={{ fontFamily: "'Poppins', sans-serif" }}
      >
        {/* Close Button */}
        <button
          onClick={onClose}
          className="absolute top-5 right-5 bg-black/10 border-none rounded-full w-10 h-10 flex items-center justify-center cursor-pointer z-10 transition-all duration-200 text-[#292929] hover:bg-black/20 hover:rotate-90"
        >
          <CloseIcon className="w-6 h-6" />
        </button>

        {/* Content */}
        <div className="w-full p-4 box-border text-[#1a1a1a] leading-relaxed">
          <div className="mff-card bg-white rounded-xl p-6 md:px-[30px] relative">
            {/* Back Arrow */}
            {step > 1 && submitStatus !== "success" && (
              <button
                type="button"
                onClick={handlePrev}
                className="absolute top-4 left-4 bg-black/10 border-none rounded-full w-8 h-8 flex items-center justify-center cursor-pointer z-10 transition-all duration-200 text-[#292929] hover:bg-black/20 hover:-translate-x-[2px]"
              >
                <ChevronLeftIcon className="w-[18px] h-[18px]" />
              </button>
            )}

            {/* Header */}
            {submitStatus !== "success" && (
              <div className="mff-header text-center mb-6">
                <h2 className="text-[26px] font-normal m-0 mb-2 text-[#1a1a1a]">
                  Deine Wünsche
                  <br />
                  für ein unvergessliches Event
                </h2>
                <p className="text-sm font-light text-[#666666] m-0">
                  Teile uns deine Anforderungen mit und erhalten innerhalb von
                  24 Stunden ein unverbindliches Angebot.
                </p>
              </div>
            )}

            {/* Steps */}
            {step === 1 && (
              <ContactStep1
                formData={formData}
                errors={errors}
                citySuggestions={citySuggestions}
                showSuggestions={showSuggestions}
                activeIndex={activeIndex}
                onUpdateField={updateField}
                onFetchCitySuggestions={fetchCitySuggestions}
                onHideSuggestions={hideSuggestions}
                onSelectCity={handleSelectCity}
                onNext={handleNext}
              />
            )}

            {step === 2 && (
              <ContactStep2
                formData={formData}
                onUpdateField={updateField}
                onNext={handleNext}
              />
            )}

            {step === 3 && submitStatus !== "success" && (
              <ContactStep3
                formData={formData}
                errors={errors}
                isSubmitting={isSubmitting}
                submitStatus={submitStatus}
                onUpdateField={updateField}
                onSubmit={handleSubmit}
              />
            )}

            {submitStatus === "success" && <ContactSuccess onClose={onClose} />}
          </div>
        </div>
      </div>
    </div>
  );
}
