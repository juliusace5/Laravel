# Use the official PHP image as the base
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install Laravel dependencies
RUN composer install --optimize-autoloader --no-dev

RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set the entrypoint to start up php-fpm
ENTRYPOINT ["docker-entrypoint.sh"]

FROM php:8.2-fpm


CMD ["php-fpm"]
