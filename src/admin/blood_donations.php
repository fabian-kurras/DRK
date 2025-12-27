<?php
/**
 * Blutspendetermine-Verwaltung
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';
require_once dirname(dirname(__DIR__)) . '/src/config/database.php';
require_once dirname(dirname(__DIR__)) . '/src/classes/BloodDonation.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . '/src/admin/login.php');
}

$db = $GLOBALS['db'];
$donationObj = new BloodDonation($db);
$action = $_GET['action'] ?? 'list';

// Neuen Termin erstellen
if ($action === 'new' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $location = trim($_POST['location'] ?? '');
    
    if (!empty($date) && !empty($time) && !empty($location)) {
        if ($donationObj->create($date, $time, $location, getCurrentUser())) {
            redirect(BASE_URL . '/src/admin/blood_donations.php', 'Blutspendetermin erstellt!', 'success');
        } else {
            $error = 'Fehler beim Erstellen des Termins! Hinweis: Führe das Setup aus über das Dashboard → ⚙️ Verwaltung → "Blutspendetermine initialisieren"';
        }
    } else {
        $error = 'Bitte alle Felder ausfüllen!';
    }
}

// Termin aktualisieren
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $location = trim($_POST['location'] ?? '');
    
    if (!empty($date) && !empty($time) && !empty($location)) {
        if ($donationObj->update($id, $date, $time, $location)) {
            redirect(BASE_URL . '/src/admin/blood_donations.php', 'Blutspendetermin aktualisiert!', 'success');
        } else {
            $error = 'Fehler beim Aktualisieren!';
        }
    } else {
        $error = 'Bitte alle Felder ausfüllen!';
    }
}

// Termin löschen
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $donationObj->delete($id);
    redirect(BASE_URL . '/src/admin/blood_donations.php', 'Blutspendetermin gelöscht!', 'success');
}

$donations = $donationObj->getAll(100);
$editId = $_GET['edit'] ?? null;
$editDonation = null;

if ($editId) {
    $editDonation = $donationObj->getById((int)$editId);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blutspendetermine - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-red-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Blutspendetermine-Verwaltung</h1>
            <div class="space-x-4">
                <a href="<?php echo BASE_URL; ?>/src/admin/dashboard.php" class="hover:bg-red-800 px-3 py-2 rounded">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/src/admin/blood_donations_diagnose.php" class="hover:bg-red-800 px-3 py-2 rounded text-sm">🔍 Diagnose</a>
                <a href="<?php echo BASE_URL; ?>" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">
                    🌐 Zur Website
                </a>
                <a href="<?php echo BASE_URL; ?>/src/admin/logout.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4 py-8">
        <?php if (isset($_GET['message'])): ?>
        <div class="bg-<?php echo ($_GET['type'] ?? 'blue'); ?>-100 border-l-4 border-<?php echo ($_GET['type'] ?? 'blue'); ?>-700 text-<?php echo ($_GET['type'] ?? 'blue'); ?>-700 p-4 mb-6">
            <?php echo h($_GET['message']); ?>
        </div>
        <?php endif; ?>

        <?php if ($action === 'new' || $action === 'edit'): ?>
            <!-- Form für neuen/bearbeiteten Termin -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold mb-6">
                    <?php echo ($action === 'new') ? 'Neuer Blutspendetermin' : 'Termin bearbeiten'; ?>
                </h2>
                
                <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-700 text-red-700 p-4 mb-4 rounded">
                    <p class="font-bold mb-2">⚠️ Fehler:</p>
                    <p class="mb-4"><?php echo $error; ?></p>
                    <p class="text-sm bg-red-50 p-2 rounded border border-red-200">
                        <strong>Schnelle Lösung:</strong> Klicke 
                        <a href="<?php echo BASE_URL; ?>/src/admin/setup_blood_donations.php" class="underline font-bold text-red-800">hier um das Setup zu starten</a>
                    </p>
                </div>
                <?php endif; ?>
                
                <form method="POST" class="space-y-4">
                    <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo (int)$editDonation['id']; ?>">
                    <?php endif; ?>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2">Datum *</label>
                        <input type="date" name="date" required 
                            value="<?php echo h($editDonation['donation_date'] ?? ''); ?>"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2">Uhrzeit *</label>
                        <input type="time" name="time" required 
                            value="<?php echo h($editDonation['donation_time'] ?? ''); ?>"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2">Ort *</label>
                        <input type="text" name="location" required 
                            value="<?php echo h($editDonation['location'] ?? ''); ?>"
                            placeholder="z.B. Wiehl Stadthalle"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700">
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded hover:bg-red-800">
                            <?php echo ($action === 'new') ? 'Erstellen' : 'Aktualisieren'; ?>
                        </button>
                        <a href="<?php echo BASE_URL; ?>/src/admin/blood_donations.php" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                            Abbrechen
                        </a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Termine Liste -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-red-700 text-white px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold">Blutspendetermine</h2>
                    <a href="<?php echo BASE_URL; ?>/src/admin/blood_donations.php?action=new" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">
                        + Neuer Termin
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-bold">Datum</th>
                                <th class="px-6 py-3 text-left text-sm font-bold">Uhrzeit</th>
                                <th class="px-6 py-3 text-left text-sm font-bold">Ort</th>
                                <th class="px-6 py-3 text-left text-sm font-bold">Organisator</th>
                                <th class="px-6 py-3 text-left text-sm font-bold">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php if (empty($donations)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Keine Blutspendetermine vorhanden.
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($donations as $donation): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <strong><?php echo formatDate($donation['donation_date'], 'd.m.Y'); ?></strong>
                                    </td>
                                    <td class="px-6 py-4"><?php echo h($donation['donation_time']); ?></td>
                                    <td class="px-6 py-4"><?php echo h($donation['location']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo h($donation['organizer']); ?></td>
                                    <td class="px-6 py-4">
                                        <a href="?edit=<?php echo $donation['id']; ?>" class="text-blue-600 hover:underline text-sm">
                                            Bearbeiten
                                        </a>
                                        <span class="text-gray-400">|</span>
                                        <a href="?delete=<?php echo $donation['id']; ?>" onclick="return confirm('Wirklich löschen?')" class="text-red-600 hover:underline text-sm">
                                            Löschen
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
