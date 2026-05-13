# Sécurisation des Formulaires Publics contre les Bots

Ce document explique les différentes couches de protection implémentées pour sécuriser les formulaires publics de l'application contre les bots et les soumissions automatisées.

## 🛡️ Protections Implémentées

### 1. **CSRF Token (Cross-Site Request Forgery)**
Protection contre les attaques CSRF qui empêchent les soumissions depuis d'autres sites web.

**Comment ça fonctionne :**
- Un token unique est généré pour chaque affichage du formulaire
- Le token est stocké en session et inséré dans un champ caché du formulaire
- Lors de la soumission, le token est vérifié
- Le token expire après 1 heure
- Le token est à usage unique (supprimé après utilisation)

**Avantages :**
- ✅ Empêche les attaques CSRF
- ✅ Protège contre les soumissions depuis des sites tiers
- ✅ Standard de sécurité web reconnu

### 2. **Honeypot Field (Champ Piège)**
Un champ invisible qui piège les bots automatisés.

**Comment ça fonctionne :**
- Un champ texte nommé "website" est ajouté au formulaire
- Ce champ est masqué visuellement (CSS: `position: absolute; left: -9999px`)
- Les utilisateurs réels ne voient pas ce champ et ne le remplissent donc pas
- Les bots remplissent automatiquement tous les champs, y compris celui-ci
- Si le champ est rempli, la soumission est rejetée

**Avantages :**
- ✅ Très efficace contre les bots basiques
- ✅ Invisible pour les utilisateurs réels
- ✅ Pas de friction utilisateur (pas de CAPTCHA)
- ✅ Pas de dépendance externe

### 3. **Rate Limiting (Limitation de Taux)**
Limite le nombre de soumissions par adresse IP.

**Comment ça fonctionne :**
- Chaque soumission est enregistrée avec l'IP et l'horodatage
- **Limite horaire :** Maximum 5 soumissions par heure
- **Limite quotidienne :** Maximum 20 soumissions par jour
- Les anciennes entrées (>24h) sont automatiquement nettoyées

**Avantages :**
- ✅ Empêche le spam massif
- ✅ Protège contre les attaques par force brute
- ✅ Limite l'abus du formulaire
- ✅ Gestion automatique de l'IP (supporte proxies et CDN)

### 4. **Time-Based Validation (Validation Temporelle)**
Détecte si le formulaire a été rempli trop rapidement (comportement de bot).

**Comment ça fonctionne :**
- L'horodatage d'affichage du formulaire est stocké en session
- Lors de la soumission, on calcule le temps écoulé
- **Temps minimum requis :** 3 secondes
- Si le formulaire est soumis en moins de 3 secondes, il est rejeté

**Avantages :**
- ✅ Détecte les scripts automatisés
- ✅ Les utilisateurs réels prennent toujours plus de 3 secondes
- ✅ Pas de friction pour l'utilisateur
- ✅ Complémentaire aux autres méthodes

## 📁 Fichiers Modifiés

### Nouveaux Fichiers
- **`app/Core/FormSecurity.php`** : Classe centrale contenant toutes les méthodes de sécurité

### Fichiers Modifiés
- **`app/Controllers/ContactController.php`** : Intégration des validations de sécurité
- **`app/Views/pages/public/contact.php`** : Ajout du token CSRF et du champ honeypot

## 🚀 Utilisation

### Dans le Contrôleur (affichage du formulaire)
```php
public function index(): void {
    // 1. Stocker l'horodatage pour validation temporelle
    FormSecurity::storeFormTimestamp('contact_form');
    
    // 2. Générer le token CSRF
    $view = new View('public/contact', [
        'csrfToken' => FormSecurity::generateCsrfToken('contact_form'),
        // ... autres données
    ], 'public');
    
    $view->render();
}
```

### Dans le Contrôleur (traitement du formulaire)
```php
public function send(): void {
    // 1. Validation de sécurité complète
    $securityCheck = FormSecurity::validateFormSubmission('contact_form', $_POST);
    
    if (!$securityCheck['valid']) {
        // Afficher les erreurs
        $_SESSION['form_errors'] = $securityCheck['errors'];
        header('Location: ' . BASE_URL . '/contact');
        exit;
    }
    
    // 2. Continuer avec la validation métier...
    
    // 3. Nettoyage des anciennes données de session
    FormSecurity::cleanOldSessionData();
}
```

### Dans la Vue (formulaire)
```html
<form action="/contact" method="post">
    <!-- Token CSRF -->
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
    
    <!-- Champ Honeypot (invisible) -->
    <input type="text" name="website" value="" 
           style="position: absolute; left: -9999px;" 
           tabindex="-1" autocomplete="off" aria-hidden="true">
    
    <!-- Autres champs du formulaire... -->
</form>
```

### Affichage des Erreurs de Sécurité
```php
<?php if (isset($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])) { 
    foreach ($_SESSION['form_errors'] as $error) { ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php }
    unset($_SESSION['form_errors']);
} ?>
```

## ⚙️ Configuration

Les constantes de configuration se trouvent dans `FormSecurity.php` :

```php
// Rate limiting
private const MAX_SUBMISSIONS_PER_HOUR = 5;    // Max par heure
private const MAX_SUBMISSIONS_PER_DAY = 20;     // Max par jour

// Validation temporelle
private const MIN_FORM_FILL_TIME = 3;           // Secondes minimum

// Autres
private const SESSION_CSRF_KEY = 'csrf_tokens';
private const SESSION_RATE_LIMIT_KEY = 'form_submissions';
```

**Ajustement recommandé selon le contexte :**
- **Site à fort trafic légitime :** Augmenter `MAX_SUBMISSIONS_PER_HOUR`
- **Formulaire complexe :** Augmenter `MIN_FORM_FILL_TIME` (5-10 secondes)
- **Formulaire simple :** Garder `MIN_FORM_FILL_TIME` à 3 secondes

## 🔒 Sécurité Additionnelle (Recommandations)

### Déjà Implémenté ✅
- ✅ CSRF Token
- ✅ Honeypot
- ✅ Rate Limiting
- ✅ Time-Based Validation
- ✅ Validation des données métier (Entity validation)
- ✅ Échappement HTML (`htmlspecialchars`)
- ✅ Requêtes préparées (protection SQL injection)

### À Considérer pour l'Avenir 🔮

1. **Google reCAPTCHA v3**
   - Protection invisible supplémentaire
   - Score de risque par soumission
   - Implémentation simple via API
   ```html
   <script src="https://www.google.com/recaptcha/api.js?render=YOUR_SITE_KEY"></script>
   ```

2. **Système de Blacklist IP**
   - Bloquer automatiquement les IPs abusives
   - Stockage en base de données
   - Expiration automatique après X jours

3. **Détection de VPN/Proxy**
   - Services comme IPQualityScore ou MaxMind
   - Bloquer ou marquer les soumissions suspectes

4. **Analyse Comportementale**
   - Tracking des mouvements de souris
   - Patterns de frappe au clavier
   - JavaScript pour détecter comportement humain

5. **Rate Limiting par Base de Données**
   - Actuellement en session (limité à la session PHP)
   - Migration vers BDD pour tracking persistant
   - Partage entre serveurs (si infrastructure multi-serveurs)

## 📊 Messages d'Erreur Affichés

| Situation | Message Utilisateur |
|-----------|-------------------|
| Token CSRF invalide | "Token de sécurité invalide ou expiré. Veuillez recharger la page." |
| Honeypot rempli | "Soumission invalide détectée." |
| Formulaire trop rapide | "Le formulaire a été soumis trop rapidement. Veuillez prendre le temps de le remplir." |
| Limite horaire atteinte | "Trop de demandes. Veuillez patienter avant de soumettre à nouveau." |
| Limite quotidienne atteinte | "Limite quotidienne atteinte. Veuillez réessayer demain." |

## 🧪 Tests Recommandés

### Tests de Base
1. ✅ Soumission normale → Doit passer
2. ✅ Soumission sans token CSRF → Doit échouer
3. ✅ Soumission avec honeypot rempli → Doit échouer
4. ✅ Soumission rapide (<3s) → Doit échouer
5. ✅ Multiples soumissions (>5/heure) → Doit échouer

### Tests Avancés
- Test avec différentes IPs (proxy, VPN)
- Test de l'expiration du token (>1h)
- Test du nettoyage automatique des sessions
- Test des messages d'erreur dans l'interface

## 🛠️ Maintenance

### Nettoyage Automatique
La méthode `FormSecurity::cleanOldSessionData()` est appelée après chaque soumission réussie pour :
- Supprimer les tokens CSRF expirés (>1h)
- Supprimer les entrées de rate limiting anciennes (>24h)
- Éviter la surcharge mémoire de la session

### Monitoring Recommandé
- Logs des tentatives bloquées (pour détecter attaques)
- Statistiques de rate limiting (pour ajuster limites)
- Analyse des IPs bloquées régulièrement

## 📞 Support

Pour toute question ou amélioration :
1. Vérifier ce document en premier
2. Consulter `app/Core/FormSecurity.php` pour les détails d'implémentation
3. Tester dans un environnement de développement avant production

---

**Version :** 1.0  
**Dernière mise à jour :** Mai 2026  
**Auteur :** Équipe Vadrouille
