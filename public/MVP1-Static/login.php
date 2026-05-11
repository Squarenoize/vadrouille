<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/menu.js" defer></script>
</head>
<body>
    <main>
        <section class="login-section">
            <div class="login-container">
                <img class="logo" src="assets/img/VB_logo_hori.png" alt="Logo de Vadrouille & Bourlingue">
                <h1>Connexion à votre espace <span>voyage</span></h1>
                <form action="index.php?action=login" method="post" class="login-form">
                    <div class="form-group">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">MOT DE PASSE</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn-primary">Se connecter</button>
                </form>
                <p class="reset-password">Mot de passe oublié ? <a href="#">Réinitialiser</a></p>
            </div>
        </section>
    </main>
</body>
</html>