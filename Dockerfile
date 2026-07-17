# ============================================================
# Stage 1: Build Frontend Assets (Vite/Node)
# ============================================================
FROM node:20-alpine AS frontend-build

WORKDIR /app

# Salin file dependency frontend terlebih dahulu (layer caching)
COPY package.json package-lock.json* ./

# Instal dependency Node.js
RUN npm install --ignore-scripts

# Salin seluruh source code untuk proses build
COPY . .

# Build aset frontend (Vite)
RUN npm run build


# ============================================================
# Stage 2: Install PHP Dependencies (Composer)
# ============================================================
FROM composer:2 AS composer-build

WORKDIR /app

# Salin file dependency PHP terlebih dahulu (layer caching)
COPY composer.json composer.lock ./

# Instal dependency production saja (tanpa dev)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# Salin seluruh source code lalu jalankan post-install scripts
COPY . .
RUN composer dump-autoload --optimize --no-dev


# ============================================================
# Stage 3: Production Image (PHP-FPM + Nginx pada Alpine)
# ============================================================
FROM php:8.3-fpm-alpine AS production

# ------- Instal Ekstensi PHP & Nginx -------
RUN apk add --no-cache \
        nginx \
        supervisor \
        curl \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
        libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && rm -rf /var/cache/apk/*

# ------- Konfigurasi OPcache untuk Production -------
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# ------- Konfigurasi PHP Production -------
RUN { \
        echo 'upload_max_filesize=64M'; \
        echo 'post_max_size=64M'; \
        echo 'memory_limit=256M'; \
        echo 'max_execution_time=600'; \
        echo 'expose_php=Off'; \
    } > /usr/local/etc/php/conf.d/custom-php.ini

# ------- Setup Direktori Aplikasi -------
WORKDIR /var/www/html

# Salin source code dari stage Composer (sudah termasuk vendor/)
COPY --from=composer-build /app /var/www/html

# Salin aset frontend yang sudah di-build dari stage Node
COPY --from=frontend-build /app/public/build /var/www/html/public/build

# ------- Salin File Konfigurasi -------
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# ------- Hak Akses -------
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# ------- Buat direktori log Nginx -------
RUN mkdir -p /var/log/nginx /run/nginx

# ------- Expose Port (Railway akan override via $PORT) -------
EXPOSE 8080

# ------- Entrypoint & Command -------
ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
