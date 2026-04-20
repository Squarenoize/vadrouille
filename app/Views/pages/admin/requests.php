<?php
?>
<h2>Liste des Demandes</h2>
<table class="requests-table">
    <thead>
        <tr>
            <th></th>
            <th>ID</th>
            <th>Nom Complet</th>
            <th>Email</th>
            <th>Type de Voyage</th>
            <th>Destination</th>
            <th>Durée</th>
            <th>Date de Réception</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requests as $request): ?>
        <tr>
            <td><a href="<?= BASE_URL ?>/admin/requests/<?= htmlspecialchars($request->getId()) ?>"><span class="material-symbols-outlined" data-icon="visibility">visibility</span></a></td>
            <td><?= htmlspecialchars($request->getId()) ?></td>
            <td><?= htmlspecialchars($request->getFirstName() . ' ' . $request->getLastName()) ?></td>
            <td><?= htmlspecialchars($request->getEmail()) ?></td>
            <td><?= htmlspecialchars($request->getTripType()) ?></td>
            <td><?= htmlspecialchars($request->getDestination()) ?></td>
            <td><?= htmlspecialchars($request->getDuration()) ?></td>
            <td><?= htmlspecialchars($request->getCreatedAt()) ?></td>
            <td><?= htmlspecialchars(ucfirst($request->getStatus())) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>