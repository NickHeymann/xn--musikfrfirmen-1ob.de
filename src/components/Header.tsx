"use client";

import { useState, useEffect } from "react";
import Link from "next/link";
import { useRouter, usePathname } from "next/navigation";
import { navLinks } from "@/config/site";
import { basePath } from "@/lib/config";

interface HeaderProps {
  editable?: boolean;
  _editableProps?: {
    onContentChange: (path: string, value: string) => void;
    isEditing: boolean;
  };
}

export default function Header({ editable = false, _editableProps }: HeaderProps = {}) {
  const router = useRouter();
  const pathname = usePathname();
  const isEditorMode = pathname?.startsWith('/admin/editor/');
  const [isVisible, setIsVisible] = useState(true);
  const [lastScrollY, setLastScrollY] = useState(0);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      const currentScrollY = window.scrollY;

      if (currentScrollY <= 10) {
        setIsVisible(true);
      } else if (currentScrollY > lastScrollY + 5) {
        setIsVisible(false);
        setIsMobileMenuOpen(false);
      } else if (currentScrollY < lastScrollY - 5) {
        setIsVisible(true);
      }

      setLastScrollY(currentScrollY);
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    return () => window.removeEventListener("scroll", handleScroll);
  }, [lastScrollY]);

  const handleNavClick = (e: React.MouseEvent<HTMLAnchorElement>, href: string, isAnchor: boolean) => {
    // In editor mode, ignore anchor links completely (they're for live site only)
    if (isEditorMode && isAnchor) {
      e.preventDefault();
      setIsMobileMenuOpen(false);
      return;
    }

    if (!isAnchor) {
      e.preventDefault();
      setIsMobileMenuOpen(false);
      router.push(href);
      return;
    }

    e.preventDefault();
    const targetId = href.replace("/#", "");

    // Check if we're on the main page
    const isOnMainPage = window.location.pathname === "/" ||
                         window.location.pathname === basePath ||
                         window.location.pathname === basePath + "/";

    if (isOnMainPage) {
      // On main page - just scroll
      const target = document.getElementById(targetId);
      if (target) {
        target.scrollIntoView({ behavior: "smooth", block: "start" });
        window.history.pushState(null, "", `${basePath}/${href.replace("/", "")}`);
      }
    } else {
      // On other page - navigate to main page with anchor
      // eslint-disable-next-line react-hooks/immutability
      window.location.href = `${basePath}/#${targetId}`;
    }
    setIsMobileMenuOpen(false);
  };

  return (
    <>
      <header
        id="header"
        className={`fixed top-0 left-0 right-0 z-[9999] transition-transform duration-300 ease-in-out ${
          isVisible ? "translate-y-0" : "-translate-y-full"
        }`}
        style={{ backgroundColor: "#ffffff" }}
      >
        <div className="header-inner w-full px-[80px]">
          <div className="flex items-center justify-between h-[108px]">
            <div className="header-title">
              <Link
                href="/"
                className="text-[32px] font-medium text-black hover:opacity-70 transition-opacity duration-200"
                style={{ fontFamily: "'Poppins', sans-serif" }}
                {...(editable && {
                  "data-editable": "siteName",
                  contentEditable: _editableProps?.isEditing,
                  suppressContentEditableWarning: true,
                  onBlur: (e) => {
                    if (_editableProps) {
                      _editableProps.onContentChange('siteName', e.currentTarget.textContent || '');
                    }
                  }
                })}
              >
                musikfürfirmen.de
              </Link>
            </div>

            <nav className="header-nav hidden md:flex items-center gap-14">
              {navLinks.map((item) => {
                // In editor mode, convert page links to editor links, keep anchors as-is
                const editorHref = isEditorMode && !item.isAnchor
                  ? `/admin/editor/${item.href.replace('/', '') || 'home'}`
                  : item.href;

                return (
                  <a
                    key={item.href}
                    href={editorHref}
                    onClick={(e) => handleNavClick(e, editorHref, item.isAnchor)}
                    className="text-[17px] font-light text-black hover:opacity-70 transition-opacity duration-200"
                    style={{ fontFamily: "'Poppins', sans-serif" }}
                    contentEditable={isEditorMode}
                    suppressContentEditableWarning={true}
                    onBlur={(e) => {
                      // Save edited header text
                      if (isEditorMode) {
                        console.log('Header text changed:', e.currentTarget.textContent);
                      }
                    }}
                  >
                    {item.label}
                  </a>
                );
              })}
            </nav>

            <button
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
              className="header-burger md:hidden p-2"
              aria-label={isMobileMenuOpen ? "Menü schließen" : "Menü öffnen"}
            >
              <div className="burger-box w-6 flex flex-col gap-[6px]">
                <span
                  className={`w-full h-[1px] bg-black transform transition-all duration-300 origin-center ${
                    isMobileMenuOpen ? "rotate-45 translate-y-[3.5px]" : ""
                  }`}
                />
                <span
                  className={`w-full h-[1px] bg-black transform transition-all duration-300 origin-center ${
                    isMobileMenuOpen ? "-rotate-45 -translate-y-[3.5px]" : ""
                  }`}
                />
              </div>
            </button>
          </div>
        </div>

        <div className="h-[1px]" style={{ backgroundColor: "#e5e7eb" }} />
      </header>

      <div
        className={`fixed inset-0 z-[9998] md:hidden transition-all duration-300 ${
          isMobileMenuOpen
            ? "opacity-100 visible"
            : "opacity-0 invisible pointer-events-none"
        }`}
      >
        <div
          className="absolute inset-0 bg-black/20"
          onClick={() => setIsMobileMenuOpen(false)}
        />

        <nav
          className={`absolute top-[108px] left-0 right-0 bg-white shadow-lg transform transition-transform duration-300 ${
            isMobileMenuOpen ? "translate-x-0" : "translate-x-full"
          }`}
          style={{ backgroundColor: "#ffffff" }}
        >
          <div className="py-4 px-6">
            {navLinks.map((item) => {
              // In editor mode, convert page links to editor links, keep anchors as-is
              const editorHref = isEditorMode && !item.isAnchor
                ? `/admin/editor/${item.href.replace('/', '') || 'home'}`
                : item.href;

              return (
                <a
                  key={item.href}
                  href={editorHref}
                  onClick={(e) => handleNavClick(e, editorHref, item.isAnchor)}
                  className="block py-4 text-base font-normal text-black hover:opacity-70 transition-opacity"
                  style={{ fontFamily: "'Poppins', sans-serif" }}
                >
                  {item.label}
                </a>
              );
            })}
          </div>
        </nav>
      </div>

      <div className="h-[108px]" />
    </>
  );
}
