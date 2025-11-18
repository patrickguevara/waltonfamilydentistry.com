# Claude Code Project Context

This document provides context for Claude Code when working on the Walton Family Dentistry website.

## Project Overview

Walton Family Dentistry is a dental practice website with two main parts:
1. **Public website** - Services, team info, and contact form
2. **Admin panel** - Content management via Filament

The site prioritizes accessibility (WCAG 2.2 AA), clean design, and ease of content management.

## Architecture

### Stack
- **Backend:** Laravel 12 + SQLite + Filament v3
- **Frontend:** Vue 3 + TypeScript + Inertia.js + Tailwind CSS v4
- **Components:** Reka UI (Radix Vue) for accessible primitives
- **Testing:** Pest (installed but tests pending)

### Key Patterns

**Inertia.js Pages:**
- Pages live in `resources/js/pages/`
- Controllers return `inertia()` responses
- No separate API layer - controllers share data directly to Vue
- Shared data (like office info) available globally via `HandleInertiaRequests` middleware

**Caching:**
- All public-facing models use cache tags (`services`, `team_members`, `office_info`)
- Caches invalidate automatically on model save/delete
- Cache duration: 1 hour (3600 seconds)
- See model `boot()` methods for implementation

**File Structure:**
```
app/
├── Filament/Resources/      # Admin CRUD resources
├── Http/Controllers/        # Public controllers + settings
├── Models/                  # Eloquent models with caching
└── Policies/               # Authorization (UserPolicy)

resources/js/
├── components/             # Reusable Vue components
├── composables/            # useAccessibility, useReducedMotion, etc.
├── layouts/               # App, auth, settings layouts
└── pages/                 # Inertia pages (Home, About, Services, Contact)
```

## Core Models

**Service** (`app/Models/Service.php`)
- Fields: title, slug, description, icon, order, is_active
- Auto-generates slug from title
- Supports drag-to-reorder in admin
- Cached as `services` key
- Scope: `active()`, `ordered()`

**TeamMember** (`app/Models/TeamMember.php`)
- Fields: name, title, bio, photo, order, is_active
- Photo uploads handled by Filament
- Supports drag-to-reorder
- Cached as `team_members` key

**OfficeInfo** (`app/Models/OfficeInfo.php`)
- Singleton pattern (only one record)
- Fields: phone, email, address, hours (JSON)
- Shared globally via Inertia
- Cached as `office_info` key

**ContactSubmission** (`app/Models/ContactSubmission.php`)
- Stores contact form submissions
- Status workflow: pending → contacted → resolved → spam
- Validated via `ContactFormRequest`

**User** (`app/Models/User.php`)
- Roles: super_admin, manager, staff
- Authorization handled by `UserPolicy`
- Fortify for authentication

## Routes

**Public Routes** (`routes/web.php`):
```php
Route::get('/', [HomeController::class, 'index'])
Route::get('/about', [AboutController::class, 'index'])
Route::get('/services', [ServiceController::class, 'index'])
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])
Route::get('/contact', [ContactController::class, 'index'])
Route::post('/contact', [ContactController::class, 'store'])
```

**Settings Routes** (`routes/settings.php`):
- Profile, password, 2FA, appearance settings
- Uses `settings/Layout.vue` wrapper

**Admin Panel:**
- Accessible at `/admin`
- Configured in `app/Providers/Filament/AdminPanelProvider.php`

## Frontend Conventions

### Component Naming
- UI primitives in `components/ui/` (Button, Input, Card, etc.)
- App components in `components/` (Header, Footer, AppLayout, etc.)
- Use PascalCase for component names
- Prefer composition over props drilling

### Accessibility
- All interactive elements must have 44x44px touch targets (app uses 48px minimum)
- Focus indicators: 2px solid ring with 2px offset
- Use semantic HTML (`<nav>`, `<main>`, `<article>`, etc.)
- Always include ARIA labels where needed
- Test with keyboard navigation
- Support reduced motion preferences via `useReducedMotion()` composable

### TypeScript
- Use strict mode
- Define prop types with `defineProps<{ ... }>()`
- Avoid `any` - use `unknown` or specific types
- Inertia page props typed in each page component

### Styling
- Tailwind CSS v4 (note: uses native CSS, not PostCSS)
- Design tokens: minimal black/white palette, soft/airy aesthetic
- Responsive breakpoints: sm (640px), md (768px), lg (1024px), xl (1280px)
- Color contrast minimum: 4.5:1 for text, 3:1 for UI components

## Common Tasks

### Adding a New Public Page

1. Create controller in `app/Http/Controllers/`
2. Create Inertia page in `resources/js/pages/`
3. Add route to `routes/web.php`
4. Update navigation in Header component if needed
5. Add SEO meta tags via Inertia `Head` component

### Adding a New Filament Resource

1. Generate resource: `php artisan filament:resource ModelName`
2. Define form schema in resource class
3. Add authorization in model policy if needed
4. Configure table columns and filters
5. Add to navigation in resource class

### Working with Caching

When modifying models that use caching:
- Cache invalidation happens automatically via model events
- Manually clear: `Cache::forget('cache_key')`
- Clear all: `php artisan cache:clear`

### Running Commands

**Development:**
```bash
composer dev          # Runs server + queue + logs + vite
npm run dev          # Vite only
php artisan serve    # Laravel server only
```

**Code Quality:**
```bash
./vendor/bin/pint    # Format PHP
npm run format       # Format JS/Vue
npm run lint         # Lint JS/Vue
```

**Database:**
```bash
php artisan migrate:fresh --seed    # Reset and seed
php artisan db:seed                 # Seed only
```

## Important Notes

### Do's
- Always test keyboard navigation after UI changes
- Use existing composables (`useAccessibility`, `useFocusTrap`, etc.)
- Follow WCAG 2.2 AA guidelines (see `docs/ACCESSIBILITY.md`)
- Cache public-facing queries
- Validate forms both client and server-side
- Use semantic HTML
- Test with reduced motion enabled

### Don'ts
- Don't bypass cache for public data (use cache tags)
- Don't create inline styles - use Tailwind classes
- Don't skip ARIA attributes on custom components
- Don't use `any` in TypeScript
- Don't forget to invalidate cache when updating models
- Don't use hardcoded URLs - use `route()` helper

## Development Workflow

1. **Making changes:**
   - Backend: Edit PHP, auto-reloads with `php artisan serve`
   - Frontend: Edit Vue/TS, HMR with `npm run dev`
   - Styles: Edit Tailwind classes, HMR enabled

2. **Testing changes:**
   - Manual: Visit http://waltonfamilydentistry.com.test
   - Admin: Visit http://waltonfamilydentistry.com.test/admin
   - Automated: `composer test` (when tests exist)

3. **Checking code quality:**
   ```bash
   ./vendor/bin/pint    # Fix PHP formatting
   npm run format       # Fix JS/Vue formatting
   npm run lint         # Fix linting issues
   ```

4. **Database changes:**
   - Create migration: `php artisan make:migration`
   - Run migration: `php artisan migrate`
   - Rollback: `php artisan migrate:rollback`

## Admin Credentials

**Email:** admin@waltonfamilydentistry.com
**Password:** password

## File Locations Quick Reference

- **Models:** `app/Models/`
- **Controllers:** `app/Http/Controllers/`
- **Filament Resources:** `app/Filament/Resources/`
- **Vue Pages:** `resources/js/pages/`
- **Vue Components:** `resources/js/components/`
- **Composables:** `resources/js/composables/`
- **Routes:** `routes/web.php`, `routes/settings.php`
- **Migrations:** `database/migrations/`
- **Seeders:** `database/seeders/`
- **Config:** `config/`
- **Docs:** `docs/`

## Environment

- **App URL:** http://waltonfamilydentistry.com.test (Laravel Herd)
- **Database:** SQLite at `database/database.sqlite`
- **Queue:** Database driver
- **Cache:** Database driver
- **Mail:** Log driver (development)

## Current Status

Phase 1-7 complete. See `docs/IMPLEMENTATION_STATUS.md` for details.

**Pending:**
- Phase 8: Testing (Pest tests to be written)
- Phase 9: Deployment preparation
- Real content and images

## Need Help?

- Implementation status: `docs/IMPLEMENTATION_STATUS.md`
- Accessibility guidelines: `docs/ACCESSIBILITY.md`
- Laravel docs: https://laravel.com/docs/12.x
- Vue 3 docs: https://vuejs.org
- Inertia docs: https://inertiajs.com
- Filament docs: https://filamentphp.com/docs/3.x
- Tailwind docs: https://tailwindcss.com/docs
