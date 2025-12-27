<?php
/**
 * Startseite
 */
$title = 'Deutsche Rotes Kreuz Oberberg SW';
$db = $GLOBALS['db'];

require_once SRC_PATH . '/classes/News.php';
require_once SRC_PATH . '/classes/Event.php';

$newsObj = new News($db);
$eventObj = new Event($db);

$latestNews = $newsObj->getAllPublished(3);
$upcomingEvents = $eventObj->getAllPublished(3);

ob_start();
?>

<!-- Hero Section -->
<section class="relative rounded-lg shadow-lg mb-12 overflow-hidden">
    <div class="absolute inset-0">
        <img src="<?php echo BASE_URL; ?>/DRK.jpg" alt="DRK Hero" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black opacity-40"></div>
    </div>
    
    <div class="relative z-10 text-white p-8 md:p-16 min-h-96 flex flex-col justify-center">
        <div class="max-w-3xl">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-lg">Willkommen beim DRK Oberberg Südwesten</h1>
            <p class="text-xl md:text-2xl mb-8 drop-shadow-md max-w-2xl">
                Wir sind eine gemeinnützige Hilfsorganisation, die sich seit über 100 Jahren dem Wohl der Menschheit verschrieben hat.
            </p>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="<?php echo BASE_URL; ?>/index.php?page=uber-uns" class="bg-white text-red-700 px-8 py-3 rounded font-bold hover:bg-gray-100 transition text-center">
                    Mehr erfahren
                </a>
                <a href="<?php echo BASE_URL; ?>/index.php?page=contact" class="border-2 border-white px-8 py-3 rounded font-bold hover:bg-red-600 transition text-center">
                    Kontakt
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Neueste Nachrichten -->
<section class="mb-12">
    <h2 class="text-3xl font-bold mb-6">Aktuelle Nachrichten</h2>
    
    <?php if (!empty($latestNews)): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($latestNews as $article): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="bg-red-700 h-32 flex items-center justify-center text-white text-2xl">
                📰
            </div>
            <div class="p-4">
                <h3 class="text-lg font-bold mb-2"><?php echo h($article['title']); ?></h3>
                <p class="text-gray-600 text-sm mb-3">
                    <?php echo h(substr($article['excerpt'], 0, 100)); ?>...
                </p>
                <div class="text-xs text-gray-500 mb-3">
                    von <?php echo h($article['author_name']); ?> | 
                    <?php echo formatDate($article['published_at'], 'd.m.Y'); ?>
                </div>
                <a href="<?php echo BASE_URL; ?>/index.php?page=news&id=<?php echo $article['id']; ?>" class="text-red-700 font-bold hover:underline">
                    Lesen &rarr;
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-gray-600">Keine Nachrichten verfügbar.</p>
    <?php endif; ?>
</section>

<!-- Kommende Veranstaltungen -->
<section class="mb-12">
    <h2 class="text-3xl font-bold mb-6">Kommende Veranstaltungen</h2>
    
    <?php if (!empty($upcomingEvents)): ?>
    <div class="space-y-4">
        <?php foreach ($upcomingEvents as $event): ?>
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold mb-2"><?php echo h($event['title']); ?></h3>
                    <p class="text-gray-600 mb-3"><?php echo h($event['description']); ?></p>
                    <div class="flex space-x-4 text-sm text-gray-500">
                        <span>📅 <?php echo formatDate($event['event_date'], 'd.m.Y'); ?></span>
                        <span>🕐 <?php echo h($event['event_time'] ?? 'TBA'); ?></span>
                        <span>📍 <?php echo h($event['location']); ?></span>
                    </div>
                </div>
                <a href="<?php echo BASE_URL; ?>/index.php?page=event&id=<?php echo $event['id']; ?>" class="text-red-700 font-bold hover:underline">
                    Details &rarr;
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-gray-600">Keine Veranstaltungen geplant.</p>
    <?php endif; ?>
</section>

<!-- Aufgaben -->
<section class="bg-gray-100 rounded-lg p-8 mb-12">
    <h2 class="text-3xl font-bold mb-6">Unsere Aufgaben</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="flex space-x-4">
            <div class="text-4xl">🚑</div>
            <div>
                <h3 class="font-bold text-lg mb-2">Notfallhilfe</h3>
                <p class="text-gray-600">Professionelle Rettungs- und Notfalldienste</p>
            </div>
        </div>
        
        <div class="flex space-x-4">
            <div class="text-4xl">🩺</div>
            <div>
                <h3 class="font-bold text-lg mb-2">Pflege & Betreuung</h3>
                <p class="text-gray-600">Fachgerechte Pflege und soziale Dienste</p>
            </div>
        </div>
        
        <div class="flex space-x-4">
            <div class="text-4xl">📚</div>
            <div>
                <h3 class="font-bold text-lg mb-2">Ausbildung</h3>
                <p class="text-gray-600">Erste-Hilfe-Kurse und Schulungen</p>
            </div>
        </div>
        
        <div class="flex space-x-4">
            <div class="text-4xl">🩸</div>
            <div>
                <h3 class="font-bold text-lg mb-2">Blutspende</h3>
                <p class="text-gray-600">Blutspendendienste für Krankenhäuser</p>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once SRC_PATH . '/pages/layout.php';
?>
