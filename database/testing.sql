-- ============================================================
-- Dress Rental — TESTING database (manual phpMyAdmin import)
-- Generated from Laravel migrations + DatabaseSeeder
--
-- HOW TO USE:
--   1. Log in to cPanel → phpMyAdmin
--   2. Select your TESTING database
--      (e.g. cpanelusername_dressrental_test)
--   3. Click the "Import" tab → choose this file → click "Go"
--
-- What this file creates:
--   • Full schema (all tables)
--   • Admin account   — admin@dressrental.com  / password
--   • Demo customer   — user@dressrental.com   / password
--   • 6 dress categories
--   • 12 sample dresses
--
-- ⚠️  NEVER import this file into the production database.
--     It contains demo credentials and sample data only.
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
  `created_at`        TIMESTAMP       DEFAULT NULL,
  `updated_at`        TIMESTAMP       DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- password_reset_tokens
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email`      VARCHAR(255) NOT NULL,
  `token`      VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP    DEFAULT NULL,
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
  `created_at`  TIMESTAMP       DEFAULT NULL,
  `updated_at`  TIMESTAMP       DEFAULT NULL,
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
  `created_at`     TIMESTAMP       DEFAULT NULL,
  `updated_at`     TIMESTAMP       DEFAULT NULL,
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
  `created_at` TIMESTAMP       DEFAULT NULL,
  `updated_at` TIMESTAMP       DEFAULT NULL,
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
  `paid_at`        TIMESTAMP       DEFAULT NULL,
  `returned_at`    TIMESTAMP       DEFAULT NULL,
  `created_at`     TIMESTAMP       DEFAULT NULL,
  `updated_at`     TIMESTAMP       DEFAULT NULL,
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
  `verified_at`      TIMESTAMP       DEFAULT NULL,
  `created_at`       TIMESTAMP       DEFAULT NULL,
  `updated_at`       TIMESTAMP       DEFAULT NULL,
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
-- TESTING DATA
-- ============================================================

-- ------------------------------------------------------------
-- Users
-- Passwords are bcrypt of "password" (10 rounds).
-- Login:
--   Admin : admin@dressrental.com / password
--   Demo  : user@dressrental.com  / password
-- ------------------------------------------------------------
INSERT IGNORE INTO `users`
  (`name`, `email`, `phone`, `role`, `password`, `email_verified_at`, `created_at`, `updated_at`)
VALUES
  (
    'Admin',
    'admin@dressrental.com',
    '9800000001',
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NOW(), NOW(), NOW()
  ),
  (
    'Demo User',
    'user@dressrental.com',
    '9800000002',
    'user',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NOW(), NOW(), NOW()
  );

-- ------------------------------------------------------------
-- Dress categories
-- ------------------------------------------------------------
INSERT IGNORE INTO `dress_categories`
  (`name`, `slug`, `icon`, `is_active`, `sort_order`, `created_at`, `updated_at`)
VALUES
  ('Bridal Wear',    'bridal-wear',    '👰', 1, 1, NOW(), NOW()),
  ('Party Dresses',  'party-dresses',  '🎉', 1, 2, NOW(), NOW()),
  ('Ethnic Wear',    'ethnic-wear',    '🪷', 1, 3, NOW(), NOW()),
  ('Casual Dresses', 'casual-dresses', '👗', 1, 4, NOW(), NOW()),
  ('Formal Wear',    'formal-wear',    '💼', 1, 5, NOW(), NOW()),
  ('Festival Wear',  'festival-wear',  '🎊', 1, 6, NOW(), NOW());

-- ------------------------------------------------------------
-- Sample dresses
-- category_id values match the INSERT order above:
--   1 = Bridal Wear   2 = Party Dresses  3 = Ethnic Wear
--   4 = Casual Dresses 5 = Formal Wear   6 = Festival Wear
-- ------------------------------------------------------------
INSERT IGNORE INTO `dresses`
  (`category_id`, `name`, `slug`, `description`, `size`,
   `price_per_day`, `deposit_amount`, `status`, `is_featured`, `color`,
   `created_at`, `updated_at`)
VALUES
  (1, 'Red Silk Bridal Lehenga',  'red-silk-bridal-lehenga',
   'Premium quality Red Silk Bridal Lehenga available for rent. Perfect for special occasions.',
   'M',         1500.00, 3000.00, 'available', 1, 'Red',    NOW(), NOW()),

  (3, 'Golden Anarkali Suit',     'golden-anarkali-suit',
   'Premium quality Golden Anarkali Suit available for rent. Perfect for special occasions.',
   'L',         800.00,  1500.00, 'available', 1, 'Gold',   NOW(), NOW()),

  (3, 'Blue Georgette Saree',     'blue-georgette-saree',
   'Premium quality Blue Georgette Saree available for rent. Perfect for special occasions.',
   'Free Size', 600.00,  1000.00, 'available', 0, 'Blue',   NOW(), NOW()),

  (2, 'Black Evening Gown',       'black-evening-gown',
   'Premium quality Black Evening Gown available for rent. Perfect for special occasions.',
   'S',         1200.00, 2000.00, 'available', 1, 'Black',  NOW(), NOW()),

  (2, 'Pink Floral Party Dress',  'pink-floral-party-dress',
   'Premium quality Pink Floral Party Dress available for rent. Perfect for special occasions.',
   'XS',        700.00,  1000.00, 'available', 0, 'Pink',   NOW(), NOW()),

  (2, 'Purple Cocktail Dress',    'purple-cocktail-dress',
   'Premium quality Purple Cocktail Dress available for rent. Perfect for special occasions.',
   'M',         900.00,  1500.00, 'available', 1, 'Purple', NOW(), NOW()),

  (3, 'Cream Chiffon Saree',      'cream-chiffon-saree',
   'Premium quality Cream Chiffon Saree available for rent. Perfect for special occasions.',
   'Free Size', 500.00,  800.00,  'available', 0, 'Cream',  NOW(), NOW()),

  (5, 'Royal Blue Sherwani',      'royal-blue-sherwani',
   'Premium quality Royal Blue Sherwani available for rent. Perfect for special occasions.',
   'XL',        1100.00, 2000.00, 'available', 0, 'Blue',   NOW(), NOW()),

  (1, 'Green Banarasi Lehenga',   'green-banarasi-lehenga',
   'Premium quality Green Banarasi Lehenga available for rent. Perfect for special occasions.',
   'L',         2000.00, 4000.00, 'available', 1, 'Green',  NOW(), NOW()),

  (1, 'White Lace Wedding Dress', 'white-lace-wedding-dress',
   'Premium quality White Lace Wedding Dress available for rent. Perfect for special occasions.',
   'S',         2500.00, 5000.00, 'available', 1, 'White',  NOW(), NOW()),

  (6, 'Orange Dashain Kurta',     'orange-dashain-kurta',
   'Premium quality Orange Dashain Kurta available for rent. Perfect for special occasions.',
   'M',         400.00,  600.00,  'available', 0, 'Orange', NOW(), NOW()),

  (6, 'Yellow Tihar Dress',       'yellow-tihar-dress',
   'Premium quality Yellow Tihar Dress available for rent. Perfect for special occasions.',
   'L',         350.00,  500.00,  'available', 0, 'Yellow', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
