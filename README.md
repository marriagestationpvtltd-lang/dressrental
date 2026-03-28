# Dress Rental

An AI-assisted dress rental website and web app built with **Laravel 13**, featuring online booking, eSewa/Khalti payment integration, and cPanel shared-hosting deployment.

---

## Table of Contents

1. [Requirements](#requirements)
2. [Quick Start — Local Development](#quick-start--local-development)
3. [cPanel Shared Hosting Deployment — No Terminal Needed](#cpanel-shared-hosting-deployment--no-terminal-needed)
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

## cPanel Shared Hosting Deployment — No Terminal Needed

> **You do not need to open the cPanel Terminal at all.** The steps below use only the cPanel web interface (File Manager, MySQL® Databases, phpMyAdmin, Git™ Version Control).

> See **[DATABASE_SETUP.md](DATABASE_SETUP.md)** for the full database setup guide including two-database (prod / test) configuration, auto-deploy via cPanel Git, and upgrade procedures.

> ⚠️ **Two separate sets of credentials — a common source of confusion:**
>
> | Where you set it | What it is |
> |---|---|
> | **`database/production.sql`** INSERT row | Your **website admin login** (email + bcrypt password) — used to log in to the admin panel |
> | **`.env`** `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | Your **MySQL server connection** — the database name and user you created in cPanel → MySQL® Databases |
>
> Both must be configured. Updating only the SQL file will not connect the app to the database. Updating only `.env` will not create your admin account.

### Step 1 — Create the MySQL database

1. Log in to **cPanel → MySQL® Databases**.
2. Create a database: `cpanelusername_dressrental_prod`
3. Create a MySQL user and grant it **All Privileges** on that database.

### Step 2 — Import the database schema (phpMyAdmin)

1. Open **cPanel → phpMyAdmin**.
2. Click the database name on the left sidebar.
3. Click the **Import** tab → **Choose File**.
4. Select **`database/production.sql`** from this repository.

   > ⚠️ Before importing, open `database/production.sql` in a text editor and replace the three placeholders (search for `<<<REPLACE_WITH_BCRYPT_HASH>>>`):
   > - `admin@yourdomain.com` → your real admin email
   > - `<<<REPLACE_WITH_BCRYPT_HASH>>>` → a bcrypt hash of your admin password.
   >   Preferred (offline): `php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_BCRYPT, ['cost'=>12]);"`
   >   Online alternative: <https://bcrypt-generator.com> (12 rounds) — **do not enter your real password** into any third-party site; use a throwaway password and change it after first login.
   > - `98XXXXXXXX` → your real phone number

5. Click **Go**.

### Step 3 — Set up the `.env` file (File Manager)

> **Finding your document root:** The folder to navigate to depends on where your site lives.
> Open **cPanel → Subdomains** (or **Domains**) and look at the **Document Root** column
> next to your domain or subdomain name.
> - Main domain → usually `public_html`
> - Subdomain (e.g. `your-subdomain.yourdomain.com`) → usually a folder with the same name
>   (a folder at the same level as `public_html`, not inside it)

1. Open **cPanel → File Manager** → navigate to your site's document root folder
   (e.g. `your-subdomain.yourdomain.com/` for a subdomain, or `public_html/` for a main domain).
2. If `.env` does **not** exist yet, copy `.env.example` and rename the copy to `.env`.
   *(Right-click → Copy; then rename the copy.)*
3. Right-click `.env` → **Edit** and fill in the values below:

```env
APP_URL=https://your-subdomain.yourdomain.com

DB_DATABASE=cpanelusername_dressrental_prod
DB_USERNAME=cpanelusername_dbuser
DB_PASSWORD=your_strong_db_password

ADMIN_EMAIL=admin@yourdomain.com
ADMIN_PHONE=98XXXXXXXX
```

4. Save the file.

> **APP_KEY** is generated automatically on the first deploy (step 5 below).
> You do not need to generate it manually.

### Step 4 — Set up cPanel Git™ Version Control (auto-deploy)

1. Find your document root path:
   **cPanel → Subdomains** (or **Domains**) → look at the **Document Root** column.
   - Example for a subdomain: `/home/cpanelusername/your-subdomain.yourdomain.com`
   - Example for main domain: `/home/cpanelusername/public_html`

2. Open **cPanel → Git™ Version Control → Create Repository**.
   - **Clone URL**: `https://github.com/marriagestationpvtltd-lang/dressrental.git`
   - **Repository Path**: your document root path from step 1

3. Before cloning, edit **`.cpanel.yml`** in this repository — set the `DEPLOYPATH`
   line to your full document root path (the same path used above), then commit and push.
   ```yaml
   # Subdomain example:
   - export DEPLOYPATH=/home/cpanelusername/your-subdomain.yourdomain.com/
   # Main domain example:
   - export DEPLOYPATH=/home/cpanelusername/public_html/
   ```

4. Back in cPanel, click **Manage → Deploy HEAD Commit**.

cPanel will automatically:
- Copy all files to your document root
- Run `composer install --no-dev`
- Create `.env` from `.env.example` if it is missing
- Generate `APP_KEY` if it is not already set
- Build front-end CSS/JS (`npm run build`) if Node.js is available on the server
- Create the `public/storage` symlink for uploaded files
- Run `php artisan migrate --force`
- Rebuild config / route / view caches

> After the first deploy, every `git push` to `main` triggers this automatically —
> no terminal or manual action required.

### Front-end assets (CSS / JS) — if your host has no Node.js

If cPanel reports that `npm` is unavailable and the site has no styling, build the
assets on your local machine first, then commit them:

```bash
# Run once on your local machine
npm ci && npm run build
git add public/build
git commit -m "chore: pre-build front-end assets for no-Node deployment"
git push
```

cPanel will pick up the pre-built files on the next deploy and skip the npm step.

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
4. Click **Choose File** and select **`database/production.sql`** from this repository.
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
> Generate one free at <https://bcrypt-generator.com> (12 rounds) and paste it in.
> Never store a plain-text password.

3. Click **Go** to save.

### Step 4 — Configure `.env` and cache

The caches are rebuilt automatically by `.cpanel.yml` on every deploy.
If you need to rebuild them manually, use the cPanel Terminal or run through
the Git auto-deploy by pushing any change to `main`.

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

## Troubleshooting

### "Website not loading after updating credentials in the MySQL file"

This is the most common deployment mistake. There are **two completely separate** credential sets:

1. **`database/production.sql`** — website admin login  
   The `INSERT INTO users` row sets the admin **email**, **phone**, and **bcrypt password** used to log in to the website's admin panel.  
   These have **nothing to do with the MySQL server connection**.

2. **`.env` DB variables** — MySQL server connection  
   `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` are the cPanel MySQL database name and user credentials you created in **cPanel → MySQL® Databases**.  
   If these are still set to the placeholder values (`cpanelusername_dressrental_prod`, `cpanelusername_dbuser`, `your_strong_db_password`), the website cannot connect to the database and will not load.

**Fix:** Open `.env` in **cPanel → File Manager** and replace the three `DB_*` placeholder values with your real cPanel MySQL credentials.

### "404 Not Found — website shows nothing after setting up .env"

This happens because cPanel deploys the full Laravel repository into `public_html/`, but
Laravel's actual web entry point is the `public/` subdirectory inside it.
The root `.htaccess` (included in this repository) fixes this by forwarding all web
requests into `public/` automatically — **no manual action needed**.

If you still get 404 after a fresh deploy, check that `.htaccess` was copied to
`public_html/`. In **cPanel → File Manager → public_html**, look for `.htaccess`
(you may need to tick **"Show Hidden Files"**). If it is missing, trigger a new deploy:
**cPanel → Git™ Version Control → Manage → Deploy HEAD Commit**.

### "Website shows an error page / blank page after first deploy"

Check these in order:

| Symptom | Likely cause | Fix |
|---|---|---|
| 404 Not Found on every page | `public_html/.htaccess` missing | Trigger a new Git deploy — `.cpanel.yml` copies it automatically |
| "No application encryption key" | `APP_KEY` is blank in `.env` | Trigger a new Git deploy — `.cpanel.yml` auto-generates it |
| "SQLSTATE: Connection refused" or "Access denied" | Wrong `DB_*` values in `.env` | Edit `.env` via File Manager with real cPanel MySQL credentials |
| Page has no CSS or styling | `public/build/` missing (npm didn't run) | Build locally: `npm ci && npm run build`, commit `public/build/`, push |
| Uploaded images not showing | `public/storage` symlink missing | Trigger a new Git deploy — `.cpanel.yml` runs `storage:link` |
| "Class not found" errors | `vendor/` not installed | Trigger a new Git deploy — `.cpanel.yml` runs `composer install` |
| Cannot log in as admin | Admin account not created or wrong password hash | Re-import `production.sql` after fixing the hash placeholder |

### How to re-trigger the auto-deploy

If you fix `.env` via File Manager and need to rebuild caches without a new git push:
**cPanel → Git™ Version Control → Manage → Deploy HEAD Commit**

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

