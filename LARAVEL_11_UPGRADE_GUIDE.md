# Laravel 9 → 11 Complete Upgrade Guide
## Lake County Local Deals Platform

**⚠️ CRITICAL: OpenELMS is LIVE - Test thoroughly before production deployment**

---

## PRE-UPGRADE CHECKLIST

- [x] PHP 8.3.28 verified (meets L11 requirement)
- [ ] Database backup created
- [ ] Codebase backup created  
- [ ] Current test suite baseline documented
- [ ] OpenELMS functionality documented

---

## STEP 1: CREATE BACKUPS

```bash
# Database backup
php artisan db:backup --destination=storage/backups/pre-upgrade-$(date +%Y%m%d_%H%M%S).sql

# Or manually:
mysqldump -u [username] -p [database] > storage/backups/pre-upgrade-$(date +%Y%m%d_%H%M%S).sql

# Codebase backup
cp -r . ../lake-county-backup-$(date +%Y%m%d_%H%M%S)
```

---

## STEP 2: UPDATE COMPOSER.JSON

✅ **COMPLETED** - composer.json has been updated with Laravel 11 requirements.

**Key Changes:**
- PHP: `^8.0.2` → `^8.2`
- Laravel: `^9.19` → `^11.0`
- Sanctum: `^3.0` → `^4.0`
- Tinker: `^2.7` → `^2.9`
- All dev dependencies updated

**Next:** Run `composer update` (will be done in phases)

---

## STEP 3: LARAVEL 9 → 10 UPGRADE

### 3.1 Update to Laravel 10

```bash
composer require laravel/framework:^10.0 --update-with-dependencies
```

### 3.2 Handle Breaking Changes

**RouteServiceProvider:**
- Update namespace handling
- Verify route model bindings

**Validation:**
- Update password validation rules to include explicit min length

**Database:**
- Check for deprecated query builder methods

### 3.3 Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 3.4 Run Migrations

```bash
php artisan migrate
```

### 3.5 Test OpenELMS

- [ ] Vendor training routes work
- [ ] Course enrollment works
- [ ] Progress tracking works
- [ ] Deal creation restriction works

---

## STEP 4: LARAVEL 10 → 11 UPGRADE

### 4.1 Update to Laravel 11

```bash
composer require laravel/framework:^11.0 --update-with-dependencies
```

### 4.2 Restructure Application

**NEW bootstrap/app.php** - See file created below

**REMOVE:**
- app/Http/Kernel.php (replaced by bootstrap/app.php)

**UPDATE:**
- app/Providers/AppServiceProvider.php
- config/app.php (simplified providers)

---

## STEP 5: POST-UPGRADE TESTING

### 5.1 OpenELMS Critical Tests

```bash
# Test vendor training routes
curl http://localhost/vendor/training
curl http://localhost/vendor/training/course/1

# Verify database tables
php artisan tinker
>>> \App\Models\VendorCourseProgress::count()
```

### 5.2 Hybrid Pricing Tests

```bash
# Test commission service
php artisan tinker
>>> app(\App\Services\CommissionService::class)->getMonthlyStats(1)
```

### 5.3 Full Application Tests

- [ ] Authentication (login, register, password reset)
- [ ] Vendor dashboard
- [ ] Deal creation/editing
- [ ] Consumer purchase flow
- [ ] Admin functions
- [ ] Settings page

---

## ROLLBACK PLAN

If upgrade fails:

1. **Restore Database:**
```bash
mysql -u [username] -p [database] < storage/backups/pre-upgrade-[timestamp].sql
```

2. **Restore Codebase:**
```bash
rm -rf .
cp -r ../lake-county-backup-[timestamp]/* .
```

3. **Restore Composer:**
```bash
composer install
```

---

## NOTES

- OpenELMS routes are in routes/web.php (lines 268-271)
- Custom middleware: CheckSubscriptionFeature
- Settings system uses Settings model (not config-based)
- Hybrid pricing uses custom services and models

---

## SUPPORT

If issues arise:
1. Check UPGRADE_LOG.md for documented issues
2. Review Laravel 11 upgrade guide: https://laravel.com/docs/11.x/upgrade
3. Test OpenELMS functionality first
4. Check error logs: storage/logs/laravel.log


