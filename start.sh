#!/bin/sh

echo "==> Starting Mantra..."

# ─── Resolve DB connection ────────────────────────────────────────────────────
# Priority: DATABASE_URL > POSTGRES_URL > individual DB_* > PG_* vars

DB_HOST="${DB_HOST:-${PGHOST:-127.0.0.1}}"
DB_PORT="${DB_PORT:-${PGPORT:-5432}}"
DB_DATABASE="${DB_DATABASE:-${PGDATABASE:-${POSTGRES_DB:-laravel}}}"
DB_USERNAME="${DB_USERNAME:-${PGUSER:-${POSTGRES_USER:-postgres}}}"
DB_PASSWORD="${DB_PASSWORD:-${PGPASSWORD:-${POSTGRES_PASSWORD:-}}}"

# Auto-fix Render internal hostname → external (resolves DNS failure)
# Render internal: dpg-xxx-a  →  External: dpg-xxx-a.oregon-postgres.render.com
if echo "$DB_HOST" | grep -qE '^dpg-' && ! echo "$DB_HOST" | grep -q '\.'; then
    DB_HOST="${DB_HOST}.oregon-postgres.render.com"
    echo "==> Render host converted to external: ${DB_HOST}"
fi

# Use full DATABASE_URL if injected (Render/Railway provide this)
DB_URL="${DATABASE_URL:-${POSTGRES_URL:-}}"

# If DATABASE_URL has internal Render hostname, rebuild it with external host
if echo "$DB_URL" | grep -qE 'dpg-[a-z0-9]+-[a-z]/' && ! echo "$DB_URL" | grep -q 'render\.com'; then
    # Replace internal host in URL with external host
    INTERNAL_HOST=$(echo "$DB_URL" | sed 's|.*@||' | sed 's|:.*||')
    EXTERNAL_HOST="${INTERNAL_HOST}.oregon-postgres.render.com"
    DB_URL=$(echo "$DB_URL" | sed "s|${INTERNAL_HOST}|${EXTERNAL_HOST}|")
    echo "==> DATABASE_URL host converted to external"
fi

echo "==> DB: ${DB_USERNAME}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}"

# ─── Write .env ──────────────────────────────────────────────────────────────
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

# ─── Bootstrap ───────────────────────────────────────────────────────────────
php artisan config:clear 2>&1 || true
php artisan cache:clear 2>&1 || true
php artisan storage:link --force 2>&1 || true

# ─── Migrate ─────────────────────────────────────────────────────────────────
echo "==> Running migrations..."
php artisan migrate --force 2>&1
if [ $? -eq 0 ]; then
    echo "==> Migrations OK"
else
    echo "==> WARNING: Migrations failed"
fi

# ─── Start server ────────────────────────────────────────────────────────────
PORT="${PORT:-8080}"
echo "==> Live on port ${PORT}"
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
