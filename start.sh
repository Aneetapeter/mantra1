#!/bin/sh
set -e

echo "==> Mantra startup..."

# Map Railway's auto-injected PG* vars to Laravel DB_* vars (if DB_* not explicitly set)
DB_HOST="${DB_HOST:-$PGHOST}"
DB_PORT="${DB_PORT:-$PGPORT}"
DB_DATABASE="${DB_DATABASE:-$PGDATABASE}"
DB_USERNAME="${DB_USERNAME:-$PGUSER}"
DB_PASSWORD="${DB_PASSWORD:-$PGPASSWORD}"
DB_CONNECTION="${DB_CONNECTION:-pgsql}"

# Generate .env from all available environment variables
cat > .env <<EOF
APP_NAME=${APP_NAME:-Mantra}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=${LOG_CHANNEL:-stderr}
LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
DATABASE_URL=${DATABASE_URL}

SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=120

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}
CACHE_STORE=${CACHE_STORE:-file}

MAIL_MAILER=${MAIL_MAILER:-smtp}
MAIL_HOST=${MAIL_HOST:-smtp-relay.brevo.com}
MAIL_PORT=${MAIL_PORT:-587}
MAIL_USERNAME=${MAIL_USERNAME}
MAIL_PASSWORD=${MAIL_PASSWORD}
MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-tls}
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}
MAIL_FROM_NAME="${APP_NAME:-Mantra}"

GEMINI_API_KEY=${GEMINI_API_KEY}
VITE_APP_NAME="${APP_NAME:-Mantra}"
EOF

echo "==> .env generated — DB_HOST=${DB_HOST}, DB_DATABASE=${DB_DATABASE}"

echo "==> Clearing config cache..."
php artisan config:clear

echo "==> Running migrations..."
php artisan migrate --force && echo "==> Migrations OK" || echo "==> WARNING: Migrations failed"

echo "==> Starting server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
