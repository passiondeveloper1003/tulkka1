#!/bin/bash
set -e

echo "Deployment started..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# Pull the latest version of the app
git fetch origin master
git reset --hard FETCH_HEAD
git clean -df

# Install composer dependencies
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs
chmod -R 775 storage
chmod -R 755 bootstrap/cache

# Exit maintenance mode
php artisan up

echo "Deployment finished"
