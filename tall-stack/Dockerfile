# Multi-Stage Build for Laravel 12 Application
# Base stage with PHP 8.3 and system dependencies
FROM php:8.3-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_pgsql gd exif

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Dependencies stage - install PHP and Node dependencies
FROM base AS dependencies

COPY composer.json composer.lock package.json package-lock.json ./

# Install PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Install Node dependencies
RUN npm ci --production

# Build stage - build assets and optimize
FROM dependencies AS build

COPY . .

# Run Composer scripts
RUN composer run-script post-autoload-dump

# Build frontend assets
RUN npm run build

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Production stage - final lightweight image
FROM php:8.3-fpm-alpine AS production

# Install runtime dependencies only
RUN apk add --no-cache \
    postgresql-dev \
    libpng \
    libjpeg-turbo \
    libwebp \
    freetype

# Install PHP extensions (runtime only)
RUN docker-php-ext-install pdo pdo_pgsql gd exif

WORKDIR /var/www

# Copy application from build stage
COPY --from=build --chown=www-data:www-data /var/www /var/www

# Copy vendor from dependencies stage (smaller than build stage)
COPY --from=dependencies --chown=www-data:www-data /var/www/vendor /var/www/vendor

# Optimize Laravel for production
RUN php artisan config:cache || true \
    && php artisan route:cache || true \
    && php artisan view:cache || true

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Switch to non-root user
USER www-data

EXPOSE 9000

CMD ["php-fpm"]
