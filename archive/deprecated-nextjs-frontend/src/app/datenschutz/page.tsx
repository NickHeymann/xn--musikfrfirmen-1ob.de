import type { Metadata } from "next";
import Link from "next/link";

export const metadata: Metadata = {
  title: "Datenschutzerklärung | musikfürfirmen.de",
  robots: { index: false, follow: false },
};

export default function Datenschutz() {
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
          Datenschutzerklärung
        </h1>

        <div className="space-y-10 text-[#444]">
          {/* 1. Datenschutz auf einen Blick */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              1. Datenschutz auf einen Blick
            </h2>
            <div className="space-y-4 leading-relaxed">
              <div>
                <h3 className="font-medium text-[#1a1a1a] mb-2">Allgemeine Hinweise</h3>
                <p>
                  Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren
                  personenbezogenen Daten passiert, wenn Sie diese Website besuchen. Personenbezogene
                  Daten sind alle Daten, mit denen Sie persönlich identifiziert werden können.
                </p>
              </div>
              <div>
                <h3 className="font-medium text-[#1a1a1a] mb-2">
                  Wer ist verantwortlich für die Datenerfassung?
                </h3>
                <p>
                  Die Datenverarbeitung auf dieser Website erfolgt durch den Websitebetreiber.
                  Dessen Kontaktdaten können Sie dem{" "}
                  <Link href="/impressum" className="text-[#0D7A5F] hover:underline">
                    Impressum
                  </Link>{" "}
                  dieser Website entnehmen.
                </p>
              </div>
              <div>
                <h3 className="font-medium text-[#1a1a1a] mb-2">Wie erfassen wir Ihre Daten?</h3>
                <p>
                  Ihre Daten werden zum einen dadurch erhoben, dass Sie uns diese mitteilen. Hierbei
                  kann es sich z.B. um Daten handeln, die Sie in ein Kontaktformular eingeben.
                </p>
                <p className="mt-2">
                  Andere Daten werden automatisch oder nach Ihrer Einwilligung beim Besuch der Website
                  durch unsere IT-Systeme erfasst. Das sind vor allem technische Daten (z.B.
                  Internetbrowser, Betriebssystem oder Uhrzeit des Seitenaufrufs).
                </p>
              </div>
            </div>
          </section>

          {/* 2. Hosting */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              2. Hosting
            </h2>
            <div className="space-y-4 leading-relaxed">
              <p>Wir hosten unsere Website bei GitHub Pages.</p>
              <div className="bg-[#f9faf9] rounded-xl p-6">
                <p className="font-medium text-[#1a1a1a]">GitHub, Inc.</p>
                <p>88 Colin P Kelly Jr St</p>
                <p>San Francisco, CA 94107</p>
                <p>USA</p>
              </div>
              <p>
                Wenn Sie unsere Website besuchen, werden automatisch technische Daten (z.B. IP-Adresse,
                Browsertyp, Betriebssystem) erfasst. Diese Daten werden von GitHub verarbeitet.
              </p>
              <p>
                Details entnehmen Sie der Datenschutzerklärung von GitHub:{" "}
                <a
                  href="https://docs.github.com/de/site-policy/privacy-policies/github-general-privacy-statement"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-[#0D7A5F] hover:underline break-all"
                >
                  https://docs.github.com/de/site-policy/privacy-policies
                </a>
              </p>
            </div>
          </section>

          {/* 3. Kontaktformular */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              3. Kontaktaufnahme
            </h2>
            <div className="space-y-4 leading-relaxed">
              <p>
                Wenn Sie uns per Kontaktformular oder E-Mail Anfragen zukommen lassen, werden Ihre
                Angaben inklusive der von Ihnen angegebenen Kontaktdaten zwecks Bearbeitung der
                Anfrage und für den Fall von Anschlussfragen bei uns gespeichert.
              </p>
              <p>
                Die Verarbeitung dieser Daten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. b DSGVO,
                sofern Ihre Anfrage mit der Erfüllung eines Vertrags zusammenhängt oder zur
                Durchführung vorvertraglicher Maßnahmen erforderlich ist.
              </p>
              <p>
                In allen übrigen Fällen beruht die Verarbeitung auf unserem berechtigten Interesse
                an der effektiven Bearbeitung der an uns gerichteten Anfragen (Art. 6 Abs. 1 lit. f
                DSGVO) oder auf Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO).
              </p>
            </div>
          </section>

          {/* 4. E-Mail Marketing */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              4. E-Mail-Marketing (Brevo)
            </h2>
            <div className="space-y-4 leading-relaxed">
              <p>
                Für den Versand von E-Mails und die Verwaltung von Kontaktdaten nutzen wir den
                Dienst Brevo (ehemals Sendinblue):
              </p>
              <div className="bg-[#f9faf9] rounded-xl p-6">
                <p className="font-medium text-[#1a1a1a]">Sendinblue GmbH</p>
                <p>Köpenicker Str. 126</p>
                <p>10179 Berlin</p>
                <p>Deutschland</p>
              </div>
              <p>
                Brevo ist ein Dienst, mit dem u.a. der Versand von E-Mails organisiert und analysiert
                werden kann. Wenn Sie Daten zum Zwecke des E-Mail-Kontakts eingeben, werden diese auf
                den Servern von Brevo in der EU gespeichert.
              </p>
              <p>
                Datenschutzerklärung von Brevo:{" "}
                <a
                  href="https://www.brevo.com/de/legal/privacypolicy/"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-[#0D7A5F] hover:underline"
                >
                  https://www.brevo.com/de/legal/privacypolicy/
                </a>
              </p>
            </div>
          </section>

          {/* 5. Cookies */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              5. Cookies
            </h2>
            <div className="space-y-4 leading-relaxed">
              <p>
                Unsere Website verwendet nur technisch notwendige Cookies, die für den Betrieb der
                Seite erforderlich sind. Diese Cookies werden nach Ende der Browser-Sitzung
                automatisch gelöscht.
              </p>
              <p>
                Wir verwenden keine Tracking-Cookies oder Analyse-Tools wie Google Analytics.
              </p>
            </div>
          </section>

          {/* 6. Ihre Rechte */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              6. Ihre Rechte
            </h2>
            <div className="space-y-4 leading-relaxed">
              <p>Sie haben jederzeit das Recht auf:</p>
              <ul className="list-none space-y-3">
                {[
                  "Auskunft über Ihre bei uns gespeicherten Daten (Art. 15 DSGVO)",
                  "Berichtigung unrichtiger personenbezogener Daten (Art. 16 DSGVO)",
                  "Löschung Ihrer bei uns gespeicherten Daten (Art. 17 DSGVO)",
                  "Einschränkung der Datenverarbeitung (Art. 18 DSGVO)",
                  "Datenübertragbarkeit (Art. 20 DSGVO)",
                  "Widerspruch gegen die Datenverarbeitung (Art. 21 DSGVO)",
                ].map((right, index) => (
                  <li key={index} className="flex items-start gap-3">
                    <span className="text-[#0D7A5F] mt-1">✓</span>
                    <span>{right}</span>
                  </li>
                ))}
              </ul>
              <div className="bg-[#f9faf9] rounded-xl p-6 mt-4">
                <p className="font-medium text-[#1a1a1a] mb-2">Zur Ausübung Ihrer Rechte:</p>
                <a
                  href="mailto:kontakt@musikfürfirmen.de"
                  className="text-[#0D7A5F] hover:underline"
                >
                  kontakt@musikfürfirmen.de
                </a>
              </div>
            </div>
          </section>

          {/* 7. Beschwerderecht */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              7. Beschwerderecht bei der Aufsichtsbehörde
            </h2>
            <div className="space-y-4 leading-relaxed">
              <p>
                Sofern Sie der Ansicht sind, dass die Verarbeitung Ihrer personenbezogenen Daten
                gegen Datenschutzrecht verstößt, haben Sie das Recht, sich bei einer Datenschutz-
                Aufsichtsbehörde zu beschweren.
              </p>
              <p>Zuständige Aufsichtsbehörde für Hamburg:</p>
              <div className="bg-[#f9faf9] rounded-xl p-6">
                <p className="font-medium text-[#1a1a1a]">
                  Der Hamburgische Beauftragte für Datenschutz und Informationsfreiheit
                </p>
                <p>Ludwig-Erhard-Str. 22, 7. OG</p>
                <p>20459 Hamburg</p>
                <p className="mt-2">
                  <a
                    href="https://datenschutz-hamburg.de"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-[#0D7A5F] hover:underline"
                  >
                    https://datenschutz-hamburg.de
                  </a>
                </p>
              </div>
            </div>
          </section>

          {/* 8. SSL/TLS */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              8. SSL- bzw. TLS-Verschlüsselung
            </h2>
            <div className="space-y-4 leading-relaxed">
              <p>
                Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der Übertragung
                vertraulicher Inhalte eine SSL- bzw. TLS-Verschlüsselung. Eine verschlüsselte
                Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von „http://"
                auf „https://" wechselt und an dem Schloss-Symbol in Ihrer Browserzeile.
              </p>
              <p>
                Wenn die SSL- bzw. TLS-Verschlüsselung aktiviert ist, können die Daten, die Sie
                an uns übermitteln, nicht von Dritten mitgelesen werden.
              </p>
            </div>
          </section>

          {/* 9. Änderungen */}
          <section>
            <h2 className="text-xl font-semibold text-[#1a1a1a] mb-4">
              9. Aktualität und Änderung dieser Datenschutzerklärung
            </h2>
            <div className="space-y-4 leading-relaxed">
              <p>
                Diese Datenschutzerklärung ist aktuell gültig und hat den Stand Dezember 2024.
              </p>
              <p>
                Durch die Weiterentwicklung unserer Website oder aufgrund geänderter gesetzlicher
                bzw. behördlicher Vorgaben kann es notwendig werden, diese Datenschutzerklärung
                zu ändern. Die jeweils aktuelle Datenschutzerklärung kann jederzeit auf dieser
                Seite abgerufen werden.
              </p>
            </div>
          </section>
        </div>

        {/* Footer Links */}
        <div className="mt-16 pt-8 border-t border-gray-200">
          <div className="flex gap-6 text-sm">
            <Link href="/impressum" className="text-[#0D7A5F] hover:underline">
              Impressum
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
