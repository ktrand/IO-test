#!/bin/sh
set -e

echo "Install dependencies..."
composer install

echo "Run migrations..."
php artisan migrate

exec "$@"
