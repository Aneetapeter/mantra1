#!/bin/sh
set -e

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Caching config..."
php artisan config:cache

echo "==> Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
