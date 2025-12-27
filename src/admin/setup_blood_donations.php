<?php
/**
 * Blutspendetermine - Datenbankinitialisierung
 * Rufe auf: http://localhost/DRK/src/admin/setup_blood_donations.php
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';
require_once dirname(dirname(__DIR__)) . '/src/config/database.php';

// Nur Admin darf das ausführen
if (!isLoggedIn()) {
    die('Zugriff verweigert. Bitte <a href="' . BASE_URL . '/src/admin/login.php">anmelden</a>.');
}

$db = $GLOBALS['db'];
$success = false;
$message = '';

// SQL für Tabellenerstellung
$sql = "CREATE TABLE IF NOT EXISTS blood_donations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    donation_date DATE NOT NULL,
    donation_time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    organizer VARCHAR(255) DEFAULT 'Admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (donation_date),
    INDEX idx_location (location)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

try {
    $db->exec($sql);
    $success = true;
    $message = '✅ Tabelle "blood_donations" wurde erfolgreich erstellt!';
    
    // Beispieldaten hinzufügen, wenn Tabelle noch leer ist
    $stmt = $db->query('SELECT COUNT(*) as count FROM blood_donations');
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        $inserts = [
            ['2025-01-15', '09:00', 'Wiehl Stadthalle', 'Admin'],
            ['2025-01-22', '14:00', 'Oberberg Zentrum', 'Admin'],
            ['2025-02-05', '10:00', 'DRK Zentrale Wiehl', 'Admin'],
        ];
        
        $insertStmt = $db->prepare('INSERT INTO blood_donations (donation_date, donation_time, location, organizer) VALUES (:date, :time, :location, :organizer)');
        
        foreach ($inserts as $insert) {
            $insertStmt->execute([
                ':date' => $insert[0],
                ':time' => $insert[1],
                ':location' => $insert[2],
                ':organizer' => $insert[3]
            ]);
        }
        
        $message .= '<br>✅ 3 Beispiel-Termine wurden hinzugefügt!';
    }
} catch (PDOException $e) {
    $success = false;
    $message = '❌ Fehler: ' . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blutspendetermine - Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-red-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">DRK Admin</h1>
            <div class="space-x-4">
                <a href="<?php echo BASE_URL; ?>/src/admin/dashboard.php" class="hover:bg-red-800 px-3 py-2 rounded">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">
                    🌐 Zur Website
                </a>
                <a href="<?php echo BASE_URL; ?>/src/admin/logout.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">🩸 Blutspendetermine - Datenbankinitialisierung</h2>
            
            <?php if ($success): ?>
                <div class="bg-green-100 border-l-4 border-green-700 text-green-700 p-4 mb-6 rounded">
                    <p class="font-bold text-lg mb-2">Erfolg! 🎉</p>
                    <p><?php echo $message; ?></p>
                </div>
                
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
                    <p class="font-bold mb-2">Nächste Schritte:</p>
                    <ol class="list-decimal list-inside space-y-2">
                        <li><a href="<?php echo BASE_URL; ?>/src/admin/blood_donations.php" class="text-blue-600 hover:underline font-bold">Gehe zur Blutspendetermine-Verwaltung →</a></li>
                        <li>Erstelle oder bearbeite Blutspendetermine</li>
                        <li>Die öffentliche Website zeigt die Termine automatisch an</li>
                    </ol>
                </div>
            <?php else: ?>
                <div class="bg-red-100 border-l-4 border-red-700 text-red-700 p-4 rounded mb-6">
                    <p class="font-bold text-lg mb-2">Fehler bei der Initialisierung 😞</p>
                    <p><?php echo $message; ?></p>
                </div>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <p class="font-bold mb-2">Was tun?</p>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li>Überprüfe deine Datenbankverbindung</li>
                        <li>Stelle sicher, dass die Datenbank "drk_oberberg" existiert</li>
                        <li>Probiere es erneut oder kontaktiere den Administrator</li>
                    </ol>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
