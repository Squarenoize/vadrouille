# 📋 Workflow Admin — Gestion des demandes de voyage

> **Vadrouille Travel Planner** — De la demande initiale au voyage confirmé

---

## 🎯 Vue d'ensemble

L'admin accompagne le client à travers **6 phases principales** :

1. **Réception** — Nouvelle demande depuis le site vitrine
2. **Étude** — Analyse de la faisabilité
3. **Création** — Construction du voyage et du devis
4. **Envoi** — Transmission du devis au prospect
5. **Acceptation** — Création du compte client
6. **Suivi** — Accompagnement jusqu'au voyage

---

## 📊 Cycle de vie des statuts

### Table `contact_requests`
```
new          → Demande reçue, non traitée
studying     → Admin analyse la demande  
quoted       → Devis envoyé au prospect
accepted     → Client a accepté le devis
refused      → Demande refusée (par admin ou client)
archived     → Dossier clôturé/archivé
```

### Table `trips`
```
draft        → Voyage en construction
quoted       → Devis envoyé, en attente
accepted     → Client a accepté (compte créé)
ongoing      → Voyage en cours de préparation/réalisation
finished     → Voyage terminé
cancelled    → Voyage annulé
```

---

## 🔄 Workflow détaillé

### **PHASE 1 : Réception de la demande**

#### Déclencheur
Un visiteur remplit le formulaire de contact sur le site vitrine.

#### Actions système
- Création d'une entrée dans `contact_requests`
  - `status='new'`
  - Pas encore de compte utilisateur
  - Pas de voyage créé

#### Vue admin
- Dashboard affiche le compteur de nouvelles demandes
- Liste des demandes avec badge "NEW"

---

### **PHASE 2 : Étude de la demande**

#### Actions admin
**URL** : `/admin/requests/{id}`

1. **Consulter la demande**
   - Nom, email, téléphone du prospect
   - Type de voyage (`trip_type`)
   - Destination souhaitée
   - Dates souhaitées (`desired_start`, `desired_end`)
   - Nombre de voyageurs (`travelers_count`)
   - Budget indicatif
   - Message personnalisé

2. **Décisions possibles**

   **Option A : Passer en étude**
   - Clic sur "Étudier cette demande"
   - `contact_requests.status` → `'studying'`
   - Admin peut ajouter des notes internes

   **Option B : Refuser directement**
   - Clic sur "Refuser"
   - `contact_requests.status` → `'refused'`
   - Email automatique envoyé au prospect (optionnel)
   - **FIN DU PROCESSUS**

---

### **PHASE 3 : Création du voyage (brouillon)**

#### Prérequis
- `contact_requests.status='studying'`

#### Actions admin
**URL** : `/admin/trips/create?from_request={id}`

**Étape 3.1 : Initialiser le voyage**
- Créer une entrée dans `trips`
  ```sql
  user_id       = NULL              -- Pas encore de compte client
  request_id    = {id_demande}
  name          = "Voyage à [destination]"
  destination   = [copié depuis demande]
  start_date    = [dates souhaitées]
  end_date      = [dates souhaitées]
  status        = 'draft'
  ```
- Lier la demande : `contact_requests.converted_to_trip = {id_trip}`

**Étape 3.2 : Construire le planning**
**URL** : `/admin/trips/{id}/edit`

Pour chaque journée/activité, créer des `trip_items` :

| Champ | Description | Exemple |
|-------|-------------|---------|
| `trip_id` | ID du voyage | 42 |
| `category` | Type d'élément | `'TRANSPORT'`, `'HÉBERGEMENT'`, `'ACTIVITÉ'`, `'RESTAURATION'`, `'LIBRE'` |
| `title` | Nom court | "Vol Paris-Rome" |
| `start_datetime` | Début | `2026-06-15 08:00:00` |
| `end_datetime` | Fin | `2026-06-15 10:30:00` |
| `description` | Détails | "Vol direct Air France AF1234" |
| `requires_booking` | Réservation nécessaire ? | `1` (oui) ou `0` (non) |
| `external_link` | Lien réservation | `https://airfrance.com/...` |
| `indicative_price` | Prix estimé | `250.00` |
| `sort_order` | Ordre d'affichage | Auto-incrémenté |

**Exemple de planning :**
```
Jour 1 - 08:00-10:30 : Vol Paris-Rome (TRANSPORT) - 250€
Jour 1 - 12:00-18:00 : Check-in Hôtel Trevi (HÉBERGEMENT) - 0€
Jour 1 - 14:00-17:00 : Visite du Colisée (ACTIVITÉ) - 35€
Jour 1 - 19:30-21:30 : Restaurant Da Paolo (RESTAURATION) - 60€
Jour 2 - 09:00-12:00 : Vatican & Chapelle Sixtine (ACTIVITÉ) - 45€
...
```

**Étape 3.3 : Finaliser le devis**
- Calculer le `total_price` (somme des `trip_items.indicative_price`)
- Ajouter une `description` générale du voyage
- Ajouter des `admin_note` (notes internes, non visibles par le client)
- Status reste en `'draft'`

---

### **PHASE 4 : Envoi du devis**

#### Actions admin
**URL** : `/admin/trips/{id}` → Bouton "Envoyer le devis"

#### Actions système
1. **Générer le token d'accès**
   ```sql
   UPDATE trips SET
       status = 'quoted',
       quote_token = [token_unique_64_chars],
       quote_sent_at = NOW()
   WHERE id = {id};
   ```

2. **Mettre à jour la demande**
   ```sql
   UPDATE contact_requests SET
       status = 'quoted'
   WHERE id = {request_id};
   ```

3. **Envoyer l'email au prospect**
   - **À** : `contact_requests.email`
   - **Objet** : "Votre devis personnalisé — Vadrouille Travel"
   - **Contenu** :
     - Message personnalisé de l'admin
     - Lien du devis : `https://vadrouille.com/quote/{quote_token}`
     - Validité du devis (ex: 15 jours)
     - Pas encore d'identifiants (pas de compte créé)

---

### **PHASE 5 : Consultation et acceptation du devis**

#### Côté prospect (sans compte)

**URL** : `/quote/{quote_token}`

**Le prospect voit :**
- Nom du voyage
- Destination, dates, durée
- Planning complet (tous les `trip_items`)
- Prix total
- Boutons : **[J'accepte]** ou **[Je refuse]**

#### Si REFUS
- `trips.status` → `'cancelled'`
- `contact_requests.status` → `'refused'`
- Email de confirmation envoyé
- **FIN DU PROCESSUS**

#### Si ACCEPTATION
**Actions système automatiques :**

1. **Créer le compte client**
   ```sql
   INSERT INTO users (
       email,
       password_hash,
       first_name,
       last_name,
       phone,
       role,
       must_change_pwd
   ) VALUES (
       [email_from_request],
       [password_temporaire_hashé],
       [first_name],
       [last_name],
       [phone],
       'traveler',
       1  -- Doit changer son mot de passe
   );
   ```

2. **Lier le voyage au compte**
   ```sql
   UPDATE trips SET
       user_id = [nouvel_id_user],
       status = 'accepted',
       accepted_at = NOW()
   WHERE id = {id};
   ```

3. **Mettre à jour la demande**
   ```sql
   UPDATE contact_requests SET
       status = 'accepted'
   WHERE id = {request_id};
   ```

4. **Créer les statuts de réservation**
   Pour chaque `trip_item` avec `requires_booking=1` :
   ```sql
   INSERT INTO booking_status (
       trip_item_id,
       user_id,
       status
   ) VALUES (
       [id_item],
       [id_user],
       'to_book'
   );
   ```

5. **Envoyer l'email de confirmation**
   - **À** : email du client
   - **Objet** : "Bienvenue ! Votre voyage est confirmé"
   - **Contenu** :
     - Confirmation de l'acceptation
     - Identifiants de connexion :
       - Email : `{email}`
       - Mot de passe temporaire : `{password}`
     - Lien de connexion : `https://vadrouille.com/login`
     - Instructions pour changer le mot de passe

---

### **PHASE 6 : Suivi du voyage**

#### Côté client (espace connecté)

**URL** : `/traveler/dashboard`

**Le client peut :**
- Voir le planning complet
- Pour chaque élément à réserver (`requires_booking=1`) :
  - Voir le statut : `to_book`, `in_progress`, `booked`
  - Marquer comme "Réservé"
  - Ajouter une référence de confirmation
  - Ajouter des notes personnelles
- Envoyer des messages à l'admin (table `messages`)
- Télécharger des documents (si implémenté)

#### Côté admin

**URLs** :
- `/admin/trips/{id}` — Vue détaillée
- `/admin/trips/{id}/messages` — Messagerie

**L'admin peut :**
- Voir l'avancement des réservations
  - Combien d'items réservés / total
  - Références de confirmation
- Répondre aux messages du client
- Modifier le voyage si besoin (ajout/suppression d'items)
- Changer le statut :
  - `'accepted'` → `'ongoing'` (voyage en préparation)
  - `'ongoing'` → `'finished'` (voyage terminé)
  - `'accepted'` → `'cancelled'` (annulation)

#### Gestion des statuts de réservation

| Status | Signification | Action client |
|--------|---------------|---------------|
| `to_book` | À réserver | Cliquer sur le lien, réserver |
| `in_progress` | En cours | Réservation commencée |
| `booked` | Réservé | Ajouter référence de confirmation |
| `to_verify` | À vérifier | Admin doit valider |
| `not_applicable` | N/A | Pas de réservation nécessaire |
| `cancelled` | Annulé | Item annulé |

---

## 🔐 Sécurité & Permissions

### Accès public (sans compte)
- ✅ Consulter le devis via `quote_token`
- ✅ Accepter/refuser le devis

### Accès `role='traveler'`
- ✅ Dashboard avec ses voyages
- ✅ Planning détaillé
- ✅ Gestion des réservations
- ✅ Messagerie avec l'admin
- ❌ Voir les demandes d'autres clients
- ❌ Modifier le voyage (lecture seule)

### Accès `role='admin'`
- ✅ Toutes les demandes (`contact_requests`)
- ✅ Tous les voyages (`trips`)
- ✅ Créer/modifier/supprimer des voyages
- ✅ Ajouter/modifier des items
- ✅ Voir tous les messages
- ✅ Accès aux statistiques

---

## 📧 Templates d'emails

### 1. Envoi du devis
```
Objet : Votre devis personnalisé — Voyage à [destination]

Bonjour [prénom],

Nous avons le plaisir de vous proposer un voyage sur-mesure 
correspondant à votre demande.

📍 Destination : [destination]
📅 Dates : du [start] au [end]
💰 Prix total : [total_price]€

👉 Consultez votre devis détaillé : 
   https://vadrouille.com/quote/[token]

Ce devis est valable 15 jours.

À bientôt,
L'équipe Vadrouille
```

### 2. Confirmation d'acceptation
```
Objet : 🎉 Bienvenue ! Votre voyage est confirmé

Bonjour [prénom],

Merci d'avoir accepté notre devis ! Nous sommes ravis de 
vous accompagner dans votre voyage à [destination].

🔑 Vos identifiants de connexion :
   Email : [email]
   Mot de passe temporaire : [password]

👉 Connectez-vous : https://vadrouille.com/login

Une fois connecté, vous pourrez :
- Consulter votre planning détaillé
- Gérer vos réservations
- Échanger avec nous

⚠️ Pensez à changer votre mot de passe lors de votre 
première connexion.

Bon voyage !
L'équipe Vadrouille
```

---

## 🗄️ Tables impliquées

| Table | Rôle |
|-------|------|
| `contact_requests` | Demandes des prospects (avant compte) |
| `users` | Comptes admin et clients (après acceptation) |
| `trips` | Voyages (devis + confirmés) |
| `trip_items` | Éléments du planning (chronologiques) |
| `booking_status` | Statut de réservation par item |
| `messages` | Chat client ↔ admin |

---

## ✅ Checklist Admin

### Nouvelle demande reçue
- [ ] Lire la demande complète
- [ ] Vérifier la faisabilité (dates, destination, budget)
- [ ] Passer en `'studying'` ou refuser

### Création du voyage
- [ ] Créer le trip en brouillon
- [ ] Ajouter tous les items (transport, hébergement, activités)
- [ ] Vérifier la cohérence des dates
- [ ] Calculer le prix total
- [ ] Relire l'ensemble du devis

### Envoi du devis
- [ ] Vérifier que tout est complet
- [ ] Cliquer sur "Envoyer le devis"
- [ ] Vérifier que l'email est bien parti

### Après acceptation
- [ ] Vérifier la création du compte client
- [ ] Envoyer un message de bienvenue
- [ ] Suivre l'avancement des réservations
- [ ] Répondre aux questions

### Voyage terminé
- [ ] Passer le statut en `'finished'`
- [ ] Demander un témoignage (optionnel)
- [ ] Archiver la demande initiale

---

## 🎨 Suggestions d'interface

### Dashboard Admin
```
┌─────────────────────────────────────────┐
│ 📊 Dashboard Admin                      │
├─────────────────────────────────────────┤
│ Nouvelles demandes        [12]          │
│ Devis en attente          [5]           │
│ Voyages acceptés          [8]           │
│ Voyages en cours          [3]           │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ 📝 Demandes récentes                    │
├─────────────────────────────────────────┤
│ [NEW] Dupont - Rome - 15/06 → 22/06     │
│ [STUDYING] Martin - Japon - 01/08       │
│ [QUOTED] Durand - Islande - 10/07       │
└─────────────────────────────────────────┘
```

### Liste des demandes
```
┌──────────────────────────────────────────────────────────────┐
│ Filtre : [Toutes ▼] [new] [studying] [quoted]               │
├──────────────────────────────────────────────────────────────┤
│ ID  | Client    | Destination | Dates      | Status         │
├──────────────────────────────────────────────────────────────┤
│ 156 | Dupont    | Rome        | 15-22/06   | [NEW]     [>]  │
│ 155 | Martin    | Japon       | 01-15/08   | [STUDYING][>]  │
│ 154 | Durand    | Islande     | 10-17/07   | [QUOTED]  [>]  │
└──────────────────────────────────────────────────────────────┘
```

---

**Dernière mise à jour** : 20 avril 2026  
**Version** : 1.0
