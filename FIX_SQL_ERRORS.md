# Fix SQL Errors - Admin Pages

## Issue
All admin pages (`/admin/analytics`, `/admin/deals`, `/admin/subscriptions`) are showing SQL errors because required database tables don't exist.

## Solution

### Option 1: Run All Pending Migrations (Recommended)

Run this command to create all missing tables:

```bash
php artisan migrate --force
```

**Note**: If you get foreign key errors, you may need to temporarily disable foreign key checks or fix the migration files.

### Option 2: Run Migrations Selectively

If you have an existing database and want to be careful, run migrations one by one:

```bash
# Critical tables for Module 6
php artisan migrate --path=database/migrations/2025_01_20_000001_create_subscriptions_table.php
php artisan migrate --path=database/migrations/2025_01_20_000002_create_package_features_table.php
php artisan migrate --path=database/migrations/2025_12_17_184605_create_deals_table.php
php artisan migrate --path=database/migrations/2025_12_17_184608_create_deal_purchases_table.php
```

### Option 3: Error Handling Already Added

I've already added comprehensive error handling to all three controllers:
- `AnalyticsController` - All queries wrapped in try-catch
- `DealController` - Error handling for missing tables
- `SubscriptionController` - Error handling for missing tables

The pages should now show empty data instead of crashing, but you'll need to run migrations to see actual data.

## Tables Required

### For `/admin/analytics`:
- ✅ `deals` (exists)
- ✅ `deal_purchases` (exists)
- ✅ `users` (exists)
- ⚠️ `subscriptions` (migration pending)
- ✅ `package_features` (exists)
- ✅ `categories` (exists)

### For `/admin/deals`:
- ✅ `deals` (exists)
- ✅ `users` (exists)
- ✅ `categories` (exists)

### For `/admin/subscriptions`:
- ⚠️ `subscriptions` (migration pending - but we added deleted_at)
- ✅ `users` (exists)
- ✅ `package_features` (exists)

## Quick Fix

The main issue is the `subscriptions` table migration is pending. Run:

```bash
php artisan migrate --path=database/migrations/2025_01_20_000001_create_subscriptions_table.php
```

Then refresh the pages.


