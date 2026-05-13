# Guide d'Intégration Rapide - Sécurité Formulaire

Ce guide vous permet d'ajouter rapidement les protections anti-bots à n'importe quel formulaire de l'application.

## ⚡ Intégration en 3 Étapes

### Étape 1 : Modifier le Contrôleur (Affichage)

```php
<?php
class VotreController {
    
    public function showForm(): void {
        // 1. Stocker l'horodatage du formulaire
        FormSecurity::storeFormTimestamp('nom_de_votre_formulaire');
        
        // 2. Passer le token CSRF à la vue
        $view = new View('votre/vue', [
            'csrfToken' => FormSecurity::generateCsrfToken('nom_de_votre_formulaire'),
            // ... autres données
        ], 'layout');
        
        $view->render();
    }
}
```

### Étape 2 : Modifier la Vue (Formulaire)

```html
<!-- Afficher les erreurs de sécurité -->
<?php 
if (isset($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])) { 
    foreach ($_SESSION['form_errors'] as $error) { ?>
        <p class="error-message" style="color: #d32f2f; padding: 0.75rem; background: #ffebee; border-radius: 4px;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php }
    unset($_SESSION['form_errors']);
} 
?>

<form action="/votre-url" method="post">
    <!-- Token CSRF (obligatoire) -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
    
    <!-- Honeypot (obligatoire) -->
    <input type="text" name="website" value="" 
           style="position: absolute; left: -9999px; width: 1px; height: 1px;" 
           tabindex="-1" autocomplete="off" aria-hidden="true">
    
    <!-- Vos champs de formulaire... -->
    <input type="text" name="nom" required>
    <input type="email" name="email" required>
    
    <button type="submit">Envoyer</button>
</form>
```

### Étape 3 : Modifier le Contrôleur (Traitement)

```php
<?php
class VotreController {
    
    public function processForm(): void {
        try {
            // 1. VALIDATION DE SÉCURITÉ (toujours en premier !)
            $securityCheck = FormSecurity::validateFormSubmission('nom_de_votre_formulaire', $_POST);
            
            if (!$securityCheck['valid']) {
                $_SESSION['form_errors'] = $securityCheck['errors'];
                header('Location: ' . BASE_URL . '/votre-formulaire');
                exit;
            }
            
            // 2. Validation métier (vos validations habituelles)
            $errors = [];
            if (empty($_POST['nom'])) {
                $errors[] = "Le nom est requis";
            }
            // ... autres validations
            
            if (!empty($errors)) {
                // Régénérer token et timestamp pour le rechargement
                FormSecurity::storeFormTimestamp('nom_de_votre_formulaire');
                
                $view = new View('votre/vue', [
                    'errors' => $errors,
                    'formData' => $_POST,
                    'csrfToken' => FormSecurity::generateCsrfToken('nom_de_votre_formulaire')
                ], 'layout');
                
                $view->render();
                return;
            }
            
            // 3. Traitement des données (sauvegarde, email, etc.)
            // ... votre logique métier
            
            // 4. Nettoyage de session
            FormSecurity::cleanOldSessionData();
            
            // 5. Redirection avec succès
            $_SESSION['success_message'] = "Formulaire soumis avec succès !";
            header('Location: ' . BASE_URL . '/success');
            exit;
            
        } catch (Exception $e) {
            // Gestion d'erreur
            http_response_code(500);
            echo "Erreur : " . htmlspecialchars($e->getMessage());
        }
    }
}
```

## 📋 Checklist de Vérification

Avant de mettre en production, vérifiez :

- [ ] ✅ Token CSRF généré dans le contrôleur d'affichage
- [ ] ✅ Token CSRF passé à la vue
- [ ] ✅ Champ caché `csrf_token` dans le formulaire
- [ ] ✅ Champ honeypot `website` dans le formulaire (invisible)
- [ ] ✅ `FormSecurity::storeFormTimestamp()` appelé à l'affichage
- [ ] ✅ `FormSecurity::validateFormSubmission()` appelé en premier lors du traitement
- [ ] ✅ Gestion des `$_SESSION['form_errors']` dans la vue
- [ ] ✅ `FormSecurity::cleanOldSessionData()` appelé après succès
- [ ] ✅ Nouveau token généré en cas d'erreur de validation

## 🎯 Exemple Complet : Formulaire de Newsletter

### Contrôleur
```php
<?php
class NewsletterController {
    
    public function showForm(): void {
        FormSecurity::storeFormTimestamp('newsletter_form');
        
        $view = new View('newsletter/subscribe', [
            'pageTitle' => 'Newsletter',
            'csrfToken' => FormSecurity::generateCsrfToken('newsletter_form')
        ], 'public');
        
        $view->render();
    }
    
    public function subscribe(): void {
        // Validation sécurité
        $securityCheck = FormSecurity::validateFormSubmission('newsletter_form', $_POST);
        
        if (!$securityCheck['valid']) {
            $_SESSION['form_errors'] = $securityCheck['errors'];
            header('Location: ' . BASE_URL . '/newsletter');
            exit;
        }
        
        // Validation métier
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $_SESSION['form_errors'] = ['Email invalide'];
            header('Location: ' . BASE_URL . '/newsletter');
            exit;
        }
        
        // Sauvegarde
        $newsletterModel = new NewsletterModel();
        $newsletterModel->addSubscriber($email);
        
        // Nettoyage et succès
        FormSecurity::cleanOldSessionData();
        $_SESSION['success_message'] = "Inscription réussie !";
        header('Location: ' . BASE_URL . '/newsletter/success');
        exit;
    }
}
```

### Vue
```html
<div class="newsletter-form">
    <?php 
    if (isset($_SESSION['form_errors'])) { 
        foreach ($_SESSION['form_errors'] as $error) { ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php }
        unset($_SESSION['form_errors']);
    } 
    ?>
    
    <form action="<?= BASE_URL ?>/newsletter/subscribe" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="text" name="website" value="" style="position: absolute; left: -9999px;" tabindex="-1" autocomplete="off" aria-hidden="true">
        
        <div class="form-group">
            <label for="email">Votre email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <button type="submit">S'inscrire</button>
    </form>
</div>
```

## ⚠️ Erreurs Courantes à Éviter

### ❌ Oublier de régénérer le token en cas d'erreur
```php
// MAUVAIS
if (!empty($errors)) {
    $view = new View('form', [
        'errors' => $errors,
        // ❌ Pas de nouveau csrfToken !
    ]);
}

// BON
if (!empty($errors)) {
    FormSecurity::storeFormTimestamp('form_name');
    $view = new View('form', [
        'errors' => $errors,
        'csrfToken' => FormSecurity::generateCsrfToken('form_name') // ✅
    ]);
}
```

### ❌ Valider la sécurité APRÈS la validation métier
```php
// MAUVAIS - La validation métier est faite en premier
$user = User::fromArray($_POST);
$errors = $user->validate();
$securityCheck = FormSecurity::validateFormSubmission('form', $_POST); // Trop tard !

// BON - Sécurité en PREMIER
$securityCheck = FormSecurity::validateFormSubmission('form', $_POST);
if (!$securityCheck['valid']) {
    // Bloquer immédiatement
}
// Ensuite validation métier
$user = User::fromArray($_POST);
```

### ❌ Utiliser le même nom pour tous les formulaires
```php
// MAUVAIS - Tous les formulaires partagent le même pool de tokens
FormSecurity::generateCsrfToken('form'); // ❌ Générique

// BON - Nom unique par type de formulaire
FormSecurity::generateCsrfToken('contact_form');     // ✅
FormSecurity::generateCsrfToken('newsletter_form');  // ✅
FormSecurity::generateCsrfToken('login_form');       // ✅
```

## 🔧 Personnalisation

### Ajuster les Limites de Rate Limiting

Modifiez les constantes dans `app/Core/FormSecurity.php` :

```php
// Pour un formulaire de contact classique
private const MAX_SUBMISSIONS_PER_HOUR = 5;
private const MAX_SUBMISSIONS_PER_DAY = 20;

// Pour une newsletter (plus permissif)
private const MAX_SUBMISSIONS_PER_HOUR = 10;
private const MAX_SUBMISSIONS_PER_DAY = 50;

// Pour un formulaire de connexion (strict)
private const MAX_SUBMISSIONS_PER_HOUR = 3;
private const MAX_SUBMISSIONS_PER_DAY = 10;
```

**Note :** Si vous avez besoin de limites différentes par formulaire, créez des constantes spécifiques ou passez les limites en paramètres.

### Personnaliser les Messages d'Erreur

Modifiez les messages dans `FormSecurity::validateFormSubmission()` :

```php
if (!self::verifyCsrfToken($formName, $postData['csrf_token'] ?? null)) {
    $errors[] = 'Votre message personnalisé ici';
}
```

## 📚 Pour Aller Plus Loin

- Consultez [SECURITE_FORMULAIRE.md](./SECURITE_FORMULAIRE.md) pour la documentation complète
- Lisez le code source de `app/Core/FormSecurity.php` pour comprendre les détails
- Testez dans un environnement de développement avant production

---

**Besoin d'aide ?** Vérifiez l'implémentation dans `ContactController.php` comme référence complète.
