#!/bin/sh
set -e

echo "==> Starting Mantra on Railway..."

# Step 1: Map Railway PostgreSQL plugin vars → Laravel DB vars
DB_HOST="${DB_HOST:-${PGHOST:-127.0.0.1}}"
DB_PORT="${DB_PORT:-${PGPORT:-5432}}"
DB_DATABASE="${DB_DATABASE:-${PGDATABASE:-laravel}}"
DB_USERNAME="${DB_USERNAME:-${PGUSER:-postgres}}"
DB_PASSWORD="${DB_PASSWORD:-${PGPASSWORD:-}}"

echo "==> DB: ${DB_USERNAME}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}"

# Step 2: Write .env so Laravel can boot (artisan needs this file)
cat > /app/.env << ENVEOF
APP_NAME=${APP_NAME:-Mantra}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=${LOG_CHANNEL:-stderr}
LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=120

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}
CACHE_STORE=${CACHE_STORE:-file}

MAIL_MAILER=${MAIL_MAILER:-smtp}
MAIL_HOST=${MAIL_HOST:-smtp-relay.brevo.com}
MAIL_PORT=${MAIL_PORT:-587}
MAIL_USERNAME=${MAIL_USERNAME:-}
MAIL_PASSWORD=${MAIL_PASSWORD:-}
MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-tls}
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-hello@example.com}
MAIL_FROM_NAME=Mantra

GEMINI_API_KEY=${GEMINI_API_KEY:-}
VITE_APP_NAME=Mantra
ENVEOF

echo "==> .env written successfully"

# Step 3: Clear any stale cached config
php artisan config:clear
php artisan cache:clear

# Step 4: Run migrations
echo "==> Running migrations..."
if php artisan migrate --force; then
    echo "==> Migrations OK"
else
    echo "==> WARNING: Migrations failed - check DB connection"
    echo "==> DB_HOST=${DB_HOST} DB_DATABASE=${DB_DATABASE}"
fi

# Step 5: Start server
PORT="${PORT:-8080}"
echo "==> Serving on http://0.0.0.0:${PORT}"
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
