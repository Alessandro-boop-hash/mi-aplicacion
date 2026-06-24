#!/bin/sh

# Exit immediately if a command exits with a non-zero status
set -e

echo "Running environment setup..."

# Cache configuration, routes, and views for production performance
echo "Caching Laravel configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations automatically in production
echo "Running database migrations..."
php artisan migrate --force

echo "Starting Apache web server..."
exec apache2-foreground
