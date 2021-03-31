FROM php:8.0-cli as phpbuild
ADD server /var/www/html
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN composer install


FROM node:13 as npmbuild
COPY --from=phpbuild /var/www/html /var/www/html
WORKDIR /var/www/html
RUN npm install && npm run production


FROM ghcr.io/utaustin-laits/laravel-base:8.x-php8.0
COPY --from=npmbuild /var/www/html /var/www/html
RUN chmod 777 -R /var/www/html/bootstrap/cache
RUN chmod 777 -R /var/www/html/storage
