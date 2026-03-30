#!/bin/sh
set -e

echo "==> Environment: $APP_ENV"

# If DATABASE_URL is set (Railway injects this), parse it into Laravel DB vars
if [ -n "$DATABASE_URL" ]; then
  echo "==> DATABASE_URL detected, using it for DB connection"
  export DB_CONNECTION=pgsql
fi

echo "==> Clearing config cache..."
php artisan config:clear

echo "==> Running migrations..."
php artisan migrate --force 2>&1 && echo "Migrations OK" || echo "WARNING: Migrations failed - DB may not be configured yet"

echo "==> Starting Laravel server on port ${PORT:-10000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
