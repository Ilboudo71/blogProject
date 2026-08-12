#!/usr/bin/env bash
set -e

echo "Running composer..."
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

echo "Ensuring storage directories..."
mkdir -p \
  /var/www/html/storage/framework/cache \
  /var/www/html/storage/framework/sessions \
  /var/www/html/storage/framework/views \
  /var/www/html/storage/logs \
  /var/www/html/storage/app/public \
  /var/www/html/bootstrap/cache

chmod -R ug+rwx /var/www/html/storage /var/www/html/bootstrap/cache

echo "Linking public storage..."
php artisan storage:link || true

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Deploy script finished."
