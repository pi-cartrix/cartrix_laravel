
FROM php:8.5.10-apache-bookworm

RUN apt-get update && apt-get install -y \
    $PHPIZE_DEPS \
    libssl-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    && pecl install mongodb-2.4.1 \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install mbstring zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.10.3 /usr/bin/composer /usr/bin/composer

COPY docker/apache/laravel.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite headers

WORKDIR /var/www/html

RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/docker-memory.ini \
    && echo "date.timezone=America/Sao_Paulo" >> /usr/local/etc/php/conf.d/docker-memory.ini

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
