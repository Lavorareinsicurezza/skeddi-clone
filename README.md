# Skeddi — Training & Certification Management

A Laravel 12 application for managing company training plans, worker certifications, and document expiration tracking with automated email notifications. Supports multi-company management with role-based access control and per-location SMTP configuration.

---

## Prerequisites

| Tool | Required Version |
|------|-----------------|
| PHP | ^8.2 |
| Composer | 2.x |
| Node.js | ^18 |
| npm | ^9 |
| SQLite | bundled with PHP (default) |

---

## Quick Start

```bash
git clone https://github.com/Lavorareinsicurezza/skeddi-clone skeddi
cd skeddi
composer setup
php artisan db:seed
php artisan serve
```

Open `http://localhost:8000` and log in with the default credentials below.

---

## Setup — Step by Step

### 1. Clone

```bash
git clone https://github.com/Lavorareinsicurezza/skeddi-clone skeddi
cd skeddi
```

### 2. Install & Bootstrap

```bash
composer setup
```

This runs in sequence:

1. `composer install` — installs PHP dependencies
2. Copies `.env.example` → `.env`
3. `php artisan key:generate` — generates `APP_KEY`
4. `php artisan migrate --force` — runs all database migrations
5. `npm install` — installs JS dependencies
6. `npm run build` — compiles frontend assets (Vite + Tailwind + Flowbite)

### 3. Configure Environment

Open `.env` and update values for your environment:

```env
APP_NAME="Skeddi"
APP_URL=http://localhost:8000

# Database — MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skeddi
DB_USERNAME=root
DB_PASSWORD=

# Mail — "log" writes emails to storage/logs instead of sending
MAIL_MAILER=log
```

### 4. Seed the Database

```bash
php artisan db:seed
```

Creates companies, permissions, roles, and a default superadmin account.

**Default credentials:**

| Field | Value |
|-------|-------|
| Email | `ismail@devop360.com` |
| Password | `devop360` |
| Role | `superadmin` |

> Change these credentials before deploying to production.

### 5. Start Development Server

```bash
php artisan serve
```

Starts four concurrent processes:

| Process | Command | Purpose |
|---------|---------|---------|
| `server` | `php artisan serve` | App at `http://localhost:8000` |
| `queue` | `php artisan queue:listen --tries=1` | Processes background jobs |
| `logs` | `php artisan pail --timeout=0` | Streams application logs |
| `vite` | `npm run dev` | Hot-reloads CSS/JS assets |

---

## Development Commands

```bash
composer test        # Run test suite
npm run build        # Build production assets
vendor/bin/pint      # Fix code style (Laravel Pint)
```

Individual services:

```bash
php artisan serve
php artisan queue:listen --tries=1
php artisan pail --timeout=0
npm run dev
```

Background job (expiry check + email notifications):

```bash
php artisan expiry:check-and-mail
```

---

## Production Deployment

```bash
npm run build
php artisan optimize
```

Set in `.env`:

```env
APP_ENV=production
APP_DEBUG=false
```

---

## Running Tests

```bash
composer test
```

Tests use an in-memory SQLite database — no separate test database needed.

---

## Troubleshooting

**`migrate` fails — "Access denied" or "Unknown database"**
Make sure MySQL is running, the database exists, and `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in `.env` are correct.

**Assets return 404**
Assets aren't compiled. Run `npm run build` or keep `npm run dev` running.

**Emails not sending**
`MAIL_MAILER=log` (default) writes emails to `storage/logs/laravel.log`. Set `MAIL_MAILER=smtp` and configure SMTP credentials in `.env`, or configure per-company SMTP in the admin panel under **Settings**.

**Queue jobs not processing**
The queue worker must be running. Start it with `php artisan queue:listen --tries=1` or use `composer dev`.
