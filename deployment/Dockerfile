# OneHealth+ Laravel web application.
#
# Build context is the REPOSITORY ROOT (composer.json, artisan and app/ live
# there, not in deployment/):
#
#   docker build -f deployment/Dockerfile .
#
FROM php:8.2-fpm

WORKDIR /var/www/html

# System dependencies and PHP extensions required by Laravel + MySQL.
# barryvdh/laravel-dompdf needs gd; Laravel needs pdo_mysql, mbstring, zip.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        curl \
        zip \
        unzip \
        nginx \
        supervisor \
        default-mysql-client \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies first so this layer caches independently of source.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Application source
COPY . .

RUN composer dump-autoload --optimize \
    && mkdir -p public/uploads/mri public/uploads/gradcam public/uploads/profile \
    && chown -R www-data:www-data storage bootstrap/cache public/uploads \
    && chmod -R 775 storage bootstrap/cache public/uploads

# Nginx + supervisor + entrypoint
COPY deployment/docker/nginx/default.conf /etc/nginx/sites-available/default
COPY deployment/docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY deployment/docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
