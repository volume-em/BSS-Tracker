FROM php:8.1-apache-buster as production

RUN apt-get -y update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && apt-get install -y gpg

RUN apt-get update \
    && apt-get install -y ca-certificates curl gnupg \
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key --insecure | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && NODE_MAJOR=20 \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list

RUN apt-get update \
    && apt-get install nodejs -y

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=production
ENV APP_DEBUG=false

COPY docker/entry.sh /docker-entry.sh

RUN chmod +x /docker-entry.sh

RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql
COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY .. /var/www/html
RUN composer install --prefer-dist --ignore-platform-reqs --no-dev --optimize-autoloader --no-interaction
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN rm -rf node_modules \
    && rm -rf package-lock.json \
    && npm install \
    && npm run build

RUN php artisan config:cache && \
    php artisan route:cache && \
    ln -s /var/www/html/storage/app/public/ storage && \
    chmod 777 -R /var/www/html/storage/ && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite

ENTRYPOINT /docker-entry.sh
