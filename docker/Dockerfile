# syntax=docker/dockerfile:1

FROM php:8.4.1-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
        libonig-dev libzip-dev libicu-dev libxml2-dev libcurl4-openssl-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql curl zip intl mbstring bcmath exif pcntl gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY ./php/php.ini /usr/local/etc/php/conf.d/projects.ini
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# COPY ./.env.docker /var/www/html/.env
# VOLUME ["/var/www/html"]

WORKDIR /var/www/html

EXPOSE 80 443

CMD ["apache2-foreground"]

