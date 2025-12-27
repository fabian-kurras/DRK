<?php
/**
 * Admin Logout
 */

require_once dirname(dirname(__DIR__)) . '/src/config/config.php';

$_SESSION = array();
session_destroy();

redirect(BASE_URL . '/src/admin/login.php', 'Sie wurden abgemeldet.', 'success');
?>
