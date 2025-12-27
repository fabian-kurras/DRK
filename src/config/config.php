<?php
/**
 * Allgemeine Konfiguration und Konstanten
 */

// Basis-URLs und Pfade
define('BASE_URL', 'http://localhost/DRK');
define('BASE_PATH', dirname(dirname(__DIR__)));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('SRC_PATH', BASE_PATH . '/src');

// Sicherheits-Einstellungen
define('SESSION_TIMEOUT', 3600); // 1 Stunde
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_HASH_OPTIONS', ['cost' => 10]);

// Fehler-Reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Fehler nicht direkt anzeigen
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/logs/php_errors.log');

// Charset
header('Content-Type: text/html; charset=utf-8');

// Session starten
session_start();

// Sicherheits-Header
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

// User-Authentifizierung Check
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

function getCurrentUser() {
    return $_SESSION['user_id'] ?? null;
}

function getCurrentRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Weiterleitung mit Message
 */
function redirect($url, $message = '', $type = 'info') {
    if (!empty($message)) {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }
    header('Location: ' . $url);
    exit();
}

/**
 * Message anzeigen und löschen
 */
function getAndClearMessage() {
    $message = $_SESSION['message'] ?? '';
    $type = $_SESSION['message_type'] ?? 'info';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    return ['text' => $message, 'type' => $type];
}

/**
 * Sichere Ausgabe
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Datum formatieren
 */
function formatDate($date, $format = 'd.m.Y H:i') {
    if (!$date) return '';
    try {
        $dt = new DateTime($date);
        return $dt->format($format);
    } catch (Exception $e) {
        return $date;
    }
}
