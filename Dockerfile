FROM dunglas/frankenphp:php8.2-bookworm

# System packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libatomic1 \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP extensions
RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    redis \
    mbstring \
    zip \
    opcache

WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Build frontend
RUN npm install
RUN npm run build

# Startup script
COPY docker-start.sh /usr/local/bin/docker-start.sh
RUN chmod +x /usr/local/bin/docker-start.sh

EXPOSE 8080

CMD ["/usr/local/bin/docker-start.sh"]