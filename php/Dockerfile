FROM php:8.2-apache

# Install dependencies
RUN apt-get clean && rm -rf /var/lib/apt/lists/* && \
    apt-get update && \
    apt-get install -y gnupg

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    libmariadb-dev \
    && docker-php-ext-install \
    intl \
    opcache \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    pgsql \
    zip \
    && a2enmod \
    rewrite \
    headers \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

# Set DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Enable Apache2 mod_rewrite
RUN a2enmod rewrite

# Enable Apache2 mod_headers
RUN a2enmod headers

# Copy application files
COPY app /var/www/html/app
COPY core /var/www/html/core
COPY index.php /var/www/html/index.php
COPY .htaccess /var/www/html/.htaccess
COPY ./composer.json /var/www/html/composer.json

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# Set permissions
ENV COMPOSER_ALLOW_SUPERUSER=1

