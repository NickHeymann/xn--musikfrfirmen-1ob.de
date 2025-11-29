import { NextRequest, NextResponse } from "next/server";
import { createBrevoContact } from "@/lib/brevo";

// Rate Limiting (einfache In-Memory-Lösung)
const rateLimit = new Map<string, { count: number; timestamp: number }>();
const RATE_LIMIT_WINDOW = 60 * 1000; // 1 Minute
const RATE_LIMIT_MAX = 5; // Max 5 Anfragen pro Minute

function isRateLimited(ip: string): boolean {
  const now = Date.now();
  const record = rateLimit.get(ip);

  if (!record || now - record.timestamp > RATE_LIMIT_WINDOW) {
    rateLimit.set(ip, { count: 1, timestamp: now });
    return false;
  }

  if (record.count >= RATE_LIMIT_MAX) {
    return true;
  }

  record.count++;
  return false;
}

export async function POST(request: NextRequest) {
  // Rate Limiting Check
  const ip = request.headers.get("x-forwarded-for") || "unknown";
  if (isRateLimited(ip)) {
    return NextResponse.json(
      { error: "Zu viele Anfragen. Bitte warte einen Moment." },
      { status: 429 }
    );
  }

  try {
    const body = await request.json();

    // Validierung
    const { name, email, eventDate, city, guests, packageType } = body;

    if (!name || !email || !eventDate || !city || !guests || !packageType) {
      return NextResponse.json(
        { error: "Bitte fülle alle Pflichtfelder aus." },
        { status: 400 }
      );
    }

    // E-Mail Format prüfen
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return NextResponse.json(
        { error: "Bitte gib eine gültige E-Mail-Adresse ein." },
        { status: 400 }
      );
    }

    // An Brevo senden
    const result = await createBrevoContact({
      name,
      email,
      company: body.company,
      phone: body.phone,
      message: body.message,
      eventDate,
      eventTime: body.eventTime,
      city,
      budget: body.budget,
      guests,
      packageType,
      reserveDate: body.reserveDate,
    });

    if (!result.success) {
      return NextResponse.json(
        { error: result.error || "Fehler beim Senden" },
        { status: 500 }
      );
    }

    return NextResponse.json({ success: true });
  } catch (error) {
    console.error("Contact API Error:", error);
    return NextResponse.json(
      { error: "Ein unerwarteter Fehler ist aufgetreten." },
      { status: 500 }
    );
  }
}
