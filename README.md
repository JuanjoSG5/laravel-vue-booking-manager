# Candidate Exercise Scaffold

This repo is prepped so candidates can focus on the exercise (frontend changes, a few API routes/controllers, maybe a migration/model) without setup friction.

## What’s already done
- Laravel app configured for SQLite (`database/database.sqlite` is committed).
- Default migrations already applied (users, sessions, cache, jobs, failed jobs).
- `.env` and `.env.example` point to the bundled SQLite DB and include an `APP_KEY`.
- Tailwind CSS v4 is wired through Vite (`resources/css/app.css`, `@tailwindcss/vite` plugin).

## Prerequisites
- PHP 8.2+ with the SQLite extension enabled.
- Composer
- Node.js + npm (only if the exercise touches the frontend build).

## Getting started
1) Install deps: `composer install` and `npm install`.  
2) Env is already provided (`.env`). If you prefer to regenerate: `cp .env.example .env`.  
3) You’re ready to run the app. If you want to re-run migrations or add new ones: `php artisan migrate`.

## Frontend / Tailwind
- Entrypoints: `resources/js/app.ts` imports `resources/css/app.css` (Tailwind layer).
- Dev server: `npm run dev` (or `npm run dev -- --host` if needed).
- Production build: `npm run build`.

## Notes for candidates
- DB lives at `database/database.sqlite`; no setup required.
- If you add migrations/models, `php artisan migrate` will update the same DB.
- Keep focus on the specified components; everything else should be ready to go.
