#!/bin/bash

cd /var/www/app
sudo -u nginx git pull origin main
sudo -u nginx composer install --prefer-dist --no-progress --no-interaction
sudo -u nginx php artisan migrate --seed --force
sudo -u npm ci
sudo -u npm run build
