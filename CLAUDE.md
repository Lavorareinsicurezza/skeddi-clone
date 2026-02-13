# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application for managing company training plans, worker certifications, and document expiration tracking with automated email notifications. The system supports multi-company management with role-based access control and per-location SMTP configuration.

## Development Commands

### Setup
```bash
composer setup  # Install dependencies, generate key, run migrations, build assets
```

### Development
```bash
composer dev    # Start all dev services (PHP server, queue worker, logs, Vite)
                # Runs on: server (http://localhost:8000), queue, pail logs, vite
```

Individual services:
```bash
php artisan serve                    # Start development server
php artisan queue:listen --tries=1   # Start queue worker
php artisan pail --timeout=0         # Tail application logs
npm run dev                          # Start Vite dev server
```

### Testing
```bash
composer test                        # Run all tests
php artisan test --filter TestName   # Run specific test
```

### Building
```bash
npm run build   # Build production assets
```

### Code Quality
```bash
vendor/bin/pint                      # Fix code style with Laravel Pint
```

### Background Jobs
```bash
php artisan expiry:check-and-mail    # Check training/document expiries and send reminder emails
```

## Architecture

### Domain Model

The application manages a hierarchical structure:
- **Companies**: Top-level entities with contacts and settings
- **Operating Locations**: Physical locations per company with optional SMTP overrides
- **Workers**: Employees assigned to companies and locations
- **Training Plan Records**: Worker course completions with expiration dates
- **Company Course Types**: Company-specific course definitions
- **Documents**: Company documents with expiration tracking
- **Visit Types**: Scheduled visit types with expiry dates

### Permission System

Uses Spatie Laravel Permission for role-based access control:
- Custom middleware `EnsurePermission` maps controller actions to permissions
- Permission format: `{action} {resource}` (e.g., "view companies", "edit training-plan")
- Action mapping: `index/show → view`, `create/store → create`, `edit/update → edit`, `destroy → delete`
- Check permissions with `$user->can('permission name')`

### Email Notification System

Multi-tiered SMTP configuration:
1. **Global SMTP**: Default settings stored in `settings` table (per company)
2. **Location SMTP**: Operating locations can override SMTP settings for their workers
3. **Notification Logic**: The `CheckExpiryAndSendEmails` command checks training plan expirations against configurable notification periods (stored in settings as JSON array)
4. **Mail Tracking**: `expiry_mail_logs` table prevents duplicate notifications

### Route Structure

All protected routes use `auth` middleware and are prefixed with `admin.*`:
- **Resource routes**: Follow standard Laravel conventions (index, create, store, show, edit, update, destroy)
- **Export/Import routes**: Separate routes for bulk operations (e.g., `/companies/export`, `/companies/import`)
- **Company context**: Selected company stored in session, used across various views
- **Permission gates**: All routes protected by `ensure.permission` middleware

### Frontend

- **Stack**: Blade templates + Vite + TailwindCSS + Flowbite
- **Layout**: Main layouts in `resources/views/layouts/`
- **Sections**: `admin/` for admin views, `company/` for company-specific views, `auth/` for authentication
- **Assets**: Compiled from `resources/js/app.js` and `resources/css/app.css`

### Key Packages

- `spatie/laravel-permission`: Role and permission management
- `maatwebsite/excel`: Excel import/export functionality
- `barryvdh/laravel-dompdf`: PDF generation for reports and charts
- `doctrine/dbal`: Database schema inspection for migrations

## File Organization

### Controllers
- `app/Http/Controllers/Admin/*`: Admin CRUD controllers for all resources
- `app/Http/Controllers/DashboardController.php`: Main dashboard with deadlines view and email sending
- `app/Http/Controllers/ChartController.php`: Organization chart generation and PDF export

### Models
All models in `app/Models/`:
- Use Eloquent relationships extensively
- Key relationships: Company hasMany Workers, Workers belongsTo OperatingLocation, TrainingPlanRecord belongsTo Worker/Company

### Imports/Exports
- `app/Imports/*`: Excel import classes (Users, Companies)
- `app/Exports/*`: Excel export classes (Deadlines, various resources)

### Views
- `resources/views/admin/*`: Admin section views
- `resources/views/company/*`: Company-specific views
- `resources/views/emails/*`: Email templates
- `resources/views/layouts/*`: Layout templates

## Database

### Migrations
Migrations in `database/migrations/` include:
- Core tables: users, companies, workers, operating_locations
- Course management: course_types, company_course_types, training_plan_records
- Documents: documents, document_types, training_plan_documents
- Visits: visit_types, company_visit_types
- System: settings, course_renewal_logs, expiry_mail_logs

### Testing
- Uses SQLite in-memory database for tests
- Test suites: `tests/Feature/` and `tests/Unit/`
- Run tests with `composer test`

## Development Workflows

### Adding New Resource
1. Create migration: `php artisan make:migration create_tablename_table`
2. Create model in `app/Models/`
3. Create controller in `app/Http/Controllers/Admin/`
4. Add routes in `routes/web.php` with permission middleware
5. Create views in `resources/views/admin/resource/`
6. Add permissions using Spatie package

### Working with Permissions
- Permissions are managed through the admin panel
- Format: `{action} {resource}` in lowercase
- Check `app/Http/Middleware/EnsurePermission.php` for action mappings
- Use `ensure.permission:resource,action` middleware on routes

### Email Notifications
- Email templates in `resources/views/emails/`
- SMTP config stored per company in `settings` table
- Operating locations can override SMTP for their workers
- Notification periods configurable per company (JSON array in settings)
- Use `CheckExpiryAndSendEmails` command for expiry checking

## Common Patterns

### Controller Methods
- Standard CRUD methods return views with data
- Export methods return Excel downloads via Maatwebsite\Excel
- Import methods validate and process uploaded Excel files
- API methods return JSON responses for AJAX requests

### Company Context
- Selected company stored in session via `admin.select-company` route
- Retrieved with `session('selected_company_id')`
- Used to filter data in company-specific views (workers, documents, training plans)

### Date Handling
- Expiration dates stored in database
- Carbon used for date manipulation
- Deadline calculations in `DashboardController` check various expiry fields
- Notification periods customizable per company (e.g., [90, 30] means notify 90 and 30 days before expiry)
