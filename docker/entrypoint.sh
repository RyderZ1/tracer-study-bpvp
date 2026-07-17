#!/bin/sh

# ============================================================
# entrypoint.sh — Skrip inisialisasi container Laravel
# Mendeteksi $PORT dari Railway dan mengkonfigurasi Nginx
# ============================================================

# JANGAN gunakan set -e agar kita bisa menangani error secara manual
# dan tetap menjalankan supervisord meskipun ada langkah yang gagal.

# Default port jika Railway tidak menyediakannya
PORT="${PORT:-8080}"

echo "========================================"
echo " Railway Laravel Entrypoint"
echo " Listening on port: $PORT"
echo "========================================"

# ------- 1. Substitusi $PORT pada konfigurasi Nginx -------
sed -i "s/\${PORT}/${PORT}/g" /etc/nginx/nginx.conf
echo "[OK] Nginx port dikonfigurasi ke: $PORT"

# ------- 2. Generate .env dari environment variables Railway -------
echo "[INFO] Membuat file .env dari environment variables..."
cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-true}"
APP_URL="${APP_URL:-http://localhost}"

APP_LOCALE=${APP_LOCALE:-en}
APP_FALLBACK_LOCALE=${APP_FALLBACK_LOCALE:-en}
APP_FAKER_LOCALE=${APP_FAKER_LOCALE:-en_US}

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=${BCRYPT_ROUNDS:-12}

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-laravel}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-}

SESSION_DRIVER=${SESSION_DRIVER:-cookie}
SESSION_LIFETIME=${SESSION_LIFETIME:-120}
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}

CACHE_STORE=${CACHE_STORE:-file}
EOF

echo "[OK] .env dibuat. DB_CONNECTION=${DB_CONNECTION:-mysql} | DB_HOST=${DB_HOST:-127.0.0.1} | DB_DATABASE=${DB_DATABASE:-laravel}"

# ------- 3. Pastikan direktori storage writable -------
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null
echo "[OK] Storage permissions set"

# ------- 4. Generate APP_KEY jika belum di-set -------
if [ -z "$APP_KEY" ]; then
    echo "[INFO] APP_KEY belum di-set, generating..."
    php artisan key:generate --force 2>&1
    echo "[OK] APP_KEY generated"
else
    echo "[OK] APP_KEY sudah di-set dari environment"
fi

# ------- 5. Clear semua cache lama -------
echo "[INFO] Membersihkan cache lama..."
php artisan config:clear 2>&1 || true
php artisan cache:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true
echo "[OK] Cache cleared"

# ------- 6. Buat symlink storage jika belum ada -------
php artisan storage:link --force 2>&1 || true
echo "[OK] Storage link created"

# ------- 7. Jalankan migrasi database -------
echo "[INFO] Menjalankan migrasi database..."
if php artisan migrate --force 2>&1; then
    echo "[OK] Migrasi berhasil"
else
    echo "[WARN] Migrasi gagal — pastikan database sudah terkonfigurasi di Railway"
fi

# ------- 8. Cache konfigurasi Laravel (Optimasi Production) -------
echo "[INFO] Mengoptimasi Laravel untuk production..."
php artisan config:cache 2>&1 || echo "[WARN] config:cache gagal"
php artisan route:cache 2>&1 || echo "[WARN] route:cache gagal"
php artisan view:cache 2>&1 || echo "[WARN] view:cache gagal"
echo "[OK] Optimasi selesai"

echo "========================================"
echo " Inisialisasi selesai! Memulai server..."
echo "========================================"

# ------- 9. Jalankan command utama (supervisord) -------
exec "$@"
