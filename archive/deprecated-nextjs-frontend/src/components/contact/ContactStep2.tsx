import type { ContactFormData } from "@/types";
import { packageOptions } from "@/config/site";

interface ContactStep2Props {
  formData: ContactFormData;
  onUpdateField: <K extends keyof ContactFormData>(
    field: K,
    value: ContactFormData[K]
  ) => void;
  onNext: () => void;
}

export default function ContactStep2({
  formData,
  onUpdateField,
  onNext,
}: ContactStep2Props) {
  return (
    <div className="mff-step mb-4">
      <div className="mff-step-label flex items-center gap-[10px] text-[15px] font-normal mb-[14px]">
        <span className="mff-step-number inline-flex items-center justify-center w-7 h-7 bg-[#B2EAD8] text-[#292929] rounded-full text-[13px] font-semibold">
          2
        </span>
        <span>Was möchtest du buchen?</span>
      </div>

      <div className="grid gap-[10px]">
        {packageOptions.map((pkg) => (
          <label
            key={pkg.value}
            className={`relative flex items-center p-[14px_18px] border-2 rounded-[10px] cursor-pointer transition-all duration-200 hover:border-[#B2EAD8] hover:bg-[#f8f8f8] hover:-translate-y-[2px] hover:shadow-[0_2px_12px_rgba(0,0,0,0.08)] ${
              formData.package === pkg.value
                ? "border-[#B2EAD8] bg-[#f8f8f8]"
                : "border-[#e0e0e0]"
            }`}
          >
            <input
              type="radio"
              name="package"
              value={pkg.value}
              checked={formData.package === pkg.value}
              onChange={(e) => onUpdateField("package", e.target.value)}
              className="absolute opacity-0"
            />
            <span
              className={`relative w-5 h-5 border-2 rounded-full mr-3 transition-all duration-200 ${
                formData.package === pkg.value
                  ? "border-[#B2EAD8] bg-[#B2EAD8]"
                  : "border-[#e0e0e0]"
              }`}
            >
              {formData.package === pkg.value && (
                <span className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-[#292929]" />
              )}
            </span>
            <span className="text-sm font-light">{pkg.label}</span>
          </label>
        ))}
      </div>

      {/* Next Button */}
      <div className="grid grid-cols-1 gap-3 mt-4">
        <button
          type="button"
          onClick={onNext}
          disabled={!formData.package}
          className="p-[10px_20px] text-sm font-normal border-none rounded-[10px] cursor-pointer transition-all duration-200 text-center inline-flex items-center justify-center gap-[6px] bg-[#B2EAD8] text-[#292929] hover:bg-[#7dc9b1] hover:-translate-y-[2px] hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)] disabled:opacity-40 disabled:cursor-not-allowed disabled:bg-[#e0e0e0]"
          style={{ fontFamily: "'Poppins', sans-serif" }}
        >
          Weiter
        </button>
      </div>
    </div>
  );
}
