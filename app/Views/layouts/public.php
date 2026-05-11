<?php

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
    
    <!-- Données structurées Schema.org -->
    <?php if (isset($schemaOrganization)): ?>
    <script type="application/ld+json">
    <?php echo json_encode($schemaOrganization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <?php endif; ?>
    
    <?php if (isset($schemaPage)): ?>
    <script type="application/ld+json">
    <?php echo json_encode($schemaPage, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <?php endif; ?>
    
    <?php if (isset($breadcrumbs)): ?>
    <script type="application/ld+json">
    <?php echo json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <?php endif; ?>
    
    <?php if (isset($schemaFAQ)): ?>
    <script type="application/ld+json">
    <?php echo json_encode($schemaFAQ, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/img/favicon.ico?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>/assets/img/favicon.ico?v=2">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>/assets/img/favicon.ico?v=2">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
</head>

<body>
    <header>
        <div class="header-container">
            <a href="<?php echo BASE_URL; ?>/"><img class="logo" src="<?= BASE_URL ?>/assets/img/VB_logo_hori.png" alt="Logo de Vadrouille & Bourlingue"></a>
            
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <nav class="mainNav" id="mainNav">
                <a class="mainNavLink <?php if(isset($currentAction) && $currentAction == 'trips') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/voyages">Voyages</a>
                <a class="mainNavLink <?php if(isset($currentAction) && $currentAction == 'about') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/a-propos">À propos</a>
                <a class="mainNavLink <?php if(isset($currentAction) && $currentAction == 'contact') echo 'active'; ?>" href="<?php echo BASE_URL; ?>/contact">Contact</a>
                
                <?php if (Auth::check()): ?>
                    <?php if (Auth::isAdmin()): ?>
                        <a class="mainNavLink mobile-only" href="<?= BASE_URL ?>/admin/requests">Tableau de bord</a>
                    <?php else: ?>
                        <a class="mainNavLink mobile-only" href="<?= BASE_URL ?>/traveler/trips">Mon compte</a>
                    <?php endif; ?>
                    <a class="mainNavLink mobile-only" href="<?= BASE_URL ?>/deconnexion">Déconnexion</a>
                <?php else: ?>
                    <a class="mainNavLink mobile-only" href="<?= BASE_URL ?>/connexion">Connexion</a>
                <?php endif; ?>
            </nav>
            <div class="mainLinks">
                <?php if (Auth::check()): ?>
                    <?php $user = Auth::user(); ?>
                    <?php if ($user->isAdmin()): ?>
                        <button class="btn-connexion" onclick="window.location.href='<?= BASE_URL ?>/admin/requests'">Tableau de bord</button>
                    <?php else: ?>
                        <button class="btn-connexion" onclick="window.location.href='<?= BASE_URL ?>/traveler/trips'">Mon compte</button>
                    <?php endif; ?>
                    <button class="btn-primary" onclick="window.location.href='<?= BASE_URL ?>/deconnexion'">Déconnexion</button>
                <?php else: ?>
                    <button class="btn-connexion" onclick="window.location.href='<?= BASE_URL ?>/connexion'">Connexion</button>
                    <button class="btn-primary" onclick="window.location.href='<?php echo BASE_URL; ?>/contact'">Demander mon voyage</button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Page -->
    <main>
        <?php include $contentView; ?>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-resume">
                <p class="footer-title">Vadrouille & Bourlingue</p>
                <p class="footer-description">Votre compagnon de voyage pour des aventures inoubliables à travers le monde.</p>
                <div class="footer-links">
                    <a href="https://www.instagram.com/vadrouillebourlingue/" target="_blank"><img src="assets/img/instagram.png" alt="Instagram"></a>
                    <a href="#"><span class="material-icons">share</span></a>
                </div>
            </div>
            <div class="footer-info">
                <div class="footer-explorer">
                    <h3>Explorer</h3>
                    <a href="<?php echo BASE_URL; ?>/voyages">Voyages</a>
                </div>
                <div class="footer-legal">
                    <h3>Légal</h3>
                    <a href="<?php echo BASE_URL; ?>/mentions-legales">Mentions Légales et CGU</a>
                    <a href="<?php echo BASE_URL; ?>/confidentialite">Politique de confidentialité</a>
                </div>
            </div>
            <div class="footer-copyright">
                <p>Copyright © Vadrouille & Bourlingue 2026</p>
            </div>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>/assets/js/menu.js"></script>
</body>
</html>