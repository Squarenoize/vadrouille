# Travel Planner — Documentation des Classes PHP

> Architecture MVC maison · PHP 8.2+ · PSR-4 · PSR-12  
> Namespace racine : `App\`

---

## Sommaire

- [Core — Infrastructure](#core--infrastructure)
  - [Database](#database)
  - [Router](#router)
  - [Request](#request)
  - [Session](#session)
  - [BaseController](#basecontroller)
  - [BaseModel](#basemodel)
  - [Validator](#validator)
  - [Mailer](#mailer)
  - [FileUploader](#fileuploader)
  - [Env](#env)
- [Modèles](#modèles)
  - [UserModel](#usermodel)
  - [ContactRequestModel](#contactrequestmodel)
  - [TripsModel](#tripsmodel)
  - [TripItemModel](#tripitemmodel)
  - [BookingStatusModel](#bookingstatusmodel)
  - [MessageModel](#messagemodel)
  - [BlogPostModel](#blogpostmodel)
  - [TestimonialModel](#testimonialmodel)
  - [TripDocumentModel](#tripdocumentmodel)
- [Contrôleurs publics (Vitrine)](#contrôleurs-publics-vitrine)
  - [HomeController](#homecontroller)
  - [BlogController](#blogcontroller)
  - [ContactController](#contactcontroller)
  - [AuthController](#authcontroller)
- [Contrôleurs Admin](#contrôleurs-admin)
  - [AdminDashboardController](#admindashboardcontroller)
  - [AdminRequestController](#adminrequestcontroller)
  - [AdminTripController](#admintripcontroller)
  - [AdminTripItemController](#admintripitemcontroller)
  - [AdminClientController](#adminclientcontroller)
  - [AdminBlogController](#adminblogcontroller)
- [Contrôleurs Client](#contrôleurs-client)
  - [ClientDashboardController](#clientdashboardcontroller)
  - [ClientTripController](#clienttripcontroller)
  - [BookingController](#bookingcontroller)
  - [MessageController](#messagecontroller)
  - [DocumentController](#documentcontroller)
  - [ProfileController](#profilecontroller)
- [Contrôleur API](#contrôleur-api)
  - [ApiController](#apicontroller)

---

## Core — Infrastructure

### Database

**Namespace** : `App\Core\Database`  
**Pattern** : Singleton  
**Rôle** : Fournit une connexion PDO unique et partagée dans toute l'application. Toutes les requêtes SQL passent par cette classe — jamais de PDO instancié directement dans un modèle ou un contrôleur.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `private static` | `$instance: ?self` | Instance unique (Singleton) |
| `private` | `$pdo: PDO` | Objet PDO encapsulé |
| `private` | `__construct()` | Crée la connexion PDO avec les paramètres de `Env`. Active `ERRMODE_EXCEPTION`, `FETCH_ASSOC`, `EMULATE_PREPARES = false`. |
| `public static` | `getInstance(): self` | Retourne (ou crée) l'instance unique. |
| `public` | `query(string $sql, array $params = []): PDOStatement` | Prépare et exécute une requête. Retourne le `PDOStatement`. |
| `public` | `fetchOne(string $sql, array $params = []): array\|false` | Exécute et retourne la première ligne. |
| `public` | `fetchAll(string $sql, array $params = []): array` | Exécute et retourne toutes les lignes. |
| `public` | `insert(string $table, array $data): int` | Construit et exécute un INSERT. Retourne le `lastInsertId()`. |
| `public` | `update(string $table, array $data, string $where, array $whereParams): int` | Construit et exécute un UPDATE. Retourne le nombre de lignes affectées. |
| `public` | `delete(string $table, string $where, array $whereParams): int` | Construit et exécute un DELETE. |
| `public` | `beginTransaction(): void` | Démarre une transaction. |
| `public` | `commit(): void` | Valide la transaction. |
| `public` | `rollBack(): void` | Annule la transaction. |

---

### Router

**Namespace** : `App\Core\Router`  
**Rôle** : Analyse l'URI et la méthode HTTP de la requête entrante, trouve la route correspondante et dispatche vers le bon contrôleur. Gère les middlewares d'authentification.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `private` | `$routes: array` | Collection de routes enregistrées, indexées par méthode HTTP. |
| `public` | `get(string $uri, string $action, array $middlewares = []): void` | Enregistre une route GET. `$action` = `'ControllerClass@method'`. |
| `public` | `post(string $uri, string $action, array $middlewares = []): void` | Enregistre une route POST. |
| `public` | `put(string $uri, string $action, array $middlewares = []): void` | Enregistre une route PUT (ou POST avec `_method=PUT`). |
| `public` | `delete(string $uri, string $action, array $middlewares = []): void` | Enregistre une route DELETE. |
| `public` | `dispatch(Request $request): void` | Compare l'URI courante aux routes enregistrées (regex), extrait les paramètres dynamiques, exécute les middlewares, puis appelle le contrôleur. |
| `private` | `resolve(string $uri, string $method): array\|null` | Retourne la route correspondante ou `null` (→ 404). |
| `private` | `runMiddlewares(array $middlewares): void` | Exécute chaque middleware. S'arrête et redirige si l'un échoue. |
| `private` | `abort(int $code): void` | Charge la vue d'erreur correspondante (404, 403, 500) et stoppe l'exécution. |

**Middlewares disponibles** :

| Clé | Comportement |
|-----|-------------|
| `auth:admin` | Vérifie session active + rôle `admin`. Sinon → redirect `/login`. |
| `auth:user` | Vérifie session active + rôle `user`. Sinon → redirect `/login`. |
| `auth:any` | Vérifie session active (rôle quelconque). |
| `guest` | Redirige vers le dashboard si déjà connecté. |

---

### Request

**Namespace** : `App\Core\Request`  
**Rôle** : Encapsule les données de la requête HTTP entrante (`$_GET`, `$_POST`, `$_FILES`, `$_SERVER`). Évite l'accès direct aux superglobales dans les contrôleurs.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `public` | `method(): string` | Retourne la méthode HTTP (tient compte de `_method` pour PUT/DELETE). |
| `public` | `uri(): string` | Retourne le chemin URI sans query string. |
| `public` | `get(string $key, mixed $default = null): mixed` | Lit un paramètre GET. |
| `public` | `post(string $key, mixed $default = null): mixed` | Lit un paramètre POST. |
| `public` | `all(): array` | Retourne tous les paramètres POST. |
| `public` | `file(string $key): array\|null` | Retourne les données d'un fichier uploadé (`$_FILES[$key]`). |
| `public` | `isPost(): bool` | `true` si méthode POST. |
| `public` | `isAjax(): bool` | `true` si header `X-Requested-With: XMLHttpRequest`. |
| `public` | `header(string $name): string\|null` | Lit un header HTTP. |
| `public` | `ip(): string` | Retourne l'IP du client. |

---

### Session

**Namespace** : `App\Core\Session`  
**Rôle** : Encapsule `$_SESSION`. Gère les flash messages (visibles une seule fois) et la sécurité des sessions.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `public static` | `start(): void` | Démarre la session avec les paramètres sécurisés (`httponly`, `secure`, `strict_mode`). |
| `public static` | `set(string $key, mixed $value): void` | Stocke une valeur en session. |
| `public static` | `get(string $key, mixed $default = null): mixed` | Lit une valeur en session. |
| `public static` | `has(string $key): bool` | Vérifie l'existence d'une clé. |
| `public static` | `remove(string $key): void` | Supprime une clé. |
| `public static` | `destroy(): void` | Détruit complètement la session (logout). |
| `public static` | `regenerate(): void` | Régénère l'ID de session (appeler après login). |
| `public static` | `flash(string $type, string $message): void` | Stocke un message flash (`success`, `error`, `info`, `warning`). |
| `public static` | `getFlashes(): array` | Retourne et supprime tous les messages flash. |
| `public static` | `userId(): int\|null` | Raccourci : lit `$_SESSION['user_id']`. |
| `public static` | `userRole(): string\|null` | Raccourci : lit `$_SESSION['user_role']`. |

---

### BaseController

**Namespace** : `App\Core\BaseController`  
**Type** : Classe abstraite  
**Rôle** : Classe parente de tous les contrôleurs. Fournit les utilitaires communs.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `protected` | `render(string $view, array $data = [], string $layout = 'public'): void` | Extrait `$data`, inclut le layout qui lui-même inclut la vue. |
| `protected` | `json(mixed $data, int $status = 200): void` | Envoie une réponse JSON (Content-Type, http_response_code, echo, exit). |
| `protected` | `redirect(string $url): void` | Redirige via header Location + exit. |
| `protected` | `back(): void` | Redirige vers le Referer HTTP (ou `/` par défaut). |
| `protected` | `isAuthenticated(): bool` | Vérifie que `Session::userId()` est défini. |
| `protected` | `currentUser(): array\|null` | Retourne les données de l'utilisateur connecté (depuis session ou BDD). |
| `protected` | `requireRole(string $role): void` | Vérifie le rôle. Lance une redirection 403 si non autorisé. |
| `protected` | `generateCsrfToken(): string` | Génère, stocke en session et retourne un token CSRF. |
| `protected` | `checkCsrfToken(): void` | Compare le token du formulaire avec celui en session. Abort 403 si invalide. |
| `protected` | `abort(int $code, string $message = ''): void` | Charge la vue d'erreur et stoppe l'exécution. |

---

### BaseModel

**Namespace** : `App\Core\BaseModel`  
**Type** : Classe abstraite  
**Rôle** : Classe parente de tous les modèles. Fournit les opérations CRUD génériques et l'accès à `Database`.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `protected` | `$table: string` | Nom de la table MySQL. Défini dans chaque modèle enfant. |
| `protected` | `$fillable: array` | Liste blanche des colonnes acceptées (protection mass-assignment). |
| `protected` | `$db: Database` | Instance Database injectée dans `__construct`. |
| `public` | `find(int $id): array\|null` | SELECT par PK. |
| `public` | `findAll(string $orderBy = 'id DESC', int $limit = 0, int $offset = 0): array` | SELECT all avec tri et pagination optionnels. |
| `public` | `findBy(string $column, mixed $value): array\|null` | SELECT avec condition simple (première ligne). |
| `public` | `findAllBy(string $column, mixed $value, string $orderBy = 'id DESC'): array` | SELECT toutes les lignes d'une condition simple. |
| `public` | `save(array $data): int` | INSERT si pas d'`id`, UPDATE sinon. Filtre sur `$fillable`. |
| `public` | `delete(int $id): bool` | DELETE par PK. |
| `public` | `count(string $where = '1', array $params = []): int` | COUNT(*) avec condition optionnelle. |
| `public` | `paginate(int $page, int $perPage, string $where = '1', array $params = [], string $orderBy = 'id DESC'): array` | Retourne `['data' => [...], 'total' => int, 'pages' => int, 'current' => int]`. |

---

### Validator

**Namespace** : `App\Core\Validator`  
**Rôle** : Validation des données d'entrée côté serveur. Utilisé dans les contrôleurs avant tout traitement.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `private` | `$errors: array` | Tableau des erreurs indexé par nom de champ. |
| `public` | `required(string $field, mixed $value): self` | Vérifie que la valeur n'est pas vide. |
| `public` | `email(string $field, mixed $value): self` | Vérifie le format email (filter_var). |
| `public` | `minLength(string $field, mixed $value, int $min): self` | Longueur minimale. |
| `public` | `maxLength(string $field, mixed $value, int $max): self` | Longueur maximale. |
| `public` | `numeric(string $field, mixed $value): self` | Vérifie que la valeur est numérique. |
| `public` | `date(string $field, mixed $value, string $format = 'Y-m-d'): self` | Vérifie le format de date. |
| `public` | `datetime(string $field, mixed $value): self` | Vérifie le format datetime (Y-m-d H:i). |
| `public` | `dateAfter(string $field, mixed $value, string $reference): self` | Vérifie que la date est postérieure à `$reference`. |
| `public` | `in(string $field, mixed $value, array $allowed): self` | Vérifie que la valeur est dans la liste. |
| `public` | `url(string $field, mixed $value): self` | Vérifie le format URL. |
| `public` | `passes(): bool` | `true` si aucune erreur. |
| `public` | `fails(): bool` | `true` si au moins une erreur. |
| `public` | `errors(): array` | Retourne le tableau des erreurs. |
| `public` | `firstError(string $field): string\|null` | Retourne le premier message d'erreur pour un champ. |

---

### Mailer

**Namespace** : `App\Core\Mailer`  
**Rôle** : Envoi d'emails transactionnels via PHPMailer (ou `mail()` native). Utilise des templates de vue pour le corps HTML.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `public` | `send(string $to, string $subject, string $template, array $data = []): bool` | Charge la vue `views/emails/{template}.php`, construit le message et l'envoie. Retourne `true` en succès. |
| `public` | `sendToAdmin(string $subject, string $template, array $data = []): bool` | Raccourci : destinataire = email admin (depuis `Env`). |
| `private` | `renderTemplate(string $template, array $data): string` | Inclut le template PHP et capture le HTML via `ob_start()`. |
| `private` | `buildMailer(): PHPMailer` | Configure l'objet PHPMailer (SMTP, auth, charset, etc.) depuis `Env`. |

**Templates email utilisés** :

| Template | Déclencheur |
|----------|------------|
| `contact_admin` | Nouvelle demande de contact reçue |
| `contact_confirm` | Accusé de réception au prospect |
| `quote_sent` | Envoi du devis au client (avec lien token) |
| `quote_accepted` | Confirmation d'acceptation à l'admin |
| `welcome_client` | Création du compte client (identifiants) |
| `trip_modified` | Modification d'un item après acceptation |
| `new_message` | Nouveau message (si notif. email activée) |
| `password_reset` | Lien de réinitialisation du mot de passe |

---

### FileUploader

**Namespace** : `App\Core\FileUploader`  
**Rôle** : Gestion sécurisée des uploads. Stockage hors document root.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `private` | `$allowedMimes: array` | Liste blanche des types MIME acceptés (ex: `application/pdf`, `image/jpeg`). |
| `private` | `$maxSize: int` | Taille maximale en octets. |
| `private` | `$storagePath: string` | Chemin absolu de stockage (hors `public/`). |
| `public` | `__construct(array $allowedMimes, int $maxSizeBytes)` | Initialise les contraintes. |
| `public` | `upload(array $fileData): array` | Valide le type MIME réel via `finfo`, vérifie la taille, génère un nom UUID, déplace le fichier. Retourne `['stored_name' => ..., 'original_name' => ..., 'mime_type' => ..., 'file_size' => ...]`. |
| `public` | `delete(string $storedName): bool` | Supprime le fichier du stockage. |
| `private` | `detectMime(string $tmpPath): string` | Utilise `finfo_file()` — ne fait pas confiance au `type` fourni par le navigateur. |
| `private` | `generateName(string $extension): string` | Retourne `bin2hex(random_bytes(16)) . '.' . $extension`. |

---

### Env

**Namespace** : `App\Core\Env`  
**Rôle** : Chargement et accès aux variables d'environnement depuis le fichier `.env`.

| Visibilité | Membre | Description |
|-----------|--------|-------------|
| `public static` | `load(string $path): void` | Parse le fichier `.env` et charge chaque `KEY=value` dans `$_ENV` et `putenv()`. |
| `public static` | `get(string $key, mixed $default = null): mixed` | Lit une variable d'environnement. |
| `public static` | `require(string $key): string` | Comme `get()` mais lance une `RuntimeException` si la clé est absente. |

---

## Modèles

### UserModel

**Namespace** : `App\Models\UserModel`  
**Table** : `users`  
**Hérite de** : `BaseModel`

| Méthode | Signature | Description |
|---------|-----------|-------------|
| `findByEmail` | `(string $email): array\|null` | Recherche un utilisateur par email. |
| `verifyPassword` | `(string $plain, string $hash): bool` | Appelle `password_verify()`. |
| `hashPassword` | `(string $plain): string` | Appelle `password_hash($plain, PASSWORD_BCRYPT, ['cost' => 12])`. |
| `createClient` | `(array $data, string $plainPassword): int` | Crée un compte client : hache le mot de passe, force `must_change_pwd = 1`, insère. Retourne l'ID. |
| `recordFailedAttempt` | `(int $id): void` | Incrémente `failed_attempts`. Verrouille le compte 15 min après 5 échecs. |
| `resetFailedAttempts` | `(int $id): void` | Remet `failed_attempts = 0` et `locked_until = NULL`. |
| `isLocked` | `(array $user): bool` | `true` si `locked_until` est dans le futur. |
| `updatePassword` | `(int $id, string $plainPassword): void` | Hache et met à jour. Remet `must_change_pwd = 0`. |
| `generateResetToken` | `(int $id): string` | Génère un token sécurisé, le stocke en session (ou table dédiée), retourne le token. |

---

### ContactRequestModel

**Namespace** : `App\Models\ContactRequestModel`  
**Table** : `contact_requests`  
**Hérite de** : `BaseModel`

| Méthode | Signature | Description |
|---------|-----------|-------------|
| `getWithFilters` | `(array $filters, int $page, int $perPage): array` | Liste paginée avec filtres sur `status` et recherche texte. |
| `changeStatus` | `(int $id, string $status): void` | Met à jour le statut. Valide que `$status` est dans l'enum. |
| `convertToTrip` | `(int $id, int $tripId): void` | Remplit `converted_to_trip` et passe le statut à `accepted`. |
| `countByStatus` | `(string $status): int` | Raccourci pour le tableau de bord admin. |

---

### TrispModel

**Namespace** : `App\Models\TripsModel`  
**Table** : `trips`  
**Hérite de** : `BaseModel`

| Méthode | Signature | Description |
|---------|-----------|-------------|
| `findByUser` | `(int $userId): array` | Tous les voyages d'un client, triés par `start_date DESC`. |
| `findWithItems` | `(int $id): array\|null` | Voyage + ses `trip_items` triés par `start_datetime`. |
| `findByToken` | `(string $token): array\|null` | Recherche par `quote_token` (pour le lien email devis). |
| `sendQuote` | `(int $id, string $token): void` | Passe le statut à `quoted`, stocke le token, enregistre `quote_sent_at`. |
| `acceptQuote` | `(int $id): void` | Passe le statut à `accepted`, enregistre `accepted_at`, invalide le token. |
| `refuseQuote` | `(int $id): void` | Passe le statut à `cancelled`, invalide le token. |
| `changeStatus` | `(int $id, string $status): void` | Mise à jour directe du statut (admin). |
| `countByStatus` | `(string $status): int` | Raccourci pour le tableau de bord. |

---

### TripItemModel

**Namespace** : `App\Models\TripItemModel`  
**Table** : `trip_items`  
**Hérite de** : `BaseModel`

| Méthode | Signature | Description |
|---------|-----------|-------------|
| `findByTrip` | `(int $tripId): array` | Tous les items d'un voyage, triés par `start_datetime ASC`. |
| `create` | `(array $data): int` | Insère l'item. Si `requires_booking = 1`, crée automatiquement la ligne `booking_status` associée (statut `to_book`). Si `requires_booking = 0`, crée la ligne avec statut `not_applicable`. Utilise une transaction. |
| `update` | `(int $id, array $data): void` | Met à jour l'item. Si des champs temporels ou de titre changent et que le voyage est en statut `accepted`, repasse le `booking_status` associé à `to_verify` et notifie le client. |
| `delete` | `(int $id): void` | Supprime l'item (CASCADE supprime aussi `booking_status`). |
| `reorder` | `(array $orderedIds): void` | Met à jour `sort_order` de chaque item selon l'ordre fourni. |
| `checkOverlap` | `(int $tripId, string $start, string $end, ?int $excludeId = null): bool` | `true` si un autre item du même voyage chevauche la plage horaire. |

---

### BookingStatusModel

**Namespace** : `App\Models\BookingStatusModel`  
**Table** : `booking_status`  
**Hérite de** : `BaseModel`

| Méthode | Signature | Description |
|---------|-----------|-------------|
| `findByItem` | `(int $tripItemId): array\|null` | Récupère le statut pour un item. |
| `findByTrip` | `(int $tripId): array` | Tous les statuts des items d'un voyage (JOIN trip_items). |
| `updateStatus` | `(int $tripItemId, string $status, ?string $confirmationRef = null, ?string $notes = null): void` | Met à jour statut, référence et notes. Enregistre `booked_at` si statut = `booked`. |
| `getProgress` | `(int $tripId): array` | Retourne `['total' => int, 'booked' => int, 'percent' => float]` pour l'avancement du voyage. |

---

### MessageModel

**Namespace** : `App\Models\MessageModel`  
**Table** : `messages`  
**Hérite de** : `BaseModel`

| Méthode | Signature | Description |
|---------|-----------|-------------|
| `findByTrip` | `(int $tripId, int $limit = 50): array` | Messages d'une conversation triés par `created_at ASC`. |
| `findSince` | `(int $tripId, string $since): array` | Messages postérieurs à un datetime (pour le polling AJAX). |
| `send` | `(int $tripId, int $senderId, string $body): int` | Insère le message. Retourne son ID. |
| `markAsRead` | `(int $tripId, int $readerId): void` | Marque comme lus tous les messages non lus dont l'expéditeur ≠ `$readerId`. |
| `countUnread` | `(int $userId): int` | Nombre total de messages non lus pour un utilisateur (tous voyages). |
| `countUnreadForTrip` | `(int $tripId, int $userId): int` | Idem, restreint à un voyage. |

---

### BlogPostModel

**Namespace** : `App\Models\BlogPostModel`  
**Table** : `blog_posts`  
**Hérite de** : `BaseModel`

| Méthode | Signature | Description |
|---------|-----------|-------------|
| `findPublished` | `(int $page, int $perPage, array $filters = []): array` | Articles publiés, paginés. Filtres : `destination`, `trip_type`. |
| `findBySlug` | `(string $slug): array\|null` | Article par slug (URL). |
| `generateSlug` | `(string $title): string` | Translittère, met en minuscules, remplace les espaces par `-`, garantit l'unicité. |
| `publish` | `(int $id): void` | Passe `is_published = 1`, enregistre `published_at`. |
| `unpublish` | `(int $id): void` | Passe `is_published = 0`. |

---

### TestimonialModel

**Namespace** : `App\Models\TestimonialModel`  
**Table** : `testimonials`  
**Hérite de** : `BaseModel`

| Méthode | Signature | Description |
|---------|-----------|-------------|
| `findApprovedByPost` | `(int $blogPostId): array` | Témoignages approuvés pour un article. |
| `findPending` | `(): array` | Témoignages en attente de modération (admin). |
| `approve` | `(int $id): void` | Passe `is_approved = 1`. |
| `reject` | `(int $id): void` | Supprime le témoignage. |
| `canSubmit` | `(int $userId, int $tripId): bool` | `true` si le client a bien effectué ce voyage (statut `finished`) et n'a pas encore soumis de témoignage pour ce trip. |

---

### TripDocumentModel

**Namespace** : `App\Models\TripDocumentModel`  
**Table** : `trip_documents`  
**Hérite de** : `BaseModel`

| Méthode | Signature | Description |
|---------|-----------|-------------|
| `findByTrip` | `(int $tripId): array` | Tous les documents d'un voyage. |
| `isOwner` | `(int $documentId, int $userId): bool` | Vérifie que le voyage du document appartient à `$userId` (ou que l'utilisateur est admin). Utilisé dans `DocumentController` avant de servir le fichier. |

---

## Contrôleurs publics (Vitrine)

### HomeController

**Namespace** : `App\Controllers\HomeController`  
**Hérite de** : `BaseController`  
**Routes couvertes** : `GET /`, `GET /comment-ca-marche`, `GET /a-propos`, `GET /mentions-legales`

| Méthode | Route | Description |
|---------|-------|-------------|
| `index()` | `GET /` | Charge les derniers articles de blog publiés (3), les affiche dans la vue d'accueil. |
| `howItWorks()` | `GET /comment-ca-marche` | Vue statique du processus en 4 étapes. |
| `about()` | `GET /a-propos` | Vue statique de présentation. |
| `legal()` | `GET /mentions-legales` | Vue mentions légales. |
| `privacy()` | `GET /confidentialite` | Vue politique de confidentialité. |

---

### BlogController

**Namespace** : `App\Controllers\BlogController`  
**Hérite de** : `BaseController`  
**Routes couvertes** : `GET /voyages`, `GET /voyages/{slug}`

| Méthode | Route | Description |
|---------|-------|-------------|
| `index()` | `GET /voyages` | Liste paginée des articles publiés. Gère les filtres `destination` et `trip_type` en query string. |
| `show(string $slug)` | `GET /voyages/{slug}` | Article complet + témoignages approuvés. 404 si non publié. |

---

### ContactController

**Namespace** : `App\Controllers\ContactController`  
**Hérite de** : `BaseController`  
**Routes couvertes** : `GET /contact`, `POST /contact`

| Méthode | Route | Description |
|---------|-------|-------------|
| `show()` | `GET /contact` | Affiche le formulaire. Génère et passe le token CSRF à la vue. |
| `store()` | `POST /contact` | Vérifie CSRF, valide tous les champs via `Validator`, insère via `ContactRequestModel`, envoie les deux emails via `Mailer`, flash success, redirige. |

---

### AuthController

**Namespace** : `App\Controllers\AuthController`  
**Hérite de** : `BaseController`  
**Routes couvertes** : `GET /login`, `POST /login`, `POST /logout`, `GET /mot-de-passe/reinitialiser`, `POST /mot-de-passe/reinitialiser`

| Méthode | Route | Description |
|---------|-------|-------------|
| `showLogin()` | `GET /login` | Affiche le formulaire de connexion (middleware `guest`). |
| `login()` | `POST /login` | Vérifie CSRF, cherche l'utilisateur par email, vérifie le verrou, vérifie le mot de passe, régénère la session, stocke `user_id` et `user_role`, redirige selon le rôle. En cas d'échec : incrémente `failed_attempts`. |
| `logout()` | `POST /logout` | Détruit la session, redirige vers `/`. |
| `showChangePassword()` | `GET /changer-mot-de-passe` | Vue forcée si `must_change_pwd = 1`. |
| `changePassword()` | `POST /changer-mot-de-passe` | Valide ancien et nouveau mot de passe, met à jour, flash success. |
| `showReset()` | `GET /mot-de-passe/reinitialiser` | Formulaire de demande de réinitialisation. |
| `sendReset()` | `POST /mot-de-passe/reinitialiser` | Génère un token, envoie l'email, flash info. |

---

## Contrôleurs Admin

> Tous les contrôleurs admin héritent de `BaseController` et sont protégés par le middleware `auth:admin`.

### AdminDashboardController

**Namespace** : `App\Controllers\Admin\AdminDashboardController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `index()` | `GET /admin` | Récupère les KPI : demandes nouvelles, voyages par statut, messages non lus. Passe tout à la vue dashboard. |

---

### AdminRequestController

**Namespace** : `App\Controllers\Admin\AdminRequestController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `index()` | `GET /admin/demandes` | Liste paginée des demandes. Filtres par statut. |
| `show(int $id)` | `GET /admin/demandes/{id}` | Fiche détaillée d'une demande. |
| `changeStatus(int $id)` | `POST /admin/demandes/{id}/statut` | Vérifie CSRF, change le statut. |
| `convert(int $id)` | `POST /admin/demandes/{id}/convertir` | Crée un voyage vierge depuis la demande, crée/active le compte client, redirige vers l'éditeur de voyage. |

---

### AdminTripController

**Namespace** : `App\Controllers\Admin\AdminTripController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `index()` | `GET /admin/voyages` | Liste de tous les voyages avec filtres sur statut. |
| `show(int $id)` | `GET /admin/voyages/{id}` | Vue complète du voyage + timeline des items + statuts de réservation. |
| `store()` | `POST /admin/voyages` | Crée un nouveau voyage vierge (depuis une demande). |
| `update(int $id)` | `POST /admin/voyages/{id}` | Modifie les informations générales du voyage (nom, dates, description, prix). |
| `sendQuote(int $id)` | `POST /admin/voyages/{id}/devis/envoyer` | Génère le token, passe au statut `quoted`, envoie l'email devis. |
| `quoteResponse(string $token)` | `GET /devis/{token}/reponse` | Page publique (lien email) : affiche le résumé du voyage et les boutons accepter/refuser. |
| `acceptQuote(string $token)` | `POST /devis/{token}/accepter` | Accepte le devis, active le compte client, envoie les emails. |
| `refuseQuote(string $token)` | `POST /devis/{token}/refuser` | Refuse le devis, met à jour les statuts. |

---

### AdminTripItemController

**Namespace** : `App\Controllers\Admin\AdminTripItemController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `store(int $tripId)` | `POST /admin/voyages/{tripId}/items` | Valide (dates, cohérence, chevauchement), crée l'item et le `booking_status`. Retourne JSON si AJAX. |
| `update(int $id)` | `POST /admin/items/{id}` | Met à jour l'item. Notifie le client si le voyage est accepté et que des champs critiques ont changé. |
| `delete(int $id)` | `POST /admin/items/{id}/supprimer` | Supprime l'item (et son booking_status par CASCADE). |
| `reorder(int $tripId)` | `POST /admin/voyages/{tripId}/items/reorder` | Reçoit un tableau d'IDs ordonnés (JSON), appelle `TripItemModel::reorder()`. |

---

### AdminClientController

**Namespace** : `App\Controllers\Admin\AdminClientController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `index()` | `GET /admin/clients` | Liste des clients actifs et inactifs. |
| `show(int $id)` | `GET /admin/clients/{id}` | Fiche client : coordonnées + liste de ses voyages. |
| `create()` | `GET /admin/clients/nouveau` | Formulaire de création manuelle d'un compte client. |
| `store()` | `POST /admin/clients` | Crée le compte via `UserModel::createClient()`, envoie l'email de bienvenue. |
| `toggleActive(int $id)` | `POST /admin/clients/{id}/toggle` | Active ou désactive le compte. |

---

### AdminBlogController

**Namespace** : `App\Controllers\Admin\AdminBlogController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `index()` | `GET /admin/blog` | Liste des articles (publiés et brouillons). |
| `create()` | `GET /admin/blog/nouveau` | Formulaire de création. |
| `store()` | `POST /admin/blog` | Valide, génère le slug, upload de l'image de couverture si présente, insère. |
| `edit(int $id)` | `GET /admin/blog/{id}/modifier` | Formulaire pré-rempli. |
| `update(int $id)` | `POST /admin/blog/{id}` | Met à jour l'article. |
| `publish(int $id)` | `POST /admin/blog/{id}/publier` | Publie l'article. |
| `delete(int $id)` | `POST /admin/blog/{id}/supprimer` | Supprime l'article. |
| `testimonials()` | `GET /admin/temoignages` | Liste des témoignages en attente de modération. |
| `approveTestimonial(int $id)` | `POST /admin/temoignages/{id}/approuver` | Approuve un témoignage. |
| `rejectTestimonial(int $id)` | `POST /admin/temoignages/{id}/refuser` | Supprime un témoignage. |

---

## Contrôleurs Client

> Tous les contrôleurs client héritent de `BaseController` et sont protégés par le middleware `auth:user`.

### ClientDashboardController

**Namespace** : `App\Controllers\Client\ClientDashboardController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `index()` | `GET /client/tableau-de-bord` | Charge le ou les voyages du client, le nombre de messages non lus, la progression des réservations, et rend le dashboard. |

---

### ClientTripController

**Namespace** : `App\Controllers\Client\ClientTripController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `show(int $id)` | `GET /client/voyage/{id}` | Vérifie que le voyage appartient au client connecté. Charge les items + leurs statuts de réservation + les documents. Rend la vue timeline. |

---

### BookingController

**Namespace** : `App\Controllers\Client\BookingController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `update(int $tripItemId)` | `POST /client/reservation/{tripItemId}` | Vérifie CSRF. Vérifie que l'item appartient à un voyage du client. Vérifie que le statut demandé est autorisé (pas `not_applicable` ni `to_verify`). Met à jour via `BookingStatusModel::updateStatus()`. |

---

### MessageController

**Namespace** : `App\Controllers\Client\MessageController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `index()` | `GET /client/messagerie` | Charge les messages du voyage actif, marque les messages reçus comme lus. |
| `store()` | `POST /client/messagerie` | Vérifie CSRF, valide le corps du message (non vide, longueur max), insère via `MessageModel::send()`. Répond en JSON si AJAX. |
| `adminIndex()` | `GET /admin/messagerie` | Vue admin : liste de toutes les conversations avec indicateurs de non-lu. |
| `adminConversation(int $tripId)` | `GET /admin/messagerie/{tripId}` | Conversation admin pour un voyage donné. |
| `adminStore(int $tripId)` | `POST /admin/messagerie/{tripId}` | Envoi d'un message par l'admin. |

---

### DocumentController

**Namespace** : `App\Controllers\Client\DocumentController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `download(int $id)` | `GET /client/documents/{id}` | Vérifie via `TripDocumentModel::isOwner()` que le fichier appartient au client. Sert le fichier via `readfile()` avec les bons headers (`Content-Type`, `Content-Disposition`). Ne retourne jamais le chemin réel. |
| `adminUpload(int $tripId)` | `POST /admin/voyages/{tripId}/documents` | Upload via `FileUploader` (MIME whitelist PDF). Insère les métadonnées. |
| `adminDelete(int $id)` | `POST /admin/documents/{id}/supprimer` | Supprime le fichier physique et l'enregistrement BDD. |

---

### ProfileController

**Namespace** : `App\Controllers\Client\ProfileController`

| Méthode | Route | Description |
|---------|-------|-------------|
| `show()` | `GET /client/profil` | Affiche le formulaire profil pré-rempli. |
| `update()` | `POST /client/profil` | Met à jour prénom, nom, téléphone, email (si changé : vérifie unicité), préférences de notification. |
| `changePassword()` | `POST /client/profil/mot-de-passe` | Vérifie l'ancien mot de passe, valide le nouveau (longueur, confirmation), met à jour. |

---

## Contrôleur API

### ApiController

**Namespace** : `App\Controllers\ApiController`  
**Hérite de** : `BaseController`  
**Rôle** : Endpoints JSON légers pour les interactions AJAX (polling messagerie, mises à jour partielles). Toutes les méthodes répondent en JSON via `$this->json()`.

| Méthode | Route | Accès | Description |
|---------|-------|-------|-------------|
| `pollMessages()` | `GET /api/messages/poll?trip_id=X&since=Y` | `auth:any` | Retourne les messages du voyage `X` postérieurs au datetime `Y`. Format : `{ messages: [...], server_time: "..." }`. |
| `unreadCount()` | `GET /api/messages/unread` | `auth:any` | Retourne le nombre total de messages non lus pour l'utilisateur connecté. |
| `bookingProgress()` | `GET /api/voyage/{id}/progression` | `auth:user` | Retourne `{ total, booked, percent }` pour l'avancement des réservations. |

---

*Fin de la documentation des classes — Travel Planner v1.0*
