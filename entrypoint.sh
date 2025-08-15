#!/bin/bash

# Espera banco inicializar
sleep 10

# Laravel
php artisan migrate --force
php artisan config:cache
php artisan route:cache

# Storage link
if [ ! -L "/var/www/html/public/storage" ]; then
    php artisan storage:link
fi

# Inicia Apache
exec "$@"
