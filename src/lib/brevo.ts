// Brevo API Client - Alle Anfragen laufen über das Backend
// API-Key ist SICHER in .env.local, nicht im Frontend!

interface ContactData {
  name: string;
  email: string;
  company?: string;
  phone?: string;
  message?: string;
  eventDate: string;
  eventTime?: string;
  city: string;
  budget?: string;
  guests: string;
  packageType: string;
  reserveDate?: boolean;
}

interface BrevoContact {
  email: string;
  attributes: {
    FIRSTNAME: string;
    LASTNAME: string;
    COMPANY?: string;
    SMS?: string;
    EVENT_DATE: string;
    EVENT_TIME?: string;
    EVENT_CITY: string;
    EVENT_BUDGET?: string;
    EVENT_GUESTS: string;
    EVENT_PACKAGE: string;
    MESSAGE?: string;
    RESERVE_DATE?: string;
  };
  listIds: number[];
  updateEnabled: boolean;
}

export async function createBrevoContact(data: ContactData): Promise<{ success: boolean; error?: string }> {
  const apiKey = process.env.BREVO_API_KEY;
  const listId = parseInt(process.env.BREVO_LIST_ID || "2");

  if (!apiKey) {
    console.error("BREVO_API_KEY nicht konfiguriert");
    return { success: false, error: "Server-Konfigurationsfehler" };
  }

  const nameParts = data.name.trim().split(" ");
  const firstName = nameParts[0] || data.name;
  const lastName = nameParts.slice(1).join(" ") || "";

  const payload: BrevoContact = {
    email: data.email,
    attributes: {
      FIRSTNAME: firstName,
      LASTNAME: lastName,
      COMPANY: data.company || "",
      SMS: data.phone || "",
      EVENT_DATE: data.eventDate,
      EVENT_TIME: data.eventTime || "",
      EVENT_CITY: data.city,
      EVENT_BUDGET: data.budget || "",
      EVENT_GUESTS: data.guests,
      EVENT_PACKAGE: data.packageType,
      MESSAGE: data.message || "",
      RESERVE_DATE: data.reserveDate ? "yes" : "no",
    },
    listIds: [listId],
    updateEnabled: true,
  };

  try {
    const response = await fetch("https://api.brevo.com/v3/contacts", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        "api-key": apiKey,
      },
      body: JSON.stringify(payload),
    });

    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      console.error("Brevo API Error:", response.status, errorData);
      return { success: false, error: "Fehler beim Speichern" };
    }

    return { success: true };
  } catch (error) {
    console.error("Brevo request failed:", error);
    return { success: false, error: "Netzwerkfehler" };
  }
}
