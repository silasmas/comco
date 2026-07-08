# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

COMCO is the institutional website and back-office for the "Commission de la Concurrence" (Competition Commission, DRC — `institution.gouv.cd`). It is a Laravel 13 application, not a generic starter — content is entirely French (`APP_LOCALE=fr`) and models a real government body: public info pages, news posts, a public forum, e-service submission forms (merger notifications, exemption requests, complaints, dangerous-product reports), newsletter signup, and a contact form. There is no payment/SMS integration; outbound comms are email only (see `app/Support/InstitutionNotifier.php`, `SubmitterNotifier.php`, and `resources/views/mail/`).

A distinctive feature: the app ships with a self-service **site installer** (`app/Http/Controllers/Admin/InstallationPageController.php`, `InstallationActionController.php`, `app/Support/SiteInstaller.php`, `SiteDeploymentState.php`) reachable at `/admin/site-installation`. Until installation is marked complete, the Filament admin panel is redirected there (`RedirectAdminToInstallation` / `FilamentInstallAccess` middleware) so a non-technical operator can run migrations, create the storage symlink, cache config, seed initial posts, create the first super admin, and "launch" the site — all from a UI instead of the CLI.

## Commands

Install dependencies:
```
composer install
npm install
```

Full local setup (copies .env, generates key, migrates, installs & builds JS) — via Composer script:
```
composer run setup
```

Dev server (serve + queue listener + log tailing + Vite, all concurrently):
```
composer run dev
```

Build frontend assets:
```
npm run build
```
Frontend dev server only:
```
npm run dev
```

Lint/format (Pint is installed as a dev dependency; no npm lint script is configured):
```
vendor/bin/pint
```

Run the full test suite (Composer script wraps `artisan test`, clearing config first):
```
composer run test
```
Equivalent direct call:
```
php artisan test
```
Run a single test file or test method:
```
php artisan test tests/Feature/ExampleTest.php
php artisan test --filter=test_method_name
```

Tests run against in-memory SQLite (`phpunit.xml`: `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`), with Pulse/Telescope/Nightwatch disabled and queue set to `sync`.

## Architecture

- **Admin (Filament v5)**: `app/Filament/Resources/*` — one folder per resource (`Pages`, `Posts`, `ForumTopics`, `ForumReplies`, `ContactMessages`, `EServiceSubmissions`, `NewsletterSubscribers`, `Users`), each split into `Pages/`, `Schemas/` (form/infolist schemas), and `Tables/` subfolders rather than single monolithic resource classes. Dashboard widgets live in `app/Filament/Widgets/` (chart widgets per content type + `SubmissionStatsWidget`). Panel is configured in `app/Providers/Filament/AdminPanelProvider.php`, themed with COMCO brand colors, and uses the `yacoubalhaidari/filament-tour` plugin for a guided onboarding tour (`config/comco-filament.php`).
- **Public site**: server-rendered Blade + Livewire, no SPA framework. Controllers under `app/Http/Controllers/Public/` (`HomeController`, `PageController`, `PostController`, `ForumController`) handle read views; interactive forms are Livewire components under `app/Livewire/Public/` (`ContactForm`, `NewsletterForm`, `EServiceForm`, `CreateForumTopic`, `CreateForumReply`, `LatestPosts`), sharing a `WithUserFeedback` concern (`app/Livewire/Concerns/`) for flash-style toasts (`resources/views/components/toast-container.blade.php`).
- **Routing**: `routes/web.php` uses a single dynamic pattern — `Route::prefix('{section}')->whereIn('section', array_keys(config('navigation.sections')))` — so all "Qui sommes-nous / Centre d'information / Médias / E-services" pages are resolved generically via `PageController::showBySection` rather than one route per page. Site navigation (header + footer) is entirely config-driven from `config/navigation.php`, not hardcoded in Blade.
- **Config-as-content pattern**: much of the site's content/structure lives in PHP config files rather than the database: `config/navigation.php` (menu tree), `config/pages-content.php` (static page bodies), `config/page-templates.php`, `config/e-services.php` (defines each e-service form's fields/labels/options — used by the generic `EServiceForm` Livewire component to render/validate any of the 6 service types), `config/forum.php`, `config/legal.php`, `config/institution.php` (org name/address/contact, mirrors `INSTITUTION_*` env vars).
- **Models** (`app/Models/`): `Page`, `Post`, `ForumTopic`, `ForumReply`, `ContactMessage`, `EServiceSubmission`, `NewsletterSubscriber`, `User`. `User` uses PHP attributes (`#[Fillable]`, `#[Hidden]`) instead of the classic `$fillable`/`$hidden` properties, and has an `is_super_admin` boolean (added via a dedicated migration) instead of a roles/permissions package.
- **Auth**: standard Laravel session auth (`config/auth.php`), gating the Filament panel; no Breeze/Jetstream/Fortify or Sanctum currently installed. Super admin bootstrap is done via `php artisan` command `CreateSuperAdminCommand` or through the installer UI (`SiteInstaller::createSuperAdmin`).
- **Mail**: submissions (e-service forms, contact form) trigger two notifications — one to the institution (`InstitutionNotifier`) and a confirmation to the submitter (`SubmitterNotifier`) — using Blade mail views under `resources/views/mail/`.
- **Frontend stack**: Vite + Tailwind CSS v4 + Alpine.js (no Vue/React/Inertia). Livewire v4 handles server-driven interactivity. Assets in `resources/js`, `resources/css`; views in `resources/views/public`, `resources/views/livewire`, `resources/views/mail`, `resources/views/filament`.
- **Env/config notes**: `APP_LOCALE`/`APP_FALLBACK_LOCALE`/`APP_FAKER_LOCALE` are all `fr`/`fr_FR`. `INSTITUTION_*` env vars back `config/institution.php`. Default local stack: SQLite DB, database session/cache/queue drivers, log-based mail driver — no external services (Redis/S3/AWS creds are present in `.env.example` but unused by default).
