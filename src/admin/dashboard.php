<?php
/**
 * Admin Dashboard
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';
require_once dirname(dirname(__DIR__)) . '/src/config/database.php';
require_once dirname(dirname(__DIR__)) . '/src/classes/News.php';
require_once dirname(dirname(__DIR__)) . '/src/classes/Event.php';
require_once dirname(dirname(__DIR__)) . '/src/classes/Contact.php';

// Authentifizierung
if (!isLoggedIn()) {
    redirect(BASE_URL . '/src/admin/login.php');
}

$db = $GLOBALS['db'];
$newsObj = new News($db);
$eventObj = new Event($db);
$contactObj = new Contact($db);

// Statistiken
$newsCount = $newsObj->count();
$unreadContacts = $contactObj->countUnread();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DRK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-red-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">DRK Admin</h1>
            <div class="space-x-4">
                <span class="text-sm">Willkommen, <?php echo h($_SESSION['full_name']); ?>!</span>
                <a href="<?php echo BASE_URL; ?>" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">
                    🌐 Zur Website
                </a>
                <a href="<?php echo BASE_URL; ?>/src/admin/logout.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">
                    Logout
                </a>
            </div>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Messages -->
        <?php 
        $msg = getAndClearMessage();
        if (!empty($msg['text'])): 
        ?>
        <div class="p-4 rounded-lg mb-6 <?php echo $msg['type'] === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
            <?php echo h($msg['text']); ?>
        </div>
        <?php endif; ?>
        
        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-3xl mb-2">📰</div>
                <p class="text-gray-600">Nachrichten</p>
                <p class="text-4xl font-bold"><?php echo $newsCount; ?></p>
                <a href="<?php echo BASE_URL; ?>/src/admin/news.php" class="text-red-700 text-sm hover:underline">Verwalten →</a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-3xl mb-2">📅</div>
                <p class="text-gray-600">Veranstaltungen</p>
                <p class="text-4xl font-bold">--</p>
                <a href="<?php echo BASE_URL; ?>/src/admin/events.php" class="text-red-700 text-sm hover:underline">Verwalten →</a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-3xl mb-2">💬</div>
                <p class="text-gray-600">Kontaktanfragen</p>
                <p class="text-4xl font-bold"><?php echo $unreadContacts; ?> neu</p>
                <a href="<?php echo BASE_URL; ?>/src/admin/contacts.php" class="text-red-700 text-sm hover:underline">Verwalten →</a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-3xl mb-2">⚙️</div>
                <p class="text-gray-600">Verwaltung</p>
                <p class="text-4xl font-bold">--</p>
                <a href="<?php echo BASE_URL; ?>/src/admin/setup_blood_donations.php" class="text-red-700 text-sm hover:underline">Blutspendetermine initialisieren →</a>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Schnellaktionen</h2>
            
            <div class="space-y-2">
                <a href="<?php echo BASE_URL; ?>/src/admin/news.php?action=new" class="block bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800 w-full text-center">
                    + Neue Nachricht erstellen
                </a>
                <a href="<?php echo BASE_URL; ?>/src/admin/events.php?action=new" class="block bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 w-full text-center">
                    + Neue Veranstaltung erstellen
                </a>
                <a href="<?php echo BASE_URL; ?>/src/admin/blood_donations.php" class="block bg-yellow-700 text-white px-4 py-2 rounded hover:bg-yellow-800 w-full text-center">
                    🩸 Blutspendetermine verwalten
                </a>
                <a href="<?php echo BASE_URL; ?>/src/admin/contacts.php" class="block bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800 w-full text-center">
                    → Kontaktanfragen ansehen
                </a>
                
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="<?php echo BASE_URL; ?>/src/admin/setup.php" class="block bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800 w-full text-center">
                    🔐 Admin-Account erstellen
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
