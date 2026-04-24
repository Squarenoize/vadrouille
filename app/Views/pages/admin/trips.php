<?php
if (isset($_SESSION['traveler_access_info'])) {
    echo "<div class='info-message'>" . $_SESSION['traveler_access_info'] . "</div>";
    unset($_SESSION['traveler_access_info']);
}?>
<h1 class="admin-trips-title">Gestion des voyages</h1>
<nav class="admin-trips-nav">
    <a class="admin-trips-link <?= ($currentStatusFilter ?? '') == null ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/trips">Tous</a>
    <a class="admin-trips-link <?= ($currentStatusFilter ?? '') === 'draft' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/trips?status=draft">Brouillon</a>
    <a class="admin-trips-link <?= ($currentStatusFilter ?? '') === 'quoted' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/trips?status=quoted">Devis envoyé</a>
    <a class="admin-trips-link <?= ($currentStatusFilter ?? '') === 'accepted' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/trips?status=accepted">Devis acceptés</a>
    <a class="admin-trips-link <?= ($currentStatusFilter ?? '') === 'ongoing' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/trips?status=ongoing">En cours</a>
    <a class="admin-trips-link <?= ($currentStatusFilter ?? '') === 'finished' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/trips?status=finished">Terminé</a>
    <a class="admin-trips-link <?= ($currentStatusFilter ?? '') === 'cancelled' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/trips?status=cancelled">Annulé</a>
</nav>
<?php 
if (empty($trips)) { ?>
    <p class="no-trips-message">Aucun voyage pour ce statut actuellement.</p>
<?php 
} else { 
    foreach ($trips as $trip) { ?>
        <div class="trip-card">
            <h2><?= htmlspecialchars($trip->getName()) ?></h2>
            <a href="<?= BASE_URL ?>/admin/trips/<?= $trip->getId() ?>">Voir les détails</a>
            <a href="<?= BASE_URL ?>/admin/requests/<?= $trip->getRequestId() ?>">Voir la demande</a>
            <form method="POST" action="<?= BASE_URL ?>/admin/trips/<?= $trip->getId() ?>/status">
                <label for="status">Statut :</label>
                <select id="status" name="status">
                    <option value="draft" <?= $trip->getStatus() === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="quoted" <?= $trip->getStatus() === 'quoted' ? 'selected' : '' ?>>Devis envoyé</option>
                    <option value="accepted" <?= $trip->getStatus() === 'accepted' ? 'selected' : '' ?>>Devis accepté</option>
                    <option value="ongoing" <?= $trip->getStatus() === 'ongoing' ? 'selected' : '' ?>>En cours</option>
                    <option value="finished" <?= $trip->getStatus() === 'finished' ? 'selected' : '' ?>>Terminé</option>
                    <option value="cancelled" <?= $trip->getStatus() === 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                </select>
                <button type="submit">Mettre à jour</button>
            </form>
            <?php if ($trip->getStatus() === 'accepted') { ?>
            <button onclick="window.location.href='<?= BASE_URL ?>/admin/trips/<?= $trip->getId() ?>/traveler-access'">Créer l'accès voyageur</button>
            <?php } ?>
            <p><?= htmlspecialchars($trip->getStatus()) ?></p>
            <p>Destination: <?= htmlspecialchars($trip->getDestination()) ?></p>
            <p>Dates: <?= htmlspecialchars($trip->getStartDate()) ?> to <?= htmlspecialchars($trip->getEndDate()) ?></p>
        </div>
    <?php
    }
}