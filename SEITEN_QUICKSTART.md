# SCHNELLANLEITUNG: Über Uns & Aktivitäten Seiten

## 🚀 Schnell los geht's!

### Schritt 1: Datenbank initialisieren (einmalig)
```bash
mysql -u root drk_oberberg < setup/add_pages.sql
```

### Schritt 2: Admin-Panel öffnen
1. Gehe zu: `http://localhost/DRK/src/admin/`
2. Login: 
   - Benutzername: `admin`
   - Passwort: `admin123`

### Schritt 3: Seiten verwalten
- **Seiten-Verwaltung:** http://localhost/DRK/src/admin/pages.php
- Hier kannst du "Über uns", "Datenschutz" und andere Seiten bearbeiten

## 📄 Die neuen Seiten

| Seite | URL | Beschreibung |
|-------|-----|-------------|
| Über Uns | `/index.php?page=uber-uns` | Infos über die Organisation |
| Aktivitäten | `/index.php?page=activities` | Nachrichten + Veranstaltungen |
| Datenschutz | `/index.php?page=datenschutz` | Datenschutzerklärung |

## 🎨 Inhalt bearbeiten

### Beispiel: "Über Uns" Seite ändern
1. Im Admin-Panel: **Seiten-Verwaltung**
2. In der Liste: "Über Uns" → **Bearbeiten** klicken
3. Text ändern (HTML erlaubt!)
4. **Aktualisieren** klicken ✅

### Neue Seite erstellen
1. **+ Neue Seite** Knopf klicken
2. Formular ausfüllen:
   - **Slug:** `meine-seite` (für URL)
   - **Titel:** `Meine Seite`
   - **Inhalt:** Dein Text (mit HTML möglich)
3. **Erstellen** klicken ✅

## 🔗 Navigation

Die Seiten sind automatisch in der Navigation eingebunden:
- **Oben:** Start | Über uns | Aktivitäten | Blutspende | Kontakt
- **Unten:** Schnelllinks im Footer

## ✨ HTML-Beispiele

### Überschrift
```html
<h2>Meine Überschrift</h2>
<h3>Unterüberschrift</h3>
```

### Text mit Formatierung
```html
<p>Das ist ein <strong>wichtiger</strong> Text und <em>kursiv</em>.</p>
```

### Liste
```html
<ul>
<li>Punkt 1</li>
<li>Punkt 2</li>
<li>Punkt 3</li>
</ul>
```

### Link
```html
<a href="https://www.beispiel.de">Zum Beispiel</a>
```

## 📋 Was wurde implementiert?

✅ Admin-Interface für Seiten (`/src/admin/pages.php`)
✅ "Über Uns" Seite 
✅ "Aktivitäten" Seite (dynamisch)
✅ "Datenschutz" Seite
✅ Navigation aktualisiert
✅ Dashboard erweitert

## 🆘 Probleme?

1. **Seite nicht erreichbar?**
   - Datenbank-Init-Script ausgeführt? `setup/add_pages.sql`
   - Ist die Seite veröffentlicht? (Status in Admin)

2. **Login funktioniert nicht?**
   - User: `admin`, Passwort: `admin123`
   - Session starten: F5 drücken

3. **Änderungen werden nicht angezeigt?**
   - Browser-Cache leeren (Strg+F5)
   - Seite ist veröffentlicht? (Green-Status im Admin)

## 📞 Support
Bei Fragen: info@drk-oberberg.de
