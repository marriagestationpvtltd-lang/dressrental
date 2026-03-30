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
--   • Default settings
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
  ('0001_01_01_000000_create_users_table',                                      1),
  ('0001_01_01_000001_create_cache_table',                                      1),
  ('0001_01_01_000002_create_jobs_table',                                       1),
  ('2026_03_27_033936_add_fields_to_users_table',                               1),
  ('2026_03_27_033936_create_dress_categories_table',                           1),
  ('2026_03_27_033937_create_dresses_table',                                    1),
  ('2026_03_27_033938_create_bookings_table',                                   1),
  ('2026_03_27_033938_create_dress_images_table',                               1),
  ('2026_03_27_033939_create_payments_table',                                   1),
  ('2026_03_30_000001_create_settings_table',                                   1),
  ('2026_03_30_000002_add_gemini_api_key_setting',                              1),
  ('2026_03_30_000003_add_missing_settings',                                    1),
  ('2026_03_30_000004_add_google_oauth',                                        1),
  ('2026_03_30_000004_create_pages_table',                                      1),
  ('2026_03_30_100000_add_parent_id_to_dress_categories_table',                 1),
  ('2026_03_30_100000_create_ornaments_table',                                  1),
  ('2026_03_30_100001_create_dress_ornament_table',                             1),
  ('2026_03_30_100002_create_category_ornament_recommendations_table',          1),
  ('2026_03_30_200000_fix_dress_category_parent_id_mismatches',                 1);

-- ------------------------------------------------------------
-- users
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`              VARCHAR(255)    NOT NULL,
  `email`             VARCHAR(255)    NOT NULL,
  `google_id`         VARCHAR(255)    DEFAULT NULL,
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
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_google_id_unique` (`google_id`)
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
  `parent_id`   BIGINT UNSIGNED DEFAULT NULL,
  `created_at`  TIMESTAMP NULL  DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dress_categories_slug_unique` (`slug`),
  KEY `dress_categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `dress_categories_parent_id_foreign`
    FOREIGN KEY (`parent_id`) REFERENCES `dress_categories` (`id`) ON DELETE SET NULL
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

-- ------------------------------------------------------------
-- settings
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key`         VARCHAR(255)    NOT NULL,
  `value`       TEXT            DEFAULT NULL,
  `type`        VARCHAR(255)    NOT NULL DEFAULT 'text',
  `group`       VARCHAR(255)    NOT NULL DEFAULT 'site',
  `label`       VARCHAR(255)    NOT NULL,
  `description` VARCHAR(255)    DEFAULT NULL,
  `created_at`  TIMESTAMP NULL  DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- pages
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pages` (
  `id`          BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)      NOT NULL,
  `slug`        VARCHAR(255)      NOT NULL,
  `content`     LONGTEXT          NOT NULL,
  `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `sort_order`  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP NULL    DEFAULT NULL,
  `updated_at`  TIMESTAMP NULL    DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- ornaments
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornaments` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(255)    NOT NULL,
  `slug`           VARCHAR(255)    NOT NULL,
  `description`    TEXT            DEFAULT NULL,
  `price_per_day`  DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `deposit_amount` DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
  `category`       ENUM('jewelry','hair_accessories','footwear','handbag','other') NOT NULL DEFAULT 'other',
  `image_path`     VARCHAR(255)    DEFAULT NULL,
  `status`         ENUM('available','unavailable') NOT NULL DEFAULT 'available',
  `created_at`     TIMESTAMP NULL  DEFAULT NULL,
  `updated_at`     TIMESTAMP NULL  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ornaments_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- dress_ornament  (pivot)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dress_ornament` (
  `dress_id`    BIGINT UNSIGNED NOT NULL,
  `ornament_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`dress_id`,`ornament_id`),
  KEY `dress_ornament_ornament_id_foreign` (`ornament_id`),
  CONSTRAINT `dress_ornament_dress_id_foreign`
    FOREIGN KEY (`dress_id`)    REFERENCES `dresses`   (`id`) ON DELETE CASCADE,
  CONSTRAINT `dress_ornament_ornament_id_foreign`
    FOREIGN KEY (`ornament_id`) REFERENCES `ornaments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- category_ornament_recommendations  (pivot)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `category_ornament_recommendations` (
  `dress_category_id` BIGINT UNSIGNED   NOT NULL,
  `ornament_id`       BIGINT UNSIGNED   NOT NULL,
  `sort_order`        SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`dress_category_id`,`ornament_id`),
  KEY `category_ornament_recommendations_ornament_id_foreign` (`ornament_id`),
  CONSTRAINT `category_ornament_recommendations_dress_category_id_foreign`
    FOREIGN KEY (`dress_category_id`) REFERENCES `dress_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `category_ornament_recommendations_ornament_id_foreign`
    FOREIGN KEY (`ornament_id`)       REFERENCES `ornaments`        (`id`) ON DELETE CASCADE
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
  (`name`, `email`, `google_id`, `phone`, `role`, `password`, `email_verified_at`, `created_at`, `updated_at`)
VALUES
  (
    'Admin',
    'admin@dressrental.com',
    NULL,
    '9800000001',
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NOW(), NOW(), NOW()
  ),
  (
    'Demo User',
    'user@dressrental.com',
    NULL,
    '9800000002',
    'user',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NOW(), NOW(), NOW()
  );

-- ------------------------------------------------------------
-- Default settings
-- ------------------------------------------------------------
INSERT IGNORE INTO `settings` (`key`, `value`, `type`, `group`, `label`, `description`, `created_at`, `updated_at`) VALUES
  ('site_name',                  'DressRental Nepal',         'text',     'site',    'Site Name',                     'The name of the website shown in the browser title and emails.',                                                               NOW(), NOW()),
  ('site_tagline',               'Premium Dress Rental Service', 'text',  'site',    'Site Tagline',                  'A short description displayed on the homepage.',                                                                               NOW(), NOW()),
  ('contact_phone',              '',                          'text',     'site',    'Contact Phone',                 'Business contact phone number.',                                                                                               NOW(), NOW()),
  ('contact_email',              '',                          'text',     'site',    'Contact Email',                 'Business contact email address.',                                                                                              NOW(), NOW()),
  ('contact_address',            '',                          'textarea', 'site',    'Contact Address',               'Physical business address.',                                                                                                   NOW(), NOW()),
  ('currency',                   'NPR',                       'text',     'site',    'Currency Code',                 'ISO currency code (e.g. NPR, USD).',                                                                                          NOW(), NOW()),
  ('currency_symbol',            'रू',                        'text',     'site',    'Currency Symbol',               'Symbol displayed before amounts (e.g. रू, $).',                                                                               NOW(), NOW()),
  ('meta_description',           'Rent premium dresses in Nepal. Easy booking with Nepali calendar, pay via eSewa & Khalti.', 'textarea', 'site', 'Meta Description (SEO)', 'Short description shown in search engine results (max 160 characters).', NOW(), NOW()),
  ('social_facebook',            '',                          'text',     'site',    'Facebook Page URL',             'Full URL to your Facebook page (e.g. https://facebook.com/yourpage).',                                                        NOW(), NOW()),
  ('social_instagram',           '',                          'text',     'site',    'Instagram Profile URL',         'Full URL to your Instagram profile (e.g. https://instagram.com/yourhandle).',                                                 NOW(), NOW()),
  ('social_whatsapp',            '',                          'text',     'site',    'WhatsApp Number',               'WhatsApp contact number with country code (e.g. 9779800000000).',                                                             NOW(), NOW()),
  ('advance_payment_percentage', '50',                        'integer',  'booking', 'Advance Payment Percentage',    'Percentage of total amount collected as advance at booking (e.g. 50 for 50%).',                                               NOW(), NOW()),
  ('min_rental_days',            '1',                         'integer',  'booking', 'Minimum Rental Days',           'Minimum number of days a dress can be rented.',                                                                                NOW(), NOW()),
  ('max_rental_days',            '365',                       'integer',  'booking', 'Maximum Rental Days',           'Maximum number of days a dress can be rented.',                                                                                NOW(), NOW()),
  ('cancellation_notice_hours',  '24',                        'integer',  'booking', 'Cancellation Notice (Hours)',   'Minimum hours before booking start that cancellation is allowed.',                                                             NOW(), NOW()),
  ('late_return_fine_per_day',   '0',                         'decimal',  'booking', 'Late Return Fine per Day (रू)', 'Fine charged per extra day when a dress is returned late.',                                                                   NOW(), NOW()),
  ('esewa_enabled',              '1',                         'boolean',  'payment', 'Enable eSewa',                  'Allow customers to pay via eSewa.',                                                                                            NOW(), NOW()),
  ('khalti_enabled',             '1',                         'boolean',  'payment', 'Enable Khalti',                 'Allow customers to pay via Khalti.',                                                                                           NOW(), NOW()),
  ('esewa_service_charge',       '0',                         'decimal',  'payment', 'eSewa Service Charge (रू)',     'Fixed service charge added to eSewa payments.',                                                                               NOW(), NOW()),
  ('esewa_delivery_charge',      '0',                         'decimal',  'payment', 'eSewa Delivery Charge (रू)',    'Fixed delivery charge added to eSewa payments.',                                                                              NOW(), NOW()),
  ('tax_percentage',             '0',                         'decimal',  'payment', 'Tax Percentage (%)',            'Tax percentage applied on the payment amount (e.g. 13 for 13% VAT).',                                                        NOW(), NOW()),
  ('esewa_merchant_id',          '',                          'text',     'payment', 'eSewa Merchant ID',             'Your eSewa merchant product code. Overrides the ESEWA_MERCHANT_ID env variable when set.',                                    NOW(), NOW()),
  ('esewa_secret_key',           '',                          'password', 'payment', 'eSewa Secret Key',              'Your eSewa HMAC secret key. Overrides the ESEWA_SECRET_KEY env variable when set.',                                          NOW(), NOW()),
  ('esewa_sandbox',              '',                          'boolean',  'payment', 'eSewa Sandbox Mode',            'Enable to use the eSewa test/sandbox environment. Overrides the ESEWA_SANDBOX env variable when checked.',                    NOW(), NOW()),
  ('khalti_public_key',          '',                          'text',     'payment', 'Khalti Public Key',             'Your Khalti public key. Overrides the KHALTI_PUBLIC_KEY env variable when set.',                                              NOW(), NOW()),
  ('khalti_secret_key',          '',                          'password', 'payment', 'Khalti Secret Key',             'Your Khalti secret key. Overrides the KHALTI_SECRET_KEY env variable when set.',                                             NOW(), NOW()),
  ('khalti_sandbox',             '',                          'boolean',  'payment', 'Khalti Sandbox Mode',           'Enable to use the Khalti test/sandbox environment. Overrides the KHALTI_SANDBOX env variable when checked.',                 NOW(), NOW()),
  ('gemini_api_key',             '',                          'password', 'ai',      'Google Gemini API Key',         'Free API key for Google Gemini AI. Get yours at https://aistudio.google.com/ — used to auto-generate dress descriptions from photos.', NOW(), NOW()),
  ('google_client_id',           '',                          'text',     'oauth',   'Google Client ID',              'OAuth 2.0 Client ID from Google Cloud Console. Required for "Login with Google".',                                            NOW(), NOW()),
  ('google_client_secret',       '',                          'password', 'oauth',   'Google Client Secret',          'OAuth 2.0 Client Secret from Google Cloud Console. Required for "Login with Google".',                                        NOW(), NOW());

-- ------------------------------------------------------------
-- Dress categories
-- parent_id is NULL for all top-level categories
-- ------------------------------------------------------------
INSERT IGNORE INTO `dress_categories`
  (`name`, `slug`, `icon`, `is_active`, `sort_order`, `parent_id`, `created_at`, `updated_at`)
VALUES
  ('Bridal Wear',    'bridal-wear',    '👰', 1, 1, NULL, NOW(), NOW()),
  ('Party Dresses',  'party-dresses',  '🎉', 1, 2, NULL, NOW(), NOW()),
  ('Ethnic Wear',    'ethnic-wear',    '🪷', 1, 3, NULL, NOW(), NOW()),
  ('Casual Dresses', 'casual-dresses', '👗', 1, 4, NULL, NOW(), NOW()),
  ('Formal Wear',    'formal-wear',    '💼', 1, 5, NULL, NOW(), NOW()),
  ('Festival Wear',  'festival-wear',  '🎊', 1, 6, NULL, NOW(), NOW());

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
