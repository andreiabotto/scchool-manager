FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Copy app files from the app directory.
COPY . /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Composer dependencies
RUN composer install --prefer-dist --no-scripts

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install system dependencies and Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_14.x | bash - \
    && apt-get install -y nodejs npm

# Copy existing application directory permissions
COPY --chown=www-data:www-data .. /var/www

# Use the default production configuration for PHP runtime arguments
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Criar os diretórios necessários caso não existam
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache
RUN mkdir -p /var/www/storage/framework/views /var/www/storage/framework/cache

# Set permissions for storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www && \
    chmod -R 777 /var/www/storage && \
    chmod -R 775 /var/www/bootstrap/cache && \
    chmod -R 777 /var/www/storage/logs && \
    chmod -R 777 /var/www/storage/framework/views && \
    chmod -R 777 /var/www/storage/framework/sessions

# Install npm dependencies
RUN npm install && npm run dev

EXPOSE 8000
EXPOSE 5173

CMD ["php-fpm"]
