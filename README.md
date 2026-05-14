# Document Nest

Document Nest is a private personal document vault built with Laravel, Inertia, and Vue.

This app is designed for securely storing important documents, organizing them with categories and tags, previewing files, and tracking expiry dates.

## Current Focus (V1)

V1 is focused on single-user personal document management:

- Google-only authentication
- Private document upload and storage
- Category and tag organization
- Document metadata (dates, status, notes, file info)
- PDF and image preview
- Authorized file download
- Search, filters, sorting, and archive flow
- Dashboard with expiring/missing-expiry visibility

Not included in V1: teams, family sharing, OCR/AI, billing, audit logs, and S3 storage.

## Planned Stack

- Backend: Laravel 13 + PHP 8.5
- Frontend: Inertia.js + Vue 3
- Styling: Tailwind CSS 4 + Tailwind UI
- Database: PostgreSQL
- Auth: Google OAuth via Laravel Socialite
- Storage: private local disk (v1)
- Build tool: Vite

## Security Model

- All documents, categories, and tags are user-owned.
- All queries are scoped to the authenticated user.
- Uploaded files are stored outside public paths.
- Preview/download routes must enforce authorization.

## Local Setup

1. Install dependencies:

```bash
composer install
npm install
```

2. Configure environment:

```bash
cp .env.example .env
php artisan key:generate
```

3. Configure PostgreSQL credentials in `.env`, then run:

```bash
php artisan migrate
```

4. Start development servers:

```bash
php artisan serve
npm run dev
```

5. Open the app at the Laravel local URL shown by `php artisan serve`.

## Test Commands

Run the test suite:

```bash
php artisan test --compact
```

## Implementation Plan

The full build plan, feature scope, phased roadmap, and test plan are tracked in:

- [PLAN.md](PLAN.md)
