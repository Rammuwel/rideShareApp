#!/bin/sh

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start Reverb internally
php artisan reverb:start \
    --host=127.0.0.1 \
    --port=8081 &

# Start Laravel
php artisan octane:frankenphp \
    --host=0.0.0.0 \
    --port=${PORT:-8080}