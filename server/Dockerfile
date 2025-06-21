# Stage 1: Build dependencies using Composer
FROM composer:2 as composer

WORKDIR /app

# Copy composer files
COPY /assets/php/composer.json /app/composer.json

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

FROM php:8-apache

# Install required PHP extensions and tools
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    && docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY --from=composer /app/vendor /var/www/html/vendor

WORKDIR /var/www/html

COPY . .
COPY php.ini /usr/local/etc/php/conf.d/php.ini
