#!/usr/bin/env bash

# Start Nginx in background
nginx

# Cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start PHP-FPM in foreground (to keep container running)
php-fpm
