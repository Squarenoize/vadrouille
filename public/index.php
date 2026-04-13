<?php
// 
// CONFIGURATION
// 

// Charge configuration (DB, BASE_URL, constants, etc.)
require_once __DIR__ . '/../config/config.php';

// Charge autoloader
require_once __DIR__ . '/../config/autoloader.php';

// Session start for flash messages, auth, etc.
session_start();

// 
// ROUTES
// 

$router = new Router();

// Public routes GET
$router->get('/', 'HomeController', 'index');                    // Page d'accueil
$router->get('/a-propos', 'HomeController', 'about');           // À propos
$router->get('/mentions-legales', 'HomeController', 'terms');   // CGU
$router->get('/confidentialite', 'HomeController', 'privacy'); // Politique de confidentialité
$router->get('/voyages', 'TripsController', 'index');            // Voyages
$router->get('/contact', 'ContactController', 'index');          // Contact

// Login routes
$router->get('/connexion', 'AuthController', 'showLogin');       // Afficher formulaire login
$router->post('/connexion', 'AuthController', 'login');          // Traiter la connexion
$router->get('/deconnexion', 'AuthController', 'logout');        // Déconnexion

// For  POST requests (form)
$router->post('/contact', 'ContactController', 'send');     // Soumettre formulaire contact

// 
// DISPATCH
// 

try {
    $router->dispatch();
} catch (Exception $e) {
    // Error handling
    http_response_code(500);
    echo '<h1>Erreur</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}