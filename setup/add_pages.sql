-- ===================================================================
-- Über Uns und Datenschutz Seiten hinzufügen
-- ===================================================================

-- Prüfen ob die Tabelle "pages" existiert und die Seiten einfügen
INSERT IGNORE INTO pages (slug, title, content, author_id, is_published) 
SELECT 'uber-uns', 
       'Über Uns', 
       '<h2>Das Deutsche Rote Kreuz Oberberg SW</h2>
<p>Wir sind eine gemeinnützige Hilfsorganisation mit über 100 Jahren Erfahrung im Dienste der Menschheit. Unsere Aufgabe ist es, Menschen in Not zu helfen, unabhängig von ihrer Herkunft, Nationalität, Religion oder sozialen Stellung.</p>

<h3>Unsere Geschichte</h3>
<p>Das Deutsche Rote Kreuz Oberberg Südwest wurde gegründet, um den Menschen in unserer Region Hilfe und Unterstützung zu bieten. Seitdem haben wir tausenden Menschen in Notlagen geholfen.</p>

<h3>Unsere Aufgaben</h3>
<ul>
<li><strong>Rettungsdienste und Notfallhilfe:</strong> Schnelle und professionelle Hilfe in Notfällen</li>
<li><strong>Pflegeausbildung und Krankenbetreuung:</strong> Qualifizierte Fachkräfte für medizinische Versorgung</li>
<li><strong>Erste-Hilfe-Kurse:</strong> Schulung von Bürgern in Erste-Hilfe-Maßnahmen</li>
<li><strong>Blutspendendienste:</strong> Sichere und ausreichende Blutversorgung für Krankenhäuser</li>
<li><strong>Soziale Dienste und Beratung:</strong> Unterstützung für Menschen in schwierigen Lebenssituationen</li>
</ul>

<h3>Unsere Werte</h3>
<p>Wir arbeiten nach den sieben Grundprinzipien des Roten Kreuzes:</p>
<ul>
<li>Menschlichkeit</li>
<li>Unparteilichkeit</li>
<li>Neutralität</li>
<li>Unabhängigkeit</li>
<li>Freiwilligkeit</li>
<li>Einheit</li>
<li>Universalität</li>
</ul>

<h3>Werden Sie Mitglied</h3>
<p>Sie möchten uns unterstützen? Wir freuen uns über Ihre Mitgliedschaft oder Ihre Spende. Kontaktieren Sie uns gerne für weitere Informationen!</p>',
       1,
       1
WHERE NOT EXISTS (SELECT 1 FROM pages WHERE slug = 'uber-uns');

INSERT IGNORE INTO pages (slug, title, content, author_id, is_published)
SELECT 'datenschutz',
       'Datenschutz',
       '<h2>Datenschutzerklärung</h2>

<p>Das Deutsche Rote Kreuz Oberberg Südwest nimmt den Schutz Ihrer persönlichen Daten sehr ernst. Diese Datenschutzerklärung informiert Sie über die Verarbeitung Ihrer personenbezogenen Daten auf unserer Website.</p>

<h3>Verantwortlicher</h3>
<p>Verantwortlich für die Datenverarbeitung ist:<br>
<strong>DRK-Ortsverein Oberberg Südwest e.V.</strong><br>
Florastraße 3<br>
51674 Wiehl<br>
Email: info@drk-oberberg.de<br>
Tel: +49 (0) 2202 123456</p>

<h3>Erfasste Daten</h3>
<p>Diese Website erhebt nur minimal notwendige Daten:</p>
<ul>
<li>Kontaktformular-Daten (Name, Email, Telefon, Nachricht) - nur mit Ihrer Zustimmung</li>
<li>Server-Logs (anonymisierte IP-Adressen, Zugriffszeiten) für technische Verwaltung</li>
</ul>

<h3>Datenverarbeitung</h3>
<p>Ihre Daten werden ausschließlich für die Beantwortung Ihrer Anfrage verwendet und nicht an Dritte weitergegeben. Wir speichern die Daten nur so lange wie nötig.</p>

<h3>Ihre Rechte</h3>
<p>Sie haben das Recht:</p>
<ul>
<li>Auskunft über Ihre gespeicherten Daten zu erhalten</li>
<li>Ihre Daten korrigieren zu lassen</li>
<li>Ihre Daten löschen zu lassen (Recht auf Vergessenwerden)</li>
<li>Ihre Einwilligung zur Datenverarbeitung zu widerrufen</li>
</ul>

<h3>Kontakt</h3>
<p>Für Anfragen zum Datenschutz können Sie uns unter der oben angegebenen Adresse oder per Email kontaktieren.</p>',
       1,
       1
WHERE NOT EXISTS (SELECT 1 FROM pages WHERE slug = 'datenschutz');

-- Optional: Aktivitäten-Seite hinzufügen (falls als statische Seite gewünscht)
INSERT IGNORE INTO pages (slug, title, content, author_id, is_published)
SELECT 'aktivitaten',
       'Aktivitäten',
       '<h2>Aktivitäten und Veranstaltungen</h2>

<p>Unser DRK Ortsverein führt laufend verschiedene Aktivitäten und Veranstaltungen durch, um der Bevölkerung zu helfen und sie zu unterstützen.</p>

<h3>Regelmäßige Angebote</h3>
<ul>
<li><strong>Erste-Hilfe-Kurse:</strong> Regelmäßig stattfindende Schulungen für alle interessierten Bürger</li>
<li><strong>Blutspendetermine:</strong> Mehrmals jährlich organisieren wir Blutspendetermine</li>
<li><strong>Soziale Dienste:</strong> Betreuung und Unterstützung von bedürftigen Menschen</li>
<li><strong>Jugendaktivitäten:</strong> Spezielle Programme für die Jungendgruppen des DRK</li>
</ul>

<h3>Aktuelle Veranstaltungen</h3>
<p>Alle aktuellen Veranstaltungen und Aktivitäten finden Sie <a href="?page=activities">in unserem Aktivitätenkalender</a>.</p>

<h3>Werde aktiv</h3>
<p>Sie möchten beim DRK aktiv werden und helfen? Wir suchen ständig Ehrenamtliche und Helfer. Kontaktieren Sie uns für weitere Informationen!</p>',
       1,
       1
WHERE NOT EXISTS (SELECT 1 FROM pages WHERE slug = 'aktivitaten');
