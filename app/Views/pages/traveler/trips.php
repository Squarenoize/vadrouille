<?php
echo 'Mes voyages';
var_dump($trips);
foreach ($trips as $trip) { ?>
    <div class="trip-card">
        <h2><?= htmlspecialchars($trip->getName()) ?></h2>
        <a href="<?= BASE_URL ?>/traveler/trips/<?= $trip->getId() ?>">Voir les détails</a>
        <p><?= htmlspecialchars($trip->getStatus()) ?></p>
        <p>Destination: <?= htmlspecialchars($trip->getDestination()) ?></p>
        <p>Dates: <?= htmlspecialchars($trip->getStartDate()) ?> to <?= htmlspecialchars($trip->getEndDate()) ?></p>
    </div>
<?php } 