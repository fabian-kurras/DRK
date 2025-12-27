<?php
/**
 * Admin Account Setup/Creator
 * Erlaubt das Erstellen von Admin-Accounts
 */

require_once __DIR__ . '/../../src/config/config.php';
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/classes/User.php';

$db = $GLOBALS['db'];
$userObj = new User($db);
$message = '';
$messageType = '';
$successCreated = false;

// Check ob bereits Admin existiert
$adminExists = false;
try {
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
    $result = $stmt->fetch();
    $adminExists = $result['count'] > 0;
} catch (Exception $e) {
    // Fehler ignorieren
}

// Sicherheit: Wenn Admin existiert, muss der Benutzer authentifiziert sein
if ($adminExists && !isLoggedIn()) {
    die('<div style="text-align:center; padding:40px; font-family:Arial;">
        <h1>🔒 Zugriff verweigert</h1>
        <p style="font-size:16px; color:#666;">Diese Seite kann nur von authentifizierten Administratoren aufgerufen werden.</p>
        <p style="margin-top:20px;"><a href="' . BASE_URL . '/src/admin/login.php" style="background:#c00; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; display:inline-block;">Zum Login</a></p>
    </div>');
}

// Zusätzliche Sicherheit: Nur Admins dürfen neue Admins erstellen
if ($adminExists && isLoggedIn() && $_SESSION['role'] !== 'admin') {
    die('<div style="text-align:center; padding:40px; font-family:Arial;">
        <h1>🔒 Zugriff verweigert</h1>
        <p style="font-size:16px; color:#666;">Nur Administratoren dürfen neue Admin-Accounts erstellen.</p>
        <p style="margin-top:20px;"><a href="' . BASE_URL . '/src/admin/dashboard.php" style="background:#c00; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; display:inline-block;">Zum Dashboard</a></p>
    </div>');
}

// Neuen Admin erstellen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_admin'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fullName = trim($_POST['full_name'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $passwordConfirm = trim($_POST['password_confirm'] ?? '');
    
    // Validierung
    if (empty($username) || empty($email) || empty($password)) {
        $message = 'Bitte füllen Sie alle erforderlichen Felder aus.';
        $messageType = 'error';
    } elseif (strlen($password) < 6) {
        $message = 'Passwort muss mindestens 6 Zeichen lang sein.';
        $messageType = 'error';
    } elseif ($password !== $passwordConfirm) {
        $message = 'Passwörter stimmen nicht überein.';
        $messageType = 'error';
    } else {
        // Admin erstellen
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
            
            $query = "INSERT INTO users (username, email, password, full_name, role, is_active) 
                     VALUES (:username, :email, :password, :full_name, 'admin', 1)";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':full_name', $fullName);
            
            if ($stmt->execute()) {
                $message = '✅ Admin-Account erfolgreich erstellt! Username: ' . h($username);
                $messageType = 'success';
                $successCreated = true;
                // Formular leeren
                $username = $email = $fullName = $password = $passwordConfirm = '';
            } else {
                $message = 'Fehler beim Erstellen des Admin-Accounts. Möglicherweise existiert der Benutzername bereits.';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Fehler: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Account Setup - DRK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <h1 class="text-3xl font-bold text-center mb-6 text-red-700">🔐 DRK Admin Setup</h1>
            
            <!-- Messages -->
            <?php if ($messageType === 'success'): ?>
                <div class="bg-green-100 border-l-4 border-green-600 text-green-700 p-4 rounded-lg mb-6">
                    <p class="font-bold"><?php echo $message; ?></p>
                </div>
                
                <?php if ($successCreated): ?>
                    <div class="bg-blue-50 p-4 rounded-lg mb-6 text-sm">
                        <p class="mb-3"><strong>✅ Nächste Schritte:</strong></p>
                        <ol class="space-y-2 text-gray-700 list-decimal list-inside">
                            <li>Gehen Sie zum <a href="<?php echo BASE_URL; ?>/src/admin/login.php" class="text-blue-600 hover:underline font-bold">Login</a></li>
                            <li>Melden Sie sich mit Ihrem Benutzernamen an</li>
                            <li>Speichern Sie Ihr Passwort sicher!</li>
                        </ol>
                    </div>
                    
                    <a href="<?php echo BASE_URL; ?>/src/admin/login.php" class="block w-full bg-red-700 text-white py-2 rounded-lg font-bold hover:bg-red-800 transition text-center">
                        Zum Login →
                    </a>
                <?php endif; ?>
            
            <?php elseif ($messageType === 'error'): ?>
                <div class="bg-red-100 border-l-4 border-red-600 text-red-700 p-4 rounded-lg mb-6">
                    <p class="font-bold">❌ Fehler</p>
                    <p class="text-sm"><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Info -->
            <?php if (!$successCreated): ?>
                <?php if ($adminExists): ?>
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6">
                        <p class="text-yellow-800 font-bold mb-1">⚠️ Admin existiert bereits</p>
                        <p class="text-yellow-700 text-sm">Sie können hier einen zusätzlichen Admin-Account erstellen.</p>
                    </div>
                <?php else: ?>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                        <p class="text-blue-800 font-bold mb-1">✨ Erster Admin-Account</p>
                        <p class="text-blue-700 text-sm">Erstellen Sie hier Ihren ersten Admin-Account.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- Form -->
            <?php if (!$successCreated): ?>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Benutzername *</label>
                    <input type="text" name="username" required 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700"
                           value="<?php echo h($username ?? ''); ?>"
                           placeholder="z.B. admin">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2">Email *</label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700"
                           value="<?php echo h($email ?? ''); ?>"
                           placeholder="admin@drk.de">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2">Vollständiger Name</label>
                    <input type="text" name="full_name" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700"
                           value="<?php echo h($fullName ?? ''); ?>"
                           placeholder="z.B. Max Mustermann">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2">Passwort *</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700"
                           placeholder="Mindestens 6 Zeichen">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2">Passwort wiederholen *</label>
                    <input type="password" name="password_confirm" required 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700"
                           placeholder="Passwort bestätigen">
                </div>
                
                <button type="submit" name="create_admin" class="w-full bg-red-700 text-white py-3 rounded-lg font-bold hover:bg-red-800 transition">
                    Admin-Account erstellen
                </button>
            </form>
            
            <div class="mt-6 p-4 bg-gray-50 rounded-lg text-sm">
                <p class="font-bold mb-2">📋 Info:</p>
                <ul class="space-y-1 text-gray-600 text-xs">
                    <li>✓ Admin-Account bekommt alle Rechte</li>
                    <li>✓ Passwort wird sicher gespeichert (BCrypt)</li>
                    <li>✓ Sie können später weitere Accounts erstellen</li>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Back Link -->
            <div class="mt-6 text-center">
                <a href="<?php echo BASE_URL; ?>/src/admin/login.php" class="text-gray-600 hover:text-gray-800 text-sm">
                    ← Zurück zum Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
