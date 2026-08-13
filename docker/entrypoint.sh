#!/usr/bin/env bash
set -e

cd /var/www/html

PORT="${PORT:-8080}"

# Render fournit $PORT dynamiquement
sed "s/\${PORT}/${PORT}/g" /etc/nginx/sites-available/default > /tmp/nginx-default.conf
cp /tmp/nginx-default.conf /etc/nginx/sites-available/default
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

mkdir -p \
  storage/framework/cache \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  storage/app/public \
  bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

php artisan storage:link || true

echo "Caching Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

USER_COUNT="$(php -r 'require "vendor/autoload.php"; $app=require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); echo (int) App\Models\User::query()->count();')"
PRODUCT_COUNT="$(php -r 'require "vendor/autoload.php"; $app=require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); echo (int) App\Models\Product::query()->count();')"
echo "User count: ${USER_COUNT} | Product count: ${PRODUCT_COUNT}"

if [ "${FORCE_SEED:-false}" = "true" ] || [ "${USER_COUNT}" = "0" ] || [ "${PRODUCT_COUNT}" = "0" ]; then
  echo "Seeding admin, seller and sample products..."
  php artisan db:seed --force || echo "WARNING: seed failed (app will still start)"
else
  echo "Data already present — skipping seed."
fi

echo "Starting services on port ${PORT}..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
