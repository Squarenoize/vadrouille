<?php
?>
<div class="trip-detail-container">
    <section class="trip-detail-section">
        <h1>Détail du voyage: <br><?= htmlspecialchars($trip->getName()) ?></h1>
        <p>Implémentation à venir...</p>
    </section>
    <section class="trip-discussion-section">
        <?php include __DIR__ . '/../../../views/components/shared/chat.php'; ?>
    </section>
</div>