<?php
echo "trips page";
var_dump($trips);
if (isset($_SESSION['traveler_access_info'])) {
    echo "<div class='info-message'>" . $_SESSION['traveler_access_info'] . "</div>";
    unset($_SESSION['traveler_access_info']);
}
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