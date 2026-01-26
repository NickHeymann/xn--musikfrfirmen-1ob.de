import type { Metadata } from "next";
import Image from "next/image";
import Link from "next/link";

export const metadata: Metadata = {
  title: "Unser letztes Event",
  description:
    "Einblicke in unser letztes Firmenevent - so könnte auch deine Veranstaltung aussehen.",
  openGraph: {
    title: "Unser letztes Event | musikfürfirmen.de",
    description:
      "Einblicke in unser letztes Firmenevent - so könnte auch deine Veranstaltung aussehen.",
  },
};

// Event-Daten - später einfach austauschbar
const eventData = {
  title: "Sommerfest 2024",
  client: "Firmenname", // Später mit echtem Namen ersetzen
  date: "August 2024",
  location: "Hamburg",
  guests: "120 Gäste",
  services: ["Liveband", "DJ", "Veranstaltungstechnik"],
  description: `
    Ein unvergesslicher Abend unter freiem Himmel: Das Sommerfest begann mit entspannten
    Lounge-Beats während des Empfangs, ging über in ein energiegeladenes Live-Set unserer
    Band und endete mit einer DJ-Session, die die Gäste bis Mitternacht auf der Tanzfläche hielt.
  `,
  highlights: [
    {
      title: "Empfang & Dinner",
      time: "17:00 - 20:00",
      description: "Entspannte Hintergrundmusik während Empfang und Essen",
    },
    {
      title: "Live-Band",
      time: "20:00 - 22:00",
      description: "Zwei Stunden Livemusik von Pop bis Rock",
    },
    {
      title: "DJ & Party",
      time: "22:00 - 00:00",
      description: "Nahtloser Übergang zur DJ-Session",
    },
  ],
  images: [
    { src: "/images/events/event-1.jpg", alt: "Band auf der Bühne" },
    { src: "/images/events/event-2.jpg", alt: "Tanzende Gäste" },
    { src: "/images/events/event-3.jpg", alt: "Lichtsetup" },
    { src: "/images/events/event-4.jpg", alt: "Location Übersicht" },
  ],
  testimonial: {
    quote: "Hier könnte das Testimonial eures Kunden stehen - ein bis zwei Sätze darüber, wie zufrieden sie mit dem Event waren.",
    author: "Name des Ansprechpartners",
    position: "Position, Firmenname",
  },
};

export default function ReferenzPage() {
  return (
    <div className="bg-white min-h-screen">
      {/* Hero Section */}
      <section className="pt-16 pb-12 md:pt-24 md:pb-16 bg-[#f9f9f9]">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <p
            className="text-[#0D7A5F] font-medium mb-4"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            Unser letztes Event
          </p>
          <h1
            className="text-4xl md:text-5xl font-semibold text-[#1a1a1a] mb-6"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            {eventData.title}
          </h1>
          <p
            className="text-xl text-[#666] max-w-2xl mx-auto leading-relaxed"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            {eventData.description.trim()}
          </p>
        </div>
      </section>

      {/* Event Details */}
      <section className="py-12 md:py-16">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <div className="text-center">
              <p className="text-sm text-[#666] mb-1" style={{ fontFamily: "'Poppins', sans-serif" }}>
                Datum
              </p>
              <p className="text-lg font-semibold text-[#1a1a1a]" style={{ fontFamily: "'Poppins', sans-serif" }}>
                {eventData.date}
              </p>
            </div>
            <div className="text-center">
              <p className="text-sm text-[#666] mb-1" style={{ fontFamily: "'Poppins', sans-serif" }}>
                Ort
              </p>
              <p className="text-lg font-semibold text-[#1a1a1a]" style={{ fontFamily: "'Poppins', sans-serif" }}>
                {eventData.location}
              </p>
            </div>
            <div className="text-center">
              <p className="text-sm text-[#666] mb-1" style={{ fontFamily: "'Poppins', sans-serif" }}>
                Gäste
              </p>
              <p className="text-lg font-semibold text-[#1a1a1a]" style={{ fontFamily: "'Poppins', sans-serif" }}>
                {eventData.guests}
              </p>
            </div>
            <div className="text-center">
              <p className="text-sm text-[#666] mb-1" style={{ fontFamily: "'Poppins', sans-serif" }}>
                Leistungen
              </p>
              <p className="text-lg font-semibold text-[#1a1a1a]" style={{ fontFamily: "'Poppins', sans-serif" }}>
                {eventData.services.length} Services
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Image Gallery */}
      <section className="py-8 md:py-12">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {eventData.images.map((image, index) => (
              <div
                key={index}
                className="aspect-[4/3] bg-[#f5f5f5] rounded-lg overflow-hidden relative"
              >
                {/* Platzhalter - später mit echten Bildern ersetzen */}
                <div className="w-full h-full flex items-center justify-center text-gray-400 text-sm text-center p-4">
                  {image.alt}
                </div>
                {/* Wenn Bilder vorhanden:
                <Image
                  src={image.src}
                  alt={image.alt}
                  fill
                  className="object-cover"
                  sizes="(max-width: 768px) 50vw, 25vw"
                />
                */}
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Timeline / Ablauf */}
      <section className="py-12 md:py-16 bg-[#f9f9f9]">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2
            className="text-2xl md:text-3xl font-semibold text-[#1a1a1a] mb-8 text-center"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            Der Ablauf
          </h2>

          <div className="space-y-6">
            {eventData.highlights.map((highlight, index) => (
              <div
                key={index}
                className="flex gap-6 items-start bg-white p-6 rounded-lg shadow-sm"
              >
                <div className="flex-shrink-0 w-20 text-center">
                  <span className="text-sm font-medium text-[#0D7A5F]" style={{ fontFamily: "'Poppins', sans-serif" }}>
                    {highlight.time}
                  </span>
                </div>
                <div>
                  <h3
                    className="text-lg font-semibold text-[#1a1a1a] mb-1"
                    style={{ fontFamily: "'Poppins', sans-serif" }}
                  >
                    {highlight.title}
                  </h3>
                  <p className="text-[#666]" style={{ fontFamily: "'Poppins', sans-serif" }}>
                    {highlight.description}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Services Used */}
      <section className="py-12 md:py-16">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2
            className="text-2xl md:text-3xl font-semibold text-[#1a1a1a] mb-8 text-center"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            Unsere Leistungen
          </h2>

          <div className="flex flex-wrap justify-center gap-4">
            {eventData.services.map((service, index) => (
              <span
                key={index}
                className="px-6 py-3 bg-[#D4F4E8] text-[#0D7A5F] font-medium rounded-full"
                style={{ fontFamily: "'Poppins', sans-serif" }}
              >
                {service}
              </span>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonial */}
      <section className="py-12 md:py-16 bg-[#f9f9f9]">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <svg
            className="w-12 h-12 mx-auto mb-6 text-[#D4F4E8]"
            fill="currentColor"
            viewBox="0 0 24 24"
          >
            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
          </svg>

          <blockquote
            className="text-xl md:text-2xl text-[#1a1a1a] mb-6 leading-relaxed italic"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            &ldquo;{eventData.testimonial.quote}&rdquo;
          </blockquote>

          <div>
            <p
              className="font-semibold text-[#1a1a1a]"
              style={{ fontFamily: "'Poppins', sans-serif" }}
            >
              {eventData.testimonial.author}
            </p>
            <p className="text-[#666] text-sm" style={{ fontFamily: "'Poppins', sans-serif" }}>
              {eventData.testimonial.position}
            </p>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 md:py-24">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2
            className="text-2xl md:text-3xl font-semibold text-[#1a1a1a] mb-4"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            So könnte auch dein Event aussehen
          </h2>
          <p
            className="text-lg text-[#666] mb-8 max-w-xl mx-auto"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            Erzähle uns von deinem Vorhaben - wir melden uns innerhalb von 24 Stunden.
          </p>
          <Link
            href="/#hero"
            className="inline-block px-8 py-4 bg-[#0D7A5F] text-white font-semibold rounded-lg hover:bg-[#0a6650] transition-colors"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            Unverbindlich anfragen
          </Link>
        </div>
      </section>
    </div>
  );
}
