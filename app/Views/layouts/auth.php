<?php

/**
 * Auth layout - Pages d'authentification
 * 
 * Variables disponibles (extraites via View::render()) :
 * @var string $contentView - Chemin vers la vue de contenu à inclure
 * @var string|null $pageTitle - Titre de la page
 */

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Connexion - Vadrouille & Bourlingue' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/img/favicon.ico?v=2">
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
</head>

<body>
    <!-- Contenu de la page d'authentification -->
    <main>
        <?php include $contentView; ?>
    </main>

    <!-- Lien retour au site -->
    
</body>
</html>
