FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev nodejs npm

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

ENV APP_ENV=production
ENV LOG_CHANNEL=stderr
ENV CACHE_STORE=file
ENV SESSION_DRIVER=file

EXPOSE 10000

CMD php artisan config:clear \
    && php artisan migrate --force || true \
    && php artisan serve --host=0.0.0.0 --port=10000

