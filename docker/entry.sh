#!/bin/bash

php /var/www/html/artisan migrate --force
php /var/www/html/artisan db:seed --force

exec "/usr/local/bin/apache2-foreground"
