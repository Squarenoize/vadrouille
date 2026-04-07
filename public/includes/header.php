<?php 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vadrouille & Bourlingue</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header>
        <div class="header-container">
            <a href="index.php"><img class="logo" src="assets/img/VB_logo_hori.png" alt="Logo de Vadrouille & Bourlingue"></a>
            <nav class="mainNav">
                <a class="mainNavLink <?php if(isset($_GET['action']) && $_GET['action'] == 'voyages') echo 'active'; ?>" href="index.php?action=voyages">Voyages</a>
                <a class="mainNavLink <?php if(isset($_GET['action']) && $_GET['action'] == 'apropos') echo 'active'; ?>" href="index.php?action=apropos">À propos</a>
                <a class="mainNavLink <?php if(isset($_GET['action']) && $_GET['action'] == 'contact') echo 'active'; ?>" href="index.php?action=contact">Contact</a>
            </nav>
            <div class="mainLinks">
                <button class="btn-connexion" onclick="window.location.href='index.php?action=login'">Connexion</button>
                <button class="btn-primary" onclick="window.location.href='index.php?action=register'">Demander mon voyage</button>
            </div>
        </div>
    </header>