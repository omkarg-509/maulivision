<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/flash.php';

// Expose shared mysqli instance as $conn for existing pages
try {
    $conn = db(require __DIR__ . '/../config/config.php');
} catch (Throwable $e) {
    http_response_code(500);
    exit('Database connection error.');
}
