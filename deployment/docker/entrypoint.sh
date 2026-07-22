#!/bin/sh
# Container start-up for the Laravel web service.
#
# The named volumes for storage/ and public/uploads/ mount empty on first run,
# so the directory tree and ownership are recreated here rather than relying on
# what the image baked in.
set -e

echo "[entrypoint] preparing writable directories"
mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    storage/app/public \
    bootstrap/cache \
    public/uploads/mri \
    public/uploads/gradcam \
    public/uploads/profile

chown -R www-data:www-data storage bootstrap/cache public/uploads
chmod -R 775 storage bootstrap/cache public/uploads

# Wait for MySQL to accept connections. depends_on covers container health,
# but the grant/user can still be settling on a first-ever boot.
if [ -n "$DB_HOST" ]; then
    echo "[entrypoint] waiting for database at $DB_HOST"
    i=0
    while [ "$i" -lt 60 ]; do
        if php -r 'exit(@fsockopen(getenv("DB_HOST"), (int)(getenv("DB_PORT") ?: 3306)) ? 0 : 1);'; then
            echo "[entrypoint] database reachable"
            break
        fi
        i=$((i + 1))
        sleep 2
    done
fi

if [ -z "$APP_KEY" ]; then
    echo "[entrypoint] WARNING: APP_KEY is empty. Generate one with:"
    echo "             docker compose run --rm web php artisan key:generate --show"
fi

echo "[entrypoint] running migrations"
php artisan migrate --force || echo "[entrypoint] migrations failed - continuing so the container stays up for debugging"

# config:cache is deliberately NOT used: the controllers call env() directly,
# and cached config makes env() return null outside config files.
php artisan config:clear || true
php artisan view:clear || true

echo "[entrypoint] starting services"
exec "$@"
