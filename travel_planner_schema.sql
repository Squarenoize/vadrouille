-- ============================================================
--  TRAVEL PLANNER — Schéma de base de données MySQL 8.0+
--  Charset  : utf8mb4
--  Collation: utf8mb4_unicode_ci
--  Moteur   : InnoDB (FK + transactions)
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
--  1. users
--  Contient les comptes admin et clients.
-- ------------------------------------------------------------
CREATE TABLE `users` (
    `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `email`            VARCHAR(180)    NOT NULL,
    `password_hash`    VARCHAR(255)    NOT NULL,
    `first_name`       VARCHAR(80)     NOT NULL,
    `last_name`        VARCHAR(80)     NOT NULL,
    `phone`            VARCHAR(20)         NULL DEFAULT NULL,
    `role`             ENUM('admin','traveler') NOT NULL DEFAULT 'traveler',
    `is_active`        TINYINT(1)      NOT NULL DEFAULT 1,
    `failed_attempts`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `locked_until`     DATETIME            NULL DEFAULT NULL,
    `must_change_pwd`  TINYINT(1)      NOT NULL DEFAULT 0,
    `email_notif`      TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME            NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------------
--  2. contact_requests
--  Demandes de voyage soumises par les visiteurs (site vitrine).
-- ------------------------------------------------------------
CREATE TABLE `contact_requests` (
    `id`                 INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `first_name`         VARCHAR(80)     NOT NULL,
    `last_name`          VARCHAR(80)     NOT NULL,
    `email`              VARCHAR(180)    NOT NULL,
    `phone`              VARCHAR(20)         NULL DEFAULT NULL,
    `trip_type`          ENUM('weekend','short','long','other') NOT NULL,
    `destination`        TEXT            NOT NULL,
    `travelers_count`    TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `desired_start`      DATE                NULL DEFAULT NULL,
    `desired_end`        DATE                NULL DEFAULT NULL,
    `budget`             DECIMAL(10,2)       NULL DEFAULT NULL,
    `message`            TEXT                NULL DEFAULT NULL,
    `status`             ENUM('new','studying','quoted','accepted','refused','archived')
                         NOT NULL DEFAULT 'new',
    `converted_to_trip`  INT UNSIGNED        NULL DEFAULT NULL,
    `created_at`         DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_contact_requests_status` (`status`),
    CONSTRAINT `fk_contact_requests_trip`
        FOREIGN KEY (`converted_to_trip`) REFERENCES `trips` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- NB : la FK vers trips est ajoutée après création de trips (voir ALTER plus bas).


-- ------------------------------------------------------------
--  3. trips
--  Voyage conçu par l'admin pour un client.
--  Note: user_id est NULL tant que le devis n'est pas accepté.
-- ------------------------------------------------------------
CREATE TABLE `trips` (
    `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `user_id`       INT UNSIGNED        NULL DEFAULT NULL,
    `request_id`    INT UNSIGNED        NULL DEFAULT NULL,
    `name`          VARCHAR(255)    NOT NULL,
    `description`   TEXT                NULL DEFAULT NULL,
    `destination`   VARCHAR(255)    NOT NULL,
    `start_date`    DATE            NOT NULL,
    `end_date`      DATE            NOT NULL,
    `total_price`   DECIMAL(10,2)       NULL DEFAULT NULL,
    `admin_note`    TEXT                NULL DEFAULT NULL,
    `status`        ENUM('draft','quoted','accepted','ongoing','finished','cancelled')
                    NOT NULL DEFAULT 'draft',
    `quote_token`   VARCHAR(64)         NULL DEFAULT NULL,
    `quote_sent_at` DATETIME            NULL DEFAULT NULL,
    `accepted_at`   DATETIME            NULL DEFAULT NULL,
    `created_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME            NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_trips_quote_token` (`quote_token`),
    KEY `idx_trips_user_id`   (`user_id`),
    KEY `idx_trips_status`    (`status`),
    KEY `idx_trips_request_id`(`request_id`),

    CONSTRAINT `fk_trips_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_trips_request`
        FOREIGN KEY (`request_id`) REFERENCES `contact_requests` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FK différée sur contact_requests (table trips créée après)
ALTER TABLE `contact_requests`
    ADD CONSTRAINT `fk_contact_requests_trip`
        FOREIGN KEY (`converted_to_trip`) REFERENCES `trips` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE;


-- ------------------------------------------------------------
--  4. trip_items
--  Éléments séquentiels (chronologiques) d'un voyage.
-- ------------------------------------------------------------
CREATE TABLE `trip_items` (
    `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `trip_id`          INT UNSIGNED    NOT NULL,
    `title`            VARCHAR(255)    NOT NULL,
    `category`         ENUM('TRANSPORT','HÉBERGEMENT','ACTIVITÉ','RESTAURATION','LIBRE')
                       NOT NULL,
    `start_datetime`   DATETIME        NOT NULL,
    `end_datetime`     DATETIME        NOT NULL,
    `description`      TEXT                NULL DEFAULT NULL,
    `requires_booking` TINYINT(1)      NOT NULL DEFAULT 1,
    `external_link`    VARCHAR(512)        NULL DEFAULT NULL,
    `indicative_price` DECIMAL(8,2)        NULL DEFAULT NULL,
    `sort_order`       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME            NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_trip_items_trip_id`       (`trip_id`),
    KEY `idx_trip_items_start_datetime`(`start_datetime`),

    CONSTRAINT `fk_trip_items_trip`
        FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `chk_trip_items_dates`
        CHECK (`end_datetime` > `start_datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------------
--  5. booking_status
--  Statut de réservation du client pour chaque trip_item
--  qui nécessite une réservation (relation 1-1 avec trip_items).
-- ------------------------------------------------------------
CREATE TABLE `booking_status` (
    `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `trip_item_id`     INT UNSIGNED    NOT NULL,
    `user_id`          INT UNSIGNED    NOT NULL,
    `status`           ENUM(
                           'to_book',
                           'in_progress',
                           'booked',
                           'not_applicable',
                           'to_verify',
                           'cancelled'
                       ) NOT NULL DEFAULT 'to_book',
    `confirmation_ref` VARCHAR(255)        NULL DEFAULT NULL,
    `user_notes`       TEXT                NULL DEFAULT NULL,
    `booked_at`        DATETIME            NULL DEFAULT NULL,
    `updated_at`       DATETIME            NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_booking_status_item` (`trip_item_id`),
    KEY `idx_booking_status_user_id` (`user_id`),

    CONSTRAINT `fk_booking_status_item`
        FOREIGN KEY (`trip_item_id`) REFERENCES `trip_items` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_booking_status_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------------
--  6. messages
--  Messagerie directe client ↔ admin, rattachée à un voyage.
-- ------------------------------------------------------------
CREATE TABLE `messages` (
    `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `trip_id`    INT UNSIGNED    NOT NULL,
    `sender_id`  INT UNSIGNED    NOT NULL,
    `body`       TEXT            NOT NULL,
    `is_read`    TINYINT(1)      NOT NULL DEFAULT 0,
    `created_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_messages_trip_id`   (`trip_id`),
    KEY `idx_messages_sender_id` (`sender_id`),
    KEY `idx_messages_created_at`(`created_at`),

    CONSTRAINT `fk_messages_trip`
        FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_messages_sender`
        FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------------
--  7. blog_posts
--  Articles du blog vitrine (voyages organisés présentés).
-- ------------------------------------------------------------
CREATE TABLE `blog_posts` (
    `id`           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `author_id`    INT UNSIGNED    NOT NULL,
    `trip_id`      INT UNSIGNED        NULL DEFAULT NULL,
    `title`        VARCHAR(255)    NOT NULL,
    `slug`         VARCHAR(255)    NOT NULL,
    `excerpt`      TEXT            NOT NULL,
    `body`         LONGTEXT        NOT NULL,
    `destination`  VARCHAR(255)    NOT NULL,
    `trip_type`    VARCHAR(80)         NULL DEFAULT NULL,
    `cover_image`  VARCHAR(255)        NULL DEFAULT NULL,
    `is_published` TINYINT(1)      NOT NULL DEFAULT 0,
    `published_at` DATETIME            NULL DEFAULT NULL,
    `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_blog_posts_slug` (`slug`),
    KEY `idx_blog_posts_author_id`   (`author_id`),
    KEY `idx_blog_posts_is_published`(`is_published`),
    KEY `idx_blog_posts_published_at`(`published_at`),

    CONSTRAINT `fk_blog_posts_author`
        FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_blog_posts_trip`
        FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------------
--  8. testimonials
--  Témoignages clients publiés sur le blog (après modération).
-- ------------------------------------------------------------
CREATE TABLE `testimonials` (
    `id`           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `user_id`      INT UNSIGNED    NOT NULL,
    `trip_id`      INT UNSIGNED    NOT NULL,
    `blog_post_id` INT UNSIGNED        NULL DEFAULT NULL,
    `body`         TEXT            NOT NULL,
    `rating`       TINYINT UNSIGNED    NULL DEFAULT NULL,
    `is_approved`  TINYINT(1)      NOT NULL DEFAULT 0,
    `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_testimonials_user_id`     (`user_id`),
    KEY `idx_testimonials_trip_id`     (`trip_id`),
    KEY `idx_testimonials_blog_post_id`(`blog_post_id`),

    CONSTRAINT `fk_testimonials_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_testimonials_trip`
        FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_testimonials_blog_post`
        FOREIGN KEY (`blog_post_id`) REFERENCES `blog_posts` (`id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `chk_testimonials_rating`
        CHECK (`rating` IS NULL OR `rating` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------------
--  9. trip_documents
--  Fichiers PDF déposés par l'admin sur un voyage (servis
--  par le contrôleur PHP après vérification des droits).
-- ------------------------------------------------------------
CREATE TABLE `trip_documents` (
    `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `trip_id`       INT UNSIGNED    NOT NULL,
    `uploaded_by`   INT UNSIGNED    NOT NULL,
    `original_name` VARCHAR(255)    NOT NULL,
    `stored_name`   VARCHAR(255)    NOT NULL,
    `mime_type`     VARCHAR(100)    NOT NULL,
    `file_size`     INT UNSIGNED    NOT NULL,
    `created_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_trip_documents_trip_id` (`trip_id`),

    CONSTRAINT `fk_trip_documents_trip`
        FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_trip_documents_uploader`
        FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  DONNÉES INITIALES
-- ============================================================

-- Compte admin par défaut (mot de passe : à changer immédiatement)
-- Hash bcrypt de "ChangeMe!2025" — cost 12
INSERT INTO `users`
    (`email`, `password_hash`, `first_name`, `last_name`, `role`, `must_change_pwd`)
VALUES
    (
        'admin@travel-planner.fr',
        '$2y$12$examplehashchangethisbeforeproduction000000000000000000',
        'Admin',
        'Travel',
        'admin',
        1
    );


SET FOREIGN_KEY_CHECKS = 1;
