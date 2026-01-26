"use client";

import Hero from "@/components/Hero";
import ServiceCards from "@/components/ServiceCards";
import ProcessSteps from "@/components/ProcessSteps";
import TeamSection from "@/components/TeamSection";
import FAQ from "@/components/FAQ";
import LogoAnimation from "@/components/LogoAnimation";
import HamburgAnimation from "@/components/HamburgAnimation";

export default function Home() {
  const scrollToSection = (sectionId: string) => {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: "smooth" });
    }
  };

  return (
    <>
      <Hero />

      <section id="waswirbieten" className="bg-white overflow-visible pt-[187px] scroll-mt-[0px]">
        <ServiceCards />
      </section>

      <section id="service" className="pt-[108px] bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-[60px]">
            <h2
              onClick={() => scrollToSection("service")}
              className="text-[3rem] font-semibold text-[#1a1a1a] mb-[10px] cursor-pointer hover:opacity-70 transition-opacity"
              style={{ fontFamily: "'Poppins', sans-serif" }}
            >
              Musik und Technik? Läuft.
            </h2>
            <p
              className="text-[1.5rem] font-normal text-[#1a1a1a] max-w-[600px] mx-auto mb-2"
              style={{ fontFamily: "'Poppins', sans-serif" }}
            >
              Von uns geplant. Von euch gefeiert.
            </p>
          </div>
          <ProcessSteps />
        </div>
      </section>

      <section id="wir" className="pt-[178px] bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div
            className="text-center px-5 overflow-visible"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            <div
              onClick={() => scrollToSection("wir")}
              className="cursor-pointer hover:opacity-70 transition-opacity inline-block"
            >
              <HamburgAnimation />
            </div>
          </div>
          <TeamSection />
        </div>
      </section>

      <section id="faq" className="pt-[134px] bg-white">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2
            onClick={() => scrollToSection("faq")}
            className="text-center text-[3rem] font-semibold mb-[120px] tracking-[-1px] text-black cursor-pointer hover:opacity-70 transition-opacity"
            style={{ fontFamily: "'Poppins', sans-serif" }}
          >
            FAQ
          </h2>
          <FAQ />
        </div>
      </section>

      <section className="pt-[190px] pb-[163px] bg-white relative z-[1]">
        <LogoAnimation />
      </section>
    </>
  );
}
