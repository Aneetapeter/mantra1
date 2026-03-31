FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git curl unzip bash \
    postgresql-dev \
    nodejs npm \
    oniguruma-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring xml bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first for layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy package files and build frontend
COPY package.json package-lock.json ./
RUN npm ci

# Copy rest of app
COPY . .

# Run composer scripts after full copy
RUN composer run-script post-autoload-dump || true

# Build frontend assets
RUN npm run build

# Set permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x start.sh

EXPOSE 8080

CMD ["sh", "start.sh"]
