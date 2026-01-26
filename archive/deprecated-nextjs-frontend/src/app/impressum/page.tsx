import type { Metadata } from "next";
import Link from "next/link";

export const metadata: Metadata = {
  title: "Impressum | musikfürfirmen.de",
  robots: { index: false, follow: false },
};

export default function Impressum() {
  return (
    <div className="min-h-screen bg-white" style={{ fontFamily: "'Poppins', sans-serif" }}>
      <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        {/* Breadcrumb */}
        <nav className="mb-8">
          <Link href="/" className="text-[#0D7A5F] hover:underline text-sm">
            ← Zurück zur Startseite
          </Link>
        </nav>

        <h1 className="text-4xl md:text-5xl font-semibold text-[#1a1a1a] mb-12">
          Impressum
        </h1>

        <div className="space-y-10 text-[#444]">
          {/* Angaben gemäß § 5 TMG */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              Angaben gemäß § 5 TMG
            </h2>
            <div className="bg-[#f9faf9] rounded-xl p-6 leading-relaxed">
              <p className="font-medium text-[#1a1a1a]">Nick Heymann</p>
              <p>musikfürfirmen.de</p>
              <p className="mt-2 text-sm text-[#666]">[Straße und Hausnummer]</p>
              <p className="text-sm text-[#666]">[PLZ] Hamburg</p>
            </div>
          </section>

          {/* Kontakt */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              Kontakt
            </h2>
            <div className="bg-[#f9faf9] rounded-xl p-6 leading-relaxed">
              <p>
                <span className="text-[#666]">Telefon:</span>{" "}
                <a href="tel:+491741699553" className="text-[#0D7A5F] hover:underline">
                  +49 174 1699553
                </a>
              </p>
              <p className="mt-2">
                <span className="text-[#666]">E-Mail:</span>{" "}
                <a href="mailto:kontakt@musikfürfirmen.de" className="text-[#0D7A5F] hover:underline">
                  kontakt@musikfürfirmen.de
                </a>
              </p>
            </div>
          </section>

          {/* Umsatzsteuer-ID */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              Umsatzsteuer-ID
            </h2>
            <p className="leading-relaxed">
              Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:
            </p>
            <p className="mt-2 text-sm text-[#666]">[USt-IdNr. hier eintragen]</p>
          </section>

          {/* Verantwortlich */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV
            </h2>
            <p className="leading-relaxed">
              Nick Heymann
              <br />
              <span className="text-sm text-[#666]">[Adresse wie oben]</span>
            </p>
          </section>

          {/* Streitschlichtung */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              Streitschlichtung
            </h2>
            <p className="leading-relaxed">
              Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit:{" "}
              <a
                href="https://ec.europa.eu/consumers/odr/"
                target="_blank"
                rel="noopener noreferrer"
                className="text-[#0D7A5F] hover:underline"
              >
                https://ec.europa.eu/consumers/odr/
              </a>
            </p>
            <p className="mt-4 leading-relaxed">
              Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer
              Verbraucherschlichtungsstelle teilzunehmen.
            </p>
          </section>

          {/* Haftung für Inhalte */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              Haftung für Inhalte
            </h2>
            <p className="leading-relaxed">
              Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten
              nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als
              Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde
              Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige
              Tätigkeit hinweisen.
            </p>
            <p className="mt-4 leading-relaxed">
              Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den
              allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch
              erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei
              Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend
              entfernen.
            </p>
          </section>

          {/* Haftung für Links */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              Haftung für Links
            </h2>
            <p className="leading-relaxed">
              Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen
              Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr
              übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder
              Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der
              Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum
              Zeitpunkt der Verlinkung nicht erkennbar.
            </p>
          </section>

          {/* Urheberrecht */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              Urheberrecht
            </h2>
            <p className="leading-relaxed">
              Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen
              dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art
              der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen
              Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind
              nur für den privaten, nicht kommerziellen Gebrauch gestattet.
            </p>
          </section>
        </div>

        {/* Footer Links */}
        <div className="mt-16 pt-8 border-t border-gray-200">
          <div className="flex gap-6 text-sm">
            <Link href="/datenschutz" className="text-[#0D7A5F] hover:underline">
              Datenschutzerklärung
            </Link>
            <Link href="/ueber-uns" className="text-[#0D7A5F] hover:underline">
              Über uns
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
