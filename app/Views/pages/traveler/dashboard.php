<!-- Traveler Dashboard -->
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Bienvenue, <?= htmlspecialchars($user->getFullName()) ?> !</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Mes voyages -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Mes voyages</h2>
            <p class="text-gray-600">Consultez vos voyages en cours et à venir.</p>
            <a href="<?= BASE_URL ?>/mes-voyages" class="inline-block mt-4 text-blue-600 hover:underline">Voir mes voyages →</a>
        </div>
        
        <!-- Mes demandes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Mes demandes</h2>
            <p class="text-gray-600">Suivez l'état de vos demandes de voyage.</p>
            <a href="<?= BASE_URL ?>/mes-demandes" class="inline-block mt-4 text-blue-600 hover:underline">Voir mes demandes →</a>
        </div>
        
        <!-- Mon profil -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Mon profil</h2>
            <p class="text-gray-600">Gérez vos informations personnelles.</p>
            <a href="<?= BASE_URL ?>/profil" class="inline-block mt-4 text-blue-600 hover:underline">Modifier mon profil →</a>
        </div>
    </div>
    
    <!-- Informations du compte -->
    <div class="mt-8 bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-3">Informations du compte</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Email</p>
                <p class="font-medium"><?= htmlspecialchars($user->getEmail()) ?></p>
            </div>
            <?php if ($user->getPhone()): ?>
            <div>
                <p class="text-sm text-gray-600">Téléphone</p>
                <p class="font-medium"><?= htmlspecialchars($user->getPhone()) ?></p>
            </div>
            <?php endif; ?>
            <div>
                <p class="text-sm text-gray-600">Membre depuis</p>
                <p class="font-medium"><?= date('d/m/Y', strtotime($user->getCreatedAt())) ?></p>
            </div>
        </div>
    </div>
</div>
