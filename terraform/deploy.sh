#!/bin/bash
set -euxo pipefail

cd /var/www/app
sudo -u nginx git pull origin main
sudo -u nginx composer install --prefer-dist --no-progress --no-interaction
sudo -u nginx php artisan migrate:fresh --seed --force
sudo -u nginx npm ci
sudo -u nginx npm run build
