#!/bin/sh

echo "==> Starting Mantra on Railway..."

# Step 1: Map Railway PostgreSQL plugin vars to Laravel DB vars
DB_HOST="${DB_HOST:-${PGHOST:-127.0.0.1}}"
DB_PORT="${DB_PORT:-${PGPORT:-5432}}"
DB_DATABASE="${DB_DATABASE:-${PGDATABASE:-laravel}}"
DB_USERNAME="${DB_USERNAME:-${PGUSER:-postgres}}"
DB_PASSWORD="${DB_PASSWORD:-${PGPASSWORD:-}}"

echo "==> DB: ${DB_USERNAME}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}"

# Step 2: Write .env so Laravel artisan can boot correctly
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
DB_SSLMODE=${DB_SSLMODE:-prefer}

SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=

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

echo "==> .env written"

# Step 3: Clear stale config/cache (non-fatal)
php artisan config:clear 2>&1 || echo "config:clear warning (non-fatal)"
php artisan cache:clear 2>&1 || echo "cache:clear warning (non-fatal)"

# Step 4: Storage symlink for file uploads
php artisan storage:link --force 2>&1 || echo "storage:link warning (non-fatal)"

# Step 5: Run migrations (non-fatal - server must start even if DB not ready)
echo "==> Running migrations..."
php artisan migrate --force 2>&1
if [ $? -eq 0 ]; then
    echo "==> Migrations OK"
else
    echo "==> WARNING: Migrations failed - app will run but DB features may not work"
fi

# Step 6: Start the web server
PORT="${PORT:-8080}"
echo "==> Serving on port ${PORT}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
