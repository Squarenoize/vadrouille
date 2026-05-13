# Guide d'Intégration Google reCAPTCHA v3 (Optionnel)

Ce guide explique comment ajouter Google reCAPTCHA v3 comme couche de protection supplémentaire. Cette protection est **optionnelle** et vient en complément des protections déjà en place.

## 🎯 Pourquoi reCAPTCHA v3 ?

### Avantages
- ✅ **Invisible** : Pas d'interaction utilisateur (pas de cases à cocher)
- ✅ **Score de risque** : Évaluation du comportement de 0.0 (bot) à 1.0 (humain)
- ✅ **Gratuit** : Jusqu'à 1 million de vérifications/mois
- ✅ **Fiable** : Technologie Google éprouvée

### Inconvénients
- ⚠️ Dépendance externe (API Google)
- ⚠️ Nécessite une connexion Internet
- ⚠️ Chargement JavaScript supplémentaire
- ⚠️ Politique de confidentialité à mettre à jour

## 📝 Étape 1 : Obtenir les Clés d'API

1. Allez sur [Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin)
2. Cliquez sur "Créer" (+)
3. Remplissez le formulaire :
   - **Libellé** : Vadrouille Contact Form
   - **Type** : reCAPTCHA v3
   - **Domaines** : 
     - `localhost` (pour développement)
     - `votre-domaine.com` (pour production)
4. Acceptez les conditions
5. Enregistrez et notez :
   - **Clé du site** (Site Key) : pour le frontend
   - **Clé secrète** (Secret Key) : pour le backend

## 🔧 Étape 2 : Configuration

### Ajouter les Clés dans `config/config.php`

```php
<?php
// ... autres configurations

// Google reCAPTCHA v3
define('RECAPTCHA_SITE_KEY', 'VOTRE_SITE_KEY_ICI');
define('RECAPTCHA_SECRET_KEY', 'VOTRE_SECRET_KEY_ICI');
define('RECAPTCHA_ENABLED', true); // false pour désactiver
define('RECAPTCHA_MIN_SCORE', 0.5); // Score minimum accepté (0.0 - 1.0)
```

**Note de Sécurité :** Ne commitez jamais vos clés dans Git. Utilisez plutôt des variables d'environnement en production :

```php
define('RECAPTCHA_SECRET_KEY', getenv('RECAPTCHA_SECRET_KEY') ?: 'votre_clé_dev');
```

## 💻 Étape 3 : Mettre à Jour FormSecurity.php

Ajoutez cette méthode à la classe `FormSecurity` :

```php
<?php
// Dans app/Core/FormSecurity.php

/**
 * Verify Google reCAPTCHA v3 token
 * 
 * @param string|null $token reCAPTCHA token from frontend
 * @param string $action Action name (e.g., 'contact_form')
 * @return array ['success' => bool, 'score' => float, 'message' => string]
 */
public static function verifyRecaptcha(?string $token, string $action = 'submit'): array {
    // Check if reCAPTCHA is enabled
    if (!defined('RECAPTCHA_ENABLED') || !RECAPTCHA_ENABLED) {
        return ['success' => true, 'score' => 1.0, 'message' => 'reCAPTCHA disabled'];
    }
    
    // Check if token is provided
    if (empty($token)) {
        return [
            'success' => false,
            'score' => 0.0,
            'message' => 'Token reCAPTCHA manquant'
        ];
    }
    
    // Prepare verification request
    $data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $token,
        'remoteip' => self::getClientIp()
    ];
    
    // Call Google API
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    
    if ($result === false) {
        return [
            'success' => false,
            'score' => 0.0,
            'message' => 'Impossible de vérifier reCAPTCHA'
        ];
    }
    
    $response = json_decode($result, true);
    
    // Check response
    if (!$response['success']) {
        return [
            'success' => false,
            'score' => 0.0,
            'message' => 'Vérification reCAPTCHA échouée'
        ];
    }
    
    // Check action
    if ($response['action'] !== $action) {
        return [
            'success' => false,
            'score' => $response['score'] ?? 0.0,
            'message' => 'Action reCAPTCHA incorrecte'
        ];
    }
    
    // Check score
    $score = $response['score'] ?? 0.0;
    $minScore = defined('RECAPTCHA_MIN_SCORE') ? RECAPTCHA_MIN_SCORE : 0.5;
    
    if ($score < $minScore) {
        return [
            'success' => false,
            'score' => $score,
            'message' => 'Score reCAPTCHA trop faible (comportement suspect détecté)'
        ];
    }
    
    return [
        'success' => true,
        'score' => $score,
        'message' => 'Vérification reCAPTCHA réussie'
    ];
}
```

Mettez à jour la méthode `validateFormSubmission` pour inclure reCAPTCHA :

```php
public static function validateFormSubmission(string $formName, array $postData): array {
    $errors = [];
    
    // 1. Check CSRF token
    if (!self::verifyCsrfToken($formName, $postData['csrf_token'] ?? null)) {
        $errors[] = 'Token de sécurité invalide ou expiré. Veuillez recharger la page.';
    }
    
    // 2. Check honeypot
    if (!self::checkHoneypot($postData['website'] ?? null)) {
        $errors[] = 'Soumission invalide détectée.';
    }
    
    // 3. Check form fill time
    if (!self::checkFormFillTime($formName)) {
        $errors[] = 'Le formulaire a été soumis trop rapidement. Veuillez prendre le temps de le remplir.';
    }
    
    // 4. Check rate limiting
    $rateLimit = self::checkRateLimit($formName);
    if (!$rateLimit['allowed']) {
        $errors[] = $rateLimit['message'];
    }
    
    // 5. Check reCAPTCHA (NEW)
    if (defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED) {
        $recaptchaResult = self::verifyRecaptcha(
            $postData['recaptcha_token'] ?? null,
            $formName
        );
        
        if (!$recaptchaResult['success']) {
            $errors[] = $recaptchaResult['message'];
        }
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}
```

## 🎨 Étape 4 : Mettre à Jour la Vue

### Dans le Layout (avant `</head>`)

Ajoutez le script reCAPTCHA dans votre layout (`app/Views/layouts/public.php`) :

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- ... autres balises meta -->
    
    <?php if (defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED) : ?>
        <!-- Google reCAPTCHA v3 -->
        <script src="https://www.google.com/recaptcha/api.js?render=<?= RECAPTCHA_SITE_KEY ?>"></script>
    <?php endif; ?>
</head>
<body>
    <!-- ... contenu -->
</body>
</html>
```

### Dans le Formulaire (avant `</form>`)

Ajoutez le champ caché pour le token et le script d'exécution :

```html
<form action="<?= BASE_URL ?>/contact" method="post" id="contact-form">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    <input type="text" name="website" value="" style="position: absolute; left: -9999px;" tabindex="-1" autocomplete="off" aria-hidden="true">
    
    <!-- Champ caché pour le token reCAPTCHA -->
    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
    
    <!-- Vos champs de formulaire... -->
    
    <button type="submit">Envoyer</button>
</form>

<?php if (defined('RECAPTCHA_ENABLED') && RECAPTCHA_ENABLED) : ?>
<script>
document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    grecaptcha.ready(function() {
        grecaptcha.execute('<?= RECAPTCHA_SITE_KEY ?>', {action: 'contact_form'})
            .then(function(token) {
                // Injecter le token dans le champ caché
                document.getElementById('recaptcha_token').value = token;
                // Soumettre le formulaire
                document.getElementById('contact-form').submit();
            });
    });
});
</script>
<?php endif; ?>
```

## 🧪 Étape 5 : Tester

### Test en Local
1. Utilisez `localhost` comme domaine autorisé
2. Testez une soumission normale → Doit passer
3. Testez sans JavaScript (bloquer le script) → Doit échouer

### Test en Production
1. Ajoutez votre domaine de production dans la console reCAPTCHA
2. Déployez le code
3. Testez sur le site en ligne

### Vérifier les Scores
Ajoutez un log temporaire dans `verifyRecaptcha()` pour voir les scores :

```php
// Pour debug uniquement (retirer en production)
error_log("reCAPTCHA score for IP {$response['remoteip']}: {$response['score']}");
```

**Scores typiques :**
- 1.0 : Très probablement humain
- 0.7-0.9 : Probablement humain
- 0.5-0.6 : Suspect
- 0.0-0.4 : Très probablement bot

## ⚙️ Ajustement du Score Minimum

Le score minimum est défini dans `config/config.php` :

```php
define('RECAPTCHA_MIN_SCORE', 0.5); // Valeur par défaut
```

**Recommandations :**
- **0.3** : Permissif (peu de faux positifs, mais laisse passer plus de bots)
- **0.5** : Équilibré (recommandé)
- **0.7** : Strict (bloque plus de bots, mais peut bloquer des humains)

Ajustez selon vos besoins et observez les taux de faux positifs.

## 📊 Monitoring avec Google Admin Console

1. Connectez-vous à [reCAPTCHA Admin](https://www.google.com/recaptcha/admin)
2. Sélectionnez votre site
3. Consultez :
   - **Statistiques** : Nombre de requêtes, taux de succès
   - **Distribution des scores** : Visualisation des scores
   - **Alertes** : Détection d'anomalies

## 🔒 Politique de Confidentialité

**Important :** Vous DEVEZ mettre à jour votre politique de confidentialité pour mentionner l'utilisation de reCAPTCHA.

Exemple de texte à ajouter :

> **Protection Anti-Spam**
> 
> Ce site utilise Google reCAPTCHA v3 pour protéger nos formulaires contre le spam et les abus. 
> reCAPTCHA collecte des informations matérielles et logicielles, telles que les données de l'appareil 
> et de l'application, et les envoie à Google à des fins d'analyse.
> 
> L'utilisation de reCAPTCHA est soumise à la [Politique de confidentialité](https://policies.google.com/privacy) 
> et aux [Conditions d'utilisation](https://policies.google.com/terms) de Google.

## 🚀 Activation/Désactivation Rapide

Pour désactiver reCAPTCHA sans supprimer le code :

```php
// Dans config/config.php
define('RECAPTCHA_ENABLED', false); // Désactiver
```

Toutes les vérifications seront automatiquement ignorées.

## 🔄 Comparaison Avant/Après

### AVANT (Protections Natives)
- ✅ CSRF Token
- ✅ Honeypot
- ✅ Rate Limiting
- ✅ Time-Based Validation

**Protection :** Excellente contre bots basiques et scripts simples

### APRÈS (Avec reCAPTCHA v3)
- ✅ CSRF Token
- ✅ Honeypot
- ✅ Rate Limiting
- ✅ Time-Based Validation
- ✅ **reCAPTCHA v3 (score comportemental)**

**Protection :** Maximale contre bots avancés, fermes de clics, et attaques sophistiquées

## 💡 Recommandations

1. **Commencez sans reCAPTCHA** : Les protections natives sont déjà très efficaces
2. **Ajoutez reCAPTCHA si nécessaire** : En cas d'attaques persistantes malgré les protections
3. **Surveillez les métriques** : Consultez régulièrement Google Admin Console
4. **Ajustez le score** : Commencez à 0.5 et affinez selon vos besoins

## 📚 Ressources

- [Documentation officielle reCAPTCHA v3](https://developers.google.com/recaptcha/docs/v3)
- [Console Admin reCAPTCHA](https://www.google.com/recaptcha/admin)
- [FAQ reCAPTCHA](https://developers.google.com/recaptcha/docs/faq)

---

**Note :** L'intégration de reCAPTCHA v3 est totalement optionnelle. Les protections natives implémentées dans `FormSecurity.php` sont déjà robustes pour la majorité des cas d'usage.
