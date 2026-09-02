#!/bin/sh
set -e

# Update Nginx port dynamically if PORT is provided by Render
if [ -n "$PORT" ]; then
    sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf || true
    sed -i "s/listen \[::\]:80;/listen [::]:$PORT;/g" /etc/nginx/http.d/default.conf || true
fi

# Ensure storage directories exist and have proper permissions
mkdir -p /var/www/html/storage/app/public /var/www/html/storage/framework/cache /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || true

# Run Production Data Seeder if database is newly provisioned
echo "Checking seed data..."
php artisan db:seed --class=ProductionDataSeeder --force || true

# Link public storage
php artisan storage:link --force || true

# Cache configurations and views for maximum performance
echo "Optimizing application..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting Web Server..."
exec /usr/bin/supervisord -c /etc/supervisord.conf