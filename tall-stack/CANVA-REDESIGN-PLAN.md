# Canva Redesign Implementation Plan

**Backup Tag:** `backup-pre-canva-redesign-2026-01-26`
**Restore Command:** `git checkout backup-pre-canva-redesign-2026-01-26`

---

## Overview

Redesign the musikfürfirmen.de homepage to match the new Canva design. This involves significant changes to layout, content, and adding new sections.

---

## Section-by-Section Changes

### 1. Header Navigation
**Status:** Minor update needed

| Current | Canva Design |
|---------|--------------|
| "Was wir bieten", "Über uns", "FAQ" | "Jetzt Buchen", "Angebote", "musikfürfirmen", "Über uns", "Kontakt" |

**Files:** `resources/views/components/header.blade.php`

**Tasks:**
- [ ] Update navigation links
- [ ] Add "Jetzt Buchen" and "Angebote" links
- [ ] Center logo, move nav items to sides

---

### 2. Hero Section
**Status:** Major rewrite needed

| Current | Canva Design |
|---------|--------------|
| "Deine [Musik] für Firmenevents!" with word rotation | "Livemusik für Firmenevents." (static) |
| Checklist items below button | Subtitle: "Damit einer der größten Erfolgsfaktoren eures Events nicht mehr die zweite Geige spielen muss." |
| Video background | Simple white background with arrow |

**Files:** `resources/views/components/hero.blade.php`

**Tasks:**
- [ ] Change heading to "Livemusik für Firmenevents."
- [ ] Remove word rotation animation
- [ ] Add new subtitle text
- [ ] Change to white background (remove video)
- [ ] Update CTA button: "UNVERBINDLICHES ANGEBOT ANFRAGEN"
- [ ] Add simple down arrow (not animated chevrons)

---

### 3. Service Cards Section ("Wir bieten euch...")
**Status:** Major rewrite needed

| Current | Canva Design |
|---------|--------------|
| 3 cards in a row | Alternating left/right layout |
| Simple card design | Image on one side, text on other |
| No buttons | "MEHR ERFAHREN" + "JETZT BUCHEN" buttons per service |

**Files:** `resources/views/components/service-cards.blade.php`

**Tasks:**
- [ ] Add section heading "Wir bieten euch..."
- [ ] Create alternating layout component
- [ ] Service 1: **Livebands** - Image left, text right
- [ ] Service 2: **DJs** - Text left, image right
- [ ] Service 3: **Technik** - Image left, text right
- [ ] Add "MEHR ERFAHREN" and "JETZT BUCHEN" buttons to each
- [ ] Update service descriptions from Canva content

**Content for Livebands:**
> Sängerinnen wählen wir passend zu eurem Event aus. Von 'Halleluja' Querflöte' über 'Marmor, Stein und Eisen bricht', zu 'Wundert- Stimme' ist unsere Bands für euch Firmenevents DJ für Solo bis 20-köpfig.
> - "MEHR ERFAHREN" button
> - "JETZT BUCHEN" button

**Content for DJs:**
> Unsere DJs spielen einen maßgeschneiderten Mix aus Klassikern und aktuellen Hits, abgestimmt auf euer Event und eure Gäste. Ob elegant, energetisch oder ein Mix aus beidem — unsere DJs finden für jede Party den richtigen Sound. Dank modernster Technik und Vernetzung mit Labels ist unser DJ Angebot zu flexiblen Setups und Sonderwünschen aller Gästen.
> - "MEHR ERFAHREN" button
> - "JETZT BUCHEN" button

**Content for Technik:**
> Wir stellen professionelle Musik- und Lichttechnik im Wert von über 100.000 € bereit, damit unsere Künstler/innen ihre beste Seite/größte optimal auf die Bühne bringen. Jede Anforderung professionell. Und für jede Event-Location haben wir die passende Technik für optimale ausgestatteten Events.
> - "MEHR ERFAHREN" button
> - "JETZT BUCHEN" button

---

### 4. NEW: "Warum Wir?" Section
**Status:** New section to create

**Files:** `resources/views/components/why-us-section.blade.php`

**Tasks:**
- [ ] Create new component
- [ ] Add section heading "Warum Wir?"
- [ ] Two-column layout with text blocks
- [ ] Left column: Main explanation text
- [ ] Right column: "Eure Benefits" list with highlights

**Content:**
Left column:
> Die günstige Ecke Livemusik ist doch ein Baustein und sollte, könne und wird auch kostenpunktet nicht ausgeflammert. Wir Profis sind dabei und es sind hochklassige MFF DJs bei einer großen Bandbreite geprüfter Künstler. Der wesentliche Moment für das Event für unvergesslich: möchten wir euch garantieren.
>
> Die Band hat bei unserem Firmenevent gespielt und es war Superbomb! Die Kombination, der Einsatz und die Energie haben unserer Kollegen mitgerissen. Besonders beeindruckt hat mich, wie flexibel sie auf unsere Musikwünsche eingegangen sind.

Right column:
> Eure Benefits – sie viele Highlights:
> - Wir haben vier vorfinanzierenden Gruppen als Kernbereich für ein neues Firmenveranstaltungen mit Optionen via Rechnungen, dann könne gewünschte Zusatzbetrag direkt mit einer "Haken" 108. Günstig mit ersetzen werden müssen.
> - Mit einem sehr umfangreicher Repertoire kommen unsere Künstler/innen bei jedem Event...

---

### 5. NEW: "Das heißt für euch" Section
**Status:** New section to create

**Files:** `resources/views/components/benefits-section.blade.php`

**Tasks:**
- [ ] Create new component
- [ ] Add section heading "Das heißt für euch"
- [ ] 3-column grid layout
- [ ] Each column: Icon + Title + Description

**Columns:**
1. **Einen Ansprechpartner**
   > Kontaktdiensteausrichtung vom Erstkontakt der Discovery und aus Anmeldestätten kann auch unterschiedlichen Wünsche

2. **Kein Ausfallrisiko**
   > Netzwerk's kreativintuitiven für Events, für großartig, dass ihr die Vorstellung auch besteht wenn für DJs und Technik.

3. **100% Qualität**
   > Wir arbeiten ausschließlich mit Profi-Musikerinnen und Technikern, die nachweislich Erfahrung haben.

---

### 6. NEW: WhatsApp/Contact CTA Section
**Status:** New section to create

**Files:** `resources/views/components/whatsapp-cta.blade.php`

**Tasks:**
- [ ] Create new component
- [ ] Add text: "Wir sind 24/7 für euch telefonisch oder auch über WhatsApp erreichbar."
- [ ] Add "KOSTENLOSES ERSTGESPRÄCH" button
- [ ] Add descriptive text below button

**Content:**
> Wir sind 24/7 für euch telefonisch oder auch über WhatsApp erreichbar.
> [KOSTENLOSES ERSTGESPRÄCH]
> Gemeinsam definieren wir die musikalischen Anforderungen, besprechen ihre Veranstaltungen und schaffen die Grundlage für ein individuelles Angebot.

---

### 7. NEW: "Unser letztes Event" Testimonial Section
**Status:** New section to create

**Files:** `resources/views/components/testimonial-section.blade.php`

**Tasks:**
- [ ] Create new component
- [ ] Add section heading "Unser letztes Event."
- [ ] Large quote icon
- [ ] Testimonial text in quote format
- [ ] Attribution with name and company

**Content:**
> "Wir haben die Band für die Feier zum 25. Jubiläum unseres Unternehmens gebucht und sie haben unsere Erwartungen mehr als übertroffen. Vielen Dank noch mal an das ganze mff-Team — werdet unseren Kunden weiterempfohlen."
>
> — Peter Müller, CEO
> red life King GmbH

---

### 8. Team Section ("Moin aus Hamburg")
**Status:** Update layout and content

| Current | Canva Design |
|---------|--------------|
| Card-based team layout | Text introduction + side-by-side photos |
| Generic descriptions | Personal story + "Als Text" links |

**Files:** `resources/views/components/team-section.blade.php`

**Tasks:**
- [ ] Update to new layout
- [ ] Add intro text: "musikfürfirmen ist ein Hamburger Unternehmen spezialisiert auf den musikalischen Aspekt von Firmenevents."
- [ ] Add Jonas Glamann's story
- [ ] Add Nick Heymann's story
- [ ] Add "Als Text" links under each
- [ ] Add "Mehr Über Uns erfahren" link at bottom

**Jonas Glamann Content:**
> Seit 7 Jahren helfe ich angehenden Eltern zu glücklich und starke stellen auf die Bühne. Als Profi-Exter bei Band- und Solokünstler stand, wurde mir klar wie bereiten Sie Musik/Veranstaltung-bis heute in ständig, in einen Community gegründet.
> — Jonas Glamann, Co-Founder

**Nick Heymann Content:**
> Als Text
> — Nick Heymann, Co-Founder

---

### 9. FAQ Section
**Status:** Update questions

| Current | Canva Design |
|---------|--------------|
| Current FAQ questions | Different set of questions |

**Files:** `resources/views/components/faq.blade.php`, `database/seeders/FaqSeeder.php`

**Tasks:**
- [ ] Update FAQ questions to match Canva design:
  1. "Sind Anfragen verbindlich?"
  2. "Wie läuft eine Anfrage bei euch ab?"
  3. "Kann ich Sängerwünsche nennen?"
  4. "Kann man auch deutschlandweit buchen?"
  5. "Was passiert, wenn die Sängerin/Sänger krank wird?"
  6. "Muss ich mich noch um irgendetwas kümmern?"
  7. "Warum sollte ich mich also für eine Eventlösung mit musikfürfirmen buchen?"

---

### 10. Logo Animation Section
**Status:** Keep or simplify

**Files:** `resources/views/components/logo-animation.blade.php`

**Tasks:**
- [ ] Keep current animation or simplify based on Canva design
- [ ] Shows "musikfürfirmen.de" with "Dein Partner für Firmenevents"

---

### 11. CTA Section
**Status:** Update styling

**Files:** `resources/views/components/cta-section.blade.php`

**Tasks:**
- [ ] Update to match Canva footer CTA style
- [ ] "Bereit für unvergessliche Musik?" heading
- [ ] CTA button styling

---

### 12. Footer
**Status:** Minor update

**Files:** `resources/views/components/footer.blade.php`

**Tasks:**
- [ ] Update layout to match Canva design
- [ ] Two columns: Kontakt | Info
- [ ] Kontakt: kontakt@musikfuerfirmen.de, +49 174 6935533
- [ ] Info: Über uns, Impressum, Datenschutz
- [ ] Copyright: © 2026 musikfürfirmen.de

---

## Implementation Order

### Wave 1: Core Layout Changes
1. Header navigation update
2. Hero section rewrite (remove video, simplify)
3. Service cards alternating layout

### Wave 2: New Sections
4. "Warum Wir?" section
5. "Das heißt für euch" benefits section
6. WhatsApp CTA section
7. Testimonial section

### Wave 3: Updates & Polish
8. Team section update
9. FAQ questions update
10. Logo/CTA/Footer updates

---

## Estimated Effort

| Wave | Sections | Complexity | Estimated Tasks |
|------|----------|------------|-----------------|
| 1 | Header, Hero, Services | High | 15-20 tasks |
| 2 | 4 new sections | Medium | 12-15 tasks |
| 3 | Updates & polish | Low | 8-10 tasks |
| **Total** | | | **35-45 tasks** |

---

## Notes

- Some Canva text appears to be placeholder/lorem ipsum - may need actual content
- Button actions (JETZT BUCHEN, MEHR ERFAHREN) need defined destinations
- WhatsApp integration may require actual WhatsApp Business link
- Testimonial may need real customer quote or placeholder approval

---

## Rollback Plan

To restore the previous design:
```bash
git checkout backup-pre-canva-redesign-2026-01-26
```

Or to restore specific files:
```bash
git checkout backup-pre-canva-redesign-2026-01-26 -- resources/views/components/
```
