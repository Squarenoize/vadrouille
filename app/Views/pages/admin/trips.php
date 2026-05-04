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
    ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th></th>
                <th>Nom du voyage</th>
                <th>Destination</th>
                <th>Dates</th>
                <th>Statut</th>
                <th>Accès voyageur</th>
                <th>Actions</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($trips as $trip) { ?>
            <tr>
                <td><a href="<?= BASE_URL ?>/admin/trips/<?= $trip->getId() ?>"><span class="material-symbols-outlined" data-icon="visibility">visibility</span></a></td>
                <td><?= htmlspecialchars($trip->getName()) ?></td>
                <td><?= htmlspecialchars($trip->getDestination()) ?></td>
                <td class="trip-dates"><?= htmlspecialchars($trip->getStartDate()) ?> <br> <?= htmlspecialchars($trip->getEndDate()) ?></td>
                <td>
                    <form class="trip-status" method="POST" action="<?= BASE_URL ?>/admin/trips/<?= $trip->getId() ?>/status">
                        <select name="status">
                            <option value="draft" <?= $trip->getStatus() === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                            <option value="quoted" <?= $trip->getStatus() === 'quoted' ? 'selected' : '' ?>>Devis envoyé</option>
                            <option value="accepted" <?= $trip->getStatus() === 'accepted' ? 'selected' : '' ?>>Devis accepté</option>
                            <option value="ongoing" <?= $trip->getStatus() === 'ongoing' ? 'selected' : '' ?>>En cours</option>
                            <option value="finished" <?= $trip->getStatus() === 'finished' ? 'selected' : '' ?>>Terminé</option>
                            <option value="cancelled" <?= $trip->getStatus() === 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                        </select>
                        <button type="submit">Mettre à jour</button>
                    </form>
                </td>
                <td><?= $trip->getUserId() ? '<span class="material-symbols-outlined" data-icon="check_circle">check_circle</span>' : '<span class="material-symbols-outlined" data-icon="cancel">cancel</span>' ?></td>
                <td class="trip-actions">
                    <button onclick="window.location.href='<?= BASE_URL ?>/admin/requests/<?= $trip->getRequestId() ?>'">Voir la demande</button> 
                    
                    <?php if ($trip->getStatus() === 'accepted' && $trip->getUserId() == null) { ?>
                     <button onclick="window.location.href='<?= BASE_URL ?>/admin/trips/<?= $trip->getId() ?>/traveler-access'">Accès voyageur</button>
                    <?php } ?>
                </td>
                <td><?= htmlspecialchars($trip->getAdminNote()) ?></td>
            </tr>
            <?php
            } ?>
        </tbody>
    </table>
<?php
}