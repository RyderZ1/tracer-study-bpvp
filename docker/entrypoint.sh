#!/bin/sh
set -e

# ============================================================
# entrypoint.sh — Skrip inisialisasi container Laravel
# Mendeteksi $PORT dari Railway dan mengkonfigurasi Nginx
# ============================================================

# Default port jika Railway tidak menyediakannya
PORT="${PORT:-8080}"

echo "========================================"
echo " Railway Laravel Entrypoint"
echo " Listening on port: $PORT"
echo "========================================"

# ------- 1. Substitusi $PORT pada konfigurasi Nginx -------
sed -i "s/\${PORT}/${PORT}/g" /etc/nginx/nginx.conf

# ------- 2. Generate .env dari environment variables Railway -------
# Membuat .env secara dinamis dari OS environment variables.
# Ini memastikan Railway env vars (DB_CONNECTION=mysql, dll) digunakan,
# bukan fallback sqlite dari .env.example.
echo "Membuat file .env dari environment variables..."
cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

APP_LOCALE=${APP_LOCALE:-en}
APP_FALLBACK_LOCALE=${APP_FALLBACK_LOCALE:-en}
APP_FAKER_LOCALE=${APP_FAKER_LOCALE:-en_US}

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=${BCRYPT_ROUNDS:-12}

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_STACK=${LOG_STACK:-single}
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=${LOG_LEVEL:-error}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-laravel}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-}

SESSION_DRIVER=${SESSION_DRIVER:-database}
SESSION_LIFETIME=${SESSION_LIFETIME:-120}
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}

CACHE_STORE=${CACHE_STORE:-database}
EOF

echo "File .env berhasil dibuat dengan DB_CONNECTION=${DB_CONNECTION:-mysql}"

# ------- 3. Generate APP_KEY jika belum di-set -------
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY belum di-set, generating..."
    php artisan key:generate --force
fi

# ------- 4. Pastikan direktori storage writable -------
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ------- 5. Buat symlink storage jika belum ada -------
php artisan storage:link --force 2>/dev/null || true

# ------- 6. Jalankan migrasi database -------
echo "Menjalankan migrasi database..."
php artisan migrate --force 2>&1 || echo "PERINGATAN: Migrasi gagal. Pastikan database sudah terkonfigurasi."

# ------- 7. Cache konfigurasi Laravel (Optimasi Production) -------
echo "Mengoptimasi Laravel untuk production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "========================================"
echo " Inisialisasi selesai! Memulai server..."
echo "========================================"

# ------- 8. Jalankan command utama (supervisord) -------
exec "$@"
