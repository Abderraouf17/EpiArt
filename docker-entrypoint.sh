#!/bin/bash
set -e

# Run migrations (force is needed for production)
echo "🚀 Running database migrations..."
php artisan migrate --force

# Seed database if it's the first time (optional, commented out by default to prevent duplication)
# php artisan db:seed --force

# Clear and cache config/routes/views for performance
echo "🔥 Optimizing application..."
php artisan optimize:clear
php artisan optimize

# Start Apache
echo "✅ Starting server..."
exec apache2-foreground
