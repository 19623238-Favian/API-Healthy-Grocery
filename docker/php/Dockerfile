FROM php:8.2-apache

# Install system dependencies for intl
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-install \
        intl \
        mysqli \
        pdo \
        pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy CI4 source
COPY . /var/www/html

# Set permissions (CI4 writable)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

# Apache: point DocumentRoot to public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf