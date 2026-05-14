# Stage 1: Build PHP dependencies
FROM dunglas/frankenphp:latest-php8.5-alpine as php-build

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN apk add --no-cache git unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN composer dump-autoload --optimize --no-dev

# Stage 2: Build JS assets
FROM node:22-alpine as node-build

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
COPY --from=php-build /var/www/html/vendor ./vendor
RUN npm run build

# Stage 3: Final image
FROM dunglas/frankenphp:latest-php8.5-alpine

LABEL maintainer="Serhii Podvysotskyi"

WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    supervisor \
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

# Copy configurations
COPY docker/Caddyfile.json /etc/caddy/Caddyfile
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp

EXPOSE 80 443 443/udp

ENTRYPOINT ["entrypoint.sh"]
