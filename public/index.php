<?php
// ========================================
// CONFIGURATION
// ========================================

// Détection automatique de l'environnement
$isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
            strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
$baseUrl = $isLocal ? '/Vadrouille/public' : '';
define('BASE_URL', $baseUrl);

// Charger l'autoloader
require_once __DIR__ . '/../config/autoloader.php';

// ========================================
// CONFIGURATION DES ROUTES (style moderne)
// ========================================

$router = new Router();

// Routes publiques GET
$router->get('/', 'HomeController', 'index');                    // Page d'accueil
$router->get('/voyages', 'TripsController', 'index');            // Voyages
$router->get('/contact', 'ContactController', 'index');          // Contact
$router->get('/a-propos', 'AboutController', 'index');           // À propos
$router->get('/mentions-legales', 'TermsController', 'index');   // CGU

// Routes formulaires POST (pour plus tard)
// $router->post('/contact', 'ContactController', 'submit');     // Soumettre formulaire contact

// ========================================
// DISPATCH
// ========================================

try {
    $router->dispatch();
} catch (Exception $e) {
    // Gestion des erreurs
    http_response_code(500);
    echo '<h1>Erreur</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}