# Entwicklungsnotizen - musikfürfirmen.de

## Wichtige Hinweise für zukünftige Entwicklung

### TeamSection Komponente

**WICHTIG: Nicht mit React State für Hover-Effekte arbeiten!**

Die TeamSection verwendet einen speziellen CSS-basierten Hover-Effekt, der NICHT durch React State ersetzt werden darf.

#### Was funktioniert (Original-Ansatz):
```css
.container1:hover ~ .bio-text {
  opacity: 1;
  transform: translateX(0);
}
```

Der CSS-Sibling-Selektor (`~`) steuert die Sichtbarkeit des Bio-Textes basierend auf dem Hover-Status des Bild-Containers.

#### Was NICHT funktioniert:
```tsx
// FALSCH - führt zu Layout-Problemen!
const [isHovered, setIsHovered] = useState(false);

useEffect(() => {
  // Dynamische Positionsberechnung...
}, [isHovered]);
```

**Warum nicht?**
- React State verursacht Re-Renders die das Layout beeinflussen
- Die komplexe clip-path Animation mit `top: -200px` und `scale(0.60)` reagiert empfindlich auf State-Änderungen
- Dynamische `position: fixed` Berechnungen bringen das gesamte Sektions-Layout durcheinander
- Die FAQ-Sektion und andere Elemente verschieben sich beim Hover

#### Struktur die beibehalten werden muss:
```
.person
  └── .person-content
        ├── .container1 (Bild-Container mit Hover)
        │     └── .container-inner (clip-path)
        │           ├── .circle
        │           └── .img
        ├── .bio-text (wird über CSS ~ Selektor gesteuert)
        ├── .divider
        ├── .name
        └── .title
```

### Referenz-Datei

Der Original-Code befindet sich in:
`/Users/nickheymann/Desktop/Mein Business/Marketing/Musikfürfirmen.de/Website/alte website.html`

Bei Problemen mit der TeamSection immer diese Datei als Referenz nutzen (Zeilen ~5600-5800).

---

## Abstände zwischen Sektionen

Die Abstände sind exakt kalibriert:

| Sektion | padding-top |
|---------|-------------|
| Livebands (ServiceCards) | 187px |
| Musik und Technik (Service) | 108px |
| Moin aus Hamburg (Wir) | 178px |
| FAQ | 134px |
| Logo Animation | 190px oben, 190px unten |

---

## Allgemeine Regeln

1. **CSS vor JavaScript**: Wenn etwas mit reinem CSS funktioniert, kein React State verwenden
2. **Original-Code als Referenz**: Bei Unsicherheiten immer `alte website.html` prüfen
3. **Keine Over-Engineering**: Einfache Lösungen bevorzugen
