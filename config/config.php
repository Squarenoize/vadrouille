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
 * TIMEZONE CONFIGURATION
 */
date_default_timezone_set('Europe/Paris');

/**
 * LOAD ENVIRONMENT VARIABLES FROM .env
 */
$envFile = ROOT_DIR . '.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE or KEY = VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = array_map('trim', explode('=', $line, 2));
            if (!empty($key)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

/**
 * DATABASE
 */

    define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'travel_planner');
    define('DB_USER', $_ENV['DB_USER'] ?? 'root');
    define('DB_PASS', $_ENV['DB_PASSWORD'] ?? '');
    
