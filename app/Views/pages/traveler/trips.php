<?php
?>
<h1 class="travel-page-title">Mes voyages</h1>
<?php foreach ($trips as $trip) { ?>
<h2 class="travel-trip-title"><?= htmlspecialchars($trip->getName()) ?></h2>
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
            <p class="stat-card-label">Départ pour <?= $trip->getDaysCount() ?> jours dans</p>
            <h2 class="stat-card-value"><?= $trip->getDaysBeforeStart() ?> j</h2>
        </div>
        <p class="stat-card-footer">du <?= htmlspecialchars($trip->getStartDate()) ?> au <?= htmlspecialchars($trip->getEndDate()) ?></p>
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
<?php }
 