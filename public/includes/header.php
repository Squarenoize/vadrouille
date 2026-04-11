<?php
$adminReady = false;
// Définir BASE_URL si pas déjà défini (pour les pages qui n'incluent pas index.php)
if (!defined('BASE_URL')) {
    $isLocal = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);
    $baseUrl = $isLocal ? '/Vadrouille/public' : '';
    define('BASE_URL', $baseUrl);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Vadrouille & Bourlingue'; ?></title>
    <meta name="description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Voyages sur mesure organisés par des passionnés.'; ?>">
    <meta name="keywords" content="voyage sur mesure, travel planner, agence voyage personnalisé, voyages authentiques, séjour luxe, voyage organisé">
    <link rel="canonical" href="<?php echo isset($pageFullUrl) ? htmlspecialchars($pageFullUrl) : ''; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo isset($pageFullUrl) ? htmlspecialchars($pageFullUrl) : ''; ?>">
    <meta property="og:title" content="<?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Vadrouille & Bourlingue'; ?>">
    <meta property="og:description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Voyages sur mesure organisés par des passionnés.'; ?>">
    <meta property="og:image" content="<?php echo isset($pageFullImage) ? htmlspecialchars($pageFullImage) : ''; ?>">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Vadrouille & Bourlingue">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo isset($pageFullUrl) ? htmlspecialchars($pageFullUrl) : ''; ?>">
    <meta name="twitter:title" content="<?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Vadrouille & Bourlingue'; ?>">
    <meta name="twitter:description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Voyages sur mesure organisés par des passionnés.'; ?>">
    <meta name="twitter:image" content="<?php echo isset($pageFullImage) ? htmlspecialchars($pageFullImage) : ''; ?>">
    
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/menu.js" defer></script>
</head>

<body>
    <header>
        <div class="header-container">
            <a href="<?php echo BASE_URL; ?>/"><img class="logo" src="assets/img/VB_logo_hori.png" alt="Logo de Vadrouille & Bourlingue"></a>
            
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <nav class="mainNav" id="mainNav">
                <a class="mainNavLink <?php if(isset($_GET['action']) && $_GET['action'] == 'trips') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/voyages">Voyages</a>
                <a class="mainNavLink <?php if(isset($_GET['action']) && $_GET['action'] == 'about') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/a-propos">À propos</a>
                <a class="mainNavLink <?php if(isset($_GET['action']) && $_GET['action'] == 'contact') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/contact">Contact</a>
                <?php if ($adminReady) {?>
                <a class="mainNavLink mobile-only" href="login.php">Connexion</a>
                <?php } ?>
            </nav>
            <div class="mainLinks">
                <?php if ($adminReady) {?>
                <button class="btn-connexion" onclick="window.location.href='login.php'">Connexion</button>
                <?php } ?>
                <button class="btn-primary" onclick="window.location.href='<?php echo BASE_URL; ?>/contact'">Demander mon voyage</button>
            </div>
        </div>
    </header>