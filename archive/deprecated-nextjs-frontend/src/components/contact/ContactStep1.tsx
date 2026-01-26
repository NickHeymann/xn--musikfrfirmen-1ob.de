import type { ContactFormData, CityResult } from "@/types";
import { guestOptions } from "@/config/site";

interface ContactStep1Props {
  formData: ContactFormData;
  errors: Record<string, string>;
  citySuggestions: CityResult[];
  showSuggestions: boolean;
  activeIndex: number;
  onUpdateField: <K extends keyof ContactFormData>(
    field: K,
    value: ContactFormData[K]
  ) => void;
  onFetchCitySuggestions: (query: string) => void;
  onHideSuggestions: () => void;
  onSelectCity: (city: CityResult) => void;
  onNext: () => void;
}

export default function ContactStep1({
  formData,
  errors,
  citySuggestions,
  showSuggestions,
  activeIndex,
  onUpdateField,
  onFetchCitySuggestions,
  onHideSuggestions,
  onSelectCity,
  onNext,
}: ContactStep1Props) {
  const today = new Date().toISOString().split("T")[0];
  const maxDate = new Date();
  maxDate.setFullYear(maxDate.getFullYear() + 5);
  const maxDateStr = maxDate.toISOString().split("T")[0];

  const inputBaseClass =
    "w-full p-2 px-[10px] text-sm font-light border-2 rounded-[10px] bg-white text-[#1a1a1a] transition-all duration-200 focus:outline-none focus:border-[#B2EAD8] focus:shadow-[0_0_0_4px_rgba(178,234,216,0.1)]";
  const inputErrorClass = "border-red-600";
  const inputNormalClass = "border-[#e0e0e0]";

  return (
    <div className="mff-step mb-4">
      <div className="mff-step-label flex items-center gap-[10px] text-[15px] font-normal mb-[14px]">
        <span className="mff-step-number inline-flex items-center justify-center w-7 h-7 bg-[#B2EAD8] text-[#292929] rounded-full text-[13px] font-semibold">
          1
        </span>
        <span>Event-Details</span>
      </div>

      {/* Date & Time Row */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-[6px] mb-[6px]">
        <div className="flex flex-col gap-[3px]">
          <label htmlFor="mff-date" className="text-[13px] font-normal text-[#1a1a1a]">
            Datum *
          </label>
          <input
            type="date"
            id="mff-date"
            value={formData.date}
            onChange={(e) => onUpdateField("date", e.target.value)}
            min={today}
            max={maxDateStr}
            className={`${inputBaseClass} ${errors.date ? inputErrorClass : inputNormalClass}`}
            style={{ fontFamily: "'Poppins', sans-serif" }}
          />
          {errors.date && <span className="text-xs text-red-600 mt-1">{errors.date}</span>}
        </div>
        <div className="flex flex-col gap-[3px]">
          <label htmlFor="mff-time" className="text-[13px] font-normal text-[#1a1a1a]">
            Startzeit Event (optional)
          </label>
          <input
            type="time"
            id="mff-time"
            value={formData.time}
            onChange={(e) => onUpdateField("time", e.target.value)}
            className={`${inputBaseClass} ${inputNormalClass}`}
            style={{ fontFamily: "'Poppins', sans-serif" }}
          />
        </div>
      </div>

      {/* City & Budget Row */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-[6px] mb-[6px]">
        <div className="flex flex-col gap-[3px] relative">
          <label htmlFor="mff-city" className="text-[13px] font-normal text-[#1a1a1a]">
            Stadt *
          </label>
          <input
            type="text"
            id="mff-city"
            value={formData.city}
            onChange={(e) => {
              onUpdateField("city", e.target.value);
              onFetchCitySuggestions(e.target.value);
            }}
            onBlur={onHideSuggestions}
            placeholder="z.B. Hamburg"
            autoComplete="off"
            className={`${inputBaseClass} ${errors.city ? inputErrorClass : inputNormalClass}`}
            style={{ fontFamily: "'Poppins', sans-serif" }}
          />
          {errors.city && <span className="text-xs text-red-600 mt-1">{errors.city}</span>}

          {/* City Autocomplete Dropdown */}
          {showSuggestions && citySuggestions.length > 0 && (
            <div className="absolute top-full left-0 right-0 bg-white border-2 border-[#e0e0e0] border-t-0 rounded-b-xl max-h-[200px] overflow-y-auto z-[1000] shadow-[0_8px_24px_rgba(0,0,0,0.12)]">
              {citySuggestions.map((city, index) => (
                <div
                  key={index}
                  onClick={() => onSelectCity(city)}
                  className={`p-[10px_12px] cursor-pointer text-[13px] font-light border-b border-[#f0f0f0] last:border-b-0 transition-colors duration-200 hover:bg-[#B2EAD8] ${
                    activeIndex === index ? "bg-[#B2EAD8]" : ""
                  }`}
                >
                  <span className="font-normal text-[#1a1a1a]">{city.city}</span>
                  {city.state && (
                    <span className="text-xs text-[#666666] ml-2">{city.state}</span>
                  )}
                </div>
              ))}
            </div>
          )}
        </div>
        <div className="flex flex-col gap-[3px]">
          <label htmlFor="mff-budget" className="text-[13px] font-normal text-[#1a1a1a]">
            Budget (optional)
          </label>
          <input
            type="text"
            id="mff-budget"
            value={formData.budget}
            onChange={(e) => onUpdateField("budget", e.target.value)}
            placeholder="z.B. 5.000 €"
            className={`${inputBaseClass} ${inputNormalClass}`}
            style={{ fontFamily: "'Poppins', sans-serif" }}
          />
        </div>
      </div>

      {/* Guests */}
      <div className="flex flex-col gap-[3px] mb-[6px]">
        <label htmlFor="mff-guests" className="text-[13px] font-normal text-[#1a1a1a]">
          Anzahl Gäste *
        </label>
        <select
          id="mff-guests"
          value={formData.guests}
          onChange={(e) => onUpdateField("guests", e.target.value)}
          className={`${inputBaseClass} ${errors.guests ? inputErrorClass : inputNormalClass}`}
          style={{ fontFamily: "'Poppins', sans-serif" }}
        >
          <option value="">Bitte wählen...</option>
          {guestOptions.map((opt) => (
            <option key={opt.value} value={opt.value}>
              {opt.label}
            </option>
          ))}
        </select>
        {errors.guests && (
          <span className="text-xs text-red-600 mt-1">{errors.guests}</span>
        )}
      </div>

      {/* Next Button */}
      <div className="grid grid-cols-1 gap-3 mt-4">
        <button
          type="button"
          onClick={onNext}
          className="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[6px] bg-[#B2EAD8] text-[#292929] hover:bg-[#7dc9b1] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)]"
          style={{ fontFamily: "'Poppins', sans-serif" }}
        >
          Weiter
        </button>
      </div>
    </div>
  );
}
