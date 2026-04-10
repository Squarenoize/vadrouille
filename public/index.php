<?php
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'home';
}

// Configuration SEO dynamique par page
$seo = [
    'home' => [
        'title' => 'Vadrouille & Bourlingue - Voyages sur mesure organisés par des passionnés',
        'description' => 'Découvrez nos voyages sur mesure créés par des experts. Un parcours fluide vers l\'exceptionnel : brief, création, ajustement et départ en toute sérénité.'
    ],
    'trips' => [
        'title' => 'Nos Voyages Réalisés | Vadrouille & Bourlingue',
        'description' => 'Découvrez nos voyages soigneusement élaborés à travers le monde. Aventure, détente, culture ou week-end : nous construirons le voyage parfait pour vous.'
    ],
    'contact' => [
        'title' => 'Contactez-nous - Demandez votre voyage | Vadrouille & Bourlingue',
        'description' => 'Partagez vos aspirations avec nous. Que ce soit une quête de sérénité ou une soif d\'aventure, nous façonnons chaque détail pour une expérience sur mesure.'
    ],
    'about' => [
        'title' => 'À propos - Notre philosophie | Vadrouille & Bourlingue',
        'description' => 'L\'art de s\'égarer pour mieux se retrouver. Découvrez notre philosophie, notre passion pour les cultures lointaines et notre volonté de proposer des voyages authentiques.'
    ],
    'terms' => [
        'title' => 'Mentions légales | Vadrouille & Bourlingue',
        'description' => 'Consultez les mentions légales et conditions générales d\'utilisation de Vadrouille & Bourlingue, votre agence de voyages sur mesure.'
    ]
];

// Définir le titre et la description pour la page actuelle
$pageTitle = isset($seo[$action]) ? $seo[$action]['title'] : 'Vadrouille & Bourlingue';
$pageDescription = isset($seo[$action]) ? $seo[$action]['description'] : 'Voyages sur mesure organisés par des passionnés.';

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