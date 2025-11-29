import Link from "next/link";

export default function Footer() {
  return (
    <footer className="bg-[#D4F4E8]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {/* Logo / Brand */}
          <div>
            <Link
              href="/"
              className="text-xl font-semibold text-gray-900"
            >
              musikfürfirmen.de
            </Link>
            <p className="mt-2 text-sm text-gray-600 font-light">
              Dein Partner für Firmenevents
            </p>
          </div>

          {/* Kontakt */}
          <div>
            <h4 className="text-base font-semibold text-gray-900 mb-4">
              Kontakt
            </h4>
            <div className="space-y-2 text-sm text-gray-600 font-light">
              <p>
                <a
                  href="mailto:kontakt@musikfürfirmen.de"
                  className="hover:text-gray-900 transition-colors"
                >
                  kontakt@musikfürfirmen.de
                </a>
              </p>
              <p>
                <a
                  href="tel:+491741699553"
                  className="hover:text-gray-900 transition-colors"
                >
                  +49 174 1699553
                </a>
              </p>
            </div>
          </div>

          {/* Info / Links */}
          <div>
            <h4 className="text-base font-semibold text-gray-900 mb-4">
              Info
            </h4>
            <div className="space-y-2 text-sm">
              <p>
                <Link
                  href="/#wir"
                  className="text-gray-600 hover:text-gray-900 transition-colors font-light"
                >
                  Über uns
                </Link>
              </p>
              <p>
                <Link
                  href="/impressum"
                  className="text-gray-600 hover:text-gray-900 transition-colors font-light"
                >
                  Impressum
                </Link>
              </p>
              <p>
                <Link
                  href="/datenschutz"
                  className="text-gray-600 hover:text-gray-900 transition-colors font-light"
                >
                  Datenschutz
                </Link>
              </p>
            </div>
          </div>
        </div>

        {/* Copyright */}
        <div className="mt-12 pt-8 border-t border-gray-200/50">
          <p className="text-sm text-gray-500 text-center font-light">
            © {new Date().getFullYear()} musikfürfirmen.de. Alle Rechte
            vorbehalten.
          </p>
        </div>
      </div>
    </footer>
  );
}
