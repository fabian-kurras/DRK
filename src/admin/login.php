<?php
/**
 * Admin Login
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';
require_once dirname(dirname(__DIR__)) . '/src/config/database.php';
require_once dirname(dirname(__DIR__)) . '/src/classes/User.php';

$db = $GLOBALS['db'];
$userObj = new User($db);
$loginError = '';

// Überprüfe ob Admin existiert
$adminExists = false;
try {
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
    $result = $stmt->fetch();
    $adminExists = $result['count'] > 0;
} catch (Exception $e) {
    $adminExists = true; // Im Fehlerfall annehmen, dass Admin existiert (Sicherheit)
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        $loginError = 'Benutzername und Passwort erforderlich.';
    } else {
        if ($userObj->login($username, $password)) {
            redirect(BASE_URL . '/src/admin/dashboard.php', 'Willkommen!', 'success');
        } else {
            $loginError = 'Ungültige Anmeldedaten.';
        }
    }
}

// Bereits angemeldet?
if (isLoggedIn()) {
    redirect(BASE_URL . '/src/admin/dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DRK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <h1 class="text-3xl font-bold text-center mb-6 text-red-700">DRK Admin</h1>
            
            <?php if (!empty($loginError)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                <?php echo h($loginError); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Benutzername</label>
                    <input type="text" name="username" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2">Passwort</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700">
                </div>
                
                <button type="submit" name="login" class="w-full bg-red-700 text-white py-2 rounded-lg font-bold hover:bg-red-800 transition">
                    Anmelden
                </button>
            </form>
            
            <div class="mt-6 space-y-3">
                <p class="text-center text-gray-600 text-sm">
                    Demo Zugangsdaten: admin / admin123
                </p>
                
                <?php if (!$adminExists): ?>
                <div class="border-t pt-4">
                    <p class="text-center text-gray-600 text-xs mb-2">
                        Noch kein Admin-Account vorhanden?
                    </p>
                    <a href="<?php echo BASE_URL; ?>/src/admin/setup.php" class="block text-center bg-blue-600 text-white py-2 rounded text-sm hover:bg-blue-700 transition">
                        Admin-Account erstellen
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
