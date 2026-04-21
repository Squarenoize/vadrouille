<?php
echo '<h1>creer un voyage à partir d\'une demande</h1>';
if (isset($errors)) {
    echo '<div class="errors">';
    foreach ($errors as $error) {
        echo '<p>' . htmlspecialchars($error) . '</p>';
    }
    echo '</div>';

}
?>
<form class="new-trip-form" method="POST" action="<?= BASE_URL ?>/admin/trips/create">
    <input type="hidden" name="requestId" value="<?= $request->getId() ?>">

    <label for="tripName">Titre du voyage :</label>
    <input type="text" id="tripName" name="tripName" value="Voyage pour <?= $request->getFirstName() ?> <?= $request->getLastName() ?>" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description" required>Voyage pour <?= $request->getFirstName() ?> <?= $request->getLastName() ?> souhaitant partir à <?= $request->getDestination() ?> du <?= $request->getDesiredStart() ?> pour une durée de <?= $request->getDuration() ?> jours avec un budget de <?= $request->getBudget() ?> €. Son message: <?= $request->getMessage() ?></textarea>
    
    <label for="destination">Destination :</label>
    <input type="text" id="destination" name="destination" value="<?= $request->getDestination() ?>" required>
    
    <label for="startDate">Date de départ :</label>
    <input type="date" id="startDate" name="startDate" value="<?= date('Y-m-d', strtotime($request->getDesiredStart())) ?>" required>

    <label for="endDate">Date de retour :</label>
    <input type="date" id="endDate" name="endDate" value="<?= date('Y-m-d', strtotime($request->getDesiredStart() . ' + ' . $request->getDuration() . ' days')) ?>" required>
    
    <label for="adminNote">Note interne :</label>
    <textarea id="adminNote" name="adminNote" placeholder="Ajouter une note administrateur..."></textarea>
    
    <button type="submit">Créer le voyage</button>
</form>