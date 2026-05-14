#!/bin/sh
set -e

# Run migrations and seeders
echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Creating storage link..."
php artisan storage:link --force

# Cache configuration and routes
echo "Caching configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Fix permissions
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

if [ "$1" = "php-server" ]; then
    echo "Starting FrankenPHP..."
    exec frankenphp run --config /etc/caddy/Caddyfile --adapter json
elif [ "$1" = "worker" ]; then
    echo "Starting Laravel worker..."
    exec php artisan queue:work --sleep=3 --tries=3 --max-time=3600
fi

exec "$@"
