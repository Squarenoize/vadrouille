<?php
// ============================================
// CONFIGURATION MULTI-ENVIRONNEMENT
// ============================================

// Détection de l'environnement
$hostname = $_SERVER['HTTP_HOST'] ?? 'localhost';

// LOCAL ENVIRONEMENT (WAMP)
$isLocal = (
    strpos($hostname, 'localhost') !== false || 
    strpos($hostname, '127.0.0.1') !== false ||
    strpos($hostname, '.local') !== false ||
    strpos($hostname, '.test') !== false
);

// Configuration BASE_URL
if ($isLocal) {
    // LOCAL : Sous-dossier /Vadrouille/public
    define('BASE_URL', '/Vadrouille/public');
    
} else {
    // PRODUCTION : Racine du domaine
    // (Document Root = dossier public/)
    define('BASE_URL', '');
}

define('ROOT_DIR', __DIR__ . '/../');

// ============================================
// DATABASE
// ============================================

if ($isLocal) {
    // BASE DE DONNÉES LOCALE (WAMP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'vadrouille');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    
} else {
    // BASE DE DONNÉES PRODUCTION
    // À CONFIGURER lors du déploiement
    define('DB_HOST', 'localhost');  // ou IP du serveur MySQL
    define('DB_NAME', 'nom_bdd_production');
    define('DB_USER', 'utilisateur_bdd');
    define('DB_PASS', 'mot_de_passe_securise');
}