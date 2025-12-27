-- Blutspendetermine Tabelle erstellen und Beispieldaten einfügen
-- Führe diese Abfrage in phpMyAdmin aus, um die Blutspendetermine zu aktivieren

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

-- Beispieldaten hinzufügen (falls Tabelle leer ist)
INSERT INTO blood_donations (donation_date, donation_time, location, organizer) 
SELECT '2025-01-15', '09:00', 'Wiehl Stadthalle', 'Admin'
FROM blood_donations
WHERE NOT EXISTS (SELECT 1 FROM blood_donations WHERE donation_date = '2025-01-15');

INSERT INTO blood_donations (donation_date, donation_time, location, organizer) 
SELECT '2025-01-22', '14:00', 'Oberberg Zentrum', 'Admin'
FROM blood_donations
WHERE NOT EXISTS (SELECT 1 FROM blood_donations WHERE donation_date = '2025-01-22');

INSERT INTO blood_donations (donation_date, donation_time, location, organizer) 
SELECT '2025-02-05', '10:00', 'DRK Zentrale Wiehl', 'Admin'
FROM blood_donations
WHERE NOT EXISTS (SELECT 1 FROM blood_donations WHERE donation_date = '2025-02-05');
