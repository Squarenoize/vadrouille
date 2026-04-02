<?php 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vadrouille & Bourlingue</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <header>
        <img class="logo" src="assets/img/VB_logo_hori.png" alt="Logo de Vadrouille & Bourlingue">
        <nav class="mainNav">
            <a class="mainNavLink" href="index.php">Voyages</a>
            <a class="mainNavLink" href="index.php?action=destinations">Blog</a>
            <a class="mainNavLink" href="index.php?action=apropos">À propos</a>
            <a class="mainNavLink" href="index.php?action=contact">Contact</a>
        </nav>
        <div class="mainLinks">
            <button class="btn-connexion" onclick="window.location.href='index.php?action=login'">Connexion</button>
            <button class="btn-primary" onclick="window.location.href='index.php?action=register'">Demander mon voyage</button>
        </div>
    </header>