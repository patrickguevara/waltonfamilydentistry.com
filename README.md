# Walton Family Dentistry

A modern, accessible dental practice website built with Laravel and Vue.js.

## Overview

This is a full-stack web application for Walton Family Dentistry, featuring a public-facing website with service information, team member profiles, and contact functionality, plus a Filament-powered admin panel for content management.

**Live Site:** http://waltonfamilydentistry.com.test

## Technology Stack

### Backend
- **Laravel 12** - PHP web application framework
- **SQLite** - Lightweight database
- **Filament v3** - Admin panel for content management
- **Laravel Fortify** - Authentication system
- **Laravel Wayfinder** - Routing utilities

### Frontend
- **Vue 3** - Progressive JavaScript framework with TypeScript
- **Inertia.js** - Modern monolithic SPA framework
- **Tailwind CSS v4** - Utility-first CSS framework
- **Reka UI** (Radix Vue) - Accessible, unstyled UI components
- **Lucide Icons** - Icon library
- **Vite** - Modern build tool

### Testing
- **Pest** - Modern PHP testing framework
- **PHPUnit** - Unit testing framework

## Features

### Public Website
- Homepage with services preview and team highlights
- Services listing and individual service detail pages
- About page with team member bios
- Contact form with validation and submission tracking
- Responsive design (mobile, tablet, desktop)
- WCAG 2.2 AA accessible
- SEO-friendly with meta tags for social sharing

### Admin Panel
- Service management (CRUD with drag-to-reorder)
- Team member management with photo uploads
- Office information management (hours, contact details)
- Contact form submission tracking with status workflow
- User management with role-based access control
- Navigation badge showing new contact submissions

### Accessibility
- WCAG 2.2 AA compliant
- Keyboard navigation support with skip links
- Screen reader optimizations
- 4.5:1 minimum color contrast
- 2px focus indicators with offset
- Reduced motion support
- Semantic HTML throughout

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite extension enabled

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd waltonfamilydentistry.com
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. **Storage link** (for file uploads)
   ```bash
   php artisan storage:link
   ```

## Development

### Quick Start
Run all development services concurrently:
```bash
composer dev
```

This starts:
- Laravel development server (port 8000)
- Queue worker
- Log viewer (pail)
- Vite development server (HMR)

### Individual Commands

**Backend:**
```bash
php artisan serve              # Start development server
php artisan queue:listen       # Run queue worker
php artisan pail               # View logs
```

**Frontend:**
```bash
npm run dev                    # Start Vite dev server
npm run build                  # Build for production
npm run format                 # Format code with Prettier
npm run lint                   # Lint and fix with ESLint
```

**Database:**
```bash
php artisan migrate            # Run migrations
php artisan migrate:fresh --seed  # Reset database with seed data
php artisan db:seed            # Run seeders only
```

## Testing

```bash
composer test                  # Run PHP tests with Pest
php artisan test              # Alternative test command
```

## Admin Access

**URL:** http://waltonfamilydentistry.com.test/admin

**Default Credentials:**
- Email: `admin@waltonfamilydentistry.com`
- Password: `password`

**User Roles:**
- `super_admin` - Full access including user management
- `manager` - Can manage services and team members
- `staff` - Can edit office hours and contact info

## Project Structure

```
app/
├── Filament/Resources/    # Admin panel resources
├── Http/Controllers/      # Public & settings controllers
├── Models/               # Eloquent models
└── Policies/             # Authorization policies

resources/
├── js/
│   ├── components/       # Vue components
│   ├── layouts/         # Page layouts
│   ├── pages/           # Inertia pages
│   └── composables/     # Vue composables
└── views/               # Blade templates

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders

routes/
├── web.php             # Public routes
├── settings.php        # Settings routes
└── console.php         # Artisan commands

docs/                   # Project documentation
```

## Key Models

- **Service** - Dental services with caching and ordering
- **TeamMember** - Team bios with photo uploads
- **OfficeInfo** - Singleton for office hours and contact details
- **ContactSubmission** - Contact form submissions with status tracking
- **User** - Authentication with role-based access

## Cache Strategy

Public-facing data is cached for 1 hour (3600 seconds) and automatically invalidates on updates:
- `services` - Active services in display order
- `team_members` - Active team members in display order
- `office_info` - Office information singleton

## Code Style

**PHP:**
- Laravel Pint for code formatting
- PSR-12 coding standard

**JavaScript/TypeScript:**
- ESLint with Vue and TypeScript configs
- Prettier for formatting

**Format code:**
```bash
./vendor/bin/pint          # PHP
npm run format             # JS/Vue/CSS
```

## Production Deployment

1. Set environment to production in `.env`
2. Configure production database credentials
3. Run optimizations:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Documentation

- [Implementation Status](docs/IMPLEMENTATION_STATUS.md) - Current progress and completed features
- [Accessibility](docs/ACCESSIBILITY.md) - WCAG 2.2 AA compliance documentation

## License

MIT

## Credits

Built with Laravel, Vue.js, Tailwind CSS, and Filament.
