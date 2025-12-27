<?php
/**
 * Kontaktanfragen-Verwaltung
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';
require_once dirname(dirname(__DIR__)) . '/src/config/database.php';
require_once dirname(dirname(__DIR__)) . '/src/classes/Contact.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . '/src/admin/login.php');
}

$db = $GLOBALS['db'];
$contactObj = new Contact($db);

// Kontaktanfrage löschen
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $contactObj->delete($id);
    redirect(BASE_URL . '/src/admin/contacts.php', 'Kontaktanfrage gelöscht!', 'success');
}

// Kontaktanfrage anzeigen
$contacts = $contactObj->getAll(100);
$viewId = $_GET['view'] ?? null;
$viewContact = null;

if ($viewId) {
    $viewContact = $contactObj->getById((int)$viewId);
    if ($viewContact && !$viewContact['is_read']) {
        $contactObj->markAsRead((int)$viewId);
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontaktanfragen - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-red-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Kontaktanfragen</h1>
            <div class="space-x-4">
                <a href="<?php echo BASE_URL; ?>/src/admin/dashboard.php" class="hover:bg-red-800 px-3 py-2 rounded">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">
                    🌐 Zur Website
                </a>
                <a href="<?php echo BASE_URL; ?>/src/admin/logout.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Liste -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-200 px-4 py-3 font-bold">
                        Anfragen (<?php echo count($contacts); ?>)
                    </div>
                    
                    <div class="divide-y max-h-96 overflow-y-auto">
                        <?php foreach ($contacts as $contact): ?>
                        <a href="?view=<?php echo $contact['id']; ?>" class="block px-4 py-3 hover:bg-gray-50 <?php echo ($viewContact && $viewContact['id'] === $contact['id']) ? 'bg-blue-50 border-l-4 border-blue-700' : ''; ?>">
                            <p class="font-bold text-sm <?php echo !$contact['is_read'] ? 'font-bold text-red-700' : ''; ?>">
                                <?php echo h($contact['name']); ?>
                            </p>
                            <p class="text-gray-600 text-xs"><?php echo h(substr($contact['subject'], 0, 40)); ?>...</p>
                            <p class="text-gray-400 text-xs mt-1"><?php echo formatDate($contact['created_at'], 'd.m.Y H:i'); ?></p>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Detail-Ansicht -->
            <div class="lg:col-span-2">
                <?php if ($viewContact): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-2xl font-bold"><?php echo h($viewContact['subject']); ?></h2>
                        <a href="?delete=<?php echo $viewContact['id']; ?>" onclick="return confirm('Löschen?')" class="text-red-700 hover:underline">Löschen</a>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-6 space-y-3">
                        <div>
                            <p class="text-gray-600 text-sm">Name</p>
                            <p class="font-bold"><?php echo h($viewContact['name']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Email</p>
                            <p class="font-bold"><a href="mailto:<?php echo h($viewContact['email']); ?>" class="text-blue-700 hover:underline"><?php echo h($viewContact['email']); ?></a></p>
                        </div>
                        <?php if ($viewContact['phone']): ?>
                        <div>
                            <p class="text-gray-600 text-sm">Telefon</p>
                            <p class="font-bold"><a href="tel:<?php echo h($viewContact['phone']); ?>" class="text-blue-700 hover:underline"><?php echo h($viewContact['phone']); ?></a></p>
                        </div>
                        <?php endif; ?>
                        <div>
                            <p class="text-gray-600 text-sm">Datum</p>
                            <p class="font-bold"><?php echo formatDate($viewContact['created_at'], 'd.m.Y H:i'); ?></p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <p class="text-gray-600 text-sm mb-2">Nachricht</p>
                        <div class="bg-gray-50 p-4 rounded-lg whitespace-pre-wrap">
                            <?php echo h($viewContact['message']); ?>
                        </div>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button onclick="document.getElementById('reply-form').style.display = document.getElementById('reply-form').style.display === 'none' ? 'block' : 'none'" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                            Antworten
                        </button>
                        <a href="?delete=<?php echo $viewContact['id']; ?>" onclick="return confirm('Löschen?')" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">
                            Löschen
                        </a>
                    </div>
                    
                    <div id="reply-form" style="display: none;" class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">Um zu antworten, verwenden Sie bitte Ihren Email-Client:</p>
                        <a href="mailto:<?php echo h($viewContact['email']); ?>" class="text-blue-700 font-bold hover:underline">
                            Email verfassen
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-600">
                    <p>Wählen Sie eine Kontaktanfrage aus, um sie anzuzeigen.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
