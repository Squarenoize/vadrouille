# Tests de Sécurité - Formulaire de Contact

Ce document liste tous les tests à effectuer pour vérifier que les protections anti-bots fonctionnent correctement.

## ✅ Tests Obligatoires Avant Production

### Test 1 : Soumission Normale (Doit Réussir ✅)
**Objectif :** Vérifier qu'un utilisateur légitime peut soumettre le formulaire

**Étapes :**
1. Ouvrir la page de contact
2. Remplir tous les champs requis normalement
3. Attendre au moins 5 secondes avant de soumettre
4. Cliquer sur "Envoyer la demande"

**Résultat attendu :**
- ✅ Message de succès : "Votre demande a bien été envoyée !"
- ✅ Redirection vers la page de contact
- ✅ Données sauvegardées en base de données

---

### Test 2 : Token CSRF Invalide (Doit Échouer ❌)
**Objectif :** Vérifier que les soumissions sans token CSRF valide sont bloquées

**Méthode 1 - Via DevTools :**
1. Ouvrir le formulaire
2. Ouvrir les DevTools (F12) → Elements
3. Trouver le champ `<input name="csrf_token">`
4. Modifier sa valeur en `"invalid_token"`
5. Soumettre le formulaire

**Méthode 2 - Via Postman/cURL :**
```bash
curl -X POST https://votre-site.com/contact \
  -d "first_name=Test" \
  -d "last_name=Test" \
  -d "email=test@test.com" \
  -d "trip_type=adventure" \
  -d "destination=france" \
  -d "budget=1000" \
  -d "travelers_adult_count=1" \
  -d "travelers_child_count=0" \
  -d "message=Test" \
  -d "conditions_accepted=1" \
  -d "csrf_token=invalid_token"
```

**Résultat attendu :**
- ❌ Message d'erreur : "Token de sécurité invalide ou expiré. Veuillez recharger la page."
- ❌ Formulaire non soumis
- ❌ Aucune donnée en base

---

### Test 3 : Honeypot Rempli (Doit Échouer ❌)
**Objectif :** Vérifier que le champ honeypot bloque les bots

**Étapes :**
1. Ouvrir le formulaire
2. Ouvrir DevTools → Elements
3. Trouver le champ `<input name="website">`
4. Retirer le style `style="position: absolute; left: -9999px;"`
5. Remplir le champ avec n'importe quoi (ex: "http://spam.com")
6. Remplir les autres champs normalement
7. Soumettre

**Résultat attendu :**
- ❌ Message d'erreur : "Soumission invalide détectée."
- ❌ Formulaire bloqué
- ❌ Aucune donnée en base

---

### Test 4 : Soumission Trop Rapide (Doit Échouer ❌)
**Objectif :** Vérifier la protection temporelle contre les bots ultra-rapides

**Méthode 1 - Manuelle :**
1. Ouvrir le formulaire
2. Remplir TOUS les champs en moins de 3 secondes
3. Soumettre immédiatement

**Méthode 2 - Automatique (JavaScript Console) :**
```javascript
// Dans la console du navigateur
document.getElementById('first_name').value = 'Test';
document.getElementById('last_name').value = 'Test';
document.getElementById('email').value = 'test@test.com';
document.getElementById('adventure').checked = true;
document.getElementById('destination').value = 'france';
document.getElementById('budget').value = '1000';
document.getElementById('travelers_adult_count').value = '1';
document.getElementById('travelers_child_count').value = '0';
document.getElementById('message').value = 'Test message';
document.getElementById('conditions_accepted').checked = true;
document.querySelector('form').submit();
```

**Résultat attendu :**
- ❌ Message d'erreur : "Le formulaire a été soumis trop rapidement. Veuillez prendre le temps de le remplir."
- ❌ Formulaire bloqué

---

### Test 5 : Rate Limiting - Limite Horaire (Doit Échouer ❌)
**Objectif :** Vérifier la limitation à 5 soumissions par heure

**Étapes :**
1. Soumettre le formulaire avec succès (1ère fois)
2. Recharger et soumettre à nouveau (2ème fois)
3. Répéter jusqu'à 5 soumissions
4. Tenter une 6ème soumission dans l'heure

**Résultat attendu :**
- ✅ Les 5 premières soumissions passent
- ❌ La 6ème soumission est bloquée
- ❌ Message : "Trop de demandes. Veuillez patienter avant de soumettre à nouveau."

**Note :** Attendre 1 heure pour que les compteurs se réinitialisent

---

### Test 6 : Rate Limiting - Limite Quotidienne (Doit Échouer ❌)
**Objectif :** Vérifier la limitation à 20 soumissions par jour

**Étapes :**
1. Soumettre le formulaire 20 fois sur une journée (espacement > 10 minutes entre chaque)
2. Tenter une 21ème soumission

**Résultat attendu :**
- ❌ Message : "Limite quotidienne atteinte. Veuillez réessayer demain."

**Note :** Test long, peut être fait manuellement ou via script

---

### Test 7 : Expiration du Token CSRF (Doit Échouer ❌)
**Objectif :** Vérifier que les tokens expirent après 1 heure

**Étapes :**
1. Ouvrir le formulaire et le laisser ouvert
2. Attendre 1 heure et 5 minutes
3. Remplir et soumettre le formulaire

**Résultat attendu :**
- ❌ Message d'erreur : "Token de sécurité invalide ou expiré. Veuillez recharger la page."

**Alternative Rapide (DevTools) :**
```javascript
// Dans la console, simuler l'expiration
// 1. Récupérer la session actuelle (nécessite l'accès serveur)
// 2. Modifier manuellement le timestamp du token en session
```

---

### Test 8 : Validation Métier (Doit Échouer ❌)
**Objectif :** Vérifier que la validation métier fonctionne après la validation de sécurité

**Étapes :**
1. Ouvrir le formulaire
2. Remplir avec un email invalide (ex: "notanemail")
3. Soumettre après 5 secondes

**Résultat attendu :**
- ❌ Message d'erreur de validation métier
- ✅ Le token CSRF est régénéré
- ✅ Le formulaire reste pré-rempli

---

### Test 9 : Multiple Sessions/IPs (Doit Réussir ✅)
**Objectif :** Vérifier que le rate limiting est bien par IP

**Étapes :**
1. Soumettre 5 fois depuis l'ordinateur A (IP 1)
2. Soumettre depuis l'ordinateur B (IP 2)

**Résultat attendu :**
- ✅ L'ordinateur B peut soumettre même si A est bloqué
- ✅ Les compteurs sont indépendants par IP

---

### Test 10 : JavaScript Désactivé (Comportement Attendu)
**Objectif :** Vérifier que le formulaire fonctionne sans JavaScript

**Étapes :**
1. Désactiver JavaScript dans le navigateur
2. Ouvrir le formulaire
3. Remplir et soumettre

**Résultat attendu :**
- ✅ Formulaire toujours fonctionnel (dégradation gracieuse)
- ✅ Toutes les protections serveur actives
- ✅ Pas de reCAPTCHA (si implémenté)

---

## 🔬 Tests Avancés (Optionnels)

### Test 11 : Attaque par Script Automatisé
**Objectif :** Simuler une attaque réelle

**Script Python d'exemple :**
```python
import requests
import time

url = "https://votre-site.com/contact"
payload = {
    "first_name": "Spam",
    "last_name": "Bot",
    "email": "spam@bot.com",
    "trip_type": "adventure",
    "destination": "france",
    "budget": "1000",
    "travelers_adult_count": "1",
    "travelers_child_count": "0",
    "message": "Automated spam message",
    "conditions_accepted": "1",
    "csrf_token": "fake_token"
}

# Tenter 10 soumissions rapides
for i in range(10):
    response = requests.post(url, data=payload)
    print(f"Tentative {i+1}: {response.status_code}")
    time.sleep(0.5)  # 0.5 seconde entre chaque
```

**Résultat attendu :**
- ❌ Toutes les tentatives bloquées
- ❌ Pas de données en base

---

### Test 12 : Modification de l'IP (Proxy/VPN)
**Objectif :** Vérifier la détection d'IP avec proxy

**Étapes :**
1. Soumettre 5 fois avec IP normale
2. Activer un VPN/Proxy
3. Tenter une nouvelle soumission

**Résultat attendu :**
- ✅ Nouvelle IP détectée, compteur réinitialisé
- ✅ Peut soumettre à nouveau

---

## 📊 Tableau Récapitulatif des Tests

| # | Test | Protection Testée | Résultat Attendu | Priorité |
|---|------|-------------------|-----------------|----------|
| 1 | Soumission normale | Aucune | ✅ Succès | 🔴 Haute |
| 2 | Token CSRF invalide | CSRF | ❌ Bloqué | 🔴 Haute |
| 3 | Honeypot rempli | Honeypot | ❌ Bloqué | 🔴 Haute |
| 4 | Soumission rapide | Time-based | ❌ Bloqué | 🔴 Haute |
| 5 | Rate limiting horaire | Rate limiting | ❌ Bloqué | 🟡 Moyenne |
| 6 | Rate limiting quotidien | Rate limiting | ❌ Bloqué | 🟢 Basse |
| 7 | Expiration token | CSRF | ❌ Bloqué | 🟡 Moyenne |
| 8 | Validation métier | Validation | ❌ Bloqué | 🔴 Haute |
| 9 | Multiple IPs | Rate limiting | ✅ Succès | 🟡 Moyenne |
| 10 | Sans JavaScript | Dégradation | ✅ Succès | 🟢 Basse |

## 🛠️ Outils de Test Recommandés

### 1. **Browser DevTools** (Intégré)
- Modification HTML/CSS en direct
- Console JavaScript
- Network tab pour voir les requêtes

### 2. **Postman** (Gratuit)
- Tests d'API REST
- Collections de tests
- Environnements multiples

### 3. **cURL** (Ligne de commande)
```bash
# Test basique
curl -X POST https://votre-site.com/contact \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "first_name=Test&last_name=Test&email=test@test.com"
```

### 4. **Python Requests** (Scripts automatisés)
Voir exemple dans Test 11

### 5. **JMeter** (Tests de charge)
- Simuler des milliers de requêtes
- Tester les limites du rate limiting

---

## 📝 Checklist de Validation Finale

Avant la mise en production :

- [ ] ✅ Test 1 (Soumission normale) réussi
- [ ] ❌ Test 2 (CSRF invalide) bloqué
- [ ] ❌ Test 3 (Honeypot) bloqué
- [ ] ❌ Test 4 (Soumission rapide) bloqué
- [ ] ❌ Test 5 (Rate limiting horaire) bloqué
- [ ] ✅ Test 8 (Validation métier) fonctionne
- [ ] ✅ Messages d'erreur clairs et en français
- [ ] ✅ FormSecurity.php chargé dans autoloader
- [ ] ✅ Token CSRF présent dans le formulaire
- [ ] ✅ Champ honeypot invisible
- [ ] ✅ Pas d'erreurs PHP dans les logs
- [ ] ✅ Pas d'erreurs JavaScript dans la console
- [ ] ✅ Politique de confidentialité à jour (si reCAPTCHA)

---

## 🐛 Debugging

### En cas d'échec des tests :

**1. Vérifier les logs PHP**
```bash
# Localisation typique des logs
tail -f /var/log/php/error.log
# ou
tail -f C:\wamp64\logs\php_error.log
```

**2. Activer le mode debug**
```php
// Dans config/config.php
define('DEBUG_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**3. Ajouter des logs de debug**
```php
// Dans FormSecurity.php
error_log("CSRF Verification - Form: $formName, Token: $submittedToken");
error_log("Rate Limit - IP: {$ip}, Count: " . count($recentSubmissions));
```

**4. Vérifier la session PHP**
```php
// Afficher le contenu de la session
var_dump($_SESSION);
```

---

## 📞 Support

Si des tests échouent de manière inattendue :

1. Vérifier que `FormSecurity.php` est bien chargé par l'autoloader
2. Confirmer que les sessions PHP sont activées
3. Vérifier les permissions des fichiers
4. Consulter la documentation complète dans `SECURITE_FORMULAIRE.md`

---

**Date de création :** Mai 2026  
**Version :** 1.0
