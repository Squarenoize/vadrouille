<?php
$isEdit = isset($item) && $item !== null;
$formTitle = $isEdit ? "Modifier l'item : " . htmlspecialchars($item->getTitle()) : "Ajouter un item à l'itinéraire du voyage";
$formAction = $isEdit 
    ? BASE_URL . "/admin/trips/{$tripId}/edit-item/{$itemId}" 
    : BASE_URL . "/admin/trips/{$tripId}/add-item";
$submitButtonText = $isEdit ? "Enregistrer les modifications" : "Ajouter l'item";
?>
<div class="trip-item-container">
    <h1 class="admin-item-title"><?= $formTitle ?></h1>
    <form action="<?= $formAction ?>" method="POST" class="trip-item-form">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre de l'item</label>
                <input type="text" id="title" name="title" 
                       value="<?= $isEdit ? htmlspecialchars($item->getTitle()) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Catégorie</label>
                <select id="category" name="category" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <option value="TRANSPORT" <?= $isEdit && $item->getCategory() === 'TRANSPORT' ? 'selected' : '' ?>>Transport</option>
                    <option value="HÉBERGEMENT" <?= $isEdit && $item->getCategory() === 'HÉBERGEMENT' ? 'selected' : '' ?>>Hébergement</option>
                    <option value="ACTIVITÉ" <?= $isEdit && $item->getCategory() === 'ACTIVITÉ' ? 'selected' : '' ?>>Activité</option>
                    <option value="RESTAURATION" <?= $isEdit && $item->getCategory() === 'RESTAURATION' ? 'selected' : '' ?>>Repas</option>
                    <option value="LIBRE" <?= $isEdit && $item->getCategory() === 'LIBRE' ? 'selected' : '' ?>>Libre</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="startDatetime">Date et heure de début</label>
                <input type="datetime-local" id="startDatetime" name="startDatetime" 
                       value="<?= $isEdit ? $item->getStartDatetime()->format('Y-m-d\TH:i') : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="endDatetime">Date et heure de fin</label>
                <input type="datetime-local" id="endDatetime" name="endDatetime" 
                       value="<?= $isEdit ? $item->getEndDatetime()->format('Y-m-d\TH:i') : '' ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description (optionnel)</label>
            <textarea id="description" name="description"><?= $isEdit ? htmlspecialchars($item->getDescription() ?? '') : '' ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="requiresBooking">Nécessite une réservation ?</label>
                <input type="checkbox" id="requiresBooking" name="requiresBooking" 
                       <?= $isEdit && $item->getRequiresBooking() ? 'checked' : '' ?>>
            </div>
            <div class="form-group">
                <label for="indicativePrice">Prix indicatif (optionnel)</label>
                <input type="number" step="0.01" id="indicativePrice" name="indicativePrice" 
                       value="<?= $isEdit ? ($item->getIndicativePrice() ?? '') : '' ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="externalLink">Lien externe (optionnel)</label>
            <input type="url" id="externalLink" name="externalLink" 
                   value="<?= $isEdit ? htmlspecialchars($item->getExternalLink() ?? '') : '' ?>">
        </div>
        <div class="form-actions">
            <button type="submit"><?= $submitButtonText ?></button>
            <a href="<?= BASE_URL ?>/admin/trips/<?= $tripId ?>" class="button">Annuler</a>
        </div>
    </form>
</div>