FROM composer:2 as phpbuild
ADD server /var/www/html
WORKDIR /var/www/html
RUN composer install --ignore-platform-reqs


FROM node:13 as npmbuild
COPY --from=phpbuild /var/www/html /var/www/html
WORKDIR /var/www/html
RUN npm install && npm run production


FROM ghcr.io/utaustin-laits/laravel-base:9.x-php8.1
RUN docker-php-ext-install intl
COPY --from=npmbuild /var/www/html /var/www/html
RUN chmod 777 -R /var/www/html/bootstrap/cache
RUN chmod 777 -R /var/www/html/storage
