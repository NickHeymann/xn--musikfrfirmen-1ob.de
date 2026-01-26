import { useState, useCallback } from "react";
import type { ContactFormData, CityResult } from "@/types";
import { packageOptions } from "@/config/site";

const initialFormData: ContactFormData = {
  date: "",
  time: "",
  city: "",
  budget: "",
  guests: "",
  package: "",
  name: "",
  company: "",
  email: "",
  phone: "",
  message: "",
  privacy: false,
};

export function useContactForm() {
  const [step, setStep] = useState(1);
  const [formData, setFormData] = useState<ContactFormData>(initialFormData);
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitStatus, setSubmitStatus] = useState<"idle" | "success" | "error">("idle");

  const updateField = <K extends keyof ContactFormData>(
    field: K,
    value: ContactFormData[K]
  ) => {
    setFormData((prev) => ({ ...prev, [field]: value }));
    setErrors((prev) => ({ ...prev, [field]: "" }));
  };

  const validateStep1 = useCallback(() => {
    const newErrors: Record<string, string> = {};

    if (!formData.date) {
      newErrors.date = "Bitte wählen Sie ein Datum";
    } else {
      const selectedDate = new Date(formData.date);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      if (selectedDate < today) {
        newErrors.date = "Das Datum liegt in der Vergangenheit";
      }
      const year = parseInt(formData.date.split("-")[0], 10);
      if (year > 9999 || year < 1000) {
        newErrors.date = "Bitte geben Sie ein gültiges Jahr ein (4 Ziffern)";
      }
    }

    if (!formData.city) {
      newErrors.city = "Bitte geben Sie eine Stadt an";
    }

    if (!formData.guests) {
      newErrors.guests = "Bitte wählen Sie eine Gästeanzahl";
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  }, [formData]);

  const validateStep2 = useCallback(() => {
    return !!formData.package;
  }, [formData.package]);

  const validateStep3 = useCallback(() => {
    const newErrors: Record<string, string> = {};

    if (!formData.name.trim()) {
      newErrors.name = "Bitte gebe deinen Namen an";
    }

    if (!formData.email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
      newErrors.email = "Bitte gebe eine gültige E-Mail an";
    }

    if (!formData.phone.trim()) {
      newErrors.phone = "Bitte gebe eine Telefonnummer an";
    }

    if (!formData.privacy) {
      newErrors.privacy = "Bitte akzeptiere die Datenschutzerklärung";
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  }, [formData]);

  const handleNext = () => {
    if (step === 1 && validateStep1()) {
      setStep(2);
    } else if (step === 2 && validateStep2()) {
      setStep(3);
    }
  };

  const handlePrev = () => {
    if (step > 1) setStep(step - 1);
  };

  const handleSubmit = async () => {
    if (!validateStep3()) return;

    setIsSubmitting(true);

    const packageLabels = packageOptions.reduce(
      (acc, pkg) => ({ ...acc, [pkg.value]: pkg.label }),
      {} as Record<string, string>
    );

    const emailBody = `
Neue Anfrage von der Website

Name: ${formData.name}
Firma: ${formData.company || "-"}
E-Mail: ${formData.email}
Telefon: ${formData.phone}

Event-Details:
- Datum: ${formData.date}
- Uhrzeit: ${formData.time || "Nicht angegeben"}
- Stadt: ${formData.city}
- Gäste: ${formData.guests}
- Budget: ${formData.budget || "Nicht angegeben"}
- Paket: ${packageLabels[formData.package] || formData.package}

Nachricht:
${formData.message || "Keine Nachricht"}
    `.trim();

    const mailtoLink = `mailto:kontakt@musikfürfirmen.de?subject=${encodeURIComponent(
      `Neue Anfrage: ${formData.city} am ${formData.date}`
    )}&body=${encodeURIComponent(emailBody)}`;

    window.location.href = mailtoLink;

    setTimeout(() => {
      setSubmitStatus("success");
      setIsSubmitting(false);
    }, 500);
  };

  const reset = () => {
    setStep(1);
    setFormData(initialFormData);
    setErrors({});
    setSubmitStatus("idle");
  };

  return {
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
  };
}

// City Autocomplete Hook
export function useCityAutocomplete() {
  const [suggestions, setSuggestions] = useState<CityResult[]>([]);
  const [showSuggestions, setShowSuggestions] = useState(false);
  const [activeIndex, setActiveIndex] = useState(-1);

  const fetchSuggestions = useCallback(async (query: string) => {
    if (query.length < 2) {
      setSuggestions([]);
      setShowSuggestions(false);
      return;
    }

    try {
      const url = `https://photon.komoot.io/api/?q=${encodeURIComponent(
        query
      )}&limit=5&lang=de&layer=city&osm_tag=place:city&osm_tag=place:town&bbox=5.87,47.27,15.04,55.06`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.features?.length > 0) {
        const germanCities = data.features
          .filter(
            (f: { properties: { country?: string; countrycode?: string } }) =>
              f.properties.country === "Germany" ||
              f.properties.countrycode === "DE"
          )
          .map(
            (f: {
              properties: { name: string; state?: string; county?: string };
            }) => ({
              city: f.properties.name,
              state: f.properties.state || f.properties.county || "",
            })
          );

        if (germanCities.length > 0) {
          setSuggestions(germanCities);
          setShowSuggestions(true);
          setActiveIndex(-1);
        } else {
          setSuggestions([]);
          setShowSuggestions(false);
        }
      }
    } catch (error) {
      console.error("Autocomplete error:", error);
      setSuggestions([]);
      setShowSuggestions(false);
    }
  }, []);

  const hideSuggestions = () => {
    setTimeout(() => setShowSuggestions(false), 200);
  };

  return {
    suggestions,
    showSuggestions,
    activeIndex,
    fetchSuggestions,
    hideSuggestions,
    setSuggestions,
    setShowSuggestions,
  };
}
