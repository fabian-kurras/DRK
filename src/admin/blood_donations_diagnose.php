<?php
/**
 * Blutspendetermine - Diagnose & Status Check
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';
require_once dirname(dirname(__DIR__)) . '/src/config/database.php';

if (!isLoggedIn()) {
    die('Zugriff verweigert. Bitte <a href="' . BASE_URL . '/src/admin/login.php">anmelden</a>.');
}

$db = $GLOBALS['db'];
$diagnostics = [];

// 1. Datenbankverbindung testen
$diagnostics['database_connection'] = [
    'name' => 'Datenbankverbindung',
    'status' => 'OK',
    'details' => 'Verbindung erfolgreich'
];

// 2. Tabelle existiert?
try {
    $check = $db->query("SHOW TABLES LIKE 'blood_donations'");
    $tableExists = $check->rowCount() > 0;
    
    $diagnostics['table_exists'] = [
        'name' => 'Tabelle blood_donations',
        'status' => $tableExists ? 'OK' : 'FEHLER',
        'details' => $tableExists ? 'Tabelle existiert' : 'Tabelle existiert NICHT - bitte Setup durchführen!'
    ];
} catch (Exception $e) {
    $diagnostics['table_exists'] = [
        'name' => 'Tabelle blood_donations',
        'status' => 'FEHLER',
        'details' => $e->getMessage()
    ];
}

// 3. Tabellenstruktur prüfen
if ($tableExists) {
    try {
        $cols = $db->query("SHOW COLUMNS FROM blood_donations");
        $columns = $cols->fetchAll();
        
        $expected_columns = ['id', 'donation_date', 'donation_time', 'location', 'organizer', 'created_at'];
        $found_columns = array_column($columns, 'Field');
        
        $missing = array_diff($expected_columns, $found_columns);
        
        $diagnostics['table_structure'] = [
            'name' => 'Tabellenstruktur',
            'status' => empty($missing) ? 'OK' : 'WARNUNG',
            'details' => empty($missing) ? 'Alle Spalten vorhanden' : 'Fehlende Spalten: ' . implode(', ', $missing)
        ];
    } catch (Exception $e) {
        $diagnostics['table_structure'] = [
            'name' => 'Tabellenstruktur',
            'status' => 'FEHLER',
            'details' => $e->getMessage()
        ];
    }
}

// 4. Datensätze zählen
if ($tableExists) {
    try {
        $count = $db->query("SELECT COUNT(*) as cnt FROM blood_donations")->fetch();
        
        $diagnostics['data_count'] = [
            'name' => 'Datensätze',
            'status' => $count['cnt'] > 0 ? 'OK' : 'WARNUNG',
            'details' => $count['cnt'] . ' Termin(e) vorhanden'
        ];
    } catch (Exception $e) {
        $diagnostics['data_count'] = [
            'name' => 'Datensätze',
            'status' => 'FEHLER',
            'details' => $e->getMessage()
        ];
    }
}

// 5. Test-Insert
if ($tableExists) {
    try {
        $testStmt = $db->prepare('INSERT INTO blood_donations (donation_date, donation_time, location, organizer) VALUES (:date, :time, :location, :organizer)');
        
        $canInsert = $testStmt->execute([
            ':date' => '2025-12-31',
            ':time' => '23:59',
            ':location' => 'TEST',
            ':organizer' => 'DIAG'
        ]);
        
        if ($canInsert) {
            // Löschen
            $delStmt = $db->prepare('DELETE FROM blood_donations WHERE location = :loc AND organizer = :org');
            $delStmt->execute([':loc' => 'TEST', ':org' => 'DIAG']);
        }
        
        $diagnostics['insert_test'] = [
            'name' => 'Insert-Test',
            'status' => $canInsert ? 'OK' : 'FEHLER',
            'details' => $canInsert ? 'Insert funktioniert' : 'Insert fehlgeschlagen'
        ];
    } catch (Exception $e) {
        $diagnostics['insert_test'] = [
            'name' => 'Insert-Test',
            'status' => 'FEHLER',
            'details' => $e->getMessage()
        ];
    }
}

$allOk = !array_filter(array_map(function($d) { return $d['status'] !== 'OK'; }, array_filter($diagnostics, function($d) { return $d['status'] === 'FEHLER'; })));
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blutspendetermine - Diagnose</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-red-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">DRK Admin - Diagnose</h1>
            <div class="space-x-4">
                <a href="<?php echo BASE_URL; ?>/src/admin/dashboard.php" class="hover:bg-red-800 px-3 py-2 rounded">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">
                    🌐 Zur Website
                </a>
                <a href="<?php echo BASE_URL; ?>/src/admin/logout.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold mb-6">🔍 System-Diagnose</h2>
            
            <!-- Status Overview -->
            <div class="mb-6 p-4 rounded-lg <?php echo $allOk ? 'bg-green-50 border-2 border-green-200' : 'bg-yellow-50 border-2 border-yellow-200'; ?>">
                <p class="text-lg font-bold <?php echo $allOk ? 'text-green-700' : 'text-yellow-700'; ?>">
                    <?php echo $allOk ? '✅ Alles funktioniert!' : '⚠️ Es gibt Probleme - siehe unten'; ?>
                </p>
            </div>
            
            <!-- Diagnostics Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-bold">Komponente</th>
                            <th class="px-6 py-3 text-left text-sm font-bold">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-bold">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php foreach ($diagnostics as $key => $diag): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium"><?php echo $diag['name']; ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold 
                                    <?php 
                                    if ($diag['status'] === 'OK') echo 'bg-green-100 text-green-800';
                                    elseif ($diag['status'] === 'WARNUNG') echo 'bg-yellow-100 text-yellow-800';
                                    else echo 'bg-red-100 text-red-800';
                                    ?>">
                                    <?php echo $diag['status']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600"><?php echo $diag['details']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold mb-4">📋 Empfohlene Aktionen</h3>
            
            <?php if (!$tableExists): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded">
                <p class="font-bold text-red-700 mb-2">❌ Tabelle existiert nicht!</p>
                <p class="text-red-600 mb-4">Die Blutspendetermine-Tabelle muss zuerst erstellt werden.</p>
                <a href="<?php echo BASE_URL; ?>/src/admin/setup_blood_donations.php" class="inline-block bg-red-700 text-white px-6 py-2 rounded hover:bg-red-800 font-bold">
                    → Zur Setup-Seite
                </a>
            </div>
            <?php else: ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="font-bold text-green-700 mb-2">✅ Tabelle ist eingerichtet!</p>
                <p class="text-green-600 mb-4">Du kannst jetzt Blutspendetermine erstellen.</p>
                <a href="<?php echo BASE_URL; ?>/src/admin/blood_donations.php" class="inline-block bg-green-700 text-white px-6 py-2 rounded hover:bg-green-800 font-bold">
                    → Zur Verwaltung
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
