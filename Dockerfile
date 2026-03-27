FROM composer:2 AS phpbuild
ADD server /var/www/html
WORKDIR /var/www/html
RUN composer install --ignore-platform-reqs --no-dev


FROM node:24 AS npmbuild
COPY --from=phpbuild /var/www/html /var/www/html
WORKDIR /var/www/html
RUN npm ci && npm run production && rm -rf node_modules


FROM ghcr.io/utaustin-laits/laravel-base:13.x-php8.5
COPY --from=npmbuild /var/www/html /var/www/html
RUN chmod 777 -R /var/www/html/bootstrap/cache
RUN chmod 777 -R /var/www/html/storage
