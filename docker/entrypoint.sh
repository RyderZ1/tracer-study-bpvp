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

# ------- 2. Generate .env jika belum ada -------
if [ ! -f /var/www/html/.env ]; then
    echo "File .env tidak ditemukan, menyalin dari .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# ------- 3. Generate APP_KEY jika belum di-set -------
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY belum di-set, generating..."
    php artisan key:generate --force
else
    # Pastikan APP_KEY dari environment variable ditulis ke .env
    sed -i "s|APP_KEY=.*|APP_KEY=${APP_KEY}|g" /var/www/html/.env
fi

# ------- 4. Pastikan direktori storage writable -------
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ------- 5. Buat symlink storage jika belum ada -------
php artisan storage:link --force 2>/dev/null || true

# ------- 6. Jalankan migrasi database (opsional, aman untuk production) -------
echo "Menjalankan migrasi database..."
php artisan migrate --force 2>/dev/null || echo "PERINGATAN: Migrasi gagal. Pastikan database sudah terkonfigurasi."

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
