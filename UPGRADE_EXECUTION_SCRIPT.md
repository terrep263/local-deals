# Laravel 11 Upgrade Execution Script
## Run these commands in sequence

**⚠️ STOP: Read LARAVEL_11_UPGRADE_GUIDE.md first!**

---

## PHASE 1: BACKUP (DO THIS FIRST!)

```bash
# Create timestamp
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Database backup (adjust credentials)
mysqldump -u root -p local_deals > storage/backups/pre-upgrade-$TIMESTAMP.sql

# Codebase backup
cp -r . ../lake-county-backup-$TIMESTAMP
```

---

## PHASE 2: COMPOSER UPDATE (9 → 10 → 11)

### Step 1: Update to Laravel 10
```bash
composer require laravel/framework:^10.0 --update-with-dependencies
```

### Step 2: Clear caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 3: Test Laravel 10
```bash
php artisan --version  # Should show Laravel 10.x
php artisan test       # Run test suite
```

### Step 4: Update to Laravel 11
```bash
composer require laravel/framework:^11.0 --update-with-dependencies
```

### Step 5: Clear caches again
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## PHASE 3: STRUCTURAL CHANGES (After Laravel 11 upgrade)

### Step 1: Activate Laravel 11 bootstrap file
```bash
# BACKUP current bootstrap/app.php first!
cp bootstrap/app.php bootstrap/app.php.l9.backup

# Activate Laravel 11 bootstrap
cp bootstrap/app.l11.php bootstrap/app.php
```

### Step 2: Remove old Kernel.php (after bootstrap/app.php is verified working)
```bash
# BACKUP FIRST!
cp app/Http/Kernel.php app/Http/Kernel.php.backup

# Test the application first, then remove:
# rm app/Http/Kernel.php
```

### Step 3: Update RouteServiceProvider (if needed)
- Routes in routes/web.php use full class names (App\Http\Controllers\...)
- This is compatible with Laravel 11
- RouteServiceProvider may be simplified or removed

### Step 2: Update RouteServiceProvider
- Review routes/web.php for namespace usage
- Update if needed

### Step 3: Run migrations
```bash
php artisan migrate
```

---

## PHASE 4: TESTING

### Critical OpenELMS Tests
```bash
# Test routes
php artisan route:list | grep training

# Test in browser:
# http://localhost/vendor/training
# http://localhost/vendor/training/course/1
```

### Full Test Suite
```bash
php artisan test
```

---

## PHASE 5: VERIFICATION

### Check Laravel Version
```bash
php artisan --version
# Should show: Laravel Framework 11.x.x
```

### Check PHP Version
```bash
php -v
# Should show: PHP 8.2+ or 8.3+
```

### Verify OpenELMS
- [ ] Vendor training page loads
- [ ] Course enrollment works
- [ ] Progress tracking works
- [ ] Deal creation restriction works

---

## IF SOMETHING BREAKS

### Rollback Database
```bash
mysql -u root -p local_deals < storage/backups/pre-upgrade-[TIMESTAMP].sql
```

### Rollback Codebase
```bash
rm -rf .
cp -r ../lake-county-backup-[TIMESTAMP]/* .
composer install
```

---

## POST-UPGRADE

### Update .env (if needed)
Add Laravel 11 specific variables:
```
BCRYPT_ROUNDS=12
APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database
```

### Final Cache Clear
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## NOTES

- composer.json has been pre-updated
- bootstrap/app.php has been created (Laravel 11 structure)
- AppServiceProvider has been updated
- Kernel.php will be removed after bootstrap/app.php is verified working

**DO NOT remove Kernel.php until you've verified bootstrap/app.php works!**

