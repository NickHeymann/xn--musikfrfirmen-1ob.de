'use client';

import { siteConfig, footerLinks } from "@/config/site";
import Link from "next/link";
import { usePathname } from "next/navigation";

interface FooterProps {
  companyName?: string;
  email?: string;
  phone?: string;
}

export default function Footer({
  companyName = siteConfig.name,
  email = siteConfig.email,
  phone = siteConfig.phone
}: FooterProps = {}) {
  const pathname = usePathname();
  const isEditorMode = pathname?.startsWith('/admin/editor/');
  
  return (
    <footer className="bg-white" style={{ fontFamily: "'Poppins', sans-serif" }}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="flex flex-col md:flex-row justify-center gap-16 md:gap-32">
          <div>
            <h4 className="text-base font-semibold text-black mb-6">
              Kontakt
            </h4>
            <div className="space-y-3 text-[15px] text-black font-light">
              <p>
                <a
                  href={`mailto:${email}`}
                  className="hover:underline transition-colors"
                >
                  {email}
                </a>
              </p>
              <p>
                <a
                  href={`tel:${phone.replace(/\s/g, "")}`}
                  className="hover:underline transition-colors"
                >
                  {phone}
                </a>
              </p>
            </div>
          </div>

          <div>
            <h4 className="text-base font-semibold text-black mb-6">Info</h4>
            <div className="space-y-3 text-[15px]">
              {footerLinks.info.map((link) => {
                const isAnchor = link.href.startsWith('/#');
                
                // In editor mode: only convert real pages to editor links, ignore anchors
                const editorHref = isEditorMode && !isAnchor
                  ? `/admin/editor/${link.href.replace('/', '') || 'home'}`
                  : link.href;
                
                // In editor mode, don't render anchor links at all (they're for live site)
                if (isEditorMode && isAnchor) {
                  return null;
                }
                
                return (
                  <p key={link.href}>
                    <Link
                      href={editorHref}
                      className="text-black hover:underline transition-colors font-light"
                    >
                      {link.label}
                    </Link>
                  </p>
                );
              })}
            </div>
          </div>
        </div>
      </div>

      <div className="bg-black py-4">
        <p className="text-sm text-white text-center font-light" style={{ fontFamily: "'Poppins', sans-serif" }}>
          © {new Date().getFullYear()} {companyName}
        </p>
      </div>
    </footer>
  );
}
