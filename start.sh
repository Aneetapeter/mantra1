#!/bin/sh
set -e

echo "==> Clearing config cache..."
php artisan config:clear

echo "==> Running migrations (non-fatal)..."
php artisan migrate --force 2>&1 || echo "WARNING: Migration failed (DB may not be configured). Continuing..."

echo "==> Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
