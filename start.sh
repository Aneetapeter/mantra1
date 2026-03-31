#!/bin/sh

echo "==> Starting Mantra..."

# Step 1: Determine DB connection
# Priority: DATABASE_URL > POSTGRES_URL > individual DB_* vars > PG_* vars

# Map Railway/Render PG* vars → Laravel DB_* vars (as fallback)
DB_HOST="${DB_HOST:-${PGHOST:-127.0.0.1}}"
DB_PORT="${DB_PORT:-${PGPORT:-5432}}"
DB_DATABASE="${DB_DATABASE:-${PGDATABASE:-${POSTGRES_DB:-laravel}}}"
DB_USERNAME="${DB_USERNAME:-${PGUSER:-${POSTGRES_USER:-postgres}}}"
DB_PASSWORD="${DB_PASSWORD:-${PGPASSWORD:-${POSTGRES_PASSWORD:-}}}"

# Use DATABASE_URL or POSTGRES_URL if provided (Render/Railway inject these)
DB_URL="${DATABASE_URL:-${POSTGRES_URL:-}}"

echo "==> DB: ${DB_USERNAME}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}"
if [ -n "$DB_URL" ]; then
    echo "==> DATABASE_URL found — using full connection URL"
fi

# Step 2: Write .env for Laravel to boot
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
DATABASE_URL=${DB_URL}

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

# Step 3: Clear stale config
php artisan config:clear 2>&1 || true
php artisan cache:clear 2>&1 || true

# Step 4: Storage symlink
php artisan storage:link --force 2>&1 || true

# Step 5: Migrations
echo "==> Running migrations..."
php artisan migrate --force 2>&1
if [ $? -eq 0 ]; then
    echo "==> Migrations OK"
else
    echo "==> WARNING: Migrations failed — DB may not be ready or credentials wrong"
fi

# Step 6: Start server
PORT="${PORT:-8080}"
echo "==> Serving on http://0.0.0.0:${PORT}"
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
