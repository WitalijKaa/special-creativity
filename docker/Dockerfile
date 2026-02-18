# syntax=docker/dockerfile:1.7

ARG PHP_IMAGE=php:8.5.1-fpm
ARG COMPOSER_IMAGE=composer:2.9.3
ARG NODE_IMAGE=node:24.12.0-bookworm-slim
ARG APP_ENV

# Composer
FROM ${COMPOSER_IMAGE} AS composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    composer install --no-scripts --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --ignore-platform-req=ext-rdkafka

# NPM
FROM ${NODE_IMAGE} AS npm

ENV NODE_ENV=production
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
COPY resources/js ./resources/js
COPY resources/styles ./resources/styles
RUN --mount=type=cache,target=/root/.npm npm ci
RUN npm run build

# PHP
FROM ${PHP_IMAGE} AS app

ARG APP_ENV
WORKDIR /app

RUN set -eux; apt-get update; apt-get install -y --no-install-recommends \
      ca-certificates curl \
      librdkafka-dev libbz2-dev libcurl4-openssl-dev libffi-dev libzip-dev zlib1g-dev libicu-dev libpq-dev libsqlite3-dev libxml2-dev libonig-dev libgmp-dev libsodium-dev libjpeg62-turbo-dev libpng-dev libfreetype6-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
      bz2 ffi gd gmp intl exif mysqli pdo_mysql pdo_pgsql pgsql shmop soap sockets zip; \
    pecl install rdkafka; \
    docker-php-ext-enable rdkafka; \
    rm -rf /tmp/pear; \
    rm -rf /var/lib/apt/lists/*

COPY docker/linux/php-${APP_ENV}.ini /usr/local/etc/php/conf.d/42-custom-php.ini
COPY . .
RUN cp /app/docker/.env.docker /app/.env
COPY --from=npm /app/public/build ./public/build
RUN chown -R www-data:www-data /app
COPY --from=composer /app/vendor ./vendor

USER www-data

RUN set -eux; \
    mkdir -p storage bootstrap/cache; \
    chown -R www-data:www-data storage bootstrap/cache; \
    php artisan package:discover --ansi

EXPOSE 9000
CMD ["php-fpm"]
