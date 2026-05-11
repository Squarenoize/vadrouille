<?php
/**
 * ENVIRONMENT CONFIGURATION
 */

// Environment detection based on hostname
$hostname = $_SERVER['HTTP_HOST'] ?? 'localhost';

// LOCAL ENVIRONEMENT (WAMP)
$isLocal = (
    strpos($hostname, 'localhost') !== false
);

// BASE_URL
if ($isLocal) {
    // LOCAL : Sub-directory /Vadrouille/public
    define('BASE_URL', '/Vadrouille/public');
    
} else {
    // PRODUCTION : Root of the domain
    // (Document Root = public/ folder)
    define('BASE_URL', '');
}

define('ROOT_DIR', __DIR__ . '/../');

/**
 * DATABASE
 */

    define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'vadrouille');
    define('DB_USER', $_ENV['DB_USER'] ?? 'root');
    define('DB_PASS', $_ENV['DB_PASSWORD'] ?? 'sirius');
    
