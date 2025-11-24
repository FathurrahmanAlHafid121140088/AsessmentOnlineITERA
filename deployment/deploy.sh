#!/bin/bash

##############################################################################
# Laravel Deployment Script
#
# Script otomatis untuk deploy/update aplikasi Assessment Online
#
# Usage:
#   bash deploy.sh           # Normal deployment
#   bash deploy.sh --fresh   # Fresh install (WARNING: hapus semua data!)
#
##############################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

print_header() {
    echo ""
    echo -e "${BLUE}=====================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}=====================================${NC}"
}

# Check if running as www-data or with sudo
check_permissions() {
    if [ "$EUID" -eq 0 ] && [ "$1" != "--allow-root" ]; then
        print_error "Jangan jalankan script ini dengan sudo!"
        print_info "Jalankan sebagai user biasa, script akan minta sudo jika perlu"
        exit 1
    fi
}

# Main deployment
main() {
    print_header "🚀 Memulai Deployment Assessment Online"

    # Check if this is fresh install
    FRESH_INSTALL=false
    if [ "$1" == "--fresh" ]; then
        print_warning "WARNING: Fresh install akan menghapus semua data!"
        read -p "Apakah Anda yakin? (yes/no): " confirm
        if [ "$confirm" != "yes" ]; then
            print_error "Deployment dibatalkan"
            exit 1
        fi
        FRESH_INSTALL=true
    fi

    # Step 1: Git Pull
    print_header "📥 Step 1: Update Code dari Git"
    if [ -d .git ]; then
        print_info "Pulling latest code..."
        git pull origin main || git pull origin master
        print_success "Code updated"
    else
        print_warning "Bukan git repository, skip git pull"
    fi

    # Step 2: Maintenance Mode
    print_header "🔧 Step 2: Enable Maintenance Mode"
    php artisan down --render="errors::503" --retry=60 || true
    print_success "Application is now in maintenance mode"

    # Step 3: Install Composer Dependencies
    print_header "📦 Step 3: Install Composer Dependencies"
    print_info "Running composer install..."
    composer install --optimize-autoloader --no-dev --no-interaction
    print_success "Composer dependencies installed"

    # Step 4: Install NPM Dependencies
    print_header "📦 Step 4: Install NPM Dependencies"
    if [ -f package.json ]; then
        print_info "Running npm install..."
        npm install --legacy-peer-deps
        print_success "NPM dependencies installed"
    else
        print_warning "package.json not found, skip npm install"
    fi

    # Step 5: Build Assets
    print_header "🎨 Step 5: Build Frontend Assets"
    if [ -f package.json ]; then
        print_info "Running npm build..."
        npm run build
        print_success "Assets built successfully"
    fi

    # Step 6: Database Migration
    print_header "💾 Step 6: Database Migration"
    if [ "$FRESH_INSTALL" = true ]; then
        print_warning "Running fresh migration (deletes all data)..."
        php artisan migrate:fresh --force --seed
        print_success "Database reset & seeded"
    else
        print_info "Running migrations..."
        php artisan migrate --force
        print_success "Database migrated"
    fi

    # Step 7: Clear Caches
    print_header "🧹 Step 7: Clear All Caches"
    print_info "Clearing caches..."
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    php artisan view:clear
    print_success "All caches cleared"

    # Step 8: Optimize Laravel
    print_header "⚡ Step 8: Optimize Application"
    print_info "Caching configuration..."
    php artisan config:cache
    print_info "Caching routes..."
    php artisan route:cache
    print_info "Caching views..."
    php artisan view:cache
    print_success "Application optimized"

    # Step 9: Fix Permissions
    print_header "🔐 Step 9: Fix Permissions"
    print_info "Setting correct permissions..."

    # Storage & cache must be writable
    sudo chmod -R 775 storage bootstrap/cache

    # Set ownership to web server
    sudo chown -R www-data:www-data .

    # If using SQLite, make database writable
    if [ -f database/database.sqlite ]; then
        sudo chmod 664 database/database.sqlite
        sudo chown www-data:www-data database/database.sqlite
    fi

    print_success "Permissions fixed"

    # Step 10: Restart Services
    print_header "🔄 Step 10: Restart Services"
    print_info "Reloading PHP-FPM..."
    sudo systemctl reload php8.2-fpm || sudo systemctl reload php8.1-fpm || sudo systemctl reload php-fpm

    print_info "Reloading Nginx..."
    sudo systemctl reload nginx
    print_success "Services restarted"

    # Step 11: Disable Maintenance Mode
    print_header "✅ Step 11: Disable Maintenance Mode"
    php artisan up
    print_success "Application is now live!"

    # Final Summary
    print_header "🎉 Deployment Completed!"
    print_success "Application deployed successfully!"
    echo ""
    print_info "Summary:"
    echo "  - Code updated: ✓"
    echo "  - Dependencies installed: ✓"
    echo "  - Assets built: ✓"
    echo "  - Database migrated: ✓"
    echo "  - Caches optimized: ✓"
    echo "  - Permissions fixed: ✓"
    echo "  - Services restarted: ✓"
    echo ""

    # Show application info
    print_info "Application Info:"
    php artisan --version
    echo ""

    # Check for errors in logs
    if [ -f storage/logs/laravel.log ]; then
        ERRORS=$(tail -20 storage/logs/laravel.log | grep -i "error" | wc -l)
        if [ $ERRORS -gt 0 ]; then
            print_warning "Found $ERRORS recent errors in logs"
            print_info "Check: tail -f storage/logs/laravel.log"
        fi
    fi

    print_success "Deployment selesai! Silakan test aplikasi."
}

# Cleanup on error
cleanup() {
    print_error "Deployment gagal!"
    print_info "Mencoba restore application..."
    php artisan up 2>/dev/null || true
}

trap cleanup ERR

# Check permissions first
check_permissions "$@"

# Run main deployment
main "$@"
