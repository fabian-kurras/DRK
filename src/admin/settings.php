<?php
/**
 * Admin Settings/Info Page
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';
require_once dirname(dirname(__DIR__)) . '/src/config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL . '/src/admin/login.php');
}

// Datenbankinfo
$db = $GLOBALS['db'];
try {
    $stmt = $db->query("SELECT VERSION() as version");
    $mysqlVersion = $stmt->fetch()['version'] ?? 'Unbekannt';
    $dbStatus = '✅ Verbunden';
} catch (Exception $e) {
    $mysqlVersion = 'Fehler';
    $dbStatus = '❌ Fehler';
}

$phpVersion = phpversion();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einstellungen - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-red-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Einstellungen & Systeminfo</h1>
            <div class="space-x-4">
                <a href="<?php echo BASE_URL; ?>/src/admin/dashboard.php" class="hover:bg-red-800 px-3 py-2 rounded">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>/src/admin/logout.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- System-Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">🖥️ System-Information</h2>
                
                <div class="space-y-4">
                    <div class="border-b pb-3">
                        <p class="text-gray-600 text-sm">PHP Version</p>
                        <p class="font-mono font-bold"><?php echo h($phpVersion); ?></p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <p class="text-gray-600 text-sm">MySQL Status</p>
                        <p class="font-bold"><?php echo $dbStatus; ?></p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <p class="text-gray-600 text-sm">MySQL Version</p>
                        <p class="font-mono font-bold"><?php echo h($mysqlVersion); ?></p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <p class="text-gray-600 text-sm">Server OS</p>
                        <p class="font-mono font-bold"><?php echo h(php_uname()); ?></p>
                    </div>
                    
                    <div>
                        <p class="text-gray-600 text-sm">Benutzter Speicher</p>
                        <p class="font-mono font-bold"><?php echo round(memory_get_usage() / 1024 / 1024, 2) . ' MB'; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Website-Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">🌐 Website-Information</h2>
                
                <div class="space-y-4">
                    <div class="border-b pb-3">
                        <p class="text-gray-600 text-sm">Website-Name</p>
                        <p class="font-bold">Deutsche Rotes Kreuz Oberberg SW</p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <p class="text-gray-600 text-sm">Base URL</p>
                        <p class="font-mono font-bold text-sm"><?php echo h(BASE_URL); ?></p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <p class="text-gray-600 text-sm">Aktuelle Zeit</p>
                        <p class="font-mono font-bold"><?php echo date('d.m.Y H:i:s'); ?></p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <p class="text-gray-600 text-sm">Datenbank</p>
                        <p class="font-mono font-bold"><?php echo h(DB_NAME); ?></p>
                    </div>
                    
                    <div>
                        <p class="text-gray-600 text-sm">Version</p>
                        <p class="font-bold">1.0</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Benutzerprofil -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6">👤 Ihr Profil</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 text-sm">Benutzername</p>
                    <p class="font-bold"><?php echo h($_SESSION['username']); ?></p>
                </div>
                
                <div>
                    <p class="text-gray-600 text-sm">Name</p>
                    <p class="font-bold"><?php echo h($_SESSION['full_name']); ?></p>
                </div>
                
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="font-bold"><?php echo h($_SESSION['email']); ?></p>
                </div>
                
                <div>
                    <p class="text-gray-600 text-sm">Rolle</p>
                    <p class="font-bold uppercase">
                        <?php 
                        echo $_SESSION['role'] === 'admin' ? '🔑 Administrator' : '✏️ Redakteur';
                        ?>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Schnelllinks -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">📚 Dokumentation</h2>
            
            <div class="space-y-3">
                <p class="text-gray-600 mb-4">Weitere Informationen und Setup-Anleitung:</p>
                
                <a href="<?php echo BASE_URL; ?>" target="_blank" class="block bg-blue-50 border-l-4 border-blue-700 p-4 hover:bg-blue-100">
                    <p class="font-bold">🌐 Website anschauen</p>
                    <p class="text-sm text-gray-600"><?php echo h(BASE_URL); ?></p>
                </a>
                
                <a href="<?php echo BASE_URL; ?>/SETUP.md" target="_blank" class="block bg-green-50 border-l-4 border-green-700 p-4 hover:bg-green-100">
                    <p class="font-bold">📖 SETUP.md Dokumentation</p>
                    <p class="text-sm text-gray-600">Komplette Installationsanleitung</p>
                </a>
                
                <a href="<?php echo BASE_URL; ?>/database/drk_oberberg.sql" download class="block bg-purple-50 border-l-4 border-purple-700 p-4 hover:bg-purple-100">
                    <p class="font-bold">💾 Datenbank-Backup</p>
                    <p class="text-sm text-gray-600">SQL-Datei herunterladen</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
