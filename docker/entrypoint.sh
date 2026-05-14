#!/bin/sh

set -e

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Creating storage link..."
php artisan storage:link --force

echo "Caching configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

exec "$@"
