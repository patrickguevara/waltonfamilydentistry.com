# Contributing to Walton Family Dentistry

Thank you for contributing! This document outlines the workflow for making changes to the codebase.

## Branch-Based Workflow

**All changes must go through feature branches and pull requests. Direct pushes to `main` are not allowed.**

### 1. Create a Feature Branch

Start by creating a descriptive branch name:

```bash
# For new features
git checkout -b feature/add-testimonials-section

# For bug fixes
git checkout -b fix/contact-form-validation

# For improvements/refactors
git checkout -b refactor/optimize-image-loading
```

**Branch naming conventions:**
- `feature/` - New functionality
- `fix/` - Bug fixes
- `refactor/` - Code improvements without behavior changes
- `docs/` - Documentation updates
- `test/` - Test additions/modifications

### 2. Make Your Changes

Follow the project's coding standards:

- **PHP:** Follow Laravel conventions, use type hints
- **Vue/TypeScript:** Use strict mode, define prop types, avoid `any`
- **CSS:** Use Tailwind utility classes, avoid custom CSS
- **Accessibility:** Maintain WCAG 2.2 AA compliance
- **Import Paths:** Always use capital case (@/Components/, @/Layouts/, @/Components/UI/)

See `CLAUDE.md` for detailed conventions.

### 3. Test Locally (REQUIRED)

Before committing, ensure everything works locally:

```bash
# Run the development server
composer dev  # or npm run dev

# Test manually
# Visit http://waltonfamilydentistry.com.test
# Test all affected pages and features
```

### 4. Run Quality Checks (REQUIRED)

**All checks must pass before pushing:**

```bash
# PHP formatting check
./vendor/bin/pint --test

# JavaScript/Vue formatting check
npm run format:check

# JavaScript/Vue linting check
npm run lint:check

# Run tests
composer test

# Verify build succeeds
npm run build
```

If any checks fail, fix them:

```bash
# Auto-fix PHP formatting
./vendor/bin/pint

# Auto-fix JS/Vue formatting
npm run format

# Auto-fix JS/Vue linting (where possible)
npm run lint
```

### 5. Commit Your Changes

Use conventional commit messages:

```bash
git add .
git commit -m "feat: add testimonials section to homepage"
```

**Commit message format:**
- `feat:` - New feature
- `fix:` - Bug fix
- `refactor:` - Code refactoring
- `docs:` - Documentation changes
- `test:` - Test additions/modifications
- `style:` - Formatting changes (not CSS changes)
- `chore:` - Build process or tooling changes

### 6. Push and Create Pull Request

```bash
# Push your branch to GitHub
git push -u origin feature/add-testimonials-section
```

Then create a PR on GitHub:

1. Go to https://github.com/patrickguevara/waltonfamilydentistry.com
2. Click "Compare & pull request"
3. **Target branch:** `main`
4. **Title:** Use a clear, descriptive title
5. **Description:** Include:
   - What changes were made and why
   - How to test the changes
   - Screenshots (for UI changes)
   - Any breaking changes or migrations needed

**PR Description Template:**
```markdown
## What
Brief description of what this PR does.

## Why
Why this change is needed.

## How to Test
1. Step-by-step testing instructions
2. Expected behavior

## Screenshots
(If applicable)

## Checklist
- [ ] All linters pass locally
- [ ] All tests pass locally
- [ ] Build succeeds locally
- [ ] Import paths use correct case
- [ ] Accessibility maintained (WCAG 2.2 AA)
- [ ] No TypeScript `any` types added
```

### 7. GitHub Actions (Automated)

Once you push, GitHub Actions will automatically run:

- **Linter workflow:** Checks PHP and JS/Vue code formatting and style
- **Tests workflow:** Runs tests and verifies the build succeeds

**Both workflows must pass before merge is allowed.**

If workflows fail:
1. Check the error logs on GitHub
2. Fix the issues locally
3. Re-run quality checks
4. Commit and push the fixes

### 8. Code Review and Merge

1. Wait for PR review and approval
2. Once approved, the PR will be merged to `main`
3. Delete your feature branch after merge

## Pre-Push Checklist

Before pushing any branch, verify:

- [ ] Code follows project conventions (see `CLAUDE.md`)
- [ ] All linters pass: `./vendor/bin/pint --test && npm run format:check && npm run lint:check`
- [ ] All tests pass: `composer test`
- [ ] Build succeeds: `npm run build`
- [ ] Import paths use correct case (`@/Components/`, `@/Layouts/`, `@/Components/UI/`)
- [ ] No `any` types in TypeScript
- [ ] Accessibility standards maintained (WCAG 2.2 AA)
- [ ] No console errors in browser
- [ ] Tested in both light and dark mode (if applicable)
- [ ] Tested with keyboard navigation
- [ ] Tested with reduced motion enabled

## Common Issues

### Case-Sensitive Import Paths

**Problem:** Build fails on CI with "Could not load" errors, but works locally on macOS.

**Cause:** macOS is case-insensitive, but Linux CI is case-sensitive. Git tracks the original case.

**Solution:** Always use capital case in imports:
- ✅ `@/Components/Header.vue`
- ✅ `@/Components/UI/button`
- ✅ `@/Layouts/AppLayout.vue`
- ❌ `@/components/Header.vue`
- ❌ `@/components/ui/button`
- ❌ `@/layouts/AppLayout.vue`

### Linter Failures

**Problem:** Linters fail on CI but pass locally.

**Cause:** Different tool versions or uncommitted changes.

**Solution:**
1. Ensure you have the latest dependencies: `npm install && composer install`
2. Run the exact commands CI uses (listed in section 4)
3. Commit any auto-fixes before pushing

### Test Failures

**Problem:** Tests pass locally but fail on CI.

**Cause:** Database state differences or missing migrations.

**Solution:**
1. Run `php artisan migrate:fresh` locally
2. Ensure tests don't depend on specific data
3. Check that factories and seeders are up to date

## Questions?

- See `CLAUDE.md` for detailed project conventions
- See `docs/ACCESSIBILITY.md` for accessibility guidelines
- See `docs/IMPLEMENTATION_STATUS.md` for project status
- Open an issue on GitHub for questions

## Thank You!

Your contributions help make this project better. Thank you for following these guidelines!
