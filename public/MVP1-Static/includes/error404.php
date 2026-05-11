<?php
$errorMessage = "Erreur 404 : Page non trouvée. La page que vous recherchez n'existe pas ou a été déplacée.";
?>
        <section class="error-page">
            <img src="assets/img/404.png" alt="Image d'erreur 404">
            <p><?php echo $errorMessage; ?></p>
            <button class="btn-primary" onclick="window.location.href='index.php'">Retour à l'accueil</button>
        </section>