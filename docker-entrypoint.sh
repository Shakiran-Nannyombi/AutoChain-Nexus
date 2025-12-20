#!/bin/bash
set -e

# Config Apache to listen on Render's PORT
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-enabled/000-default.conf

# SQLite Persistence Check
# If we mounted a disk to /var/www/html/database, it might be empty on first run.
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Creating database.sqlite..."
    touch /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
fi

# Run Migrations
echo "Running Migrations..."
php artisan migrate --force

# Start Apache in Foreground
echo "Starting Apache..."
apache2-foreground
