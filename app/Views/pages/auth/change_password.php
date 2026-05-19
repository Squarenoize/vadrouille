<section class="login-section">
    <div class="login-container">
        <img class="logo" src="<?= BASE_URL ?>/assets/img/VB_logo_hori.png" alt="Logo de Vadrouille & Bourlingue">
        <h1>Changement de mot de passe</h1>

        <?php if (isset($_SESSION['change_pwd_error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_SESSION['change_pwd_error']) ?>
            </div>
            <?php unset($_SESSION['change_pwd_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['change_pwd_required'])): ?>
            <div class="warning-message">
                <?= htmlspecialchars($_SESSION['change_pwd_required']) ?>
            </div>
            <?php unset($_SESSION['change_pwd_required']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/change-password" method="post" class="login-form">
            <div class="form-group">
                <label for="current_password">MOT DE PASSE ACTUEL</label>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
            </div>

            <div class="form-group">
                <label for="new_password">NOUVEAU MOT DE PASSE</label>
                <input type="password" id="new_password" name="new_password" required minlength="8" autocomplete="new-password">
                <small>Le mot de passe doit contenir au moins 8 caractères, dont au moins une lettre, un chiffre et un caractère spécial.</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">CONFIRMER LE NOUVEAU MOT DE PASSE</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8" autocomplete="new-password">
            </div>

            <button type="submit" class="btn-primary">Modifier mon mot de passe</button>
        </form>
    </div>
</section>