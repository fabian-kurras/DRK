# Blutspendetermine - Installation & Verwendung

## Installation

### Schritt 1: Datenbanktabelle erstellen

Führe folgendes SQL-Script in phpMyAdmin aus:

```sql
CREATE TABLE IF NOT EXISTS blood_donations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    donation_date DATE NOT NULL,
    donation_time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    organizer VARCHAR(255) DEFAULT 'Admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (donation_date),
    INDEX idx_location (location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Alternative:** Öffne die Datei `setup/blood_donations_setup.sql` in phpMyAdmin und führe sie aus.

### Schritt 2: Fertig!

Die Blutspendetermine-Funktionalität ist nun verfügbar.

---

## Verwendung

### Für Administratoren

1. Melde dich im Admin-Panel an: `/src/admin/dashboard.php`
2. Klicke auf **"🩸 Blutspendetermine verwalten"** in den Schnellaktionen
3. Oder navigiere direkt zu: `/src/admin/blood_donations.php`

**Verfügbare Funktionen:**
- ➕ **Neuer Termin:** Klicke auf "+ Neuer Termin" um einen Blutspendetermin hinzuzufügen
- ✏️ **Bearbeiten:** Klicke auf "Bearbeiten" um einen bestehenden Termin zu ändern
- 🗑️ **Löschen:** Klicke auf "Löschen" um einen Termin zu entfernen

**Erforderliche Felder:**
- **Datum:** Das Datum des Blutspendetermins (YYYY-MM-DD)
- **Uhrzeit:** Die Uhrzeit des Termins (HH:MM)
- **Ort:** Der Veranstaltungsort (z.B. "Wiehl Stadthalle")

### Für Website-Besucher

1. Navigiere zu **"🩸 Blutspende"** im Hauptmenü
2. Oder gehe direkt zu: `/?page=blood_donations`
3. Auf der Seite wird angezeigt:
   - 📅 Bevorstehende Blutspendetermine als Kalender-Karten
   - ⏰ Datum und Uhrzeit
   - 📍 Ort des Termins
   - 🔴 Countdown bis zum nächsten Termin
   - ❓ FAQ mit wichtigen Informationen

---

## Besonderheiten

### Terminanzeige
- **Nur bevorstehende Termine** werden auf der öffentlichen Seite angezeigt
- Termine sind nach Datum sortiert
- Für jeden Termin wird der Countdown angezeigt (Tage, morgen oder heute)

### Admin-Verwaltung
- Alle Termine sind sichtbar (auch vergangene)
- Bearbeitungshistorie wird nicht gespeichert
- Der "Organisator" wird automatisch gesetzt

---

## Datenbank-Schema

```sql
CREATE TABLE blood_donations (
    id INT PRIMARY KEY AUTO_INCREMENT          -- Eindeutige ID
    donation_date DATE NOT NULL                 -- Datum des Termins
    donation_time TIME NOT NULL                 -- Uhrzeit des Termins
    location VARCHAR(255) NOT NULL              -- Ort des Termins
    organizer VARCHAR(255) DEFAULT 'Admin'      -- Wer hat den Termin erstellt
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Erstellungsdatum
)
```

---

## Fehlerbehandlung

**Problem:** "Call to undefined class BloodDonation"
- **Lösung:** Stelle sicher, dass die Datei `src/classes/BloodDonation.php` existiert

**Problem:** "Table 'drk_oberberg.blood_donations' doesn't exist"
- **Lösung:** Führe das SQL-Setup-Script aus (siehe Schritt 1 oben)

**Problem:** Admin sieht den Button nicht
- **Lösung:** Leere den Browser-Cache (Ctrl+Shift+Delete)

---

## Weitere Anpassungen

### Beispieldaten hinzufügen

Führe folgendes SQL in phpMyAdmin aus:

```sql
INSERT INTO blood_donations (donation_date, donation_time, location, organizer) 
VALUES ('2025-01-15', '09:00', 'Wiehl Stadthalle', 'Admin');
```

### Standard-Ort ändern

Bearbeite in `src/admin/blood_donations.php`:
```php
placeholder="z.B. Wiehl Stadthalle"  // Ändere diesen Text
```

---

## Support

Bei Fragen oder Problemen kontaktiere den Website-Administrator.
