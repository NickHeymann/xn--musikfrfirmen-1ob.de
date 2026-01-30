# Multi-Stage Build for Laravel 12 Application
# Base stage with PHP 8.4 and system dependencies
FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    zlib-dev \
    icu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    $PHPIZE_DEPS

# Install PHP extensions including Redis
RUN pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_pgsql gd exif intl zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Dependencies stage - install PHP and Node dependencies
FROM base AS dependencies

COPY composer.json composer.lock package.json package-lock.json ./

# Install PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Install Node dependencies (including dev for build)
RUN npm ci

# Build stage - build assets and optimize
FROM dependencies AS build

COPY . .

# Run Composer scripts
RUN composer run-script post-autoload-dump

# Build frontend assets
RUN npm run build

# Publish Livewire assets
RUN php artisan livewire:publish --assets

# Remove dev dependencies after build
RUN npm prune --omit=dev

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Production stage - final lightweight image
FROM php:8.4-fpm-alpine AS production

# Install runtime dependencies only
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    zlib-dev \
    icu-dev \
    libzip-dev \
    nginx \
    supervisor \
    $PHPIZE_DEPS

# Install PHP extensions including Redis
RUN pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_pgsql gd exif intl zip opcache

WORKDIR /var/www

# Copy application from build stage
COPY --from=build --chown=www-data:www-data /var/www /var/www

# Copy nginx config from build stage
COPY --from=build /var/www/nginx.conf /etc/nginx/nginx.conf

# Create supervisor config
RUN mkdir -p /var/log/supervisor
COPY --chmod=644 <<EOF /etc/supervisord.conf
[supervisord]
nodaemon=true
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=/usr/local/sbin/php-fpm
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/php-fpm.err.log
stdout_logfile=/var/log/supervisor/php-fpm.out.log

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/nginx.err.log
stdout_logfile=/var/log/supervisor/nginx.out.log
EOF

# Optimize PHP for production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
