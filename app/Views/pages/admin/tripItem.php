<?php
?>
<div class="trip-item-container">
    <h1>Ajouter un item à l'itinéraire du voyage</h1>
    <form action="<?= BASE_URL ?>/admin/trips/<?= $tripId ?>/add-item" method="POST" class="trip-item-form">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre de l'item</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="category">Catégorie</label>
                <select id="category" name="category" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <option value="TRANSPORT">Transport</option>
                    <option value="HÉBERGEMENT">Hébergement</option>
                    <option value="ACTIVITÉ">Activité</option>
                    <option value="RESTAURATION">Repas</option>
                    <option value="LIBRE">Libre</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="startDatetime">Date et heure de début</label>
                <input type="datetime-local" id="startDatetime" name="startDatetime" required>
            </div>
            <div class="form-group">
                <label for="endDatetime">Date et heure de fin</label>
                <input type="datetime-local" id="endDatetime" name="endDatetime" required>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description (optionnel)</label>
            <textarea id="description" name="description"></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="requiresBooking">Nécessite une réservation ?</label>
                <input type="checkbox" id="requiresBooking" name="requiresBooking">
            </div>
            <div class="form-group">
                <label for="indicativePrice">Prix indicatif (optionnel)</label>
                <input type="number" step="0.01" id="indicativePrice" name="indicativePrice">
            </div>
        </div>
        <div class="form-group">
            <label for="externalLink">Lien externe (optionnel)</label>
            <input type="url" id="externalLink" name="externalLink">
        </div>
        <button type="submit" class="button">Ajouter l'item</button>
    </form>
</div>