# Stage 1: Build Frontend Assets
FROM node:20 as frontend
WORKDIR /app
COPY package*.json ./
COPY vite.config.js ./
# Copy resources (css, js, views)
COPY resources/ ./resources/
# Copy other config files if needed
COPY postcss.config.js ./ 

RUN npm install
RUN npm run build

# Stage 2: Build Application Runtime
FROM php:8.4-apache

# Install System Dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Fix Apache MPM Conflict: Hard Delete conflicting MPMs
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
    /etc/apache2/mods-enabled/mpm_event.conf \
    /etc/apache2/mods-enabled/mpm_worker.load \
    /etc/apache2/mods-enabled/mpm_worker.conf

# Force Enable prefork
RUN a2enmod mpm_prefork

# Set Working Directory
WORKDIR /var/www/html

# Copy Application Source
COPY . /var/www/html

# Copy Built Assets from Frontend Stage
COPY --from=frontend /app/public/build /var/www/html/public/build

# Update Apache Config to point to public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP Dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
