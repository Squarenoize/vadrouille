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

if ($isLocal) {
    // LOCAL DATABASE (WAMP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'vadrouille');
    define('DB_USER', 'root');
    define('DB_PASS', 'sirius');
    
} else {
    // PRODUCTION DATABASE
    // TO CONFIGURE during deployment
    define('DB_HOST', 'localhost');  // or MySQL server IP
    define('DB_NAME', 'production_db_name');
    define('DB_USER', 'production_db_user');
    define('DB_PASS', 'secure_password');
}