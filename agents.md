# AI Agent Project Context

This document provides comprehensive project context for AI agents and LLMs working on the Walton Family Dentistry website codebase.

## Project Identity

**Name:** Walton Family Dentistry Website
**Type:** Dental practice website with admin panel
**Purpose:** Public-facing site for dental services, team info, and contact; admin panel for content management
**Status:** Phase 1-7 complete, testing and deployment pending

## Technology Stack

### Backend
- **Framework:** Laravel 12 (latest PHP framework)
- **Database:** SQLite (file-based database at `database/database.sqlite`)
- **Admin Panel:** Filament v3 (modern Laravel admin panel builder)
- **Authentication:** Laravel Fortify (handles login, registration, 2FA)
- **Routing:** Laravel Wayfinder (enhanced routing utilities)
- **Testing:** Pest 4 (modern PHP testing framework, tests not yet written)

### Frontend
- **Framework:** Vue 3 with TypeScript (composition API pattern)
- **Bridge:** Inertia.js v2 (connects Laravel backend to Vue frontend without API)
- **Styling:** Tailwind CSS v4 (utility-first CSS framework using native CSS)
- **Components:** Reka UI / Radix Vue (accessible, unstyled component primitives)
- **Icons:** Lucide Vue Next (icon library)
- **Build Tool:** Vite 7 (fast modern bundler with HMR)
- **Utilities:** VueUse (collection of Vue composition utilities)

### Development Environment
- **Local Server:** Laravel Herd (local development environment)
- **URL:** http://waltonfamilydentistry.com.test
- **Queue Driver:** Database (for background jobs)
- **Cache Driver:** Database
- **Mail Driver:** Log (emails logged to storage/logs)

## Architecture Patterns

### Inertia.js Architecture
This project uses Inertia.js, which means:
- **No separate API layer** - Controllers return data directly to Vue pages
- **Server-side routing** - All routes defined in Laravel
- **Shared data** - Global data (like office info) shared via `HandleInertiaRequests` middleware
- **Page components** - Each route renders a Vue component in `resources/js/pages/`
- **Client-side navigation** - Feels like SPA but uses server-side routing

**Example flow:**
1. User visits `/services`
2. Laravel routes to `ServiceController@index`
3. Controller queries database and returns `inertia('Services/Index', ['services' => $services])`
4. Inertia renders `resources/js/pages/Services/Index.vue` with data
5. Navigation between pages happens client-side (SPA-like)

### Cache Strategy
All public-facing data is cached for performance:
- **Cache keys:** `services`, `team_members`, `office_info`
- **Duration:** 3600 seconds (1 hour)
- **Auto-invalidation:** Model events automatically clear cache on save/delete
- **Implementation:** See `boot()` methods in models (`app/Models/`)

**Why caching matters:**
- Reduces database queries for frequently accessed data
- Services and team members rarely change
- Cache clears automatically when admin updates content
- Manual clear: `Cache::forget('key_name')` or `php artisan cache:clear`

### Model Patterns

**Service Model** (`app/Models/Service.php`):
- Dental services (e.g., "General Dentistry", "Cosmetic Dentistry")
- Auto-generates slug from title on creation
- Supports manual ordering (drag-to-reorder in admin)
- Caches all active services
- Scopes: `active()`, `ordered()`

**TeamMember Model** (`app/Models/TeamMember.php`):
- Staff bios with photos
- Supports photo uploads via Filament
- Manual ordering for display
- Caches all active members

**OfficeInfo Model** (`app/Models/OfficeInfo.php`):
- Singleton pattern (only one record exists)
- Stores: phone, email, address, office hours (JSON)
- Shared globally via Inertia (available to all pages)
- Cached for performance

**ContactSubmission Model** (`app/Models/ContactSubmission.php`):
- Stores contact form submissions
- Status workflow: pending → contacted → resolved → spam
- Not cached (admin-only data)

**User Model** (`app/Models/User.php`):
- Admin users with role-based access
- Roles: `super_admin`, `manager`, `staff`
- Authorization via `UserPolicy` (`app/Policies/UserPolicy.php`)

## Project Structure

```
app/
├── Actions/Fortify/              # Fortify actions (user creation, password reset)
├── Filament/
│   ├── Resources/                # Admin CRUD interfaces
│   │   ├── ServiceResource.php
│   │   ├── TeamMemberResource.php
│   │   ├── OfficeInfoResource.php
│   │   ├── ContactSubmissionResource.php
│   │   └── UserResource.php
│   └── Providers/
│       └── AdminPanelProvider.php  # Filament configuration
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php      # Public homepage
│   │   ├── ServiceController.php   # Services listing/detail
│   │   ├── AboutController.php     # About/team page
│   │   ├── ContactController.php   # Contact form
│   │   └── Settings/               # User settings (profile, password, 2FA)
│   ├── Middleware/
│   │   ├── HandleInertiaRequests.php  # Shares global data
│   │   └── HandleAppearance.php       # Theme preferences
│   └── Requests/
│       ├── ContactFormRequest.php     # Contact form validation
│       └── Settings/                  # Settings form validation
├── Models/                        # Eloquent models (listed above)
├── Policies/                      # Authorization policies
└── Providers/                     # Service providers

resources/
├── js/
│   ├── app.ts                    # Vue app initialization
│   ├── components/
│   │   ├── ui/                   # Reka UI components (Button, Input, etc.)
│   │   ├── Header.vue            # Site header
│   │   ├── Footer.vue            # Site footer
│   │   ├── AppLayout.vue         # Main layout wrapper
│   │   └── [other components]
│   ├── composables/
│   │   ├── useAccessibility.ts   # Accessibility utilities
│   │   ├── useReducedMotion.ts   # Reduced motion detection
│   │   └── useFocusTrap.ts       # Focus management
│   ├── layouts/
│   │   ├── app/                  # App layouts (sidebar, header)
│   │   ├── auth/                 # Auth layouts (login, register)
│   │   └── settings/             # Settings layout
│   └── pages/
│       ├── Home.vue              # Homepage
│       ├── About.vue             # About/team page
│       ├── Services/
│       │   ├── Index.vue         # Services listing
│       │   └── Show.vue          # Service detail
│       ├── Contact.vue           # Contact page
│       ├── auth/                 # Auth pages
│       └── settings/             # Settings pages
└── views/                        # Blade templates (minimal, mainly app.blade.php)

database/
├── migrations/                   # Database schema
└── seeders/                      # Sample data (DatabaseSeeder.php)

routes/
├── web.php                       # Public routes
├── settings.php                  # User settings routes
└── console.php                   # Artisan commands

public/                           # Public assets (compiled by Vite)
storage/                          # File uploads, logs, cache
tests/                            # Test suite (Pest, not yet written)
```

## Routes & Controllers

### Public Routes (`routes/web.php`)

```php
// Homepage
GET / → HomeController@index → pages/Home.vue

// About
GET /about → AboutController@index → pages/About.vue

// Services
GET /services → ServiceController@index → pages/Services/Index.vue
GET /services/{service:slug} → ServiceController@show → pages/Services/Show.vue

// Contact
GET /contact → ContactController@index → pages/Contact.vue
POST /contact → ContactController@store (form submission)

// Auth (handled by Fortify)
GET /login, /register, /forgot-password, etc.
```

### Admin Routes
- All admin routes under `/admin`
- Configured in Filament: `app/Providers/Filament/AdminPanelProvider.php`
- Resources auto-generate routes: `/admin/services`, `/admin/team-members`, etc.

### Settings Routes (`routes/settings.php`)
- Profile, password, 2FA, appearance
- Prefix: `/settings`
- Middleware: `auth`, `verified`

## Design System

### Visual Design
- **Aesthetic:** Minimal, clean, soft/airy
- **Colors:** Black/white primary palette, subtle grays
- **Typography:** System font stack
- **Spacing:** Consistent use of Tailwind spacing scale
- **Shadows:** Subtle, soft shadows for depth

### Accessibility (WCAG 2.2 AA)
**CRITICAL:** This project prioritizes accessibility. See `docs/ACCESSIBILITY.md` for full details.

**Key Requirements:**
- Color contrast: 4.5:1 minimum for text, 3:1 for UI components
- Touch targets: 44x44px minimum (project uses 48x48px)
- Focus indicators: 2px solid ring with 2px offset
- Keyboard navigation: All interactive elements accessible via keyboard
- Screen readers: Semantic HTML, ARIA labels where needed
- Reduced motion: Respect user preferences via `useReducedMotion()` composable
- Form errors: Announced to screen readers

**When writing code:**
- Use semantic HTML (`<nav>`, `<main>`, `<article>`, not `<div>` everywhere)
- Add ARIA labels to icon buttons: `aria-label="Menu"`
- Ensure interactive elements have visible focus states
- Test keyboard navigation (Tab, Enter, Escape)
- Support reduced motion in animations

### Component Conventions

**UI Components** (`resources/js/components/ui/`):
- Built on Reka UI (Radix Vue) primitives
- Unstyled base, styled with Tailwind
- Examples: Button, Input, Card, Dialog, Dropdown

**App Components** (`resources/js/components/`):
- Application-specific components
- Examples: Header, Footer, AppLayout, NavMain

**Naming:**
- PascalCase for components: `ServiceCard.vue`
- camelCase for composables: `useAccessibility.ts`
- kebab-case for files in `ui/` directory: `pin-input/`

**Props & Events:**
- Define props with TypeScript: `defineProps<{ title: string }>()`
- Emit events: `defineEmits<{ submit: [] }>()`
- Avoid `any` type - use `unknown` or specific types

## Data Flow

### Loading Data (Server → Client)
1. User requests route (e.g., `/services`)
2. Laravel controller queries database (uses cache if available)
3. Controller returns `inertia('PageComponent', ['data' => $data])`
4. Inertia sends data to Vue component as props
5. Component receives props via `defineProps<{ data: Type }>()`

**Example:**
```php
// Controller
public function index() {
    $services = Cache::remember('services', 3600, fn() =>
        Service::active()->ordered()->get()
    );

    return inertia('Services/Index', ['services' => $services]);
}
```

```vue
<!-- Vue Component -->
<script setup lang="ts">
defineProps<{
  services: Array<{
    id: number
    title: string
    slug: string
    // ...
  }>
}>()
</script>
```

### Submitting Data (Client → Server)
1. User submits form in Vue component
2. Inertia sends POST/PUT/DELETE request to Laravel route
3. Laravel validates via FormRequest class
4. Controller processes and saves data
5. Controller redirects back with success/error message
6. Inertia updates page reactively

**Example:**
```vue
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  message: ''
})

const submit = () => {
  form.post('/contact', {
    onSuccess: () => form.reset(),
    onError: () => console.log('Validation errors:', form.errors)
  })
}
</script>
```

### Global Shared Data
Available to all pages via `HandleInertiaRequests` middleware:
- `auth.user` - Current authenticated user (if logged in)
- `officeInfo` - Office contact info (phone, email, address, hours)
- `flash` - Session flash messages (success, error)

Access in components:
```vue
<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'

const officeInfo = computed(() => usePage().props.officeInfo)
</script>
```

## Common Tasks for AI Agents

### Adding a New Public Page

1. **Create controller** (`app/Http/Controllers/NewPageController.php`):
   ```php
   public function index() {
       return inertia('NewPage', [
           'data' => // fetch data
       ]);
   }
   ```

2. **Create Vue page** (`resources/js/pages/NewPage.vue`):
   ```vue
   <script setup lang="ts">
   import AppLayout from '@/layouts/AppLayout.vue'
   defineProps<{ data: any }>()
   </script>

   <template>
     <AppLayout title="Page Title">
       <!-- content -->
     </AppLayout>
   </template>
   ```

3. **Add route** (`routes/web.php`):
   ```php
   Route::get('/new-page', [NewPageController::class, 'index']);
   ```

4. **Update navigation** if needed (Header.vue)

### Adding a New Filament Resource

1. **Generate:** `php artisan filament:resource ModelName`
2. **Define form** in resource's `form()` method
3. **Define table** in resource's `table()` method
4. **Add authorization** in model policy if needed

### Modifying Models

When changing models that use caching:
- Cache invalidation is automatic (via model events)
- If adding new fields, update migration
- If changing cache structure, manually clear: `php artisan cache:clear`

### Adding Accessibility Features

1. Use composables: `useAccessibility()`, `useReducedMotion()`, `useFocusTrap()`
2. Add ARIA attributes to custom components
3. Ensure keyboard navigation works
4. Test with screen reader (VoiceOver on Mac, NVDA on Windows)
5. Verify color contrast meets 4.5:1 ratio

### Writing Tests

Tests go in `tests/` directory using Pest:
```php
test('services page displays active services', function () {
    $service = Service::factory()->create(['is_active' => true]);

    $this->get('/services')
        ->assertOk()
        ->assertSee($service->title);
});
```

Run: `composer test` or `php artisan test`

## Important Constraints & Considerations

### Do's
- **Do** use caching for public-facing queries (services, team members)
- **Do** follow WCAG 2.2 AA accessibility guidelines
- **Do** validate forms server-side (even if client-side validation exists)
- **Do** use TypeScript types (avoid `any`)
- **Do** test keyboard navigation after UI changes
- **Do** use semantic HTML
- **Do** respect reduced motion preferences
- **Do** clear cache when changing cached data structures

### Don'ts
- **Don't** bypass cache for public data (use `Cache::remember()`)
- **Don't** create inline styles (use Tailwind classes)
- **Don't** skip ARIA attributes on custom interactive components
- **Don't** use `any` type in TypeScript
- **Don't** forget to add migrations when changing database schema
- **Don't** hardcode URLs (use Laravel's `route()` helper)
- **Don't** ignore accessibility - it's a project priority
- **Don't** make breaking changes to Inertia props without updating Vue components

## Development Commands

**Start development:**
```bash
composer dev          # Starts server, queue, logs, and Vite
npm run dev          # Vite dev server only
php artisan serve    # Laravel server only
```

**Database:**
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Reset and seed
php artisan db:seed             # Seed only
```

**Code quality:**
```bash
./vendor/bin/pint    # Format PHP code
npm run format       # Format JS/Vue/CSS
npm run lint         # Lint and fix JS/Vue
```

**Cache:**
```bash
php artisan cache:clear       # Clear application cache
php artisan config:clear      # Clear config cache
php artisan route:clear       # Clear route cache
php artisan view:clear        # Clear view cache
```

**Testing:**
```bash
composer test        # Run Pest tests
php artisan test    # Alternative
```

## Admin Credentials

**URL:** http://waltonfamilydentistry.com.test/admin
**Email:** admin@waltonfamilydentistry.com
**Password:** password

**Roles:**
- `super_admin` - Full access (user management, all resources)
- `manager` - Can manage services and team members
- `staff` - Can edit office info and contact details

## Current Project Status

**Completed (Phase 1-7):**
- Database schema and models with caching
- Filament admin panel with all resources
- Frontend components and composables
- Public pages (home, about, services, contact)
- Authentication and settings pages
- Accessibility features (WCAG 2.2 AA compliant)
- SEO and meta tags

**Pending (Phase 8-9):**
- Comprehensive Pest tests
- Real content and images
- Production deployment preparation

See `docs/IMPLEMENTATION_STATUS.md` for detailed status.

## Documentation References

- **Implementation Status:** `docs/IMPLEMENTATION_STATUS.md`
- **Accessibility Guidelines:** `docs/ACCESSIBILITY.md`
- **Project Setup:** `README.md`
- **Claude-specific Context:** `claude.md`

## External Documentation

- Laravel 12: https://laravel.com/docs/12.x
- Vue 3: https://vuejs.org
- Inertia.js: https://inertiajs.com
- Filament: https://filamentphp.com/docs/3.x
- Tailwind CSS: https://tailwindcss.com/docs
- Reka UI: https://reka-ui.com
- Pest: https://pestphp.com

## Tips for AI Agents

1. **Read existing code first** - This codebase has established patterns. Follow them.
2. **Check cache implementation** - Models use cache. Maintain this pattern.
3. **Maintain accessibility** - Every UI change should consider WCAG 2.2 AA.
4. **Use TypeScript properly** - Avoid `any`, define proper types.
5. **Test keyboard navigation** - All interactive elements must work via keyboard.
6. **Follow Inertia patterns** - No separate API, data flows through Inertia.
7. **Respect the architecture** - Don't fight the stack, work with it.
8. **Consult docs first** - Implementation status and accessibility docs have answers.

## Questions to Ask Before Making Changes

1. Does this change affect cached data? (If yes, ensure cache invalidation works)
2. Is this change accessible? (Can it be used with keyboard? Does it work with screen readers?)
3. Does this follow existing patterns? (Check similar code in the codebase)
4. Do I need a migration? (Any database schema changes?)
5. Should this be tested? (Yes, but tests aren't written yet - note for future)
6. Does this affect Inertia props? (If yes, update both controller and Vue component)
7. Are there TypeScript errors? (Run type checking before committing)

## Summary

This is a modern Laravel + Vue.js website with a strong emphasis on accessibility, clean architecture, and ease of content management. The key differentiators are:

- **Inertia.js** for seamless server-side/client-side integration
- **Filament** for powerful admin panel with minimal code
- **Caching** for performance
- **WCAG 2.2 AA** accessibility compliance
- **TypeScript** for type safety
- **Tailwind CSS v4** for styling

When working on this codebase, prioritize accessibility, follow established patterns, and maintain the cache strategy. The architecture is well-designed - respect it and build upon it.
