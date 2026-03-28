-- ============================================================
-- Dress Rental — PRODUCTION database (manual phpMyAdmin import)
-- Generated from Laravel migrations + ProductionSeeder
--
-- ⚠️  IMPORTANT — TWO SEPARATE SETS OF CREDENTIALS:
--
--   A) MySQL CONNECTION credentials (go in your .env file):
--      DB_DATABASE, DB_USERNAME, DB_PASSWORD
--      These are the cPanel MySQL username and password you
--      created in cPanel → MySQL® Databases.
--      ➜ Do NOT put these in this SQL file.
--
--   B) Website ADMIN LOGIN credentials (go in this SQL file):
--      The email, phone, and bcrypt password in the INSERT below.
--      These are what you use to log in to the website's admin panel.
--      ➜ These have nothing to do with MySQL connection.
--
--   Both sets must be configured — this file handles (B) only.
--   Your .env file handles (A).
--
-- HOW TO USE:
--   1. Log in to cPanel → phpMyAdmin
--   2. Select your PRODUCTION database
--      (e.g. cpanelusername_dressrental_prod)
--   3. ⚠️  IMPORTANT — before importing, edit the INSERT near the
--      bottom of this file (search for <<<REPLACE_WITH_BCRYPT_HASH>>>):
--      • Replace  admin@yourdomain.com  with your real admin e-mail.
--      • Replace the placeholder hash   <<<REPLACE_WITH_BCRYPT_HASH>>>
--        with the bcrypt hash of your chosen admin password.
--        Generate one with:
--          php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_BCRYPT, ['cost'=>12]);"
--        or online (test password only) at https://bcrypt-generator.com (12 rounds).
--      • Replace  98XXXXXXXX  with your real phone number.
--   4. Click the "Import" tab → choose this file → click "Go"
--   5. Then edit your .env file with the MySQL connection credentials
--      from cPanel → MySQL® Databases.  The site will NOT connect to
--      the database until .env has the correct DB_DATABASE, DB_USERNAME
--      and DB_PASSWORD values.
--
-- All CREATE TABLE statements use IF NOT EXISTS so re-importing
-- on a database that already has tables will not destroy data.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- migrations  (Laravel migration tracker)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrations` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch`     INT         NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES
  ('0001_01_01_000000_create_users_table',             1),
  ('0001_01_01_000001_create_cache_table',             1),
  ('0001_01_01_000002_create_jobs_table',              1),
  ('2026_03_27_033936_add_fields_to_users_table',      1),
  ('2026_03_27_033936_create_bookings_table',          1),
  ('2026_03_27_033936_create_dress_categories_table',  1),
  ('2026_03_27_033936_create_dress_images_table',      1),
  ('2026_03_27_033936_create_dresses_table',           1),
  ('2026_03_27_033937_create_payments_table',          1);

-- ------------------------------------------------------------
-- users
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`              VARCHAR(255)    NOT NULL,
  `email`             VARCHAR(255)    NOT NULL,
  `phone`             VARCHAR(20)     DEFAULT NULL,
  `address`           TEXT            DEFAULT NULL,
  `role`              ENUM('user','admin') NOT NULL DEFAULT 'user',
  `profile_photo`     VARCHAR(255)    DEFAULT NULL,
  `email_verified_at` TIMESTAMP NULL  DEFAULT NULL,
  `password`          VARCHAR(255)    NOT NULL,
  `remember_token`    VARCHAR(100)    DEFAULT NULL,
  `created_at`        TIMESTAMP NULL  DEFAULT NULL,
  `updated_at`        TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- password_reset_tokens
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email`      VARCHAR(255) NOT NULL,
  `token`      VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- sessions
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sessions` (
  `id`            VARCHAR(255)    NOT NULL,
  `user_id`       BIGINT UNSIGNED DEFAULT NULL,
  `ip_address`    VARCHAR(45)     DEFAULT NULL,
  `user_agent`    TEXT            DEFAULT NULL,
  `payload`       LONGTEXT        NOT NULL,
  `last_activity` INT             NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index`      (`user_id`),
  KEY `sessions_last_activity_index`(`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- cache
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cache` (
  `key`        VARCHAR(255) NOT NULL,
  `value`      MEDIUMTEXT   NOT NULL,
  `expiration` BIGINT       NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- cache_locks
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key`        VARCHAR(255) NOT NULL,
  `owner`      VARCHAR(255) NOT NULL,
  `expiration` BIGINT       NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- jobs
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jobs` (
  `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `queue`        VARCHAR(255)     NOT NULL,
  `payload`      LONGTEXT         NOT NULL,
  `attempts`     TINYINT UNSIGNED NOT NULL,
  `reserved_at`  INT UNSIGNED     DEFAULT NULL,
  `available_at` INT UNSIGNED     NOT NULL,
  `created_at`   INT UNSIGNED     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- job_batches
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id`              VARCHAR(255) NOT NULL,
  `name`            VARCHAR(255) NOT NULL,
  `total_jobs`      INT          NOT NULL,
  `pending_jobs`    INT          NOT NULL,
  `failed_jobs`     INT          NOT NULL,
  `failed_job_ids`  LONGTEXT     NOT NULL,
  `options`         MEDIUMTEXT   DEFAULT NULL,
  `cancelled_at`    INT          DEFAULT NULL,
  `created_at`      INT          NOT NULL,
  `finished_at`     INT          DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- failed_jobs
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid`       VARCHAR(255)    NOT NULL,
  `connection` TEXT            NOT NULL,
  `queue`      TEXT            NOT NULL,
  `payload`    LONGTEXT        NOT NULL,
  `exception`  LONGTEXT        NOT NULL,
  `failed_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- dress_categories
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dress_categories` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255)    NOT NULL,
  `slug`        VARCHAR(255)    NOT NULL,
  `description` TEXT            DEFAULT NULL,
  `icon`        VARCHAR(255)    DEFAULT NULL,
  `is_active`   TINYINT(1)      NOT NULL DEFAULT 1,
  `sort_order`  INT             NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP NULL  DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dress_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- dresses
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dresses` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id`    BIGINT UNSIGNED NOT NULL,
  `name`           VARCHAR(255)    NOT NULL,
  `slug`           VARCHAR(255)    NOT NULL,
  `description`    TEXT            DEFAULT NULL,
  `size`           ENUM('XS','S','M','L','XL','XXL','Free Size') NOT NULL,
  `price_per_day`  DECIMAL(10,2)   NOT NULL,
  `deposit_amount` DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `status`         ENUM('available','unavailable') NOT NULL DEFAULT 'available',
  `is_featured`    TINYINT(1)      NOT NULL DEFAULT 0,
  `color`          VARCHAR(255)    DEFAULT NULL,
  `brand`          VARCHAR(255)    DEFAULT NULL,
  `views`          INT             NOT NULL DEFAULT 0,
  `created_at`     TIMESTAMP NULL  DEFAULT NULL,
  `updated_at`     TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dresses_slug_unique` (`slug`),
  KEY `dresses_category_id_foreign` (`category_id`),
  CONSTRAINT `dresses_category_id_foreign`
    FOREIGN KEY (`category_id`) REFERENCES `dress_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- dress_images
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dress_images` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `dress_id`   BIGINT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255)    NOT NULL,
  `is_primary` TINYINT(1)      NOT NULL DEFAULT 0,
  `sort_order` INT             NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL  DEFAULT NULL,
  `updated_at` TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dress_images_dress_id_foreign` (`dress_id`),
  CONSTRAINT `dress_images_dress_id_foreign`
    FOREIGN KEY (`dress_id`) REFERENCES `dresses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- bookings
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`        BIGINT UNSIGNED NOT NULL,
  `dress_id`       BIGINT UNSIGNED NOT NULL,
  `start_date`     DATE            NOT NULL,
  `end_date`       DATE            NOT NULL,
  `bs_start_date`  VARCHAR(20)     DEFAULT NULL,
  `bs_end_date`    VARCHAR(20)     DEFAULT NULL,
  `total_days`     INT             NOT NULL,
  `rental_amount`  DECIMAL(10,2)   NOT NULL,
  `deposit_amount` DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `total_amount`   DECIMAL(10,2)   NOT NULL,
  `advance_amount` DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `fine_amount`    DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `status`         ENUM('pending','paid','active','returned','completed','cancelled') NOT NULL DEFAULT 'pending',
  `notes`          TEXT            DEFAULT NULL,
  `paid_at`        TIMESTAMP NULL  DEFAULT NULL,
  `returned_at`    TIMESTAMP NULL  DEFAULT NULL,
  `created_at`     TIMESTAMP NULL  DEFAULT NULL,
  `updated_at`     TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_user_id_foreign`  (`user_id`),
  KEY `bookings_dress_id_foreign` (`dress_id`),
  CONSTRAINT `bookings_user_id_foreign`
    FOREIGN KEY (`user_id`)  REFERENCES `users`   (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_dress_id_foreign`
    FOREIGN KEY (`dress_id`) REFERENCES `dresses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- payments
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id`       BIGINT UNSIGNED NOT NULL,
  `user_id`          BIGINT UNSIGNED NOT NULL,
  `amount`           DECIMAL(10,2)   NOT NULL,
  `payment_method`   ENUM('esewa','khalti','cash') NOT NULL DEFAULT 'esewa',
  `transaction_id`   VARCHAR(255)    DEFAULT NULL,
  `status`           ENUM('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_type`     ENUM('advance','balance','deposit_refund','fine') NOT NULL DEFAULT 'advance',
  `gateway_response` JSON            DEFAULT NULL,
  `remarks`          TEXT            DEFAULT NULL,
  `verified_at`      TIMESTAMP NULL  DEFAULT NULL,
  `created_at`       TIMESTAMP NULL  DEFAULT NULL,
  `updated_at`       TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_booking_id_foreign` (`booking_id`),
  KEY `payments_user_id_foreign`    (`user_id`),
  CONSTRAINT `payments_booking_id_foreign`
    FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_user_id_foreign`
    FOREIGN KEY (`user_id`)    REFERENCES `users`    (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PRODUCTION DATA — Admin account
--
-- The admin account is created automatically by ProductionSeeder,
-- which runs as part of the .cpanel.yml deployment script.
-- It reads credentials from the .env file:
--
--   ADMIN_EMAIL=your-admin@email.com
--   ADMIN_PASSWORD=your_secure_password
--   ADMIN_PHONE=98XXXXXXXXX
--
-- Set these values in your .env file on the server
-- (cPanel → File Manager → your document root → .env).
-- The seeder uses firstOrCreate — safe to run multiple times.
-- ============================================================

SET FOREIGN_KEY_CHECKS = 1;
