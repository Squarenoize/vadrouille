<section class="login-section">
    <a href="<?= BASE_URL ?>/" class="back-to-site">← Retour au site</a>
    <div class="login-container">
        <img class="logo" src="<?= BASE_URL ?>/assets/img/VB_logo_hori.png" alt="Logo de Vadrouille & Bourlingue">
        <h1>Connexion à votre espace <span>voyage</span></h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>/connexion" method="post" class="login-form">
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
        
        <p class="reset-password">Mot de passe oublié ? <a href="<?= BASE_URL ?>/mot-de-passe-oublie">Réinitialiser</a></p>
    </div>
</section>
