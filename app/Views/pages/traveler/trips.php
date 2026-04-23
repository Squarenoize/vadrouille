<?php
echo 'Mes voyages';
var_dump($trips);
foreach ($trips as $trip) { ?>
<h2><?= htmlspecialchars($trip->getName()) ?></h2>
<a class="traveler-trip-link" href="<?= BASE_URL ?>/traveler/trips/<?= $trip->getId() ?>">
 <section class="bento-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <p class="stat-card-label">Réservations faites</p>
            <h2 class="stat-card-value">12 / 20</h2>
        </div>
        <span class="stat-card-icon material-symbols-outlined" data-icon="pending_actions">pending_actions</span>
    </div>
    
    <div class="stat-card primary">
        <div class="stat-card-header">
            <p class="stat-card-label">Départ dans</p>
            <h2 class="stat-card-value"><?= $trip->getDaysBeforeStart() ?> j</h2>
        </div>
        <p class="stat-card-footer">Avant le départ</p>
        <span class="stat-card-icon material-symbols-outlined" data-icon="explore">explore</span>
    </div>
    
    <div class="stat-card">
        <div class="stat-card-header-flex">
            <div>
                <p class="stat-card-label">Messagerie</p>
                <h2 class="stat-card-value">
                    <?php 
                    $count = $unreadCounts[$trip->getId()] ?? 0;
                    echo $count;
                    ?>
                </h2>
            </div>
        </div>
        <p class="stat-card-footer">Messages non lus</p>
        <span class="stat-card-icon material-symbols-outlined" data-icon="chat_bubble">chat_bubble</span>
    </div>
</section>
</a>
    <div class="trip-card">
        <h2><?= htmlspecialchars($trip->getName()) ?></h2>
        <a href="<?= BASE_URL ?>/traveler/trips/<?= $trip->getId() ?>">Voir les détails</a>
        <p><?= htmlspecialchars($trip->getStatus()) ?></p>
        <p>Destination: <?= htmlspecialchars($trip->getDestination()) ?></p>
        <p>Dates: <?= htmlspecialchars($trip->getStartDate()) ?> to <?= htmlspecialchars($trip->getEndDate()) ?></p>
    </div>
<?php }
 