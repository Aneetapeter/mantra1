#!/bin/sh
set -e

echo "==> Mantra startup: environment=$APP_ENV"

# Generate .env from environment variables at runtime
# This ensures Railway's dashboard vars override any baked-in .env.example values
cat > .env <<EOF
APP_NAME=${APP_NAME:-Mantra}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=${LOG_CHANNEL:-stderr}
LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=${DB_CONNECTION:-pgsql}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE:-mantra}
DB_USERNAME=${DB_USERNAME:-postgres}
DB_PASSWORD=${DB_PASSWORD}
DATABASE_URL=${DATABASE_URL}

SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=${SESSION_LIFETIME:-120}

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

echo "==> .env generated from environment variables"

echo "==> Clearing config cache..."
php artisan config:clear

echo "==> Running migrations..."
php artisan migrate --force 2>&1 && echo "==> Migrations OK" || echo "==> WARNING: Migrations failed - check DB connection vars"

echo "==> Starting server on port ${PORT:-10000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
