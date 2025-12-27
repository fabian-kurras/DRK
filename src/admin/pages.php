<?php
/**
 * Seiten-Verwaltung (Über uns, Datenschutz, etc.)
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';
require_once dirname(dirname(__DIR__)) . '/src/config/database.php';
require_once dirname(dirname(__DIR__)) . '/src/classes/Page.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . '/src/admin/login.php');
}

$db = $GLOBALS['db'];
$pageObj = new Page($db);
$action = $_GET['action'] ?? 'list';

// Neue Seite erstellen
if ($action === 'new' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = trim($_POST['slug'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $publish = isset($_POST['publish']) ? 1 : 0;
    
    // Slug validieren (nur Kleinbuchstaben, Zahlen und Bindestriche)
    $slug = strtolower(preg_replace('/[^a-z0-9-]/', '', str_replace(' ', '-', $slug)));
    
    if (!empty($slug) && !empty($title) && !empty($content)) {
        if ($pageObj->create($slug, $title, $content, getCurrentUser())) {
            // is_published updaten
            if ($publish) {
                $lastId = $db->lastInsertId();
                $query = "UPDATE pages SET is_published = 1 WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id', $lastId, PDO::PARAM_INT);
                $stmt->execute();
            }
            redirect(BASE_URL . '/src/admin/pages.php', 'Seite erstellt!', 'success');
        }
    }
}

// Seite aktualisieren
if ($action === 'edit' && isset($_GET['id'])) {
    $page = $pageObj->getById((int)$_GET['id']);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        
        if (!empty($title) && !empty($content)) {
            if ($pageObj->update((int)$_GET['id'], $title, $content)) {
                redirect(BASE_URL . '/src/admin/pages.php', 'Seite aktualisiert!', 'success');
            }
        }
    }
}

// Seite löschen
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pageObj->delete($id);
    redirect(BASE_URL . '/src/admin/pages.php', 'Seite gelöscht!', 'success');
}

// Seite veröffentlichen/entfernen
if (isset($_GET['publish'])) {
    $id = (int)$_GET['publish'];
    $query = "UPDATE pages SET is_published = 1 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    redirect(BASE_URL . '/src/admin/pages.php', 'Seite veröffentlicht!', 'success');
}

if (isset($_GET['unpublish'])) {
    $id = (int)$_GET['unpublish'];
    $query = "UPDATE pages SET is_published = 0 WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    redirect(BASE_URL . '/src/admin/pages.php', 'Seite verborgen!', 'success');
}

$allPages = $pageObj->getAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seiten - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-red-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Seiten-Verwaltung</h1>
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
            <!-- Neue Seite Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Neue Seite</h2>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-2">Slug (URL-freundlich) *</label>
                        <input type="text" name="slug" required placeholder="z.B. uber-uns, datenschutz" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700">
                        <p class="text-gray-600 text-sm mt-1">Wird automatisch in Kleinbuchstaben konvertiert</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2">Titel *</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2">Inhalt *</label>
                        <textarea name="content" rows="10" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700 font-mono text-sm"></textarea>
                        <p class="text-gray-600 text-sm mt-1">Sie können HTML-Tags verwenden (z.B. &lt;p&gt;, &lt;h2&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;li&gt;)</p>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="publish" name="publish" value="1" checked>
                        <label for="publish" class="text-sm">Sofort veröffentlichen</label>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded font-bold hover:bg-red-800">
                            Erstellen
                        </button>
                        <a href="<?php echo BASE_URL; ?>/src/admin/pages.php" class="bg-gray-400 text-white px-6 py-2 rounded font-bold hover:bg-gray-500">
                            Abbrechen
                        </a>
                    </div>
                </form>
            </div>
        
        <?php elseif ($action === 'edit' && $page): ?>
            <!-- Seite bearbeiten -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Seite bearbeiten: <?php echo h($page['title']); ?></h2>
                
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-2">Slug</label>
                        <input type="text" value="<?php echo h($page['slug']); ?>" disabled class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-600">
                        <p class="text-gray-600 text-sm mt-1">Der Slug kann nicht geändert werden</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2">Titel *</label>
                        <input type="text" name="title" required value="<?php echo h($page['title']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold mb-2">Inhalt *</label>
                        <textarea name="content" rows="10" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700 font-mono text-sm"><?php echo h($page['content']); ?></textarea>
                        <p class="text-gray-600 text-sm mt-1">Sie können HTML-Tags verwenden</p>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded font-bold hover:bg-red-800">
                            Aktualisieren
                        </button>
                        <a href="<?php echo BASE_URL; ?>/src/admin/pages.php" class="bg-gray-400 text-white px-6 py-2 rounded font-bold hover:bg-gray-500">
                            Abbrechen
                        </a>
                    </div>
                </form>
            </div>
        
        <?php else: ?>
            <!-- Seiten-Liste -->
            <div class="mb-6">
                <a href="<?php echo BASE_URL; ?>/src/admin/pages.php?action=new" class="bg-red-700 text-white px-4 py-2 rounded font-bold hover:bg-red-800">
                    + Neue Seite
                </a>
            </div>
            
            <?php if (empty($allPages)): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="text-gray-600">Keine Seiten vorhanden.</p>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left">Titel</th>
                                <th class="px-4 py-3 text-left">Slug</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allPages as $p): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3"><?php echo h($p['title']); ?></td>
                                <td class="px-4 py-3">
                                    <code class="text-gray-600"><?php echo h($p['slug']); ?></code>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($p['is_published']): ?>
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Veröffentlicht</span>
                                    <?php else: ?>
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Verborgen</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 space-x-2">
                                    <a href="?action=edit&id=<?php echo $p['id']; ?>" class="text-blue-700 text-sm hover:underline">Bearbeiten</a>
                                    <?php if ($p['is_published']): ?>
                                        <a href="?unpublish=<?php echo $p['id']; ?>" class="text-orange-700 text-sm hover:underline">Verbergen</a>
                                    <?php else: ?>
                                        <a href="?publish=<?php echo $p['id']; ?>" class="text-green-700 text-sm hover:underline">Veröffentlichen</a>
                                    <?php endif; ?>
                                    <a href="?delete=<?php echo $p['id']; ?>" onclick="return confirm('Löschen?')" class="text-red-700 text-sm hover:underline">Löschen</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
