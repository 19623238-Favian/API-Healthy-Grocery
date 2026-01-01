FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (WAJIB CI4)
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    intl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Enable Apache rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project
COPY . .

# Fix git ownership warning (optional but clean)
RUN git config --global --add safe.directory /var/www/html

# Install CI4 dependencies
RUN composer install --no-dev --optimize-autoloader

# Permissions (CI4 wajib)
RUN chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

# Apache docroot → public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf