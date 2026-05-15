# Stage 1: Build PHP dependencies
FROM dunglas/frankenphp:php8.5-alpine AS php-build

WORKDIR /var/www/html

COPY . .

RUN apk add --no-cache git unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --no-scripts --no-autoloader --prefer-dist

RUN composer dump-autoload --optimize --no-dev

# Stage 2: Build JS assets
FROM node:22-alpine AS node-build

WORKDIR /var/www/html

COPY . .

RUN npm ci

COPY --from=php-build /var/www/html/vendor ./vendor
RUN npm run build

# Stage 3: Runtime image
FROM dunglas/frankenphp:php8.5-alpine

LABEL maintainer="Serhii Podvysotskyi"

WORKDIR /var/www/html

# Install system dependencies for FrankenPHP
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    curl \
    libcap

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql gd zip bcmath pcntl

# Copy app code
COPY --from=php-build /var/www/html /var/www/html
COPY --from=node-build /var/www/html/public/build ./public/build

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh # && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

VOLUME ["/var/www/html/storage/app/private", "/var/www/html/storage/app/public"]

ENTRYPOINT ["entrypoint.sh"]
CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
