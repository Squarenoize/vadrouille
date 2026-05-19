<div class="trip-detail-container">
    <section class="trip-detail-section">
        <h1 class="admin-detail-title"><?= htmlspecialchars($trip->getName()) ?></h1>
        <a href="<?= BASE_URL ?>/admin/trips/<?= $trip->getId() ?>/trip-item" class="trip-add-button">Ajouter un item</a>
        <?php if (!empty($itemsByDay)) { ?>
        <div class="trip-items-list">
            <?php foreach ($itemsByDay as $day) { ?>
                <div class="trip-day-group">
                    <h3 class="day-header"><?= htmlspecialchars($day['displayDate']) ?></h3>
                    <?php foreach ($day['items'] as $item) { ?>
                        <div class="trip-item-card">
                            <div class="trip-item-row">
                                <div class="item-title">
                                    <span class="item-category">
                                        <?php
                                        switch ($item->getCategory()) {
                                            case 'TRANSPORT':?>
                                                <span class="material-symbols-outlined" data-icon="directions_car">directions_car</span>
                                                <?php break;
                                            case 'HÉBERGEMENT':?>
                                                 <span class="material-symbols-outlined" data-icon="hotel">hotel</span>
                                                <?php break;
                                            case 'ACTIVITÉ':?>
                                                 <span class="material-symbols-outlined" data-icon="local_activity">local_activity</span>
                                                <?php break;
                                            case 'RESTAURATION':?>
                                                 <span class="material-symbols-outlined" data-icon="restaurant">restaurant</span>
                                                <?php break;
                                            case 'LIBRE':?>
                                                 <span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
                                                <?php break;
                                            default:
                                                echo htmlspecialchars($item->getCategory());
                                        }
                                        ?></span>
                                    <h4><?= htmlspecialchars($item->getTitle()) ?></h4>
                                </div>
                                <p><strong>⏰ Horaire :</strong> <?= $item->getStartDatetime()->format('H:i') ?> - <?= $item->getEndDatetime()->format('H:i') ?></p>
                            </div>
                            <div class="trip-item-row">
                                <?php if ($item->getDescription()) { ?>
                                    <p class="item-description"><strong>Description :</strong><br><?= nl2br(htmlspecialchars($item->getDescription())) ?></p>
                                <?php } else { ?>
                                    <p class="item-description">Aucune description disponible.</p>
                                <?php } ?>
                                <div class="item-details">
                                    <?php if ($item->getRequiresBooking()) { ?>
                                        <p>📅 Réservation requise</p>
                                    <?php } ?>
                                    <?php if ($item->getIndicativePrice() !== null) { ?>
                                        <p><strong>💰 Prix indicatif :</strong> <?= number_format($item->getIndicativePrice(), 2, ',', ' ') ?> €</p>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ($item->getExternalLink()) { ?>
                                <p><a href="<?= htmlspecialchars($item->getExternalLink()) ?>" target="_blank">🔗 Lien externe</a></p>
                            <?php } ?>
                            <div class="item-actions">
                                <a href="<?= BASE_URL ?>/admin/trips/<?= $trip->getId() ?>/edit-item/<?= $item->getId() ?>" class="button">Modifier</a>
                                <form action="<?= BASE_URL ?>/admin/trips/<?= $trip->getId() ?>/delete-item/<?= $item->getId() ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet item ?');" style="display: inline;">
                                    <button type="submit" class="delete-button"><span class="material-icons">delete</span></button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    </section>
    <section class="trip-discussion-section">
        <?php
        if (!empty($messages)) {
            include __DIR__ . '/../../../views/components/shared/chat.php'; 
        }?>
    </section>
</div>