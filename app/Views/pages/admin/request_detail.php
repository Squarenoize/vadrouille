<?php

?>
<div class="request-card">
    <h2>Détail de la demande de contact #<?= $request->getId() ?></h2>
    <div class="row">
        <p>Nom<br> <strong><?= $request->getFirstName() ?> <?= $request->getLastName() ?></strong></p>
        <p>Email<br> <strong><?= $request->getEmail() ?></strong></p>
        <p>Téléphone<br> <strong><?= $request->getPhone() ?></strong></p>
        <p>Date de réception<br> <strong><?= $request->getCreatedAt() ?></strong></p>
    </div>
    <div class="row">
        <p>Type de voyage<br> <strong><?= $request->getTripType() ?></strong></p>
        <p>Destination souhaitée<br> <strong><?= $request->getDestination() ?></strong></p>
        <p>Pays de départ<br> <strong><?= $request->getStartCountry() ?></strong></p>
    </div>
    <div class="row">
        <p>Nombre d'adultes<br> <strong><?= $request->getTravelersAdultCount() ?></strong></p>
        <p>Nombre d'enfants<br> <strong><?= $request->getTravelersChildCount() ?></strong></p>
    </div>
    <div class="row">
        <p>Date de départ souhaitée<br> <strong><?= $request->getDesiredStart() ?></strong></p>
        <p>Durée du voyage (jours)<br> <strong><?= $request->getDuration() ?></strong></p>
        <p>Budget<br> <strong><?= $request->getBudget() ?> €</strong></p>
    </div>
    <div class="row">
        <p><strong>Message :</strong><br><?= nl2br($request->getMessage()) ?></p>
    </div>
    <div class="row actions">
        <form method="POST" action="<?= BASE_URL ?>/admin/requests/<?= $request->getId() ?>/status">
            <label for="status">Statut :</label>
            <select id="status" name="status">
                <option value="new" <?= $request->getStatus() === 'new' ? 'selected' : '' ?>>Nouveau</option>
                <option value="studying" <?= $request->getStatus() === 'studying' ? 'selected' : '' ?>>En étude</option>
                <option value="quoted" <?= $request->getStatus() === 'quoted' ? 'selected' : '' ?>>Devis envoyé</option>
                <option value="accepted" <?= $request->getStatus() === 'accepted' ? 'selected' : '' ?>>Devis accepté</option>
                <option value="refused" <?= $request->getStatus() === 'refused' ? 'selected' : '' ?>>Devis refusé</option>
                <option value="archived" <?= $request->getStatus() === 'archived' ? 'selected' : '' ?>>Archivé</option>
            </select>
            <button type="submit">Mettre à jour</button>
        </form>
        <?php if ($request->getStatus() === 'studying' && !$tripId) { ?>
        <button onclick="window.location.href='<?= BASE_URL ?>/admin/trips/new/request/<?= $request->getId() ?>'">Créer un voyage à partir de cette demande</button>
    </div>
    <?php } elseif ($request->getStatus() !== 'new' && $tripId) {?>
        <button onclick="window.location.href='<?= BASE_URL ?>/admin/trips/<?= $tripId ? $tripId : '' ?>'">Voir le voyage associé</button>
    <?php } ?>
    
</div>