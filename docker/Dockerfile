# syntax=docker/dockerfile:1

FROM php:8.4.1-apache

ENV PY_VER=3.13.7
ENV TZ=Europe/Kiev

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends curl ca-certificates openssl zip unzip git supervisor nano \
    build-essential wget libssl-dev zlib1g-dev libbz2-dev libreadline-dev libsqlite3-dev libffi-dev libncursesw5-dev xz-utils tk-dev uuid-dev libgdbm-dev liblzma-dev  \
    libonig-dev libzip-dev libicu-dev libxml2-dev libcurl4-openssl-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev

# Python
WORKDIR /tmp
RUN wget https://www.python.org/ftp/python/${PY_VER}/Python-${PY_VER}.tgz \
 && tar xzf Python-${PY_VER}.tgz \
 && cd Python-${PY_VER} \
 && ./configure --enable-optimizations --prefix=/usr/local \
 && make -j"$(nproc)" \
 && make altinstall
RUN ln -s /usr/local/bin/python3.13 /usr/local/bin/python \
 && ln -s /usr/local/bin/python3.13 /usr/local/bin/py \
 && ln -s /usr/local/bin/pip3.13 /usr/local/bin/pip

# PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql curl soap zip intl mbstring bcmath exif pcntl gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && a2enmod rewrite ssl headers http2

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get purge -y --auto-remove build-essential wget make gcc \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=node:24.8.0-slim /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node:24.8.0-slim /usr/local/bin/node /usr/local/bin/
COPY --from=node:24.8.0-slim /usr/local/bin/npm  /usr/local/bin/

RUN ln -sf /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

# LLM
RUN git clone --depth=1 --branch main https://github.com/WitalijKaa/special-creativity-ai /var/www/html/ai
WORKDIR /var/www/html/ai
RUN python -m pip install -r requirements.txt && rm -rf /root/.cache/pip

RUN mkdir -p /etc/apache2/ssl
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/apache2/ssl/localhost.key -out /etc/apache2/ssl/localhost.crt -subj "/C=US/ST=Local/L=Local/O=Local/OU=Dev/CN=localhost"

# WEB
WORKDIR /var/www/html/sc

#COPY . .
RUN git clone --depth=1 --branch d.0001 https://github.com/WitalijKaa/special-creativity /var/www/html/sc

RUN cp /var/www/html/sc/docker/linux/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN cp /var/www/html/sc/docker/linux/php.ini /usr/local/etc/php/conf.d/basic.ini
RUN cp /var/www/html/sc/docker/.env.docker /var/www/html/sc/.env

RUN composer install
RUN npm ci && npm run build && rm -rf node_modules ~/.npm

RUN php artisan key:generate \
    && php artisan config:clear \
    && php artisan config:cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 80 443
