<?php
?>
<h2 class="chats-page-title">Paramètres du compte</h2>
<div class="settings-container">
    <form action="<?= BASE_URL ?>/traveler/settings/update" method="POST" class="settings-form">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($userSettings['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="first_name">Prénom</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($userSettings['first_name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="last_name">Nom</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($userSettings['last_name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($userSettings['phone'] ?? '') ?>">
        </div>
        <div class="mail-notif-group">
            <label for="email_notif">Notifications par email</label>
            <input type="checkbox" id="email_notif" name="email_notif" <?= !empty($userSettings['email_notif']) ? 'checked' : '' ?>>
        </div>
        <div class="button-form-group">
            <a href="<?= BASE_URL ?>/change-password" class="change-password-link">Changer de mot de passe</a>
            <button class="submit-btn" type="submit">Enregistrer les modifications</button>
        </div>
    </form>
</div>