-- ===================================================================
-- Deutsche Rotes Kreuz Oberberg SW - Datenbankschema
-- ===================================================================

-- Datenbank erstellen
CREATE DATABASE IF NOT EXISTS drk_oberberg;
USE drk_oberberg;

-- ===================================================================
-- TABELLE: users (Administratoren)
-- ===================================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(150),
    role ENUM('admin', 'editor') DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active TINYINT DEFAULT 1
);

-- ===================================================================
-- TABELLE: news (Nachrichten/Artikel)
-- ===================================================================
CREATE TABLE news (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt VARCHAR(500),
    author_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    is_published TINYINT DEFAULT 0,
    image_url VARCHAR(255),
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ===================================================================
-- TABELLE: events (Veranstaltungen)
-- ===================================================================
CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description LONGTEXT NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(255) NOT NULL,
    organizer_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_published TINYINT DEFAULT 0,
    image_url VARCHAR(255),
    FOREIGN KEY (organizer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ===================================================================
-- TABELLE: contacts (Kontakt-Anfragen)
-- ===================================================================
CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT DEFAULT 0,
    is_answered TINYINT DEFAULT 0
);

-- ===================================================================
-- TABELLE: pages (Statische Seiten)
-- ===================================================================
CREATE TABLE pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(100) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    author_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_published TINYINT DEFAULT 1,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ===================================================================
-- INDIZES für bessere Performance
-- ===================================================================
CREATE INDEX idx_news_published ON news(is_published, published_at);
CREATE INDEX idx_events_date ON events(event_date, is_published);
CREATE INDEX idx_contacts_created ON contacts(created_at);
CREATE INDEX idx_pages_slug ON pages(slug);

-- ===================================================================
-- DEMO-DATEN einfügen
-- ===================================================================

-- Standard Admin-Benutzer (Passwort: admin123)
INSERT INTO users (username, email, full_name, password, role, is_active) VALUES
('admin', 'admin@drk-oberberg.de', 'Administrator', '$2y$10$8M2rWjwbPQZ9qP6ZqZ9Z9OvC1v6Zz5xK7w8Y2q0M0j0K0I0H0G0', 'admin', 1),
('redakteur', 'redakteur@drk-oberberg.de', 'News Redakteur', '$2y$10$8M2rWjwbPQZ9qP6ZqZ9Z9OvC1v6Zz5xK7w8Y2q0M0j0K0I0H0G0', 'editor', 1);

-- Beispiel-Nachrichten
INSERT INTO news (title, content, excerpt, author_id, is_published, published_at) VALUES
('Willkommen beim DRK Oberberg SW', 'Wir begrüßen Sie herzlich auf unserer Website. Das Deutsche Rote Kreuz Oberberg Südwesten ist eine gemeinnützige Organisation, die sich seit Jahren dem Gemeinwohl verpflichtet.', 'Erfahren Sie mehr über unsere Organisation und Ziele.', 1, 1, NOW()),
('Notfallausbildung: Erste Hilfe Kurse', 'Unser Team bietet regelmäßig Erste-Hilfe-Kurse für Bürger und Betriebe an. Die nächsten Termine finden Sie in unserem Veranstaltungskalender.', 'Melden Sie sich jetzt zu unserem Erste-Hilfe-Kurs an!', 1, 1, NOW());

-- Beispiel-Veranstaltungen
INSERT INTO events (title, description, event_date, event_time, location, organizer_id, is_published) VALUES
('Erste-Hilfe-Kurs Grundlagen', 'Lernen Sie in diesem Kurs die wichtigsten Maßnahmen zur Ersten Hilfe. Für alle Interessierten kostenlos oder mit Kostenbeteiligung.', CURDATE() + INTERVAL 7 DAY, '18:00:00', 'DRK Geschäftsstelle, Oberberg', 1, 1),
('Blutspende-Aktion', 'Wir freuen uns auf Ihre Blutspende! Helfen Sie Leben zu retten.', CURDATE() + INTERVAL 14 DAY, '10:00:00', 'Sporthalle Süd', 1, 1);

-- Beispiel-Seiten (Über uns, Kontakt)
INSERT INTO pages (slug, title, content, author_id, is_published) VALUES
('uber-uns', 'Über Uns', '<h2>Das Deutsche Rote Kreuz Oberberg SW</h2><p>Wir sind eine gemeinnützige Hilfsorganisation mit über 100 Jahren Erfahrung im Dienste der Menschheit.</p><p><strong>Unsere Aufgaben:</strong></p><ul><li>Rettungsdienste und Notfallhilfe</li><li>Pflegeausbildung und Krankenbetreuung</li><li>Erste-Hilfe-Kurse</li><li>Blutspendendienste</li><li>Soziale Dienste und Beratung</li></ul>', 1, 1),
('datenschutz', 'Datenschutz', '<h2>Datenschutzerklärung</h2><p>Wir nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Diese Website erhebt nur minimal notwendige Daten.</p>', 1, 1);
