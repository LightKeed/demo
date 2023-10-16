#!/bin/bash

#===================== Create .env =================

cp .env.example .env
php artisan config:cache

#===================== Composer install =================

# echo "Composer install"
# composer install --working-dir=/var/www

php artisan migrate
php artisan key:generate --force
php artisan optimize

#===================== NPM install =================

npm run build

#===================== Rights install =================

chown -R www-data:www-data /var/www
chmod -R 775 /var/www

#===================== Start service ====================

echo "Start nginx & php-fpm"
nginx
service php8.2-fpm start

echo "Log start..."
tail -f /var/log/nginx/access.log
