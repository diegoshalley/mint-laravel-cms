# Ministry of the Interior — Laravel CMS

Interior website foundation for replacing `mint.gov.gh` with a Laravel 12 application and a governed content-management system.

## Product surfaces

- **Public website:** accessible, mobile-first, searchable, multilingual-ready, and optimized for slow connections.
- **Editorial CMS:** role-based authoring, review, approval, scheduling, versioning, rollback, media governance, and audit trails.
- **Integration layer:** e-services links, agency directory, emergency contacts, analytics, email/SMS adapters, and future government APIs.

## Recommended stack

- PHP 8.3+, Laravel 12, Blade, Livewire 3, Alpine.js, Tailwind CSS 4
- PostgreSQL 16
- Redis for cache, sessions, queues, and rate limiting
- S3-compatible object storage for media and public documents
- Laravel Horizon for queues; Laravel Scout + Meilisearch for full-text search
- Pest for automated tests; Playwright for critical browser journeys
- Nginx, PHP-FPM, Supervisor, TLS, automated backups, and CI/CD

## Architecture decisions

1. No WordPress runtime or plugin dependency.
2. Public rendering remains server-first. Essential content works without JavaScript.
3. Pages are structured content, not arbitrary HTML blobs.
4. Publishing uses maker-checker approval and preserves every revision.
5. Documents have ownership, category, publication status, revision, and retention metadata.
6. Every privileged action is auditable.
7. Public services are separate modules or integrations—not hard-coded menu links.

See `docs/PRODUCT-BLUEPRINT.md` for the audited information architecture and delivery plan.

## Working from multiple computers

The public Git repository is the source of truth. Each computer should clone the repository once, create its own untracked `.env`, and work through short-lived branches.

```bash
git clone https://github.com/diegoshalley/mint-laravel-cms.git
cd mint-laravel-cms
cp .env.example .env
composer install
npm install
php artisan key:generate
```

Start each task from an up-to-date main branch:

```bash
git switch main
git pull --ff-only
git switch -c feature/short-description
```

Commit and publish the branch, then merge it through a pull request. Do not work directly on `main`, even when working alone; branch protection and CI should remain enabled.
