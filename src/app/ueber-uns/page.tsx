"use client";

import { useEffect, useRef, useState } from "react";
import Image from "next/image";
import { getAssetPath } from "@/lib/config";

interface TimelineItem {
  year: string;
  title: string;
  description: string;
  person: "jonas" | "nick";
  image?: string; // Optional: für spezielle Meilensteine
}

const timelineStory: TimelineItem[] = [
  {
    year: "2002",
    title: "Jonas: Erste Gitarre",
    description: "Mit 7 Jahren beginnt die musikalische Reise. Die erste Gitarre wird zum ständigen Begleiter.",
    person: "jonas"
  },
  {
    year: "2008",
    title: "Jonas: Erste Bandgründung",
    description: "Schulband und erste Auftritte in Hamburg. Die Bühne wird zur zweiten Heimat.",
    person: "jonas"
  },
  {
    year: "2010",
    title: "Nick: Erste Kamera",
    description: "Entdeckung der Videografie und Storytelling. Bilder beginnen zu sprechen.",
    person: "nick"
  },
  {
    year: "2014",
    title: "Jonas: Professionelle Bühnentechnik",
    description: "Ausbildung zum Veranstaltungstechniker. Sound und Licht werden zur Wissenschaft.",
    person: "jonas"
  },
  {
    year: "2016",
    title: "Jonas: Tour-Techniker",
    description: "Auf Tour mit Revolverheld und Adel Tawil. Große Bühnen, große Verantwortung.",
    person: "jonas"
  },
  {
    year: "2016",
    title: "Nick: Musikstudium",
    description: "Professionelle Ausbildung in Musik und Medien. Theorie trifft Praxis.",
    person: "nick"
  },
  {
    year: "2019",
    title: "Jonas: Coverband-Karriere",
    description: "Hunderte Events mit verschiedenen Formationen. Jeder Abend eine neue Story.",
    person: "jonas"
  },
  {
    year: "2019",
    title: "Nick: Selbständigkeit",
    description: "Gründung als Videograf und Marketingberater. Der Sprung ins kalte Wasser.",
    person: "nick"
  },
  {
    year: "2022",
    title: "Nick: 100M+ Views",
    description: "Virale Event-Videos auf Social Media. Geschichten, die die Welt bewegen.",
    person: "nick"
  },
  {
    year: "2024",
    title: "musikfürfirmen.de",
    description: "Zwei Wege vereinen sich. Musik, Technik und Marketing aus einer Hand. Die Mission beginnt.",
    person: "jonas" // Both, aber Jonas als primary
  },
];

export default function UeberUnsPage() {
  const [visibleItems, setVisibleItems] = useState<number[]>([]);
  const itemRefs = useRef<(HTMLDivElement | null)[]>([]);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          const index = parseInt(entry.target.getAttribute("data-index") || "0");
          if (entry.isIntersecting) {
            setVisibleItems((prev) => [...new Set([...prev, index])]);
          }
        });
      },
      { threshold: 0.2, rootMargin: "0px 0px -100px 0px" }
    );

    itemRefs.current.forEach((ref) => {
      if (ref) observer.observe(ref);
    });

    return () => observer.disconnect();
  }, []);

  const openCalculator = () => {
    window.dispatchEvent(new CustomEvent("openMFFCalculator"));
  };

  return (
    <div className="bg-white min-h-screen" style={{ fontFamily: "'Poppins', sans-serif" }}>

      {/* Hero Section - Editorial Impact */}
      <section className="relative pt-32 pb-24 md:pt-40 md:pb-32 overflow-hidden">
        {/* Subtle mint gradient background */}
        <div className="absolute inset-0 bg-gradient-to-br from-[#D4F4E8]/10 via-white to-[#D4F4E8]/5" />

        {/* Decorative organic shape */}
        <div className="absolute top-20 right-0 w-[600px] h-[600px] bg-[#D4F4E8]/20 rounded-full blur-3xl opacity-40" />

        <div className="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="max-w-4xl">
            {/* Eyebrow */}
            <div className="inline-flex items-center gap-2 mb-6">
              <div className="w-12 h-[2px] bg-[#D4F4E8]" />
              <span className="text-sm font-semibold tracking-[0.2em] text-[#1a1a2e] uppercase">
                Über Uns
              </span>
            </div>

            {/* Main Headline - Magazine Style */}
            <h1 className="text-5xl md:text-7xl lg:text-8xl font-bold text-[#1a1a2e] mb-8 leading-[0.95]">
              Zwei Musiker.
              <br />
              <span style={{ color: '#D4F4E8' }} className="drop-shadow-[0_2px_4px_rgba(0,0,0,0.1)]">Eine Mission.</span>
            </h1>

            {/* Subheading */}
            <p className="text-xl md:text-2xl text-[#4a5568] max-w-2xl leading-relaxed font-light">
              Euer Firmenevent verdient Musik, die in Erinnerung bleibt.
              Wir liefern nicht nur Sound – wir schaffen Momente.
            </p>
          </div>
        </div>
      </section>

      {/* Stats Section - Scroll Triggered */}
      <section className="py-16 md:py-20 border-y border-[#D4F4E8]/30 bg-gradient-to-r from-white via-[#D4F4E8]/5 to-white">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
            {[
              { value: "20+", label: "Jahre Erfahrung", suffix: "" },
              { value: "500+", label: "Events gespielt", suffix: "" },
              { value: "100M+", label: "Video-Aufrufe", suffix: "" },
              { value: "24", label: "Stunden Antwortzeit", suffix: "h" },
            ].map((stat, i) => (
              <div key={stat.label} className="text-center group">
                <div className="text-4xl md:text-5xl lg:text-6xl font-bold text-[#1a1a2e] mb-2 transition-transform duration-300 group-hover:scale-110">
                  {stat.value}
                </div>
                <p className="text-sm md:text-base text-[#666] font-light leading-tight">{stat.label}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Story Section */}
      <section className="py-24 md:py-32">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-4xl md:text-5xl font-bold text-[#1a1a2e] mb-12">
            Warum wir das machen
          </h2>
          <div className="space-y-6 text-[#444] text-lg md:text-xl leading-relaxed">
            <p>
              Wir haben es selbst erlebt: Du organisierst ein Firmenevent, buchst eine Band über
              irgendeinen Katalog – und <span className="text-[#1a1a2e] font-semibold">hoffst einfach, dass es gut wird.</span>
            </p>
            <p className="text-2xl md:text-3xl font-bold text-[#1a1a2e] my-8 px-6 py-4 bg-[#D4F4E8]/30 rounded-2xl inline-block">
              Manchmal wird es das. Oft nicht.
            </p>
            <p>
              Weil Musik bei den meisten Agenturen nebenbei läuft. Weil niemand checkt, ob die
              Band zum Anlass passt. Weil am Ende keiner verantwortlich ist, wenn etwas schiefgeht.
            </p>
            <p className="text-[#1a1a2e] font-semibold">
              Bei uns ist das anders. Wir stehen selbst auf der Bühne. Wir kennen jeden Künstler
              persönlich. Und wir sind vor Ort, wenn es darauf ankommt.
            </p>
          </div>
        </div>
      </section>

      {/* Team Timeline - THE CENTERPIECE */}
      <section className="py-24 md:py-32 bg-gradient-to-b from-white via-[#f9fdfb] to-white relative overflow-hidden">
        {/* Decorative background elements */}
        <div className="absolute top-1/4 left-0 w-[400px] h-[400px] bg-[#D4F4E8]/10 rounded-full blur-3xl" />
        <div className="absolute bottom-1/4 right-0 w-[500px] h-[500px] bg-[#D4F4E8]/10 rounded-full blur-3xl" />

        <div className="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Section Header */}
          <div className="text-center mb-20">
            <h2 className="text-4xl md:text-5xl lg:text-6xl font-bold text-[#1a1a2e] mb-4">
              Unser Weg
            </h2>
            <p className="text-lg md:text-xl text-[#666] max-w-2xl mx-auto">
              Von den ersten Akkorden bis zur Gründung – so haben wir hierher gefunden.
            </p>
          </div>

          {/* Team Intro Cards */}
          <div className="grid md:grid-cols-2 gap-8 mb-24">
            {/* Jonas Card */}
            <div className="group relative bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgba(13,122,95,0.08)] hover:shadow-[0_8px_30px_rgba(13,122,95,0.15)] transition-all duration-500 border border-[#D4F4E8]/50">
              <div className="flex items-start gap-6">
                <div className="relative w-24 h-24 flex-shrink-0">
                  <div className="absolute inset-0 rounded-2xl bg-[#D4F4E8] opacity-80 group-hover:opacity-100 transition-opacity" />
                  <Image
                    src={getAssetPath("/images/team/jonas.png")}
                    alt="Jonas Glamann"
                    fill
                    className="object-cover object-top rounded-2xl scale-110"
                    sizes="96px"
                  />
                </div>
                <div className="flex-1">
                  <h3 className="text-2xl font-bold text-[#1a1a2e] mb-1">Jonas Glamann</h3>
                  <p className="text-[#1a1a2e] font-semibold mb-3 px-3 py-1 bg-[#D4F4E8] rounded-full inline-block">Der Kreative</p>
                  <p className="text-sm text-[#666] leading-relaxed mb-3">
                    Gitarrist, Tour-Techniker, Event-Koordinator. Von der Bühne bis zur Technik – alles aus einer Hand.
                  </p>
                  <div className="flex flex-wrap gap-2">
                    {["Gitarre", "Technik", "Koordination"].map((tag) => (
                      <span key={tag} className="px-3 py-1 bg-[#D4F4E8] text-[#1a1a2e] text-xs font-medium rounded-full">
                        {tag}
                      </span>
                    ))}
                  </div>
                </div>
              </div>
            </div>

            {/* Nick Card */}
            <div className="group relative bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgba(13,122,95,0.08)] hover:shadow-[0_8px_30px_rgba(13,122,95,0.15)] transition-all duration-500 border border-[#D4F4E8]/50">
              <div className="flex items-start gap-6">
                <div className="relative w-24 h-24 flex-shrink-0">
                  <div className="absolute inset-0 rounded-2xl bg-[#D4F4E8] opacity-80 group-hover:opacity-100 transition-opacity" />
                  <Image
                    src={getAssetPath("/images/team/nick.png")}
                    alt="Nick Heymann"
                    fill
                    className="object-cover object-top rounded-2xl scale-110"
                    sizes="96px"
                  />
                </div>
                <div className="flex-1">
                  <h3 className="text-2xl font-bold text-[#1a1a2e] mb-1">Nick Heymann</h3>
                  <p className="text-[#1a1a2e] font-semibold mb-3 px-3 py-1 bg-[#D4F4E8] rounded-full inline-block">Der Macher</p>
                  <p className="text-sm text-[#666] leading-relaxed mb-3">
                    Videograf, Musikstudium, Marketing-Experte. Über 100M Views, unzählige virale Event-Momente.
                  </p>
                  <div className="flex flex-wrap gap-2">
                    {["Video", "Marketing", "Musik"].map((tag) => (
                      <span key={tag} className="px-3 py-1 bg-[#D4F4E8] text-[#1a1a2e] text-xs font-medium rounded-full">
                        {tag}
                      </span>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Organic Timeline */}
          <div className="relative">
            {/* Organic vertical line (SVG for curve) */}
            <svg
              className="absolute left-8 md:left-1/2 top-0 h-full w-1 md:-translate-x-1/2 hidden md:block"
              style={{ width: '4px' }}
              preserveAspectRatio="none"
              viewBox="0 0 4 1000"
            >
              <defs>
                <linearGradient id="lineGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                  <stop offset="0%" stopColor="#D4F4E8" stopOpacity="0.5" />
                  <stop offset="50%" stopColor="#D4F4E8" stopOpacity="1" />
                  <stop offset="100%" stopColor="#D4F4E8" stopOpacity="0.5" />
                </linearGradient>
              </defs>
              <path
                d="M 2 0 Q 2 100 2 200 Q 2 300 2 400 Q 2 500 2 600 Q 2 700 2 800 Q 2 900 2 1000"
                stroke="url(#lineGradient)"
                strokeWidth="4"
                fill="none"
              />
            </svg>

            {/* Mobile simple line */}
            <div className="absolute left-8 top-0 bottom-0 w-[2px] bg-gradient-to-b from-[#D4F4E8]/50 via-[#D4F4E8] to-[#D4F4E8]/50 md:hidden" />

            {/* Timeline Items */}
            <div className="space-y-12 md:space-y-16">
              {timelineStory.map((item, index) => {
                const isJonas = item.person === "jonas";
                const isLeft = index % 2 === 0;
                const isVisible = visibleItems.includes(index);

                return (
                  <div
                    key={index}
                    ref={(el) => (itemRefs.current[index] = el)}
                    data-index={index}
                    className={`relative flex items-center transition-all duration-700 ${
                      isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'
                    }`}
                    style={{ transitionDelay: `${(index % 3) * 100}ms` }}
                  >
                    {/* Timeline dot */}
                    <div className="absolute left-8 md:left-1/2 w-5 h-5 md:w-6 md:h-6 rounded-full border-4 border-white shadow-lg -translate-x-1/2 z-10 bg-[#D4F4E8]"
                      style={{
                        boxShadow: `0 0 0 4px rgba(212,244,232,0.25)`,
                      }}
                    />

                    {/* Content - alternating sides on desktop */}
                    <div className={`ml-20 md:ml-0 w-full md:w-[calc(50%-3rem)] ${
                      isLeft ? 'md:pr-16 md:text-right' : 'md:ml-auto md:pl-16 md:text-left'
                    }`}>
                      <div className="bg-white rounded-2xl p-6 md:p-8 shadow-[0_2px_12px_rgba(0,0,0,0.06)] hover:shadow-[0_4px_20px_rgba(13,122,95,0.12)] transition-all duration-300 border border-[#D4F4E8]/30">
                        {/* Year badge */}
                        <div className={`inline-flex items-center gap-2 mb-3 ${isLeft ? 'md:flex-row-reverse' : ''}`}>
                          <div
                            className="px-4 py-1.5 rounded-full text-sm font-bold bg-[#D4F4E8] text-[#1a1a2e]"
                          >
                            {item.year}
                          </div>
                          <div className="w-8 h-[2px] bg-[#D4F4E8]" />
                        </div>

                        {/* Title */}
                        <h4 className="text-xl md:text-2xl font-bold text-[#1a1a2e] mb-2">
                          {item.title}
                        </h4>

                        {/* Description */}
                        <p className="text-[#666] leading-relaxed">
                          {item.description}
                        </p>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </section>

      {/* Values Section - Minimal & Clean */}
      <section className="py-24 md:py-32">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-4xl md:text-5xl font-bold text-[#1a1a2e] mb-16 text-center">
            Wofür stehen wir?
          </h2>
          <div className="grid md:grid-cols-3 gap-12">
            {[
              {
                icon: (
                  <svg className="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                ),
                title: "Persönlich",
                description: "Kein Callcenter, keine Katalog-Auswahl. Ihr sprecht direkt mit uns – von der ersten Anfrage bis zum letzten Ton.",
              },
              {
                icon: (
                  <svg className="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                ),
                title: "Abgesichert",
                description: "Ausfallgarantie, Haftpflicht, Ersatzkünstler – wir denken an alles, damit ihr euch auf euer Event konzentrieren könnt.",
              },
              {
                icon: (
                  <svg className="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                ),
                title: "Vor Ort",
                description: "Jonas ist Teil der Band. Wenn etwas ist, sind wir da – nicht am Telefon, sondern direkt bei euch.",
              },
            ].map((item, i) => (
              <div key={item.title} className="text-center group">
                <div className="w-20 h-20 mx-auto mb-6 rounded-2xl bg-[#D4F4E8] flex items-center justify-center text-[#1a1a2e] transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg">
                  {item.icon}
                </div>
                <h3 className="text-2xl font-bold text-[#1a1a2e] mb-3">{item.title}</h3>
                <p className="text-[#666] leading-relaxed text-lg">{item.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section - Bold & Confident */}
      <section className="py-24 md:py-32 bg-[#D4F4E8] relative overflow-hidden">
        {/* Decorative circles */}
        <div className="absolute top-0 left-1/4 w-[400px] h-[400px] bg-white/5 rounded-full blur-3xl" />
        <div className="absolute bottom-0 right-1/4 w-[300px] h-[300px] bg-white/5 rounded-full blur-3xl" />

        <div className="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-4xl md:text-5xl lg:text-6xl font-bold text-[#1a1a2e] mb-6 leading-tight">
            Lass uns reden
          </h2>
          <p className="text-xl md:text-2xl text-[#1a1a2e]/70 mb-12 max-w-2xl mx-auto leading-relaxed font-light">
            Erzähl uns von deinem Event – unverbindlich und kostenlos.
            Innerhalb von 24 Stunden hast du unser Angebot.
          </p>
          <button
            onClick={openCalculator}
            className="group inline-flex items-center gap-3 px-12 py-5 bg-[#1a1a2e] text-white font-bold text-lg rounded-full hover:bg-[#292929] transition-all duration-300 hover:shadow-[0_8px_30px_rgba(26,26,46,0.3)] hover:-translate-y-1"
          >
            Jetzt anfragen
            <svg className="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </button>
        </div>
      </section>

    </div>
  );
}
