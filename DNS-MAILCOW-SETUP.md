# DNS-Konfiguration für musikfürfirmen.de.de mit Mailcow

> **Server**: Hetzner CX32 @ 91.99.177.238
> **Referenz**: nickheymann.de DNS-Einträge (funktionierend)
> **Mailcow Admin**: https://mail.musikfürfirmen.de.de (nach DNS-Setup)

---

## Übersicht: Benötigte DNS-Einträge

| Typ | Name | Wert | TTL | Priorität | Zweck |
|-----|------|------|-----|-----------|-------|
| **A** | `mail` | `91.99.177.238` | 3600 | - | Mail-Server |
| **A** | `*` (Wildcard) | `91.99.177.238` | 300 | - | Alle Subdomains → Server |
| **MX** | `@` | `mail.musikfürfirmen.de.de.` | 3600 | 10 | Mail-Empfang |
| **CNAME** | `autoconfig` | `mail.musikfürfirmen.de.de.` | 3600 | - | Thunderbird Autoconfig |
| **CNAME** | `autodiscover` | `mail.musikfürfirmen.de.de.` | 3600 | - | Outlook Autodiscover |
| **TXT** | `@` | `v=spf1 mx ~all` | 3600 | - | SPF Anti-Spam |
| **TXT** | `dkim._domainkey` | `v=DKIM1; k=rsa; p=<PUBLIC_KEY>` | 3600 | - | DKIM Signatur |
| **TXT** | `_dmarc` | `v=DMARC1; p=quarantine; rua=mailto:postmaster@musikfürfirmen.de.de` | 3600 | - | DMARC Policy |

---

## Schritt-für-Schritt Anleitung

### 1. DKIM Public Key von Mailcow abrufen

**Option A: Via Mailcow Web-UI (empfohlen)**
```
1. SSH auf Server: ssh root@91.99.177.238
2. Mailcow UI öffnen: https://mail.91.99.177.238.nip.io
3. Login mit Admin-Credentials
4. Navigation: Configuration → Mailcow Config → DKIM Keys
5. Domain "musikfürfirmen.de.de" hinzufügen (falls nicht vorhanden)
6. Public Key kopieren (Format: "v=DKIM1; k=rsa; p=MIIBIjA...")
```

**Option B: Via SSH/Docker**
```bash
# SSH auf Server
ssh root@91.99.177.238

# Mailcow Docker Container finden
docker ps | grep mailcow

# DKIM Key generieren (falls nicht vorhanden)
cd /opt/mailcow-dockerized
./generate_config.sh dkim musikfürfirmen.de.de

# Public Key anzeigen
docker exec -it $(docker ps -qf name=rspamd-mailcow) \
  cat /var/lib/rspamd/dkim/musikfürfirmen.de.de.dkim.txt
```

**Erwartete Ausgabe:**
```
musikfürfirmen.de.de._domainkey IN TXT ( "v=DKIM1; k=rsa; "
  "p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA..." ) ;
```

→ **Kopiere nur den Wert zwischen den Anführungszeichen** (ohne `IN TXT`)

---

### 2. DNS-Einträge in Hetzner DNS-Console erstellen

#### A Records
```
Name: mail.musikfürfirmen.de.de
Type: A
Value: 91.99.177.238
TTL: 3600

Name: *.musikfürfirmen.de.de
Type: A
Value: 91.99.177.238
TTL: 300
```

#### MX Record
```
Name: @ (Root Domain)
Type: MX
Value: 10 mail.musikfürfirmen.de.de.
TTL: 3600
Priority: 10
```
⚠️ **Wichtig**: Der Trailing Dot `.` am Ende ist WICHTIG!

#### CNAME Records
```
Name: autoconfig.musikfürfirmen.de.de
Type: CNAME
Value: mail.musikfürfirmen.de.de.
TTL: 3600

Name: autodiscover.musikfürfirmen.de.de
Type: CNAME
Value: mail.musikfürfirmen.de.de.
TTL: 3600
```

#### TXT Records

**SPF (Anti-Spam)**
```
Name: @ (Root Domain)
Type: TXT
Value: v=spf1 mx ~all
TTL: 3600
```

**DKIM (Email-Signatur)**
```
Name: dkim._domainkey
Type: TXT
Value: v=DKIM1; k=rsa; p=<PASTE_HIER_DEN_PUBLIC_KEY>
TTL: 3600
```
⚠️ **Wichtig**: Ersetze `<PASTE_HIER_DEN_PUBLIC_KEY>` mit dem Key aus Schritt 1!

**DMARC (Policy)**
```
Name: _dmarc
Type: TXT
Value: v=DMARC1; p=quarantine; rua=mailto:postmaster@musikfürfirmen.de.de
TTL: 3600
```

---

### 3. DNS-Propagation prüfen (nach 5-60 Min)

```bash
# SPF Check
dig +short TXT musikfürfirmen.de.de | grep spf1

# MX Check
dig +short MX musikfürfirmen.de.de

# DKIM Check
dig +short TXT dkim._domainkey.musikfürfirmen.de.de

# DMARC Check
dig +short TXT _dmarc.musikfürfirmen.de.de
```

**Erwartete Ausgaben:**
```bash
# SPF
"v=spf1 mx ~all"

# MX
10 mail.musikfürfirmen.de.de.

# DKIM
"v=DKIM1; k=rsa; p=MIIBIjA..."

# DMARC
"v=DMARC1; p=quarantine; rua=mailto:postmaster@musikfürfirmen.de.de"
```

---

### 4. Mailcow-Konfiguration finalisieren

```bash
# SSH auf Server
ssh root@91.99.177.238

# Mailcow Web-UI: https://mail.91.99.177.238.nip.io
# (Nach DNS-Setup: https://mail.musikfürfirmen.de.de)

# 1. Domain hinzufügen
#    Configuration → Mail Setup → Domains → Add Domain
#    Domain: musikfürfirmen.de.de

# 2. Mailboxen erstellen
#    Configuration → Mail Setup → Mailboxes → Add Mailbox
#    Beispiel: info@musikfürfirmen.de.de, kontakt@musikfürfirmen.de.de

# 3. SSL-Zertifikat (Let's Encrypt)
#    System → Configuration → Let's Encrypt
#    → Automatisch via Traefik (bereits konfiguriert)
```

---

### 5. Mail-Server testen

**Sende Test-Email:**
```bash
# Via Terminal (swaks Tool)
swaks --to info@musikfürfirmen.de.de \
      --from test@gmail.com \
      --server mail.musikfürfirmen.de.de \
      --auth-user info@musikfürfirmen.de.de \
      --auth-password '<DEIN_PASSWORT>'
```

**Prüfe Spam-Score:**
- https://www.mail-tester.com
- Sende Email an angezeigte Adresse
- Score sollte **>8/10** sein

**SPF/DKIM/DMARC Validation:**
- https://mxtoolbox.com/SuperTool.aspx?action=dkim
- https://mxtoolbox.com/SuperTool.aspx?action=spf
- https://mxtoolbox.com/dmarc.aspx

---

## Vergleich mit nickheymann.de (Funktionierend)

| Eintrag | nickheymann.de | musikfürfirmen.de.de | Status |
|---------|----------------|-------------------|--------|
| A (mail) | 91.99.177.238 | 91.99.177.238 | ✅ Identisch |
| MX | 10 mail.nickheymann.de | 10 mail.musikfürfirmen.de.de | ✅ Analog |
| SPF | `v=spf1 mx ~all` | `v=spf1 mx ~all` | ✅ Identisch |
| DKIM | `dkim._domainkey` → Public Key | `dkim._domainkey` → **MUSS GENERIERT WERDEN** | ⚠️ TODO |
| DMARC | `_dmarc` → `v=DMARC1; p=quarantine;...` | `_dmarc` → `v=DMARC1; p=quarantine;...` | ✅ Analog |
| CNAME (autoconfig) | → mail.nickheymann.de | → mail.musikfürfirmen.de.de | ✅ Analog |
| CNAME (autodiscover) | → mail.nickheymann.de | → mail.musikfürfirmen.de.de | ✅ Analog |

---

## Troubleshooting

### Problem: DKIM-Validierung schlägt fehl

**Check 1: Public Key Format**
```bash
# TXT Record darf max 255 Zeichen pro String sein
# Mailcow splittet automatisch in mehrere Strings:
"v=DKIM1; k=rsa; p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA..."
# → Wird zu:
"v=DKIM1; k=rsa; " "p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA..."
```

**Check 2: Selector korrekt?**
```bash
# Mailcow nutzt "dkim" als Selector (Standard)
# DNS-Name muss exakt sein: dkim._domainkey.musikfürfirmen.de.de
```

**Fix:**
```bash
# Mailcow Container neu starten
cd /opt/mailcow-dockerized
docker-compose restart rspamd-mailcow
```

---

### Problem: Emails landen im Spam

**Checklist:**
- ✅ SPF Record vorhanden? (`dig TXT musikfürfirmen.de.de`)
- ✅ DKIM Signatur validiert? (Mail-Header prüfen: `DKIM-Signature`)
- ✅ DMARC Policy aktiv? (`dig TXT _dmarc.musikfürfirmen.de.de`)
- ✅ Reverse DNS (PTR) korrekt? (`dig -x 91.99.177.238` → sollte mail.musikfürfirmen.de.de zeigen)

**Reverse DNS (PTR) bei Hetzner setzen:**
```
1. Hetzner Cloud Console → Server → musikfürfirmen.de
2. Networking → Primary IPv4 → Click on IP
3. Edit Reverse DNS: mail.musikfürfirmen.de.de
4. Save
```

---

### Problem: Port 25/587/993 nicht erreichbar

**Check 1: Firewall (Hetzner)**
```bash
ssh root@91.99.177.238

# UFW Status
ufw status

# Ports öffnen (falls geblockt)
ufw allow 25/tcp   # SMTP
ufw allow 587/tcp  # Submission
ufw allow 465/tcp  # SMTPS
ufw allow 143/tcp  # IMAP
ufw allow 993/tcp  # IMAPS
ufw allow 110/tcp  # POP3
ufw allow 995/tcp  # POP3S
```

**Check 2: Docker-Container laufen?**
```bash
docker ps | grep mailcow
docker-compose -f /opt/mailcow-dockerized/docker-compose.yml ps
```

---

## Erwartete DNS-Konfiguration (Final)

**Hetzner DNS-Console Screenshot-Vergleich:**

| Feld | nickheymann.de (IST) | musikfürfirmen.de.de (SOLL) |
|------|---------------------|--------------------------|
| **A Record** | `mail` → 91.99.177.238 | `mail` → 91.99.177.238 |
| **A Record** | `*` → 91.99.177.238 | `*` → 91.99.177.238 |
| **MX** | `@` → 10 mail.nickheymann.de | `@` → 10 mail.musikfürfirmen.de.de |
| **CNAME** | `autoconfig` → mail.nickheymann.de | `autoconfig` → mail.musikfürfirmen.de.de |
| **CNAME** | `autodiscover` → mail.nickheymann.de | `autodiscover` → mail.musikfürfirmen.de.de |
| **TXT (SPF)** | `v=spf1 mx ~all` | `v=spf1 mx ~all` |
| **TXT (DKIM)** | `dkim._domainkey` → `v=DKIM1; k=rsa; p=MII...` | `dkim._domainkey` → **MUSS GENERIERT WERDEN** |
| **TXT (DMARC)** | `_dmarc` → `v=DMARC1; p=quarantine;...` | `_dmarc` → `v=DMARC1; p=quarantine; rua=mailto:postmaster@musikfürfirmen.de.de` |

---

## Next Steps

1. ✅ **DKIM Key generieren** (siehe Schritt 1)
2. ✅ **DNS-Einträge in Hetzner setzen** (siehe Schritt 2)
3. ⏳ **DNS-Propagation abwarten** (5-60 Min, siehe Schritt 3)
4. ✅ **Mailcow konfigurieren** (Domain + Mailboxen, siehe Schritt 4)
5. ✅ **Test-Email senden** (mail-tester.com, siehe Schritt 5)
6. ✅ **Reverse DNS (PTR) bei Hetzner setzen** (siehe Troubleshooting)

---

## Support & Referenzen

- **Mailcow Docs**: https://docs.mailcow.email
- **Hetzner DNS API**: https://dns.hetzner.com
- **Mail-Tester**: https://www.mail-tester.com
- **MXToolbox**: https://mxtoolbox.com
