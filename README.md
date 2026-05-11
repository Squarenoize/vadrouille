# ✈️ Travel Planner

> Application web sur mesure pour un **travel planner indépendant** — conception de voyages, gestion de devis et suivi des réservations clients.

---

## Sommaire

- [Présentation](#présentation)
- [Fonctionnalités](#fonctionnalités)
- [Stack technique](#stack-technique)
- [Architecture](#architecture)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Structure du projet](#structure-du-projet)
- [Base de données](#base-de-données)
- [Rôles utilisateurs](#rôles-utilisateurs)
- [Documentation](#documentation)
- [Sécurité](#sécurité)
- [Licence](#licence)

---

## Présentation

**Travel Planner** est une application web MVC développée en PHP orienté objet. Elle permet à un travel planner indépendant de concevoir des voyages sur mesure, de soumettre des devis à ses clients, et à ces derniers de gérer leurs réservations depuis un espace personnel dédié.

```
Visiteur  →  Découvre le site vitrine, soumet une demande
Admin     →  Conçoit le voyage, envoie le devis, suit les clients
Client    →  Accepte le devis, gère ses réservations, communique avec l'admin
```

---

## Fonctionnalités

### Site vitrine (public)
- Page d'accueil avec présentation du service
- Section **Comment ça marche** (processus en 4 étapes)
- Section **Voyages** des voyages réalisés avec témoignages clients
- Formulaire de **premier contact** (demande de voyage)
- Pages À propos, Mentions légales, Politique de confidentialité

### Espace Admin
- Tableau de bord avec KPI (demandes, voyages, messages)
- Gestion des **demandes** de contact (statuts, conversion en voyage)
- **Conception de voyages** : timeline de Trip Items séquentiels
  - Catégories : `TRANSPORT` · `HÉBERGEMENT` · `ACTIVITÉ` · `RESTAURATION` · `LIBRE`
  - Chaque item : dates de début/fin obligatoires, réservation requise ou non
- Envoi et suivi des **devis** (lien sécurisé par token)
- Gestion des **clients** et de leurs comptes
- Gestion du **Voyages public** et modération des témoignages
- Dépôt de **documents PDF** sur les voyages
- **Messagerie** directe avec chaque client

### Espace Client
- Vue **timeline** chronologique du voyage
- Suivi des **réservations** par voyage (statuts, références de confirmation, notes)
- Téléchargement des **documents** du voyage
- **Messagerie** directe avec l'admin
- Gestion du **profil** et du mot de passe

---

## Stack technique

| Composant | Technologie |
|-----------|-------------|
| Langage back-end | PHP 8.2+ |
| Architecture | MVC maison (sans framework) |
| Base de données | MySQL 8.0+ via PDO |
| Front-end | HTML5, CSS3, JavaScript vanilla |
| Emails | PHPMailer |
| Serveur | Apache 2.4+ |

---

## Architecture

```
public/index.php (Front Controller)
        │
        ▼
    Router  ──── middlewares (auth:admin / auth:user / guest)
        │
        ▼
  Controller  ──── Validator / Session / Mailer / FileUploader
        │
        ▼
    Model  ──── Database (PDO Singleton)
        │
        ▼
     View  ──── Layout + Partials
```

Le pattern **MVC strict** est appliqué :
- Aucune requête SQL dans les contrôleurs ou les vues
- Aucune logique métier dans les vues
- Toutes les entrées utilisateur validées côté serveur avant traitement

---

## Prérequis

- PHP **8.2+** avec les extensions : `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`
- MySQL **8.0+**
- Serveur web Apache (mod_rewrite activé) ou Nginx
- Composer (pour PHPMailer)

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/Squarenoize/vadrouille.git
cd travel-planner
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
```

Éditer `.env` avec vos valeurs (voir section [Configuration](#configuration)).

### 4. Créer la base de données

```bash
mysql -u root -p -e "CREATE DATABASE travel_planner CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p travel_planner < database/travel_planner_schema.sql
```

### 5. Configurer le serveur web

**Apache** — activer `mod_rewrite` et pointer le `DocumentRoot` vers `public/` :

```apache
<VirtualHost *:80>
    ServerName travel-planner.local
    DocumentRoot /var/www/travel-planner/public

    <Directory /var/www/travel-planner/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 6. Permissions

```bash
chmod -R 755 storage/
chmod -R 755 public/assets/
```

### 7. Connexion initiale

| Champ | Valeur |
|-------|--------|
| URL | `http://localhost/login` |
| Email | `admin@travel-planner.fr` |
| Mot de passe | défini dans `.env` → `ADMIN_DEFAULT_PASSWORD` |

> ⚠️ Le mot de passe est à changer obligatoirement à la première connexion (`must_change_pwd = 1`).

---

## Configuration

Copier `.env.example` en `.env` et renseigner les variables :

```dotenv
# Application
APP_NAME="Travel Planner"
APP_URL=http://travel-planner.local
APP_ENV=development          # development | production
APP_DEBUG=true               # false en production

# Base de données
DB_HOST=localhost
DB_NAME=travel_planner
DB_USER=root
DB_PASS=secret

# Sessions
SESSION_NAME=tp_session
SESSION_LIFETIME=7200        # secondes

# Email (SMTP)
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USER=votre_user
MAIL_PASS=votre_pass
MAIL_FROM=noreply@travel-planner.fr
MAIL_FROM_NAME="Travel Planner"
MAIL_ADMIN=admin@travel-planner.fr

# Stockage fichiers (chemin absolu, hors public/)
STORAGE_PATH=/var/www/travel-planner/storage/uploads

# Compte admin initial
ADMIN_DEFAULT_PASSWORD=ChangeMe!2025
```

---

## Structure du projet

```
travel-planner/
├── app/
│   ├── Controllers/
│   │   ├── AdminController.php
│   │   ├── TravelerController.php
│   │   ├── HomeController.php
│   │   ├── MessageController.php
│   │   ├── ContactController.php
│   │   ├── AuthController.php
│   │   └── TripsController.php
│   ├── Models/
│   │   ├── UsersModel.php
│   │   ├── TrispModel.php
│   │   ├── TripItemModel.php
│   │   ├── BookingStatusModel.php
│   │   ├── MessageModel.php
│   │   ├── TripsPostModel.php
│   │   ├── TestimonialModel.php
│   │   ├── ContactRequestModel.php
│   │   └── TripDocumentModel.php
|   |── Entities/
│   │   ├── ContactRequest.php
│   │   ├── Message.php
│   │   ├── Trip.php
│   │   └── User.php
│   ├── Views/
|   |   ├── components/shared/chat.php
│   │   ├── layouts/            # public.php, traveler.php, admin.php, auth
│   │   ├── pages/
|   |   |      ├── admin/      # chats.php, dashboard.php, newTrip.php...
|   |   |      ├── auth/        # login.php, change_password.php
|   |   |      ├── public/      # home.php, about.php, contact.php...
|   |   |      └── traveler/    # dashboard.php, trips.php, trip_details.php...
│   │   └── schemas/             # Templates emails transactionnels
│   └── Core/
│       ├── Database.php        # Singleton PDO
│       ├── Router.php
│       ├── Auth.php
│       ├── SeoHelper.php
│       ├── DataVerification.php
│       ├── View.php
│       ├── Mailer.php
│       ├── FileUploader.php
│       └── Env.php
├── config/
│   ├── config.php              # Constantes globales
│   └── autoloader.php              
├── database/
│   └── travel_planner_schema.sql
├── public/                     # Document root
│   ├── index.php               # Front controller
│   ├── .htaccess
│   └── assets/
│       ├── css/
│       ├── js/
│       └── img/
├── storage/
│   └── uploads/                # Fichiers uploadés (hors document root)
├── vendor/                     # Composer
├── .env                        # Variables d'environnement (non versionné)
├── .env.example
├── .gitignore
├── composer.json
└── README.md
```

---

## Base de données

Le schéma complet est dans `database/travel_planner_schema.sql`.

### Tables

| Table | Description |
|-------|-------------|
| `users` | Comptes admin et clients |
| `contact_requests` | Demandes de voyage soumises via le formulaire |
| `trips` | Voyages conçus par l'admin |
| `trip_items` | Éléments séquentiels d'un voyage (transport, hébergement…) |
| `booking_status` | Statut de réservation par item et par client |
| `messages` | Messagerie client ↔ admin |
| `blog_posts` | Articles du blog vitrine |
| `testimonials` | Témoignages clients (modérés avant publication) |
| `trip_documents` | Fichiers PDF déposés par l'admin sur un voyage |

### Statuts d'un voyage (`trips.status`)

```
draft → quoted → accepted → ongoing → finished
                ↘ cancelled
```

### Statuts d'une réservation (`booking_status.status`)

```
to_book → in_progress → booked
        ↘ cancelled
not_applicable   (items sans réservation requise)
to_verify        (item modifié par l'admin après réservation)
```

---

## Rôles utilisateurs

### `admin`
- Accès complet au back-office (`/admin/*`)
- Seul à pouvoir créer/modifier/supprimer des voyages et leurs items
- Gère les comptes clients, le blog, les documents

### `traveler` (client)
- Accès à son espace personnel uniquement (`/traveler/*`)
- Consulte son voyage, met à jour ses statuts de réservation
- Communique avec l'admin via la messagerie
- Ne peut pas modifier le contenu du voyage

> Les comptes clients ne sont **pas créés par auto-inscription**. Ils sont générés par l'admin lors de l'acceptation d'un devis.

---

## Documentation

| Fichier | Contenu |
|---------|---------|
| `docs/TravelPlanner_Documentation_Technique.docx` | Architecture complète, fonctionnalités, BDD, routes, workflow métier |
| `docs/travel_planner_schema.sql` | Schéma MySQL commenté, prêt à importer |

---

## Sécurité

- **Injections SQL** — PDO avec requêtes préparées exclusivement
- **XSS** — `htmlspecialchars()` sur toutes les sorties, header `Content-Security-Policy`
- **CSRF** — token synchronisé sur chaque formulaire POST
- **Mots de passe** — `password_hash()` bcrypt coût 12, jamais stockés en clair
- **Sessions** — `httponly`, `secure`, `strict_mode`, régénération d'ID après login
- **Uploads** — vérification MIME réelle (`finfo`), whitelist extensions, stockage hors `public/`
- **Brute force** — verrouillage temporaire après 5 tentatives échouées
- **Fichiers servis** — les documents sont servis par PHP après vérification des droits (jamais accessibles directement)

---

## Licence

Projet privé — tous droits réservés.
