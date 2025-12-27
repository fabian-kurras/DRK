<?php
/**
 * Datenbankverbindung - Konfiguration
 * Umgebung: XAMPP MySQL
 */

// Datenbank-Zugangsdaten
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'drk_oberberg');
define('DB_PORT', 3306);

/**
 * Verbindung zur Datenbank herstellen
 * Wirft eine Exception bei Fehler
 */
function getDbConnection() {
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        die('Datenbankfehler: ' . htmlspecialchars($e->getMessage()));
    }
}

/**
 * Globale DB-Verbindung initialisieren
 */
$GLOBALS['db'] = getDbConnection();
