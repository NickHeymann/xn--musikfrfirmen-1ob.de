import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "Datenschutzerklärung",
  robots: { index: false, follow: false },
};

export default function Datenschutz() {
  return (
    <div className="py-24 bg-white">
      <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 className="text-4xl font-bold text-gray-900 mb-8">
          Datenschutzerklärung
        </h1>

        <div className="prose prose-gray max-w-none">
          <h2>1. Datenschutz auf einen Blick</h2>

          <h3>Allgemeine Hinweise</h3>
          <p>
            Die folgenden Hinweise geben einen einfachen Überblick darüber, was
            mit Ihren personenbezogenen Daten passiert, wenn Sie diese Website
            besuchen. Personenbezogene Daten sind alle Daten, mit denen Sie
            persönlich identifiziert werden können.
          </p>

          <h3>Datenerfassung auf dieser Website</h3>
          <p>
            <strong>
              Wer ist verantwortlich für die Datenerfassung auf dieser Website?
            </strong>
          </p>
          <p>
            Die Datenverarbeitung auf dieser Website erfolgt durch den
            Websitebetreiber. Dessen Kontaktdaten können Sie dem Impressum
            dieser Website entnehmen.
          </p>

          <p>
            <strong>Wie erfassen wir Ihre Daten?</strong>
          </p>
          <p>
            Ihre Daten werden zum einen dadurch erhoben, dass Sie uns diese
            mitteilen. Hierbei kann es sich z.B. um Daten handeln, die Sie in
            ein Kontaktformular eingeben.
          </p>
          <p>
            Andere Daten werden automatisch oder nach Ihrer Einwilligung beim
            Besuch der Website durch unsere IT-Systeme erfasst. Das sind vor
            allem technische Daten (z.B. Internetbrowser, Betriebssystem oder
            Uhrzeit des Seitenaufrufs).
          </p>

          <h2>2. Hosting</h2>
          <p>Wir hosten unsere Website bei Vercel Inc.</p>
          <p>
            Vercel Inc.
            <br />
            440 N Barranca Ave #4133
            <br />
            Covina, CA 91723
            <br />
            USA
          </p>
          <p>
            Details entnehmen Sie der Datenschutzerklärung von Vercel:{" "}
            <a
              href="https://vercel.com/legal/privacy-policy"
              target="_blank"
              rel="noopener noreferrer"
              className="text-[#0D7A5F] hover:underline"
            >
              https://vercel.com/legal/privacy-policy
            </a>
          </p>

          <h2>3. Kontaktformular</h2>
          <p>
            Wenn Sie uns per Kontaktformular Anfragen zukommen lassen, werden
            Ihre Angaben aus dem Anfrageformular inklusive der von Ihnen dort
            angegebenen Kontaktdaten zwecks Bearbeitung der Anfrage und für den
            Fall von Anschlussfragen bei uns gespeichert.
          </p>
          <p>
            Die Verarbeitung dieser Daten erfolgt auf Grundlage von Art. 6 Abs.
            1 lit. b DSGVO, sofern Ihre Anfrage mit der Erfüllung eines Vertrags
            zusammenhängt oder zur Durchführung vorvertraglicher Maßnahmen
            erforderlich ist.
          </p>

          <h2>4. Newsletter / E-Mail-Marketing (Brevo)</h2>
          <p>
            Für den Versand von E-Mails und die Verwaltung von Kontaktdaten
            nutzen wir den Dienst Brevo (ehemals Sendinblue):
          </p>
          <p>
            Sendinblue GmbH
            <br />
            Köpenicker Str. 126
            <br />
            10179 Berlin
            <br />
            Deutschland
          </p>
          <p>
            Brevo ist ein Dienst, mit dem u.a. der Versand von Newslettern
            organisiert und analysiert werden kann. Wenn Sie Daten zum Zwecke
            des Newsletterbezugs eingeben, werden diese auf den Servern von
            Brevo gespeichert.
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

          <h2>5. Ihre Rechte</h2>
          <p>Sie haben jederzeit das Recht:</p>
          <ul>
            <li>
              Auskunft über Ihre bei uns gespeicherten Daten zu erhalten (Art.
              15 DSGVO)
            </li>
            <li>
              Berichtigung unrichtiger personenbezogener Daten zu verlangen
              (Art. 16 DSGVO)
            </li>
            <li>
              Die Löschung Ihrer bei uns gespeicherten Daten zu verlangen (Art.
              17 DSGVO)
            </li>
            <li>
              Die Einschränkung der Datenverarbeitung zu verlangen (Art. 18
              DSGVO)
            </li>
            <li>
              Ihre Daten in einem übertragbaren Format zu erhalten (Art. 20
              DSGVO)
            </li>
            <li>Der Datenverarbeitung zu widersprechen (Art. 21 DSGVO)</li>
          </ul>
          <p>
            Zur Ausübung Ihrer Rechte wenden Sie sich bitte an:{" "}
            <a
              href="mailto:kontakt@musikfürfirmen.de"
              className="text-[#0D7A5F] hover:underline"
            >
              kontakt@musikfürfirmen.de
            </a>
          </p>

          <h2>6. SSL- bzw. TLS-Verschlüsselung</h2>
          <p>
            Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der
            Übertragung vertraulicher Inhalte eine SSL- bzw. TLS-Verschlüsselung.
            Eine verschlüsselte Verbindung erkennen Sie daran, dass die
            Adresszeile des Browsers von „http://" auf „https://" wechselt und
            an dem Schloss-Symbol in Ihrer Browserzeile.
          </p>

          <p className="text-sm text-gray-500 mt-12">
            Stand: November 2024
          </p>
        </div>
      </div>
    </div>
  );
}
