"use client";

import { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";

interface ContactModalProps {
  isOpen: boolean;
  onClose: () => void;
}

type Step = 1 | 2 | 3;

interface FormData {
  packageType: string;
  eventDate: string;
  eventTime: string;
  city: string;
  guests: string;
  budget: string;
  name: string;
  email: string;
  company: string;
  phone: string;
  message: string;
  reserveDate: boolean;
}

const packages = [
  { id: "liveband", label: "Liveband", icon: "🎸" },
  { id: "dj", label: "DJ", icon: "🎧" },
  { id: "technik", label: "Nur Technik", icon: "🔊" },
  { id: "kombi", label: "Band + DJ", icon: "🎵" },
];

const guestRanges = [
  { id: "bis-50", label: "bis 50 Gäste" },
  { id: "50-100", label: "50–100 Gäste" },
  { id: "100-200", label: "100–200 Gäste" },
  { id: "200+", label: "über 200 Gäste" },
];

const budgetRanges = [
  { id: "bis-2000", label: "bis 2.000€" },
  { id: "2000-5000", label: "2.000–5.000€" },
  { id: "5000-10000", label: "5.000–10.000€" },
  { id: "10000+", label: "über 10.000€" },
  { id: "offen", label: "Noch offen" },
];

export default function ContactModal({ isOpen, onClose }: ContactModalProps) {
  const [step, setStep] = useState<Step>(1);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitStatus, setSubmitStatus] = useState<"idle" | "success" | "error">("idle");
  const [errorMessage, setErrorMessage] = useState("");

  const [formData, setFormData] = useState<FormData>({
    packageType: "",
    eventDate: "",
    eventTime: "",
    city: "",
    guests: "",
    budget: "",
    name: "",
    email: "",
    company: "",
    phone: "",
    message: "",
    reserveDate: false,
  });

  // Reset on close
  useEffect(() => {
    if (!isOpen) {
      setTimeout(() => {
        setStep(1);
        setSubmitStatus("idle");
        setErrorMessage("");
      }, 300);
    }
  }, [isOpen]);

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

  const updateField = <K extends keyof FormData>(field: K, value: FormData[K]) => {
    setFormData((prev) => ({ ...prev, [field]: value }));
  };

  const canProceedStep1 = formData.packageType && formData.guests;
  const canProceedStep2 = formData.eventDate && formData.city;
  const canSubmit = formData.name && formData.email;

  const handleSubmit = async () => {
    if (!canSubmit) return;

    setIsSubmitting(true);
    setErrorMessage("");

    try {
      const response = await fetch("/api/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error || "Fehler beim Senden");
      }

      setSubmitStatus("success");
    } catch (error) {
      setSubmitStatus("error");
      setErrorMessage(
        error instanceof Error ? error.message : "Ein Fehler ist aufgetreten"
      );
    } finally {
      setIsSubmitting(false);
    }
  };

  const renderStep1 = () => (
    <div className="space-y-6">
      <div>
        <label className="block text-sm font-medium text-gray-700 mb-3">
          Was suchst du? *
        </label>
        <div className="grid grid-cols-2 gap-3">
          {packages.map((pkg) => (
            <button
              key={pkg.id}
              type="button"
              onClick={() => updateField("packageType", pkg.id)}
              className={`p-4 rounded-xl border-2 transition-all text-left ${
                formData.packageType === pkg.id
                  ? "border-[#0D7A5F] bg-[#D4F4E8]"
                  : "border-gray-200 hover:border-gray-300"
              }`}
            >
              <span className="text-2xl mb-2 block">{pkg.icon}</span>
              <span className="font-medium text-gray-900">{pkg.label}</span>
            </button>
          ))}
        </div>
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 mb-3">
          Wie viele Gäste erwartest du? *
        </label>
        <div className="grid grid-cols-2 gap-3">
          {guestRanges.map((range) => (
            <button
              key={range.id}
              type="button"
              onClick={() => updateField("guests", range.id)}
              className={`p-3 rounded-xl border-2 transition-all ${
                formData.guests === range.id
                  ? "border-[#0D7A5F] bg-[#D4F4E8]"
                  : "border-gray-200 hover:border-gray-300"
              }`}
            >
              <span className="font-medium text-gray-900">{range.label}</span>
            </button>
          ))}
        </div>
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 mb-3">
          Budget (optional)
        </label>
        <div className="flex flex-wrap gap-2">
          {budgetRanges.map((range) => (
            <button
              key={range.id}
              type="button"
              onClick={() => updateField("budget", range.id)}
              className={`px-4 py-2 rounded-full border-2 transition-all text-sm ${
                formData.budget === range.id
                  ? "border-[#0D7A5F] bg-[#D4F4E8]"
                  : "border-gray-200 hover:border-gray-300"
              }`}
            >
              {range.label}
            </button>
          ))}
        </div>
      </div>
    </div>
  );

  const renderStep2 = () => (
    <div className="space-y-6">
      <div>
        <label className="block text-sm font-medium text-gray-700 mb-2">
          Event-Datum *
        </label>
        <input
          type="date"
          value={formData.eventDate}
          onChange={(e) => updateField("eventDate", e.target.value)}
          min={new Date().toISOString().split("T")[0]}
          className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#0D7A5F] focus:outline-none transition-colors"
        />
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 mb-2">
          Uhrzeit (optional)
        </label>
        <input
          type="time"
          value={formData.eventTime}
          onChange={(e) => updateField("eventTime", e.target.value)}
          className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#0D7A5F] focus:outline-none transition-colors"
        />
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 mb-2">
          Stadt / Ort *
        </label>
        <input
          type="text"
          value={formData.city}
          onChange={(e) => updateField("city", e.target.value)}
          placeholder="z.B. Hamburg"
          className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#0D7A5F] focus:outline-none transition-colors"
        />
      </div>

      <div className="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
        <input
          type="checkbox"
          id="reserveDate"
          checked={formData.reserveDate}
          onChange={(e) => updateField("reserveDate", e.target.checked)}
          className="mt-1 w-5 h-5 rounded border-gray-300 text-[#0D7A5F] focus:ring-[#0D7A5F]"
        />
        <label htmlFor="reserveDate" className="text-sm text-gray-600">
          <span className="font-medium text-gray-900">Datum reservieren</span>
          <br />
          Wir halten den Termin 48 Stunden für dich frei, ohne Verpflichtung.
        </label>
      </div>
    </div>
  );

  const renderStep3 = () => (
    <div className="space-y-6">
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Name *
          </label>
          <input
            type="text"
            value={formData.name}
            onChange={(e) => updateField("name", e.target.value)}
            placeholder="Max Mustermann"
            className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#0D7A5F] focus:outline-none transition-colors"
          />
        </div>
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Firma (optional)
          </label>
          <input
            type="text"
            value={formData.company}
            onChange={(e) => updateField("company", e.target.value)}
            placeholder="Firmenname"
            className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#0D7A5F] focus:outline-none transition-colors"
          />
        </div>
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 mb-2">
          E-Mail *
        </label>
        <input
          type="email"
          value={formData.email}
          onChange={(e) => updateField("email", e.target.value)}
          placeholder="max@firma.de"
          className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#0D7A5F] focus:outline-none transition-colors"
        />
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 mb-2">
          Telefon (optional)
        </label>
        <input
          type="tel"
          value={formData.phone}
          onChange={(e) => updateField("phone", e.target.value)}
          placeholder="+49 174 ..."
          className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#0D7A5F] focus:outline-none transition-colors"
        />
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 mb-2">
          Nachricht (optional)
        </label>
        <textarea
          value={formData.message}
          onChange={(e) => updateField("message", e.target.value)}
          placeholder="Erzähl uns mehr über dein Event..."
          rows={3}
          className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#0D7A5F] focus:outline-none transition-colors resize-none"
        />
      </div>
    </div>
  );

  const renderSuccess = () => (
    <div className="text-center py-8">
      <div className="w-16 h-16 bg-[#D4F4E8] rounded-full flex items-center justify-center mx-auto mb-6">
        <svg
          className="w-8 h-8 text-[#0D7A5F]"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M5 13l4 4L19 7"
          />
        </svg>
      </div>
      <h3 className="text-2xl font-semibold text-gray-900 mb-3">
        Anfrage gesendet!
      </h3>
      <p className="text-gray-600 mb-8">
        Wir melden uns innerhalb von 24 Stunden bei dir.
      </p>
      <button
        onClick={onClose}
        className="btn-primary"
      >
        Alles klar!
      </button>
    </div>
  );

  return (
    <AnimatePresence>
      {isOpen && (
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          className="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
          {/* Backdrop */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={onClose}
            className="absolute inset-0 bg-black/50 backdrop-blur-sm"
          />

          {/* Modal */}
          <motion.div
            initial={{ opacity: 0, scale: 0.95, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.95, y: 20 }}
            transition={{ type: "spring", duration: 0.5 }}
            className="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-hidden"
          >
            {/* Header */}
            <div className="sticky top-0 bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
              <div>
                <h2 className="text-xl font-semibold text-gray-900">
                  {submitStatus === "success"
                    ? "Geschafft!"
                    : "Angebot anfragen"}
                </h2>
                {submitStatus !== "success" && (
                  <p className="text-sm text-gray-500">
                    Schritt {step} von 3
                  </p>
                )}
              </div>
              <button
                onClick={onClose}
                className="p-2 hover:bg-gray-100 rounded-full transition-colors"
              >
                <svg
                  className="w-5 h-5 text-gray-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>

            {/* Progress bar */}
            {submitStatus !== "success" && (
              <div className="h-1 bg-gray-100">
                <motion.div
                  className="h-full bg-[#0D7A5F]"
                  initial={{ width: "33.33%" }}
                  animate={{ width: `${(step / 3) * 100}%` }}
                  transition={{ duration: 0.3 }}
                />
              </div>
            )}

            {/* Content */}
            <div className="px-6 py-6 overflow-y-auto max-h-[60vh]">
              {submitStatus === "success" ? (
                renderSuccess()
              ) : (
                <AnimatePresence mode="wait">
                  <motion.div
                    key={step}
                    initial={{ opacity: 0, x: 20 }}
                    animate={{ opacity: 1, x: 0 }}
                    exit={{ opacity: 0, x: -20 }}
                    transition={{ duration: 0.2 }}
                  >
                    {step === 1 && renderStep1()}
                    {step === 2 && renderStep2()}
                    {step === 3 && renderStep3()}
                  </motion.div>
                </AnimatePresence>
              )}

              {submitStatus === "error" && (
                <div className="mt-4 p-4 bg-red-50 text-red-700 rounded-xl text-sm">
                  {errorMessage}
                </div>
              )}
            </div>

            {/* Footer */}
            {submitStatus !== "success" && (
              <div className="sticky bottom-0 bg-white px-6 py-4 border-t border-gray-100 flex gap-3">
                {step > 1 && (
                  <button
                    onClick={() => setStep((s) => (s - 1) as Step)}
                    className="px-6 py-3 rounded-full border-2 border-gray-200 text-gray-700 font-medium hover:bg-gray-50 transition-colors"
                  >
                    Zurück
                  </button>
                )}
                {step < 3 ? (
                  <button
                    onClick={() => setStep((s) => (s + 1) as Step)}
                    disabled={step === 1 ? !canProceedStep1 : !canProceedStep2}
                    className="flex-1 btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    Weiter
                  </button>
                ) : (
                  <button
                    onClick={handleSubmit}
                    disabled={!canSubmit || isSubmitting}
                    className="flex-1 btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {isSubmitting ? "Wird gesendet..." : "Anfrage senden"}
                  </button>
                )}
              </div>
            )}
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  );
}
