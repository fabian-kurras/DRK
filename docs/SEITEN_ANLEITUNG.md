# Über Uns und Aktivitäten Seiten

## Überblick

Das System für "Über Uns" und "Aktivitäten" Seiten wurde komplett integriert:

### 1. **Über Uns Seite** (`/index.php?page=uber-uns`)
- Eine statische Seite mit Informationen über das DRK Oberberg SW
- Verwaltbar über das Admin-Interface unter "Seiten-Verwaltung"
- Die Inhalte können direkt vom Administrator bearbeitet werden

### 2. **Aktivitäten Seite** (`/index.php?page=activities`)
- Zeigt alle aktuellen Nachrichten und Veranstaltungen
- Wird dynamisch aus der Datenbank zusammengestellt
- Beinhaltet auch eine optionale statische "Aktivitäten"-Seite

### 3. **Datenschutz Seite** (`/index.php?page=datenschutz`)
- Eine neue Seite für Datenschutzinformationen
- Ebenfalls über die "Seiten-Verwaltung" verwaltbar

## Verwaltung

### Admin-Interface für Seiten
**Erreichbar unter:** `/src/admin/pages.php`

Funktionen:
- ✅ Neue Seiten erstellen
- ✅ Bestehende Seiten bearbeiten
- ✅ Seiten veröffentlichen/verbergen
- ✅ Seiten löschen

### Datenbankstruktur
Die Seiten werden in der Tabelle `pages` gespeichert:
- `slug`: Die URL-freundliche Kennung (z.B. "uber-uns")
- `title`: Der Seitentitel
- `content`: Der HTML-Inhalt der Seite
- `author_id`: Der Autor (Administrator)
- `is_published`: Status (1 = veröffentlicht, 0 = verborgen)

## Navigation

Die neuen Seiten sind automatisch in der Navigation eingebunden:
- **Header-Navigation:** Links zu "Über uns" und "Aktivitäten"
- **Footer:** Schnelllinks zu "Über uns", "Datenschutz" und anderen Seiten
- **Home-Page:** Button "Mehr erfahren" verlinkt zur "Über Uns" Seite

## Initialisierung

Zum ersten Mal müssen die Seiten in die Datenbank eingefügt werden:

```sql
-- Führe diese SQL-Datei aus:
mysql -u root drk_oberberg < setup/add_pages.sql
```

Oder manuell im phpMyAdmin:
1. Öffne die Datenbank `drk_oberberg`
2. Gehe zum Tab "SQL"
3. Kopiere den Inhalt aus `setup/add_pages.sql`
4. Führe das Skript aus

## Verwendete Features

### HTML-Content
Der Inhalt der Seiten unterstützt HTML-Tags wie:
- `<h2>`, `<h3>` für Überschriften
- `<p>` für Absätze
- `<ul>`, `<li>` für Listen
- `<strong>`, `<em>` für Formatierung
- `<a href="">` für Links

### Sicherheit
- XSS-Schutz durch `h()` Funktion beim Anzeigen
- Nur Administratoren können Seiten bearbeiten
- SQL-Injection-Schutz durch Prepared Statements

## Beispiel: Neue Seite erstellen

1. Gehe zu `/src/admin/pages.php`
2. Klicke auf "+ Neue Seite"
3. Fülle das Formular aus:
   - **Slug:** `impressum` (URL wird dann: `/index.php?page=impressum`)
   - **Titel:** `Impressum`
   - **Inhalt:** Dein HTML-Content
4. Aktiviere "Sofort veröffentlichen"
5. Klicke "Erstellen"

Die Seite ist nun unter `/index.php?page=impressum` erreichbar!

## Dashboard

Der Admin-Dashboard zeigt nun auch:
- Eine neue Karte für "Seiten-Verwaltung"
- Schnellaktion zum Erstellen neuer Seiten
