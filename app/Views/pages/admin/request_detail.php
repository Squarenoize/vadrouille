<?php

?>
<div class="card">
    <h2>Détail de la demande de contact #<?= $request->getId() ?></h2>
    <p><strong>Nom :</strong> <?= $request->getFirstName() ?> <?= $request->getLastName() ?></p>
    <p><strong>Email :</strong> <?= $request->getEmail() ?></p>
    <p><strong>Téléphone :</strong> <?= $request->getPhone() ?></p>
    <p><strong>Type de voyage :</strong> <?= $request->getTripType() ?></p>
    <p><strong>Destination souhaitée :</strong> <?= $request->getDestination() ?></p>
    <p><strong>Nombre d'adultes :</strong> <?= $request->getTravelersAdultCount() ?></p>
    <p><strong>Nombre d'enfants :</strong> <?= $request->getTravelersChildCount() ?></p>
    <p><strong>Date de départ souhaitée :</strong> <?= $request->getDesiredStart() ?></p>
    <p><strong>Durée du voyage (jours) :</strong> <?= $request->getDuration() ?></p>
    <p><strong>Budget :</strong> <?= $request->getBudget() ?> €</p>
    <p><strong>Pays de départ :</strong> <?= $request->getStartCountry() ?></p>
    <p><strong>Message :</strong><br><?= nl2br($request->getMessage()) ?></p>
    <p><strong>Status :</strong> <?= ucfirst($request->getStatus()) ?></p>
    <form method="POST" action="<?= BASE_URL ?>/admin/requests/<?= $request->getId() ?>/update">
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
    <p><strong>Date de réception :</strong> <?= $request->getCreatedAt() ?></p>
</div>