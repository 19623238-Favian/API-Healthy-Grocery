FROM php:8.2-apache

# =========================
# System dependencies
# =========================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libicu-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# =========================
# PHP extensions (CI4 + PostgreSQL)
# =========================
RUN docker-php-ext-install \
    pdo \
    pgsql \
    pdo_pgsql \
    intl

# =========================
# Composer
# =========================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================
# Apache config
# =========================
RUN a2enmod rewrite

# =========================
# App setup
# =========================
WORKDIR /var/www/html
COPY . .

# Fix git ownership warning (optional)
RUN git config --global --add safe.directory /var/www/html

# =========================
# Install dependencies
# =========================
RUN composer install --no-dev --optimize-autoloader

# =========================
# Permissions (CI4 wajib)
# =========================
RUN chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

# =========================
# Apache docroot → public
# =========================
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf