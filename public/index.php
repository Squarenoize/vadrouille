<?php
// Détection automatique de l'environnement (local vs production)
$isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
$baseUrl = $isLocal ? '/Vadrouille/public' : '';
define('BASE_URL', $baseUrl);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'home';
}

// Configuration SEO dynamique par page
$seo = [
    'home' => [
        'title' => 'Vadrouille & Bourlingue - Voyages sur mesure organisés par des passionnés',
        'description' => 'Découvrez nos voyages sur mesure créés par des experts. Un parcours fluide vers l\'exceptionnel : brief, création, ajustement et départ en toute sérénité.',
        'url' => '',
        'image' => 'assets/img/home_front.png'
    ],
    'trips' => [
        'title' => 'Nos Voyages Réalisés | Vadrouille & Bourlingue',
        'description' => 'Découvrez nos voyages soigneusement élaborés à travers le monde. Aventure, détente, culture ou week-end : nous construirons le voyage parfait pour vous.',
        'url' => 'voyages',
        'image' => 'assets/img/trips/Edimbourgh.jpg'
    ],
    'contact' => [
        'title' => 'Contactez-nous - Demandez votre voyage | Vadrouille & Bourlingue',
        'description' => 'Partagez vos aspirations avec nous. Que ce soit une quête de sérénité ou une soif d\'aventure, nous façonnons chaque détail pour une expérience sur mesure.',
        'url' => 'contact',
        'image' => 'assets/img/rockStele.png'
    ],
    'about' => [
        'title' => 'À propos - Notre philosophie | Vadrouille & Bourlingue',
        'description' => 'L\'art de s\'égarer pour mieux se retrouver. Découvrez notre philosophie, notre passion pour les cultures lointaines et notre volonté de proposer des voyages authentiques.',
        'url' => 'a-propos',
        'image' => 'assets/img/Travel_Planner_Portrait.png'
    ],
    'terms' => [
        'title' => 'Mentions légales | Vadrouille & Bourlingue',
        'description' => 'Consultez les mentions légales et conditions générales d\'utilisation de Vadrouille & Bourlingue, votre agence de voyages sur mesure.',
        'url' => 'mentions-legales',
        'image' => 'assets/img/VB_logo_hori.png'
    ]
];

// Définir les données SEO pour la page actuelle
$pageTitle = isset($seo[$action]) ? $seo[$action]['title'] : 'Vadrouille & Bourlingue';
$pageDescription = isset($seo[$action]) ? $seo[$action]['description'] : 'Voyages sur mesure organisés par des passionnés.';
$pageUrl = isset($seo[$action]) ? $seo[$action]['url'] : '';
$pageImage = isset($seo[$action]) ? $seo[$action]['image'] : 'assets/img/VB_logo_hori.png';

// URL complète du site
$siteUrl = 'https://vadbou.fr'; 
$pageFullUrl = $siteUrl . '/' . $pageUrl;
$pageFullImage = $siteUrl . '/' . $pageImage;

include_once 'includes/header.php';
?>
    <main>
        <?php    
        switch ($action) {
            case 'home':
                include_once 'includes/home.php';
                break;
            case 'trips':
                include_once 'includes/trips.php';
                break;
            case 'contact':
                include_once 'includes/contact.php';
                break;
            case 'about':
                include_once 'includes/about.php';
                break;
            case 'terms':
                include_once 'includes/terms.php';
                break;
                case 'login':
                include_once 'includes/login.php';
                break;
            default:
                include_once 'includes/error404.php';
        }
        ?>
    </main>
<?php
include_once 'includes/footer.php';