#!/bin/bash
set -e

echo "=== Running migrations ==="
php artisan migrate --force

echo "=== Clearing caches ==="
php artisan config:clear
php artisan cache:clear

echo "=== Starting server ==="
php artisan serve --host=0.0.0.0 --port=$PORT
