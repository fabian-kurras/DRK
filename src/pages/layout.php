<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($title ?? 'DRK Oberberg SW'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-red-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <a href="<?php echo BASE_URL; ?>" class="text-2xl font-bold flex items-center">
                        <img src="<?php echo BASE_URL; ?>/DRK.jpg" alt="DRK Logo" class="h-12 w-12 rounded-full object-cover mr-2 border-2 border-white">
                        <span>DRK Oberberg SW</span>
                    </a>
                </div>
                
                <div class="hidden md:flex space-x-6">
                    <a href="<?php echo BASE_URL; ?>" class="hover:bg-red-800 px-3 py-2 rounded">Start</a>
                    <a href="<?php echo BASE_URL; ?>/pages/uber-uns" class="hover:bg-red-800 px-3 py-2 rounded">Über uns</a>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=activities" class="hover:bg-red-800 px-3 py-2 rounded">Aktivitäten</a>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=blood_donations" class="hover:bg-red-800 px-3 py-2 rounded">🩸 Blutspende</a>
                    <a href="<?php echo BASE_URL; ?>/index.php?page=contact" class="hover:bg-red-800 px-3 py-2 rounded">Kontakt</a>
                    
                    <?php if (isLoggedIn()): ?>
                        <a href="<?php echo BASE_URL; ?>/src/admin/dashboard.php" class="hover:bg-red-800 px-3 py-2 rounded">Admin</a>
                        <a href="<?php echo BASE_URL; ?>/src/admin/logout.php" class="hover:bg-red-800 px-3 py-2 rounded">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/src/admin/login.php" class="hover:bg-red-800 px-3 py-2 rounded">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Messages -->
    <?php 
    $msg = getAndClearMessage();
    if (!empty($msg['text'])): 
    ?>
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="p-4 rounded-lg <?php echo $msg['type'] === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
            <?php echo h($msg['text']); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 py-8 min-h-screen">
        <?php echo $content; ?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">DRK Oberberg SW</h3>
                    <p class="text-gray-400">Hilfsorganisation im Dienste der Menschheit</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4">Schnelllinks</h3>
                    <ul class="text-gray-400 space-y-2">
                        <li><a href="<?php echo BASE_URL; ?>/pages/uber-uns" class="hover:text-white">Über uns</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?page=blood_donations" class="hover:text-white">Blutspendetermine</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/pages/datenschutz" class="hover:text-white">Datenschutz</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/index.php?page=contact" class="hover:text-white">Kontakt</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4">Kontakt</h3>
                    <p class="text-gray-400">
                        <strong>DRK-Ortsverein Oberberg Südwest e.V.</strong><br>
                        Florastraße 3<br>
                        51674 Wiehl<br>
                        <br>
                        Email: info@drk-oberberg.de<br>
                        Tel: +49 (0) 2202 123456
                    </p>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Deutsches Rotes Kreuz Oberberg SW. Alle Rechte vorbehalten.</p>
            </div>
        </div>
    </footer>
    
    <script src="<?php echo BASE_URL; ?>/public/js/main.js"></script>
</body>
</html>
