# Database Setup Guide

Complete setup guide for cPanel shared hosting with two separate MySQL databases —
one for production and one for testing/development.

---

## Overview

| Database | Purpose | Seed command |
|---|---|---|
| `cpanelusername_dressrental_prod` | Live website — real customer data | `ProductionSeeder` (admin only) |
| `cpanelusername_dressrental_test` | Testing — safe to reset at any time | `DatabaseSeeder` (admin + sample dresses) |

---

## Step 1 — Create two MySQL databases in cPanel

1. Log in to **cPanel → MySQL® Databases**.
2. Create two databases:
   - `cpanelusername_dressrental_prod`
   - `cpanelusername_dressrental_test`
3. Create a MySQL user for each (or one shared user) and grant **All Privileges**.

> Replace `cpanelusername` with your actual cPanel username throughout this guide.

---

## Quick start — Manual phpMyAdmin import (no PHP/Composer needed)

If you cannot run PHP commands on the server, you can set up both databases
directly from phpMyAdmin using the two ready-made SQL files in the
`database/` folder.

| File | Target database | What it creates |
|---|---|---|
| `database/production.sql` | `cpanelusername_dressrental_prod` | Schema + admin user placeholder |
| `database/testing.sql`    | `cpanelusername_dressrental_test` | Schema + admin + demo user + 6 categories + 12 sample dresses |

### Import steps (same for both files)

1. Log in to **cPanel → phpMyAdmin**.
2. Select the target database from the left panel.
3. Click the **Import** tab.
4. Click **Choose File** and select the SQL file.
5. Click **Go**.

### ⚠️ Production file — required edit before importing

Open `database/production.sql` in a text editor and replace the three
placeholders **before** importing:

| Placeholder | Replace with |
|---|---|
| `admin@yourdomain.com` | Your real admin e-mail address |
| `<<<REPLACE_WITH_BCRYPT_HASH>>>` | bcrypt hash of your admin password (see below) |
| `98XXXXXXXX` | Your real phone number |

**Generate the bcrypt hash** (run in cPanel Terminal or locally):

```bash
php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_BCRYPT, ['cost'=>12]);"
```

### Testing file credentials (ready to use)

| Account | Email | Password |
|---|---|---|
| Admin | `admin@dressrental.com` | `password` |
| Demo user | `user@dressrental.com` | `password` |

---

## Step 2 — Configure environment files

### Production (`.env`)

Copy the example and fill in your real values:

```bash
cp .env.example .env
```

Key values to update:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_DATABASE=cpanelusername_dressrental_prod
DB_USERNAME=cpanelusername_dbuser
DB_PASSWORD=your_strong_db_password

ADMIN_EMAIL=admin@yourdomain.com
ADMIN_PASSWORD=YOUR_SECURE_PASSWORD_HERE
ADMIN_PHONE=98XXXXXXXX

ESEWA_SANDBOX=false
KHALTI_SANDBOX=false
```

### Testing (`.env.testing`)

```bash
cp .env.testing.example .env.testing
```

Key values to update:

```env
APP_ENV=testing
DB_DATABASE=cpanelusername_dressrental_test
DB_USERNAME=cpanelusername_testuser
DB_PASSWORD=your_test_db_password
```

Neither `.env` nor `.env.testing` is committed to GitHub — they are listed in `.gitignore`.

---

## Step 3 — Initialize databases

### Production database (schema + admin user only)

```bash
# Install dependencies first (outside composer scripts)
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Then run the setup script
composer run setup:prod
```

This script:
1. Creates `.env` from `.env.example` if missing
2. Generates an `APP_KEY`
3. Runs all migrations (`--force`)
4. Seeds only the admin account (`ProductionSeeder`)
5. Caches config / routes / views

### Testing database (schema + sample data)

```bash
# Install dependencies first
composer install
npm install && npm run build

# Then run the test setup script
composer run setup:test
```

This script:
1. Creates `.env.testing` from the example if missing
2. Generates an `APP_KEY` for the testing environment
3. Runs all migrations against the test database
4. Seeds admin + demo user + 6 categories + 12 sample dresses (`DatabaseSeeder`)

Default test credentials:
- Admin: `admin@dressrental.com` / `password`
- Demo user: `user@dressrental.com` / `password`

> **Warning:** Never run `composer run setup:test` or `php artisan db:seed` against the
> production database. Production should only ever receive migrations, not seeders.

---

## Step 4 — cPanel Git auto-deploy

### One-time setup

1. In cPanel → **Git™ Version Control** → **Create Repository**
   - Repository path: `/home/cpanelusername/public_html`
   - Clone URL: `https://github.com/marriagestationpvtltd-lang/dressrental.git`
2. Edit **`.cpanel.yml`** and replace `cpanelusername` with your actual username.
3. Commit and push `.cpanel.yml` to GitHub.

### After each `git push`

cPanel will automatically:
1. Pull the latest code
2. Run `composer install --no-dev`
3. Run `php artisan migrate --force` ← **safe, never deletes data**
4. Rebuild config / route / view caches

You can also trigger it manually:
**cPanel → Git™ Version Control → Manage → Deploy HEAD Commit**

---

## Upgrading from an older database (zero data loss)

Laravel migrations are **additive** — they only add new tables or columns, never remove
existing data. Follow this pattern for every schema change:

### Adding a new column

```bash
php artisan make:migration add_material_to_dresses_table --table=dresses
```

Edit the generated file:

```php
public function up(): void
{
    Schema::table('dresses', function (Blueprint $table) {
        // Always use ->nullable() for new columns so existing rows are unaffected.
        $table->string('material')->nullable()->after('color');
    });
}

public function down(): void
{
    Schema::table('dresses', function (Blueprint $table) {
        $table->dropColumn('material');
    });
}
```

Deploy and run:

```bash
git push origin main           # cPanel auto-deploy runs migrate --force
# or manually on the server:
php artisan migrate --force
```

### Adding a new table

```bash
php artisan make:migration create_reviews_table
```

```php
public function up(): void
{
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('dress_id')->constrained('dresses')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->tinyInteger('rating');
        $table->text('comment')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('reviews');
}
```

### Commands that are SAFE on production

```bash
php artisan migrate --force          # adds new tables/columns only
php artisan config:cache             # rebuilds config cache
php artisan route:cache              # rebuilds route cache
php artisan view:cache               # rebuilds view cache
```

### Commands that are DANGEROUS on production — never run these

```bash
php artisan migrate:fresh            # drops ALL tables, ALL data lost ❌
php artisan migrate:reset            # rolls back every migration ❌
php artisan db:seed --force          # inserts sample data into production ❌
php artisan migrate:refresh          # fresh + re-migrate, all data lost ❌
```

---

## Backup strategy

1. **cPanel → phpMyAdmin** → select `cpanelusername_dressrental_prod` → **Export → Quick → SQL → Go**
2. Save the `.sql` file with the date in its name, e.g. `prod_backup_2026-03-27.sql`.
3. To restore: **Import** that file in phpMyAdmin.

Automate backups via **cPanel → Backup Wizard** or a cron job.

---

## Seeder reference

| Class | When to use |
|---|---|
| `ProductionSeeder` | `php artisan db:seed --class=ProductionSeeder --force` — production, seeds admin account only |
| `SampleDataSeeder` | `php artisan db:seed --class=SampleDataSeeder --force` — testing, seeds demo data |
| `DatabaseSeeder` | `php artisan db:seed --force` — local/testing, runs both of the above |
