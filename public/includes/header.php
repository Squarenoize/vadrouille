<?php
$adminReady = true; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Vadrouille & Bourlingue'; ?></title>
    <meta name="description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Voyages sur mesure organisés par des passionnés.'; ?>">
    
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/menu.js" defer></script>
</head>

<body>
    <header>
        <div class="header-container">
            <a href="index.php"><img class="logo" src="assets/img/VB_logo_hori.png" alt="Logo de Vadrouille & Bourlingue"></a>
            
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <nav class="mainNav" id="mainNav">
                <a class="mainNavLink <?php if(isset($_GET['action']) && $_GET['action'] == 'trips') echo 'active'; ?>" href="index.php?action=trips">Voyages</a>
                <a class="mainNavLink <?php if(isset($_GET['action']) && $_GET['action'] == 'about') echo 'active'; ?>" href="index.php?action=about">À propos</a>
                <a class="mainNavLink <?php if(isset($_GET['action']) && $_GET['action'] == 'contact') echo 'active'; ?>" href="index.php?action=contact">Contact</a>
                <?php if ($adminReady) {?>
                <a class="mainNavLink mobile-only" href="login.php">Connexion</a>
                <?php } ?>
            </nav>
            <div class="mainLinks">
                <?php if ($adminReady) {?>
                <button class="btn-connexion" onclick="window.location.href='login.php'">Connexion</button>
                <?php } ?>
                <button class="btn-primary" onclick="window.location.href='index.php?action=contact'">Demander mon voyage</button>
            </div>
        </div>
    </header>