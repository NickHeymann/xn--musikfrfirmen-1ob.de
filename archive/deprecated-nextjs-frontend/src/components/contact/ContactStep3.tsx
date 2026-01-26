import type { ContactFormData } from "@/types";
import { siteConfig } from "@/config/site";
import { SpinnerIcon } from "@/components/icons";

interface ContactStep3Props {
  formData: ContactFormData;
  errors: Record<string, string>;
  isSubmitting: boolean;
  submitStatus: "idle" | "success" | "error";
  errorMessage?: string;
  onUpdateField: <K extends keyof ContactFormData>(
    field: K,
    value: ContactFormData[K]
  ) => void;
  onSubmit: () => void;
}

export default function ContactStep3({
  formData,
  errors,
  isSubmitting,
  submitStatus,
  errorMessage,
  onUpdateField,
  onSubmit,
}: ContactStep3Props) {
  const inputBaseClass =
    "w-full p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white text-[#1a1a1a] transition-all duration-200 focus:outline-none focus:border-[#B2EAD8] focus:shadow-[0_0_0_4px_rgba(178,234,216,0.1)]";
  const inputErrorClass = "border-red-600";
  const inputNormalClass = "border-[#e0e0e0]";

  const openCalcom = () => {
    window.open(siteConfig.calComUrl, "_blank");
  };

  return (
    <form
      className="mff-step mb-4"
      onSubmit={(e) => {
        e.preventDefault();
        onSubmit();
      }}
    >
      <div className="mff-step-label flex items-center gap-[10px] text-[15px] font-normal mb-[14px]">
        <span className="mff-step-number inline-flex items-center justify-center w-7 h-7 bg-[#B2EAD8] text-[#292929] rounded-full text-[13px] font-semibold">
          3
        </span>
        <span>Deine Kontaktdaten</span>
      </div>

      {/* Name & Company Row */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-[6px] mb-[6px]">
        <div className="flex flex-col gap-[3px]">
          <label htmlFor="mff-name" className="text-[13px] font-normal text-[#1a1a1a]">
            Name *
          </label>
          <input
            type="text"
            id="mff-name"
            value={formData.name}
            onChange={(e) => onUpdateField("name", e.target.value)}
            placeholder="Dein Name"
            className={`${inputBaseClass} ${errors.name ? inputErrorClass : inputNormalClass}`}
            style={{ fontFamily: "'Poppins', sans-serif" }}
          />
          {errors.name && <span className="text-xs text-red-600 mt-1">{errors.name}</span>}
        </div>
        <div className="flex flex-col gap-[3px]">
          <label htmlFor="mff-company" className="text-[13px] font-normal text-[#1a1a1a]">
            Firma (optional)
          </label>
          <input
            type="text"
            id="mff-company"
            value={formData.company}
            onChange={(e) => onUpdateField("company", e.target.value)}
            placeholder="Deine Firma"
            className={`${inputBaseClass} ${inputNormalClass}`}
            style={{ fontFamily: "'Poppins', sans-serif" }}
          />
        </div>
      </div>

      {/* Email & Phone Row */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-[6px] mb-[6px]">
        <div className="flex flex-col gap-[3px]">
          <label htmlFor="mff-email" className="text-[13px] font-normal text-[#1a1a1a]">
            E-Mail *
          </label>
          <input
            type="email"
            id="mff-email"
            value={formData.email}
            onChange={(e) => onUpdateField("email", e.target.value)}
            placeholder="deine@email.de"
            className={`${inputBaseClass} ${errors.email ? inputErrorClass : inputNormalClass}`}
            style={{ fontFamily: "'Poppins', sans-serif" }}
          />
          {errors.email && (
            <span className="text-xs text-red-600 mt-1">{errors.email}</span>
          )}
        </div>
        <div className="flex flex-col gap-[3px]">
          <label htmlFor="mff-phone" className="text-[13px] font-normal text-[#1a1a1a]">
            Telefon *
          </label>
          <input
            type="tel"
            id="mff-phone"
            value={formData.phone}
            onChange={(e) => onUpdateField("phone", e.target.value)}
            placeholder="+49 123 456789"
            className={`${inputBaseClass} ${errors.phone ? inputErrorClass : inputNormalClass}`}
            style={{ fontFamily: "'Poppins', sans-serif" }}
          />
          {errors.phone && (
            <span className="text-xs text-red-600 mt-1">{errors.phone}</span>
          )}
        </div>
      </div>

      {/* Message */}
      <div className="flex flex-col gap-[3px] mb-[6px]">
        <label htmlFor="mff-message" className="text-[13px] font-normal text-[#1a1a1a]">
          Nachricht (optional)
        </label>
        <textarea
          id="mff-message"
          value={formData.message}
          onChange={(e) => onUpdateField("message", e.target.value)}
          placeholder="Weitere Details zu deinem Event..."
          className={`${inputBaseClass} ${inputNormalClass} min-h-[50px] resize-y`}
          style={{ fontFamily: "'Poppins', sans-serif" }}
        />
      </div>

      {/* Privacy Checkbox */}
      <div className="flex items-start gap-2 mt-2 p-2 bg-[#f8f8f8] rounded-lg">
        <input
          type="checkbox"
          id="mff-privacy"
          checked={formData.privacy}
          onChange={(e) => onUpdateField("privacy", e.target.checked)}
          className="w-4 h-4 mt-[2px] cursor-pointer accent-[#B2EAD8]"
        />
        <label
          htmlFor="mff-privacy"
          className="text-[11px] font-light cursor-pointer leading-[1.4]"
        >
          Ich habe die{" "}
          <a
            href="/datenschutz"
            target="_blank"
            className="text-[#B2EAD8] underline font-normal hover:text-[#7dc9b1]"
          >
            Datenschutzerklärung
          </a>{" "}
          gelesen und akzeptiert. *
        </label>
      </div>
      {errors.privacy && (
        <span className="text-xs text-red-600 mt-1 block">{errors.privacy}</span>
      )}

      {/* Buttons */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
        <button
          type="button"
          onClick={openCalcom}
          className="p-[10px_20px] text-sm font-normal rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[6px] bg-white text-[#1a1a1a] border-2 border-[#1a1a1a] hover:bg-[#1a1a1a] hover:text-white hover:-translate-y-[2px] hover:shadow-[0_2px_12px_rgba(0,0,0,0.08)]"
          style={{ fontFamily: "'Poppins', sans-serif" }}
        >
          Gespräch buchen
        </button>
        <button
          type="submit"
          disabled={isSubmitting}
          className="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[6px] bg-[#B2EAD8] text-[#292929] hover:bg-[#7dc9b1] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)] disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-[#e0e0e0]"
          style={{ fontFamily: "'Poppins', sans-serif" }}
        >
          {isSubmitting ? (
            <>
              <SpinnerIcon />
              Wird gesendet...
            </>
          ) : (
            "Anfrage absenden"
          )}
        </button>
      </div>

      {/* Error Message */}
      {submitStatus === "error" && (
        <div className="bg-red-600 text-white p-[14px] rounded-[10px] text-center mt-[14px] font-normal text-sm">
          {errorMessage || "Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut."}
        </div>
      )}
    </form>
  );
}
