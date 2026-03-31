#!/bin/sh
set -e

echo "==> Starting Mantra..."

# Map Railway PostgreSQL plugin vars → Laravel DB vars (if not already set)
export DB_CONNECTION="${DB_CONNECTION:-pgsql}"
export DB_HOST="${DB_HOST:-${PGHOST:-127.0.0.1}}"
export DB_PORT="${DB_PORT:-${PGPORT:-5432}}"
export DB_DATABASE="${DB_DATABASE:-${PGDATABASE:-mantra}}"
export DB_USERNAME="${DB_USERNAME:-${PGUSER:-postgres}}"
export DB_PASSWORD="${DB_PASSWORD:-${PGPASSWORD:-}}"

# Set production defaults
export APP_ENV="${APP_ENV:-production}"
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
export SESSION_DRIVER="${SESSION_DRIVER:-file}"
export CACHE_STORE="${CACHE_STORE:-file}"

echo "==> DB: ${DB_USERNAME}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}"

# Clear stale config cache
php artisan config:clear
php artisan cache:clear

# Run migrations (non-fatal so server still starts even if DB has issues)
echo "==> Running migrations..."
php artisan migrate --force && echo "==> Migrations OK" || echo "==> WARNING: Migrations failed"

# Start server
PORT="${PORT:-8080}"
echo "==> Serving on port ${PORT}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
