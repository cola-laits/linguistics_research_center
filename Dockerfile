FROM composer:2 as phpbuild
ARG BACKPACK_COMPOSER_USER
ARG BACKPACK_COMPOSER_PASS
ADD server /var/www/html
WORKDIR /var/www/html
RUN test -f auth.json || composer config http-basic.backpackforlaravel.com $BACKPACK_COMPOSER_USER $BACKPACK_COMPOSER_PASS
RUN composer install --ignore-platform-reqs --no-dev


FROM node:18 as npmbuild
COPY --from=phpbuild /var/www/html /var/www/html
WORKDIR /var/www/html
RUN npm ci && npm run production && rm -rf node_modules


FROM ghcr.io/utaustin-laits/laravel-base:11.x-php8.3
COPY --from=npmbuild /var/www/html /var/www/html
RUN chmod 777 -R /var/www/html/bootstrap/cache
RUN chmod 777 -R /var/www/html/storage
RUN php artisan storage:link
RUN php artisan basset:cache && chmod 777 -R /var/www/html/storage/app/public/basset