FROM php:8.2-fpm

# Install dependensi dasar
RUN apt-get update && apt-get install -y \
    nginx \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app
COPY . .

# Install dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Konfigurasi Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Beri izin
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 755 /app/storage /app/bootstrap/cache

# Expose port
EXPOSE 80

# Jalankan Nginx dan PHP-FPM
CMD php-fpm -D && nginx -g "daemon off;"