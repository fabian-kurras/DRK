# DRK Oberberg SW - Komplette Website
## Deutsche Rotes Kreuz Oberberg Südwesten

**Eine vollständig funktionsfähige Website für das DRK mit Admin-Panel, Nachrichtensystem und Kontaktformular.**

---

### 🚀 Quick Start

1. **Datenbank importieren**
   - Öffnen Sie phpMyAdmin: `http://localhost/phpmyadmin`
   - Datenbank `drk_oberberg` erstellen
   - SQL-Datei `database/drk_oberberg.sql` importieren

2. **Website öffnen**
   - Browser: `http://localhost/DRK`
   - Admin: `http://localhost/DRK/src/admin/login.php`

3. **Anmelden**
   - Username: `admin`
   - Passwort: `admin123`

### 📋 Inhalte

✅ **Responsive Frontend**
- Startseite mit aktuellen News und Events
- Über uns, Kontakt, Datenschutz Seiten
- Nachrichtendetails und Eventkalender
- Kontaktformular mit Validierung

✅ **Admin-Panel**
- Nachrichten verwalten (CRUD)
- Veranstaltungen verwalten (CRUD)
- Kontaktanfragen ansehen
- Benutzer & Authentifizierung
- System-Information

✅ **Datenbank**
- MySQL mit 5 Tabellen
- Sichere Prepared Statements
- Normalisiertes Schema
- Demo-Daten enthalten

✅ **Sicherheit**
- Passwort-Hashing (BCrypt)
- Session Management
- Input Validation & Output Encoding
- Security Headers

### 📂 Struktur

```
DRK/
├── index.php              # Haupteintrag
├── public/                # CSS, JS, Bilder
├── src/
│   ├── config/            # Konfiguration & DB
│   ├── classes/           # PHP-Klassen
│   ├── pages/             # Templates
│   └── admin/             # Admin-Interface
├── database/              # SQL-Dumps
└── SETUP.md              # Detaillierte Dokumentation
```

### 🔐 Standard-Zugangsdaten

- **Username**: admin
- **Passwort**: admin123

⚠️ **Ändern Sie diese in der Produktion!**

### 🛠️ Technologie

- **PHP 7.4+** (keine Frameworks)
- **MySQL 5.7+**
- **Tailwind CSS** (via CDN)
- **Vanilla JavaScript**
- **XAMPP** (Development)

### 📚 Dokumentation

Siehe **SETUP.md** für:
- Detaillierte Installationsanleitung
- Datenbank-Schema-Dokumentation
- Feature-Beschreibungen
- Häufige Fehler beheben
- Konfigurationsoptionen
- Erweiterungsmöglichkeiten

### 🎨 Features

**Öffentlich:**
- Dynamische Homepage mit News
- Eventkalender
- Kontaktformular
- Statische Seiten (Über uns, Datenschutz)
- Responsive Design

**Admin:**
- News CRUD mit Publish/Draft Status
- Event CRUD mit Datum-Management
- Kontaktanfragen-Verwaltung
- Dashboard mit Statistiken
- Sichere Authentifizierung

### 📝 Lizenz

Kostenlos zur Nutzung für DRK Oberberg SW.

---

**Version**: 1.0  
**Erstellt**: 2025-12-27  

**Support**: Alle Dateien sind umfassend kommentiert und dokumentiert.
