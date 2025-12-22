# Laragon PHP Command Setup Guide

## Problem
On Windows with Laragon, `php artisan` commands fail with:
```
php : The term 'php' is not recognized as the name of a cmdlet
```

This happens because PHP isn't in the Windows PATH environment variable.

## Solution

### Option 1: Use Full PHP Path (Recommended for this project)

Use the full path to the Laragon PHP executable:

```powershell
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan cache:clear
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan migrate
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan route:cache
```

**Note:** The PHP version number may differ. Check `C:\laragon\bin\php\` for your version.

### Option 2: Create a PowerShell Alias

Add this to your PowerShell profile to create an alias:

```powershell
Set-Alias php "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe"
```

Then you can use:
```powershell
php artisan cache:clear
php artisan migrate
```

### Option 3: Add to Windows PATH (Permanent)

1. Open Environment Variables (Win + R, type `sysdm.cpl`)
2. Click "Environment Variables"
3. Under "User variables", click "New"
4. Variable name: `PHP`
5. Variable value: `C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64`
6. Click OK and restart your terminal

Then use normally:
```powershell
php artisan cache:clear
```

## For Local Deals Project

All Laravel commands needed:

```powershell
# Clear caches
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan cache:clear
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan route:cache

# Run migrations
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan migrate

# Check migration status
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan migrate:status

# Use Tinker for database queries
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan tinker
```

## Database Migrations Status

To check which migrations have been run:
```powershell
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan migrate:status
```

## Marketing System Migrations

The AI Marketing Assistant system uses these three migrations (all now completed):

1. **2025_12_22_100001_create_ai_marketing_content_table** - Stores generated marketing content
2. **2025_12_22_100002_create_vendor_email_campaigns_table** - Tracks email campaigns
3. **2025_12_22_100003_update_ai_usage_tracking_add_marketing** - Extends usage tracking for marketing feature

All three are now successfully migrated ✅

## Troubleshooting

### If migrations fail
```powershell
# Check status
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan migrate:status

# Rollback last batch
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan migrate:rollback

# Refresh everything
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan migrate:fresh
```

### If Tinker commands fail
Make sure to use proper escaping:
```powershell
& "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" artisan tinker --execute="DB::table('users')->count(); die();"
```

## Quick Reference

Create an alias in PowerShell for faster typing:

```powershell
# Add this to your PowerShell profile
Set-Alias php "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe"
Set-Alias artisan "$PWD\artisan.php"

# Then use:
php artisan cache:clear
```

To find your PowerShell profile:
```powershell
$PROFILE
```

Edit it with:
```powershell
notepad $PROFILE
```
