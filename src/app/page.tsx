import Hero from "@/components/Hero";
import ServiceCards from "@/components/ServiceCards";
import ProcessSteps from "@/components/ProcessSteps";
import TeamSection from "@/components/TeamSection";
import FAQ from "@/components/FAQ";
import CTASection from "@/components/CTASection";

export default function Home() {
  return (
    <>
      <Hero />

      {/* Leistungen Section */}
      <section id="leistungen" className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-center mb-4">Was wir bieten</h2>
          <p className="text-center text-gray-600 font-light mb-16 max-w-2xl mx-auto">
            Ob intime Feier oder große Gala – wir haben die passende Lösung für
            dein Firmenevent.
          </p>
          <ServiceCards />
        </div>
      </section>

      {/* Service Process Section */}
      <section id="service" className="py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-center mb-4">Musik und Technik? Läuft.</h2>
          <p className="text-center text-gray-600 font-light mb-16 max-w-2xl mx-auto">
            Von uns geplant. Von euch gefeiert.
          </p>
          <ProcessSteps />
        </div>
      </section>

      {/* Team Section */}
      <section id="wir" className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-center mb-4">Moin aus Hamburg!</h2>
          <p className="text-center text-gray-600 font-light mb-16 max-w-2xl mx-auto">
            Persönliche Betreuung von Anfrage bis Afterparty.
          </p>
          <TeamSection />
        </div>
      </section>

      {/* FAQ Section */}
      <section id="faq" className="py-24 bg-gray-50">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-center mb-4">Häufige Fragen</h2>
          <p className="text-center text-gray-600 font-light mb-12">
            Alles, was du wissen musst – kurz und knapp.
          </p>
          <FAQ />
        </div>
      </section>

      {/* CTA Section */}
      <CTASection />

      {/* Logo Section */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 text-center">
          <p className="text-3xl font-semibold text-gray-900">
            musikfürfirmen.de
          </p>
          <p className="mt-2 text-gray-600 font-light">
            Dein{" "}
            <span className="text-[#0D7A5F] font-medium">Partner</span> für
            Firmenevents
          </p>
        </div>
      </section>
    </>
  );
}
