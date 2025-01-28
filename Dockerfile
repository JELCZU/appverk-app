# Use official PHP image with PHP-FPM
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required for Laravel
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_sqlite
# RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip

# Install Composer (for managing PHP dependencies)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set the working directory inside the container
WORKDIR /var/www

# Copy the Laravel application code into the container
COPY . .

# Set permissions for the Laravel application
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose the port for PHP-FPM
EXPOSE 9000

# Start the PHP-FPM server
CMD ["php-fpm"]
