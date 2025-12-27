# DRK Oberberg SW - Komplette Website
## Setup & Dokumentation

---

## 📋 Übersicht

Diese Website wurde als vollständig funktionsfähige Anwendung für das Deutsche Rote Kreuz Oberberg Südwesten entwickelt. Sie enthält:

- **Frontend**: Responsive Website mit Tailwind CSS
- **Backend**: PHP mit PDO für sichere Datenbankverbindung
- **Datenbank**: MySQL mit optimiertem Schema
- **Admin-Panel**: Verwaltung von Nachrichten, Veranstaltungen und Kontaktanfragen
- **Sicherheit**: Sichere Authentifizierung mit Passwort-Hashing

---

## 🗂️ Dateistruktur

```
DRK/
├── index.php                 # Haupteinstiegspunkt
├── public/
│   ├── css/
│   │   └── style.css        # Custom CSS Styles
│   ├── js/
│   │   └── main.js          # JavaScript Funktionalität
│   └── images/              # Bilder (leer - für User)
│
├── src/
│   ├── config/
│   │   ├── config.php       # Globale Konfiguration
│   │   └── database.php     # Datenbank-Verbindung
│   │
│   ├── classes/
│   │   ├── News.php         # Nachrichten-Klasse
│   │   ├── Event.php        # Veranstaltungen-Klasse
│   │   ├── Page.php         # Statische Seiten-Klasse
│   │   ├── Contact.php      # Kontaktformular-Klasse
│   │   └── User.php         # Benutzer & Auth-Klasse
│   │
│   ├── pages/
│   │   ├── layout.php       # Master Template
│   │   └── home.php         # Startseite
│   │
│   └── admin/
│       ├── login.php        # Admin Login
│       ├── dashboard.php    # Admin Dashboard
│       ├── news.php         # Nachrichten-Verwaltung
│       ├── events.php       # Veranstaltungen-Verwaltung
│       ├── contacts.php     # Kontaktanfragen-Verwaltung
│       └── logout.php       # Logout
│
└── database/
    └── drk_oberberg.sql     # Datenbank-Dump mit Demo-Daten
```

---

## 🚀 Installation in XAMPP

### Schritt 1: Datenbank erstellen

1. **phpMyAdmin öffnen**: `http://localhost/phpmyadmin`
2. **Neue Datenbank erstellen**:
   - Name: `drk_oberberg`
   - Kollation: `utf8mb4_unicode_ci`
3. **SQL-Datei importieren**:
   - Gehen Sie zur Datenbank `drk_oberberg`
   - Reiter "Importieren"
   - Datei `database/drk_oberberg.sql` auswählen
   - Klicken Sie "Ausführen"

### Schritt 2: Website-Dateien einrichten

1. **Alle Dateien in das XAMPP-Verzeichnis kopieren**:
   ```
   C:\xampp\htdocs\DRK\
   ```

2. **Apache neu starten** (wenn nötig)

### Schritt 3: Website testen

1. **Browser öffnen**: `http://localhost/DRK`
2. **Admin-Bereich**: `http://localhost/DRK/src/admin/login.php`

---

## 🔐 Standard-Zugangsdaten

**Demo-Benutzer für Admin-Panel:**
- **Username**: `admin`
- **Passwort**: `admin123`

> ⚠️ **WICHTIG**: Ändern Sie die Zugangsdaten in der Produktion!

---

## 📑 Seiten & Funktionen

### Öffentliche Seiten

| URL | Beschreibung |
|-----|-------------|
| `http://localhost/DRK` | Startseite mit News und Veranstaltungen |
| `http://localhost/DRK/index.php?page=activities` | Alle Nachrichten und Veranstaltungen |
| `http://localhost/DRK/index.php?page=contact` | Kontaktformular |
| `http://localhost/DRK/pages/uber-uns` | Über uns (statische Seite) |
| `http://localhost/DRK/pages/datenschutz` | Datenschutz (statische Seite) |

### Admin-Seiten

| URL | Beschreibung |
|-----|-------------|
| `http://localhost/DRK/src/admin/login.php` | Admin Login |
| `http://localhost/DRK/src/admin/dashboard.php` | Dashboard mit Übersicht |
| `http://localhost/DRK/src/admin/news.php` | Nachrichten verwalten |
| `http://localhost/DRK/src/admin/events.php` | Veranstaltungen verwalten |
| `http://localhost/DRK/src/admin/contacts.php` | Kontaktanfragen ansehen |

---

## 🗄️ Datenbank-Schema

### Tabellen

**users** - Administratoren
```sql
- id: INT (Primary Key)
- username: VARCHAR(100) - Eindeutiger Benutzername
- email: VARCHAR(100) - Email-Adresse
- password: VARCHAR(255) - Gehashtes Passwort
- full_name: VARCHAR(150) - Vollständiger Name
- role: ENUM('admin', 'editor') - Benutzerrolle
- created_at: TIMESTAMP - Erstellungsdatum
- is_active: TINYINT - Benutzer aktiv?
```

**news** - Nachrichten/Artikel
```sql
- id: INT (Primary Key)
- title: VARCHAR(255) - Titel
- content: LONGTEXT - Artikelinhalt
- excerpt: VARCHAR(500) - Zusammenfassung
- author_id: INT (Foreign Key) - Autor-ID
- published_at: TIMESTAMP - Veröffentlichungsdatum
- is_published: TINYINT - Veröffentlicht?
```

**events** - Veranstaltungen
```sql
- id: INT (Primary Key)
- title: VARCHAR(255) - Titel
- description: LONGTEXT - Beschreibung
- event_date: DATE - Veranstaltungsdatum
- event_time: TIME - Veranstaltungszeit
- location: VARCHAR(255) - Ort
- organizer_id: INT (Foreign Key) - Organisator-ID
- is_published: TINYINT - Veröffentlicht?
```

**contacts** - Kontaktanfragen
```sql
- id: INT (Primary Key)
- name: VARCHAR(150) - Name
- email: VARCHAR(100) - Email
- phone: VARCHAR(20) - Telefonnummer
- subject: VARCHAR(255) - Betreff
- message: LONGTEXT - Nachricht
- created_at: TIMESTAMP - Eingabedatum
- is_read: TINYINT - Gelesen?
```

**pages** - Statische Seiten
```sql
- id: INT (Primary Key)
- slug: VARCHAR(100) - URL-Slug
- title: VARCHAR(255) - Titel
- content: LONGTEXT - Seiteninhalt
- author_id: INT (Foreign Key) - Autor-ID
- is_published: TINYINT - Veröffentlicht?
```

---

## 🔧 Konfiguration anpassen

### 1. Datenbankverbindung (`src/config/database.php`)

Falls MySQL nicht auf dem Standard-Port läuft oder andere Credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'drk_oberberg');
define('DB_PORT', 3306);
```

### 2. Base URL (`src/config/config.php`)

Falls auf anderem Port/Domain gehostet:

```php
define('BASE_URL', 'http://localhost/DRK');
```

### 3. Email-Versand (optional)

Sie können im Kontaktformular (`index.php`) eine Email-Benachrichtigung hinzufügen:

```php
mail($adminEmail, $subject, $message);
```

---

## 💡 Funktionen nutzen

### Neue Nachricht erstellen

1. Login: `http://localhost/DRK/src/admin/login.php`
2. Gehen Sie zu "Nachrichten-Verwaltung"
3. Klicken Sie "+ Neue Nachricht"
4. Füllen Sie Titel und Inhalt aus
5. Aktivieren Sie "Sofort veröffentlichen"
6. Klicken Sie "Erstellen"

### Neue Veranstaltung erstellen

1. Im Admin-Panel → Veranstaltungen
2. "+ Neue Veranstaltung" klicken
3. Datum, Zeit, Ort ausfüllen
4. Beschreibung hinzufügen
5. Veröffentlichen

### Kontaktanfragen anzeigen

1. Im Admin-Panel → Kontaktanfragen
2. Wählen Sie eine Anfrage aus der Liste
3. Sehen Sie alle Details
4. Klicken Sie auf "Email verfassen" um zu antworten

---

## 🎨 Design anpassen

### Farben ändern

Die primäre DRK-Rotfarbe ist überall verwendet:
- **Tailwind Class**: `bg-red-700`, `text-red-700`
- **Hex**: `#dc2626`

Um die Farbe zu ändern, modifizieren Sie:
1. Alle `bg-red-*` und `text-red-*` Classes in HTML
2. oder ändern Sie `:root` in `public/css/style.css`:

```css
:root {
    --primary-red: #dc2626; /* ← Diese Farbe ändern */
}
```

### Logo/Bilder hinzufügen

Speichern Sie Bilder in `public/images/`:

```html
<img src="<?php echo BASE_URL; ?>/public/images/logo.png" alt="Logo">
```

---

## 🔒 Sicherheitsmaßnahmen

Diese Website implementiert mehrere Sicherheitsmaßnahmen:

✅ **Prepared Statements** - Schutz vor SQL-Injection
✅ **Passwort-Hashing** - BCrypt mit Salting
✅ **Session Management** - Sichere Session-Handling
✅ **Input Validation** - Validierung aller Eingaben
✅ **Output Encoding** - Escaping mit `htmlspecialchars()`
✅ **Security Headers** - X-Frame-Options, CSP, etc.
✅ **CSRF-Protection** - Token-basiert (implementierbar)

---

## 📦 Abhängigkeiten

**Keine externen PHP-Pakete erforderlich!**

Die Website verwendet nur:
- PHP 7.4+ (Standard in XAMPP)
- MySQL 5.7+ (Standard in XAMPP)
- Tailwind CSS (via CDN)
- Vanilla JavaScript (kein Framework nötig)

---

## 🐛 Häufige Fehler beheben

### "Datenbankfehler: Connection refused"
- **Lösung**: Stellen Sie sicher, dass MySQL läuft
- XAMPP Control Panel: MySQL starten

### "Blank Page" angezeigt
- **Lösung**: Überprüfen Sie `php_errors.log` im logs-Verzeichnis
- oder aktivieren Sie `display_errors = 1` in `php.ini`

### Admin-Login funktioniert nicht
- **Lösung**: Datenbank wurde nicht importiert
- Führen Sie `database/drk_oberberg.sql` erneut in phpMyAdmin aus

### Styles werden nicht geladen
- **Lösung**: Überprüfen Sie `BASE_URL` in `src/config/config.php`
- Sollte auf korrektes `/DRK` Verzeichnis zeigen

---

## 🚀 Erweiterungen

Mögliche zukünftige Funktionen:

1. **Newsletter-System** - Tabelle `newsletter_subscribers`
2. **Datei-Download** - Downloads für Formulare/Dokumente
3. **Spendenmodul** - Integration mit Payment-Gatewa (Stripe)
4. **Galerie** - Bildergalerie mit Lightbox
5. **Suche** - Volltextsuche über News und Events
6. **Mehrsprachigkeit** - i18n Support für DE/EN
7. **API** - REST API für externe Apps
8. **Reports** - Statistiken und Auswertungen

---

## 📞 Support & Dokumentation

### Code-Stil
- **Alle Dateien sind ausführlich kommentiert**
- PHP PSR-2 Konventionen
- Englische Kommentare, deutsche Beschriftung im UI

### Weitere Ressourcen
- [PHP Dokumentation](https://www.php.net/)
- [MySQL Dokumentation](https://dev.mysql.com/)
- [Tailwind CSS Docs](https://tailwindcss.com/)

---

## ✅ Checkliste für Production

Vor dem Live-Schalten:

- [ ] Datenbank-Backups einrichten
- [ ] Admin-Passwörter ändern
- [ ] `error_reporting` auf 0 setzen
- [ ] `BASE_URL` auf richtige Domain ändern
- [ ] HTTPS aktivieren
- [ ] Regelmäßige Backups planen
- [ ] Monitoring/Logging konfigurieren
- [ ] Spam-Protection für Kontaktformular

---

**Version**: 1.0  
**Erstellt**: 2025-12-27  
**Lizenz**: Kostenlos zur Nutzung für DRK

