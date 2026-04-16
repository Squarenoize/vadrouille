# Guide d'utilisation de la classe Auth

## 📌 Concepts de base

La classe `Auth` gère l'authentification de manière simple :
- **Session optimisée** : Seul l'ID utilisateur est stocké en session
- **Utilisateur chargé à la demande** : Les données sont récupérées fraîches depuis la DB

## 🔧 Méthodes disponibles

### Récupérer l'utilisateur connecté
```php
$user = Auth::user();  // Retourne User|null

if ($user) {
    echo $user->getFullName();
    echo $user->getEmail();
}
```

### Vérifier si connecté
```php
if (Auth::check()) {
    // Utilisateur connecté
}

if (Auth::guest()) {
    // Utilisateur non connecté
}
```

### Protéger une page (middleware)
```php
// Dans un contrôleur admin
public function dashboard(): void {
    Auth::requireAdmin();  // Redirige si pas admin
    
    $user = Auth::user();
    // Suite du code...
}

// Dans un contrôleur protégé
public function profile(): void {
    Auth::requireAuth();  // Redirige si non connecté
    
    $user = Auth::user();
    // Suite du code...
}
```

### Obtenir l'ID
```php
$userId = Auth::id();  // Retourne int|null
```

### Vérifier le rôle
```php
if (Auth::isAdmin()) {
    // C'est un admin
}

if (Auth::isTraveler()) {
    // C'est un traveler (client/voyageur)
}
```

## 📝 Exemples d'utilisation

### Dans un contrôleur
```php
class ProfileController {
    
    public function index(): void {
        // Protéger la page
        Auth::requireAuth();
        
        // Récupérer l'utilisateur
        $user = Auth::user();
        
        // Passer à la vue
        $view = new View('user/profile', [
            'user' => $user
        ], 'user');
        
        $view->render();
    }
}
```

### Dans un layout (admin.php, user.php)
```php
<?php $user = Auth::user(); ?>

<header>
    Bonjour, <?= htmlspecialchars($user->getFullName()) ?>
    <a href="<?= BASE_URL ?>/deconnexion">Déconnexion</a>
</header>
```

### Affichage conditionnel dans une vue
```php
<?php if (Auth::check()): ?>
    <p>Bienvenue, <?= htmlspecialchars(Auth::user()->getFirstName()) ?></p>
<?php else: ?>
    <a href="<?= BASE_URL ?>/connexion">Se connecter</a>
<?php endif; ?>
```

### Dans une navigation
```php
<nav>
    <a href="/">Accueil</a>
    
    <?php if (Auth::isAdmin()): ?>
        <a href="/admin">Panel Admin</a>
    <?php endif; ?>
    
    <?php if (Auth::check()): ?>
        <a href="/mon-compte">Mon compte</a>
        <a href="/deconnexion">Déconnexion</a>
    <?php else: ?>
        <a href="/connexion">Connexion</a>
    <?php endif; ?>
</nav>
```

## ⚠️ Important

- **Toujours appeler `Auth::user()` dans les contrôleurs** et passer l'utilisateur aux vues
- **Ne pas faire `Auth::user()` répété** dans une même page (stocker dans une variable)
- **Les sessions doivent être démarrées** (`session_start()` avant d'utiliser Auth)

## 🔄 Comparaison avant/après

### Avant (❌ Variables éparpillées)
```php
$_SESSION['userId'] = $user->getId();
$_SESSION['userFirstName'] = $user->getFirstName();
$_SESSION['role'] = $user->getRole();
$_SESSION['connected'] = true;

// Plus tard...
if ($_SESSION['connected'] ?? false) {
    $firstName = $_SESSION['userFirstName'];
}
```

### Après (✅ Classe Auth)
```php
Auth::login($user);

// Plus tard...
if (Auth::check()) {
    $user = Auth::user();
    $firstName = $user->getFirstName();
}
```
