FROM php:8.2-fpm

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    vim \
    libzip-dev \
    libpq-dev \
    libssl-dev \
    nano \
    supervisor \
    ca-certificates \
    gnupg

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get update && apt-get install -y nodejs && \
    npm install -g npm

RUN chown -R www-data:www-data /var/www

RUN composer require beyondcode/laravel-websockets

EXPOSE 9000
EXPOSE 6001
