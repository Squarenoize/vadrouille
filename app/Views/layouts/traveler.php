<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Mon Espace - Vadrouille & Bourlingue'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/img/favicon.ico?v=2">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <!-- Header Traveler -->
    <header class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-8">
                    <a href="<?= BASE_URL ?>/">
                        <img class="h-10" src="<?= BASE_URL ?>/assets/img/VB_logo_hori.png" alt="Vadrouille & Bourlingue">
                    </a>
                    <nav class="hidden md:flex gap-6">
                        <a class="text-gray-700 hover:text-blue-600" href="<?= BASE_URL ?>/mes-voyages">Mes voyages</a>
                        <a class="text-gray-700 hover:text-blue-600" href="<?= BASE_URL ?>/mes-demandes">Mes demandes</a>
                        <a class="text-gray-700 hover:text-blue-600" href="<?= BASE_URL ?>/voyages">Découvrir</a>
                    </nav>
                </div>
                
                <div class="flex items-center gap-4">
                    <?php $user = Auth::user(); ?>
                    <span class="text-sm text-gray-600">Bonjour, <strong><?= htmlspecialchars($user->getFirstName()) ?></strong></span>
                    <a href="<?= BASE_URL ?>/profil" class="text-gray-700 hover:text-blue-600">
                        <span class="material-icons">account_circle</span>
                    </a>
                    <a href="<?= BASE_URL ?>/deconnexion" class="text-gray-700 hover:text-blue-600">
                        <span class="material-icons">logout</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="min-h-screen">
        <?php include $contentView; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; <?= date('Y') ?> Vadrouille & Bourlingue - Tous droits réservés</p>
            <div class="mt-4 flex justify-center gap-6">
                <a href="<?= BASE_URL ?>/mentions-legales" class="text-gray-400 hover:text-white">Mentions légales</a>
                <a href="<?= BASE_URL ?>/confidentialite" class="text-gray-400 hover:text-white">Confidentialité</a>
                <a href="<?= BASE_URL ?>/contact" class="text-gray-400 hover:text-white">Contact</a>
            </div>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>/assets/js/menu.js"></script>
</body>
</html>