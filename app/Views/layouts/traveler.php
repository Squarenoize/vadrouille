<?php
// Traveler layout - Interface de voyageur
$prioritaryFunctionality = false; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voyageur - Vadrouille & Bourlingue</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/img/favicon.ico?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>/assets/img/favicon.ico?v=2">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>/assets/img/favicon.ico?v=2">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    
    
</head>

<body>

    <!-- Sidebar Navigation (commune à toutes les pages voyageur) -->
    <aside class="traveler-sidebar">
        <a class="logo" href="<?php echo BASE_URL; ?>/"><img src="<?= BASE_URL ?>/assets/img/VadrouilleBourlingueLogoWithoutText.png" alt="Logo de Vadrouille & Bourlingue"></a>
        <div class="sidebar-brand">Vadrouille & Bourlingue</div>
        <div class="sidebar-subtitle">Espace Voyageur</div>
        <nav class="sidebar-nav">
            <a class="sidebar-link <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>/traveler/dashboard">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span>Tableau de bord</span>
            </a>
            <a class="sidebar-link <?= ($currentPage ?? '') === 'trips' ? 'active' : '' ?>" href="<?= BASE_URL ?>/traveler/trips">
                <span class="material-symbols-outlined" data-icon="explore">explore</span>
                <span>Mes voyages</span>
            </a>
            <a class="sidebar-link <?= ($currentPage ?? '') === 'chats' ? 'active' : '' ?>" href="<?= BASE_URL ?>/traveler/chats">
                <span class="material-symbols-outlined" data-icon="chat_bubble">chat_bubble</span>
                <span>Messagerie</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="traveler-main">
        <!-- TopBar (commune à toutes les pages voyageur) -->
        <header class="traveler-topbar">
            <div class="topbar-left">
                <?php if ($prioritaryFunctionality) { ?>
                <div class="topbar-search">
                    <span class="material-symbols-outlined" data-icon="search">search</span>
                    <input placeholder="Search itineraries or clients..." type="text"/>
                </div>
                <?php } ?>
            </div>
            <div class="topbar-right">
                <?php if ($prioritaryFunctionality) { ?>
                <button class="topbar-btn">
                    <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    <span class="topbar-notification-badge"></span>
                </button>
                <?php } ?>
                <button class="topbar-btn" onclick="window.location.href='<?= BASE_URL ?>/travelers/settings'">
                    <span class="material-symbols-outlined" data-icon="settings">settings</span>
                </button>
                <div class="topbar-divider"></div>
                <div class="topbar-user">
                    <div class="topbar-user-info">
                        <p class="topbar-user-role">Voyageur</p>
                        <p class="topbar-user-name"><?= htmlspecialchars($user->getFullName()) ?></p>
                    </div>
                    <div class="topbar-user-initials"><?= $user->getInitials() ?></div>
                </div>
                <a href='<?= BASE_URL ?>/deconnexion'><span class="material-symbols-outlined" data-icon="logout">logout</span></a>
            </div>
        </header>

        <!-- Dashboard Canvas (contenu spécifique à chaque page) -->
        <div class="dashboard-canvas">
            <?php include $contentView; ?>
        </div>
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