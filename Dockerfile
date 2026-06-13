FROM dunglas/frankenphp:php8.2-bookworm

# Install system packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libatomic1 \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    redis \
    mbstring \
    bcmath \
    exif \
    gd \
    intl \
    zip \
    opcache

WORKDIR /app

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install Composer dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# Copy application files
COPY . .

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Build frontend assets
RUN npm install
RUN npm run build

# Storage permissions
RUN mkdir -p storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Startup script
COPY docker-start.sh /usr/local/bin/docker-start.sh
RUN chmod +x /usr/local/bin/docker-start.sh

EXPOSE 8080

CMD ["/usr/local/bin/docker-start.sh"]