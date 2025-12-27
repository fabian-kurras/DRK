<?php
/**
 * Dynamische Seiten (Nachrichten, Events, Kontakt)
 */

require_once __DIR__ . '/src/config/config.php';
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/classes/News.php';
require_once __DIR__ . '/src/classes/Event.php';
require_once __DIR__ . '/src/classes/Page.php';
require_once __DIR__ . '/src/classes/Contact.php';

$db = $GLOBALS['db'];
$page = $_GET['page'] ?? 'activities';
$title = 'DRK Oberberg SW';

ob_start();

if ($page === 'news' && isset($_GET['id'])) {
    // Einzelne Nachricht anzeigen
    $newsObj = new News($db);
    $article = $newsObj->getById((int)$_GET['id']);
    
    if (!$article) {
        ?>
        <div class="bg-red-100 text-red-700 p-4 rounded">
            Nachricht nicht gefunden.
        </div>
        <?php
    } else {
        $title = $article['title'];
        ?>
        <div class="mb-6">
            <a href="<?php echo BASE_URL; ?>" class="text-red-700 hover:underline">← Zurück</a>
        </div>
        
        <article class="bg-white rounded-lg shadow-lg p-8 max-w-3xl">
            <h1 class="text-4xl font-bold mb-4"><?php echo h($article['title']); ?></h1>
            
            <div class="text-gray-600 mb-6 flex space-x-4">
                <span>von <?php echo h($article['author_name']); ?></span>
                <span>|</span>
                <span><?php echo formatDate($article['published_at'], 'd.m.Y H:i'); ?></span>
            </div>
            
            <?php if ($article['image_url']): ?>
            <img src="<?php echo h($article['image_url']); ?>" alt="<?php echo h($article['title']); ?>" class="w-full rounded-lg mb-6 max-h-96 object-cover">
            <?php endif; ?>
            
            <div class="prose prose-lg max-w-none mb-6">
                <?php echo $article['content']; ?>
            </div>
        </article>
        <?php
    }
    
} elseif ($page === 'event' && isset($_GET['id'])) {
    // Einzelne Veranstaltung anzeigen
    $eventObj = new Event($db);
    $event = $eventObj->getById((int)$_GET['id']);
    
    if (!$event) {
        ?>
        <div class="bg-red-100 text-red-700 p-4 rounded">
            Veranstaltung nicht gefunden.
        </div>
        <?php
    } else {
        $title = $event['title'];
        ?>
        <div class="mb-6">
            <a href="<?php echo BASE_URL; ?>" class="text-red-700 hover:underline">← Zurück</a>
        </div>
        
        <article class="bg-white rounded-lg shadow-lg p-8 max-w-3xl">
            <h1 class="text-4xl font-bold mb-4"><?php echo h($event['title']); ?></h1>
            
            <div class="bg-blue-50 p-6 rounded-lg mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Datum</p>
                        <p class="text-2xl font-bold"><?php echo formatDate($event['event_date'], 'd.m.Y'); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Uhrzeit</p>
                        <p class="text-2xl font-bold"><?php echo h($event['event_time'] ?? 'TBA'); ?></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-600 text-sm">Ort</p>
                        <p class="text-lg font-bold"><?php echo h($event['location']); ?></p>
                    </div>
                </div>
            </div>
            
            <?php if ($event['image_url']): ?>
            <img src="<?php echo h($event['image_url']); ?>" alt="<?php echo h($event['title']); ?>" class="w-full rounded-lg mb-6 max-h-96 object-cover">
            <?php endif; ?>
            
            <div class="prose prose-lg max-w-none">
                <?php echo $event['description']; ?>
            </div>
        </article>
        <?php
    }
    
} elseif ($page === 'activities') {
    // Alle Nachrichten und Veranstaltungen anzeigen
    $newsObj = new News($db);
    $eventObj = new Event($db);
    
    $news = $newsObj->getAllPublished(20);
    $events = $eventObj->getAllPublished(20);
    
    $title = 'Aktivitäten';
    ?>
    <h1 class="text-4xl font-bold mb-8">Nachrichten & Veranstaltungen</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Nachrichten -->
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold mb-6">Nachrichten</h2>
            
            <?php if (!empty($news)): ?>
            <div class="space-y-6">
                <?php foreach ($news as $article): ?>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <h3 class="text-xl font-bold mb-2"><?php echo h($article['title']); ?></h3>
                    <p class="text-gray-600 mb-3"><?php echo h($article['excerpt']); ?></p>
                    <div class="text-xs text-gray-500 mb-3">
                        <?php echo formatDate($article['published_at'], 'd.m.Y'); ?> von <?php echo h($article['author_name']); ?>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=news&id=<?php echo $article['id']; ?>" class="text-red-700 font-bold hover:underline">
                        Lesen &rarr;
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-600">Keine Nachrichten verfügbar.</p>
            <?php endif; ?>
        </div>
        
        <!-- Veranstaltungen Sidebar -->
        <div>
            <h2 class="text-2xl font-bold mb-6">Veranstaltungen</h2>
            
            <?php if (!empty($events)): ?>
            <div class="space-y-4">
                <?php foreach ($events as $event): ?>
                <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-700">
                    <h3 class="font-bold text-sm mb-1"><?php echo h($event['title']); ?></h3>
                    <p class="text-xs text-gray-600 mb-2">
                        📅 <?php echo formatDate($event['event_date'], 'd.m.Y'); ?>
                    </p>
                    <p class="text-xs text-gray-600 mb-3">
                        📍 <?php echo h($event['location']); ?>
                    </p>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=event&id=<?php echo $event['id']; ?>" class="text-red-700 text-xs font-bold hover:underline">
                        Details
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-600 text-sm">Keine Veranstaltungen.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <?php
} elseif ($page === 'blood_donations') {
    require_once __DIR__ . '/src/pages/blood_donations.php';

} elseif ($page === 'contact') {
    $title = 'Kontakt';
    $contactObj = new Contact($db);
    
    // Kontaktformular absendet
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
            if ($contactObj->create($name, $email, $phone, $subject, $message)) {
                $_SESSION['message'] = 'Danke für Ihre Nachricht! Wir werden uns bald bei Ihnen melden.';
                $_SESSION['message_type'] = 'success';
                // Formular zurücksetzen
                $name = $email = $phone = $subject = $message = '';
            } else {
                $_SESSION['message'] = 'Fehler beim Speichern der Nachricht.';
                $_SESSION['message_type'] = 'error';
            }
        } else {
            $_SESSION['message'] = 'Bitte füllen Sie alle erforderlichen Felder aus.';
            $_SESSION['message_type'] = 'error';
        }
        redirect(BASE_URL . '/index.php?page=contact');
    }
    ?>
    
    <div class="max-w-2xl">
        <h1 class="text-4xl font-bold mb-6">Kontaktieren Sie uns</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="font-bold text-lg mb-4">📍 Adresse</h2>
                <p class="text-gray-600">
                    DRK Oberberg SW<br>
                    Oberbergischer Str. 123<br>
                    51xxx Oberberg
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="font-bold text-lg mb-4">📞 Telefon & Email</h2>
                <p class="text-gray-600">
                    Tel: +49 (0) 2202 123456<br>
                    Email: info@drk-oberberg.de<br>
                    Mo-Fr: 08:00 - 17:00 Uhr
                </p>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Kontaktformular</h2>
            
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700" value="<?php echo h($name ?? ''); ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2">Email *</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700" value="<?php echo h($email ?? ''); ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2">Telefon</label>
                    <input type="tel" name="phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700" value="<?php echo h($phone ?? ''); ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2">Betreff *</label>
                    <input type="text" name="subject" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700" value="<?php echo h($subject ?? ''); ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-bold mb-2">Nachricht *</label>
                    <textarea name="message" required rows="6" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-700"><?php echo h($message ?? ''); ?></textarea>
                </div>
                
                <button type="submit" name="submit" class="w-full bg-red-700 text-white py-2 rounded-lg font-bold hover:bg-red-800 transition">
                    Nachricht senden
                </button>
            </form>
        </div>
    </div>
    
    <?php
} else {
    // Statische Seite anzeigen
    $pageObj = new Page($db);
    $customPage = $pageObj->getBySlug($page);
    
    if (!$customPage) {
        ?>
        <div class="bg-red-100 text-red-700 p-4 rounded">
            Seite nicht gefunden.
        </div>
        <?php
    } else {
        $title = $customPage['title'];
        ?>
        <article class="bg-white rounded-lg shadow-lg p-8 max-w-3xl">
            <h1 class="text-4xl font-bold mb-6"><?php echo h($customPage['title']); ?></h1>
            
            <div class="prose prose-lg max-w-none">
                <?php echo $customPage['content']; ?>
            </div>
        </article>
        <?php
    }
}

$content = ob_get_clean();
require_once SRC_PATH . '/pages/layout.php';
?>
