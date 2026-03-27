# Dress Rental

An AI-assisted dress rental website and web app built with **Laravel 13**, featuring online booking, eSewa/Khalti payment integration, and cPanel shared-hosting deployment.

---

## Table of Contents

1. [Requirements](#requirements)
2. [Quick Start — Local Development](#quick-start--local-development)
3. [cPanel Shared Hosting Deployment](#cpanel-shared-hosting-deployment)
4. [Manual SQL Import (phpMyAdmin)](#manual-sql-import-phpmyadmin)
5. [Environment Variables Reference](#environment-variables-reference)
6. [Payment Gateways](#payment-gateways)
7. [Running Tests](#running-tests)
8. [Useful Commands](#useful-commands)

---

## Requirements

| Tool | Minimum version |
|------|----------------|
| PHP  | 8.3 |
| Composer | 2.x |
| Node.js | 20 LTS |
| npm  | 10.x |
| MySQL | 5.7 / MariaDB 10.4 |

---

## Quick Start — Local Development

```bash
# 1. Clone the repository
git clone https://github.com/marriagestationpvtltd-lang/dressrental.git
cd dressrental

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies and build front-end assets
npm install && npm run build

# 4. Copy environment files
cp .env.example .env
cp .env.testing.example .env.testing

# 5. Edit .env — set DB_DATABASE, DB_USERNAME, DB_PASSWORD
#    (create a local MySQL database first, e.g. "dressrental_dev")

# 6. Generate application key
php artisan key:generate

# 7. Run migrations
php artisan migrate

# 8. Seed the database (admin + sample dresses)
php artisan db:seed

# 9. Start the development server
composer run dev
```

Open `http://localhost:8000` in your browser.

Default test credentials (after seeding):

| Role  | Email | Password |
|-------|-------|----------|
| Admin | admin@dressrental.com | password |
| User  | user@dressrental.com  | password |

---

## cPanel Shared Hosting Deployment

> See **[DATABASE_SETUP.md](DATABASE_SETUP.md)** for the full database setup guide including two-database (prod / test) configuration, auto-deploy via cPanel Git, and upgrade procedures.

### Summary

1. **Create two MySQL databases** in cPanel → MySQL® Databases:
   - `cpanelusername_dressrental_prod`
   - `cpanelusername_dressrental_test`

2. **Configure environment files**:
   ```bash
   cp .env.example .env          # edit with your real credentials
   cp .env.testing.example .env.testing
   ```

3. **Initialize production database** (after uploading files):
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   composer run setup:prod
   ```

4. **Set up cPanel Git auto-deploy**:
   - cPanel → Git™ Version Control → Create Repository
   - Edit `.cpanel.yml` — replace `cpanelusername` with your actual cPanel username
   - Every `git push` to `main` will automatically migrate and cache

---

## Manual SQL Import (phpMyAdmin)

Use this method when SSH / Artisan is not available (e.g. fresh cPanel account, shared hosting with no terminal access).

### Step 1 — Create the database in cPanel

1. Log in to **cPanel → MySQL® Databases**.
2. Create a database, e.g. `cpanelusername_dressrental_prod`.
3. Create a MySQL user and grant it **All Privileges** on that database.

### Step 2 — Import the schema

1. In cPanel, open **phpMyAdmin**.
2. Click the database name on the left sidebar.
3. Click the **Import** tab.
4. Click **Choose File** and select **`database/schema.sql`** from this repository.
5. Leave all other settings at their defaults.
6. Click **Go**.

All tables will be created and the `migrations` table will be pre-populated so that `php artisan migrate` knows the schema is already in place.

### Step 3 — Create the admin account

After importing, create your admin user via one of these methods:

**Option A — Artisan (if terminal is available):**
```bash
# Make sure .env has ADMIN_EMAIL, ADMIN_PASSWORD, ADMIN_PHONE set
php artisan db:seed --class=ProductionSeeder --force
```

**Option B — phpMyAdmin (no terminal):**

1. In phpMyAdmin select the database → click the `users` table → **Insert** tab.
2. Fill in the row:

| Column | Value |
|--------|-------|
| name | Admin |
| email | your-admin@email.com |
| phone | 98XXXXXXXX |
| role | admin |
| password | *(see note below)* |
| created_at | 2026-01-01 00:00:00 |
| updated_at | 2026-01-01 00:00:00 |

> **Password note:** The `password` column must store a **bcrypt hash**.
> Generate one at <https://bcrypt-generator.com> (12 rounds) and paste it in.
> Never store a plain-text password.

3. Click **Go** to save.

### Step 4 — Configure `.env` and cache

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Environment Variables Reference

| Variable | Description |
|----------|-------------|
| `APP_URL` | Full URL of the site, e.g. `https://yourdomain.com` |
| `DB_DATABASE` | MySQL database name (cPanel format: `user_dbname`) |
| `DB_USERNAME` | MySQL user |
| `DB_PASSWORD` | MySQL password |
| `ADMIN_EMAIL` | Email for the admin account created by `ProductionSeeder` |
| `ADMIN_PASSWORD` | Plain-text password for the admin (hashed during seeding) |
| `ADMIN_PHONE` | Admin phone number |
| `ESEWA_MERCHANT_ID` | eSewa merchant ID |
| `ESEWA_SECRET_KEY` | eSewa secret key |
| `ESEWA_SANDBOX` | `true` for testing, `false` for live |
| `KHALTI_SECRET_KEY` | Khalti secret key |
| `KHALTI_PUBLIC_KEY` | Khalti public key |
| `KHALTI_SANDBOX` | `true` for testing, `false` for live |

Copy `.env.example` to `.env` for production, or `.env.testing.example` to `.env.testing` for tests.

---

## Payment Gateways

### eSewa
- Sandbox: `ESEWA_MERCHANT_ID=EPAYTEST`, `ESEWA_SECRET_KEY=8gBm/:&EnhH.1/q`, `ESEWA_SANDBOX=true`
- Production: obtain real credentials from the [eSewa merchant portal](https://merchant.esewa.com.np/)

### Khalti
- Sandbox keys are in `.env.testing.example`
- Production: obtain live keys from the [Khalti merchant dashboard](https://khalti.com/merchant/)

---

## Running Tests

```bash
# Make sure .env.testing is configured and the test database exists
composer run setup:test   # first time only — migrates + seeds test database
composer run test         # run PHPUnit test suite
```

---

## Useful Commands

```bash
# Start local dev server (server + queue + logs + Vite hot-reload)
composer run dev

# Production — rebuild caches after code changes
php artisan config:cache && php artisan route:cache && php artisan view:cache

# Create a new migration
php artisan make:migration add_column_to_table --table=table_name

# Run pending migrations (safe on production)
php artisan migrate --force

# Export production database backup (run in phpMyAdmin or via SSH)
mysqldump -u DB_USER -p DB_NAME > backup_$(date +%Y-%m-%d).sql
```

---

> ⚠️ **Never run** `migrate:fresh`, `migrate:reset`, or `db:seed` against the production database — they will destroy data. See [DATABASE_SETUP.md](DATABASE_SETUP.md) for the full safety guide.

