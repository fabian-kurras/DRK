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
$page = $_GET['page'] ?? '';
$title = 'DRK Oberberg SW';

ob_start();

// Wenn keine Seite angegeben, zeige home.php
if (empty($page)) {
    // Home/Startseite mit Events und Nachrichten
    require_once SRC_PATH . '/classes/News.php';
    require_once SRC_PATH . '/classes/Event.php';
    
    $newsObj = new News($db);
    $eventObj = new Event($db);
    
    $latestNews = $newsObj->getAllPublished(3);
    $upcomingEvents = $eventObj->getAllPublished(3);
    
    $title = 'Deutsche Rotes Kreuz Oberberg SW';
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
    
} elseif ($page === 'news' && isset($_GET['id'])) {
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
    
} elseif ($page === 'artikel') {
    // Alle verwalteten Seiten anzeigen mit Preview
    $pageObj = new Page($db);
    $allPages = $pageObj->getAll();
    
    $title = 'Artikel';
    ?>
    <h1 class="text-4xl font-bold mb-8">📚 Alle Artikel & Seiten</h1>
    
    <?php if (!empty($allPages)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($allPages as $article): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-red-600 to-red-700 h-32 flex items-center justify-center text-white text-4xl">
                📄
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold mb-3"><?php echo h($article['title']); ?></h3>
                
                <?php 
                // Preview aus dem Content (erste 150 Zeichen, HTML entfernen)
                $preview = strip_tags($article['content']);
                $preview = substr($preview, 0, 150);
                if (strlen($article['content']) > 150) {
                    $preview .= '...';
                }
                ?>
                <p class="text-gray-600 text-sm mb-4"><?php echo h($preview); ?></p>
                
                <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                    <span>
                        <?php if ($article['is_published']): ?>
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded">Veröffentlicht</span>
                        <?php else: ?>
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Verborgen</span>
                        <?php endif; ?>
                    </span>
                    <span><?php echo formatDate($article['created_at'], 'd.m.Y'); ?></span>
                </div>
                
                <a href="<?php echo BASE_URL; ?>/index.php?page=<?php echo h($article['slug']); ?>" class="inline-block bg-red-700 text-white px-4 py-2 rounded font-bold hover:bg-red-800 transition">
                    Lesen →
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-yellow-50 text-yellow-700 p-6 rounded-lg">
        <p>Keine Artikel verfügbar. Erstellen Sie erste Artikel im Admin-Panel!</p>
    </div>
    <?php endif; ?>
    
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
                    DRK-Ortsverein Oberberg Südwest e.V.<br>
                    Florastraße 3<br>
                    51674 Wiehl
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
} elseif ($page === 'kalender') {
    // Kalender-Seite mit Veranstaltungen
    $eventObj = new Event($db);
    $allEvents = $eventObj->getAllPublished(100);
    
    $title = 'Kalender';
    
    // Aktueller Monat/Jahr
    $month = (int)($_GET['month'] ?? date('m'));
    $year = (int)($_GET['year'] ?? date('Y'));
    
    // Sicherheit: Gültige Werte
    if ($month < 1) $month = 1;
    if ($month > 12) $month = 12;
    
    $selectedDate = $_GET['date'] ?? null;
    
    // Erstelle Kalender-Array mit Daten der Ereignisse
    $eventsByDate = [];
    foreach ($allEvents as $event) {
        $dateStr = date('Y-m-d', strtotime($event['event_date']));
        if (!isset($eventsByDate[$dateStr])) {
            $eventsByDate[$dateStr] = [];
        }
        $eventsByDate[$dateStr][] = $event;
    }
    
    // Berechne Kalenderdaten
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $lastDay = date('t', $firstDay);
    $startDayOfWeek = date('w', $firstDay); // 0=Sonntag, 6=Samstag
    if ($startDayOfWeek == 0) $startDayOfWeek = 7; // Montag = 1
    $startDayOfWeek--; // Für Montag = 0
    
    $monthName = strftime('%B', $firstDay);
    
    ?>
    <div class="max-w-6xl">
        <h1 class="text-4xl font-bold mb-8">📅 Terminkalender</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kalender -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <!-- Kalender Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold"><?php echo $monthName . ' ' . $year; ?></h2>
                        <div class="flex space-x-2">
                            <a href="?page=kalender&month=<?php echo $month == 1 ? 12 : $month - 1; ?>&year=<?php echo $month == 1 ? $year - 1 : $year; ?>" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">← Zurück</a>
                            <a href="?page=kalender&month=<?php echo date('m'); ?>&year=<?php echo date('Y'); ?>" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Heute</a>
                            <a href="?page=kalender&month=<?php echo $month == 12 ? 1 : $month + 1; ?>&year=<?php echo $month == 12 ? $year + 1 : $year; ?>" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">Weiter →</a>
                        </div>
                    </div>
                    
                    <!-- Wochentage -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <?php foreach (['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'] as $day): ?>
                        <div class="bg-gray-200 p-3 text-center font-bold text-sm"><?php echo $day; ?></div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Kalender Tage -->
                    <div class="grid grid-cols-7 gap-1">
                        <?php
                        // Leere Zellen für Tage vor dem 1.
                        for ($i = 0; $i < $startDayOfWeek; $i++) {
                            echo '<div class="bg-gray-50 p-3 min-h-20"></div>';
                        }
                        
                        // Tage des Monats
                        for ($day = 1; $day <= $lastDay; $day++) {
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            $hasEvents = isset($eventsByDate[$dateStr]);
                            $isSelected = $selectedDate === $dateStr;
                            
                            $cellClass = 'p-3 min-h-20 border rounded cursor-pointer transition ';
                            if ($hasEvents) {
                                $cellClass .= 'bg-yellow-100 border-2 border-red-700 hover:bg-yellow-200';
                            } else {
                                $cellClass .= 'bg-white border border-gray-200 hover:bg-gray-50';
                            }
                            if ($isSelected) {
                                $cellClass = str_replace('border-2 border-red-700', 'border-4 border-red-700 bg-red-50', $cellClass);
                            }
                        ?>
                        <a href="?page=kalender&date=<?php echo $dateStr; ?>&month=<?php echo $month; ?>&year=<?php echo $year; ?>" class="<?php echo $cellClass; ?>">
                            <div class="font-bold text-lg mb-1"><?php echo $day; ?></div>
                            <?php if ($hasEvents): ?>
                            <div class="text-xs text-red-700">
                                <span class="bg-red-700 text-white px-2 py-1 rounded inline-block"><?php echo count($eventsByDate[$dateStr]); ?> Termin<?php echo count($eventsByDate[$dateStr]) !== 1 ? 'e' : ''; ?></span>
                            </div>
                            <?php endif; ?>
                        </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <!-- Termine für ausgewähltes Datum -->
            <div>
                <h3 class="text-2xl font-bold mb-4">
                    <?php echo $selectedDate ? 'Termine am ' . formatDate($selectedDate, 'd.m.Y') : 'Wähle ein Datum'; ?>
                </h3>
                
                <?php if ($selectedDate && isset($eventsByDate[$selectedDate])): ?>
                    <div class="space-y-4">
                        <?php foreach ($eventsByDate[$selectedDate] as $event): ?>
                        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-700">
                            <h4 class="font-bold text-lg mb-2"><?php echo h($event['title']); ?></h4>
                            <p class="text-gray-600 text-sm mb-3"><?php echo h(substr($event['description'], 0, 150)); ?><?php echo strlen($event['description']) > 150 ? '...' : ''; ?></p>
                            <div class="text-sm text-gray-500 space-y-1 mb-3">
                                <p>📅 <?php echo formatDate($event['event_date'], 'd.m.Y'); ?></p>
                                <p>🕐 <?php echo h($event['event_time'] ?? 'Zeit nicht angegeben'); ?></p>
                                <p>📍 <?php echo h($event['location']); ?></p>
                            </div>
                            <a href="<?php echo BASE_URL; ?>/index.php?page=event&id=<?php echo $event['id']; ?>" class="inline-block bg-red-700 text-white px-3 py-1 rounded text-sm hover:bg-red-800">
                                Details →
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($selectedDate): ?>
                    <div class="bg-blue-50 text-blue-700 p-4 rounded-lg">
                        <p>Keine Termine an diesem Datum.</p>
                    </div>
                <?php else: ?>
                    <div class="bg-gray-50 text-gray-600 p-4 rounded-lg">
                        <p>Wähle ein Datum im Kalender, um Termine anzusehen.</p>
                    </div>
                <?php endif; ?>
            </div>
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
