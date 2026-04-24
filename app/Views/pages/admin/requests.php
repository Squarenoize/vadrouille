<?php
?>
<h1 class="admin-requests-title">Gestion des demandes</h1>
<nav class="admin-requests-nav">
    <a class="admin-requests-link <?= ($currentStatusFilter ?? '') == null ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/requests">Toutes</a>
    <a class="admin-requests-link <?= ($currentStatusFilter ?? '') === 'new' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/requests?status=new">Nouvelles</a>
    <a class="admin-requests-link <?= ($currentStatusFilter ?? '') === 'studying' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/requests?status=studying">En étude</a>
    <a class="admin-requests-link <?= ($currentStatusFilter ?? '') === 'quoted' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/requests?status=quoted">Devis envoyés</a>
    <a class="admin-requests-link <?= ($currentStatusFilter ?? '') === 'accepted' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/requests?status=accepted">Devis acceptés</a>
    <a class="admin-requests-link <?= ($currentStatusFilter ?? '') === 'refused' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/requests?status=refused">Devis refusés</a>
    <a class="admin-requests-link <?= ($currentStatusFilter ?? '') === 'archived' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/requests?status=archived">Archivés</a>
</nav>
<?php 
if (empty($requests)) { ?>
    <p class="no-requests-message">Aucune demande pour ce statut actuellement.</p>
<?php
} else { ?>
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
            <?php 
            foreach ($requests as $request): ?>
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
<?php } 