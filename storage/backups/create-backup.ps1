# Database Backup Script for Laravel 11 Pre-Upgrade
# Run this script with your database credentials
# Usage: .\create-backup.ps1

$date = Get-Date -Format "yyyy-MM-dd"
$backupFile = "pre-upgrade-$date.sql"

# Get database credentials from .env or set manually
# For Laragon, typical defaults are:
# DB_HOST: 127.0.0.1
# DB_USERNAME: root
# DB_PASSWORD: (usually empty)
# DB_DATABASE: (check your .env file)

# Read .env file to get database credentials
$envFile = Join-Path $PSScriptRoot "..\..\.env"
if (Test-Path $envFile) {
    $envContent = Get-Content $envFile
    $dbHost = ($envContent | Select-String "DB_HOST=").ToString().Split("=")[1].Trim()
    $dbUsername = ($envContent | Select-String "DB_USERNAME=").ToString().Split("=")[1].Trim()
    $dbPassword = ($envContent | Select-String "DB_PASSWORD=").ToString().Split("=")[1].Trim()
    $dbDatabase = ($envContent | Select-String "DB_DATABASE=").ToString().Split("=")[1].Trim()
} else {
    Write-Host "ERROR: .env file not found. Please set database credentials manually."
    exit 1
}

# Build mysqldump command
$mysqlDumpPath = "C:\laragon\bin\mysql\mysql-8.0.30\bin\mysqldump.exe"
if (-not (Test-Path $mysqlDumpPath)) {
    Write-Host "ERROR: mysqldump not found at $mysqlDumpPath"
    Write-Host "Please update the path in this script or run mysqldump manually:"
    Write-Host "mysqldump -u $dbUsername -p$dbPassword $dbDatabase > storage/backups/$backupFile"
    exit 1
}

$backupPath = Join-Path $PSScriptRoot $backupFile

# Create backup
Write-Host "Creating database backup: $backupFile"
& $mysqlDumpPath -u $dbUsername -p$dbPassword $dbDatabase > $backupPath

if ($LASTEXITCODE -eq 0) {
    $fileSize = (Get-Item $backupPath).Length / 1MB
    Write-Host "✓ Backup created successfully: $backupFile ($([math]::Round($fileSize, 2)) MB)"
    Write-Host "Location: $backupPath"
} else {
    Write-Host "ERROR: Backup failed. Please check your database credentials."
    exit 1
}






