<?php
/**
 * Nachrichten-Verwaltung
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';
require_once dirname(dirname(__DIR__)) . '/src/config/database.php';
require_once dirname(dirname(__DIR__)) . '/src/classes/News.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . '/src/admin/login.php');
}

$db = $GLOBALS['db'];
$newsObj = new News($db);
$action = $_GET['action'] ?? 'list';

// Neue Nachricht erstellen
if ($action === 'new' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $publish = isset($_POST['publish']) ? 1 : 0;
    
    if (!empty($title) && !empty($content)) {
        if ($newsObj->create($title, $content, $excerpt, getCurrentUser())) {
            if ($publish) {
                $lastId = $db->lastInsertId();
                $newsObj->publish($lastId);
            }
            redirect(BASE_URL . '/src/admin/news.php', 'Nachricht erstellt!', 'success');
        }
    }
}

// Nachricht veröffentlichen/entfernen
if (isset($_GET['publish'])) {
    $id = (int)$_GET['publish'];
    $newsObj->publish($id);
    redirect(BASE_URL . '/src/admin/news.php', 'Nachricht veröffentlicht!', 'success');
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $newsObj->delete($id);
    redirect(BASE_URL . '/src/admin/news.php', 'Nachricht gelöscht!', 'success');
}

$allNews = $newsObj->getAll(50);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nachrichten - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-red-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Nachrichten-Verwaltung</h1>
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
        <?php if ($action === 'new'): ?>
            <!-- Neue Nachricht Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Neue Nachricht</h2>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-2">Titel *</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2">Zusammenfassung</label>
                        <textarea name="excerpt" rows="2" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2">Inhalt *</label>
                        <textarea name="content" rows="8" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700 font-mono text-sm"></textarea>
                        <p class="text-gray-600 text-sm mt-1">Sie können HTML-Tags verwenden (z.B. &lt;p&gt;, &lt;h2&gt;, &lt;strong&gt;)</p>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="publish" name="publish" value="1">
                        <label for="publish" class="text-sm">Sofort veröffentlichen</label>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded font-bold hover:bg-red-800">
                            Erstellen
                        </button>
                        <a href="<?php echo BASE_URL; ?>/src/admin/news.php" class="bg-gray-400 text-white px-6 py-2 rounded font-bold hover:bg-gray-500">
                            Abbrechen
                        </a>
                    </div>
                </form>
            </div>
        
        <?php else: ?>
            <!-- Nachrichten-Liste -->
            <div class="mb-6">
                <a href="<?php echo BASE_URL; ?>/src/admin/news.php?action=new" class="bg-red-700 text-white px-4 py-2 rounded font-bold hover:bg-red-800">
                    + Neue Nachricht
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left">Titel</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Autor</th>
                            <th class="px-4 py-3 text-left">Datum</th>
                            <th class="px-4 py-3">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allNews as $news): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3"><?php echo h($news['title']); ?></td>
                            <td class="px-4 py-3">
                                <?php if ($news['is_published']): ?>
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Veröffentlicht</span>
                                <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Entwurf</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3"><?php echo h($news['author_name']); ?></td>
                            <td class="px-4 py-3"><?php echo formatDate($news['created_at'], 'd.m.Y'); ?></td>
                            <td class="px-4 py-3 space-x-2">
                                <?php if (!$news['is_published']): ?>
                                    <a href="?publish=<?php echo $news['id']; ?>" class="text-blue-700 text-sm hover:underline">Veröffentlichen</a>
                                <?php endif; ?>
                                <a href="?delete=<?php echo $news['id']; ?>" onclick="return confirm('Löschen?')" class="text-red-700 text-sm hover:underline">Löschen</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
