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
$router->get('/change-password', 'AuthController', 'showChangePassword'); // Formulaire changement mot de passe
$router->post('/change-password', 'AuthController', 'changePassword'); // Traiter changement mot de passe

// For  POST requests (form)
$router->post('/contact', 'ContactController', 'send');     // Soumettre formulaire contact

// Admin routes (GET)
$router->get('/admin/dashboard', 'AdminController', 'dashboard'); // Dashboard admin
$router->get('/admin/requests', 'AdminController', 'requests');   // Liste des demandes
$router->get('/admin/requests/(\d+)', 'AdminController', 'viewRequest'); // Détail d'une demande
$router->get('/admin/trips', 'AdminController', 'trips'); // Liste des voyages
$router->get('/admin/trips/(\d+)/trip-item', 'AdminController', 'tripItem'); // Formulaire ajout item
$router->get('/admin/trips/(\d+)/edit-item/(\d+)', 'AdminController', 'showEditTripItem'); // Formulaire édition item
$router->get('/admin/trips/(\d+)/reorder-items', 'AdminController', 'reorderTripItems'); // Réorganiser items chronologiquement
$router->get('/admin/trips/(\d+)/traveler-access', 'AdminController', 'travelerAccess'); // Donner accès voyageur pour un voyage accepté
$router->get('/admin/trips/new/request/(\d+)', 'AdminController', 'newTripFromRequest'); // Créer un voyage à partir d'une demande
$router->get('/admin/trips/(\d+)', 'AdminController', 'viewTrip'); // Détail d'un voyage
$router->get('/admin/chats', 'AdminController', 'chats'); // Messagerie
$router->get('/admin/settings', 'AdminController', 'settings'); // Paramètres du compte

//Admin routes (POST)
$router->post('/admin/requests/(\d+)/status', 'AdminController', 'updateRequestStatus'); // Mettre à jour le statut d'une demande
$router->post('/admin/trips/create', 'AdminController', 'createTrip'); // Créer un voyage à partir d'une demande
$router->post('/admin/trips/(\d+)/status', 'AdminController', 'updateTripStatus'); // Mettre à jour le statut d'un voyage
$router->post('/admin/trips/(\d+)/add-item', 'AdminController', 'addTripItem'); // Ajouter un item à un voyage
$router->post('/admin/trips/(\d+)/edit-item/(\d+)', 'AdminController', 'editTripItem'); // Modifier un item d'un voyage
$router->post('/admin/trips/(\d+)/delete-item/(\d+)', 'AdminController', 'deleteTripItem'); // Supprimer un item d'un voyage

// Traveler routes (GET)
$router->get('/traveler/dashboard', 'TravelerController', 'dashboard'); // Dashboard voyageur
$router->get('/traveler/trips', 'TravelerController', 'trips'); // Mes voyages
$router->get('/traveler/trips/(\d+)', 'TravelerController', 'viewTrip'); // Détail d'un voyage avec messagerie
$router->get('/traveler/settings', 'TravelerController', 'settings'); // Paramètres du compte

// Messages route (shared between admin and traveler)
$router->post('/messages/send', 'MessagesController', 'send'); // Send a new message
// Common settings update route (shared between admin and traveler)
$router->post('/traveler/settings/update', 'TravelerController', 'updateSettings'); //

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
