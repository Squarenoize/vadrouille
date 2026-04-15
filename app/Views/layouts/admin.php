<?php
// Variable pour afficher/masquer le bouton connexion (à mettre à true quand l'auth sera prête)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Vadrouille & Bourlingue</title>
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
                <a class="mainNavLink mobile-only" href="<?= BASE_URL ?>/deconnexion">Déconnexion</a>
            </nav>
            <div class="mainLinks">
                <button class="btn-connexion" onclick="window.location.href='<?= BASE_URL ?>/deconnexion'">Déconnexion</button>
            </div>
        </div>
    </header>

    <!-- Page -->
    <main>
        <?php include $contentView; ?>
    </main>

    <footer>
        
    </footer>

    <script src="<?= BASE_URL ?>/assets/js/menu.js"></script>
</body>
</html>