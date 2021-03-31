FROM ghcr.io/utaustin-laits/laravel-base:8.x-php8.0 as phpbuild
RUN apt-get update \
    && apt-get install -y \
       libicu-dev \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install intl dom xml mbstring
ADD server /var/www/html
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN composer install


FROM node:13 as npmbuild
COPY --from=phpbuild /var/www/html /var/www/html
WORKDIR /var/www/html
RUN npm install && npm run production


FROM ghcr.io/utaustin-laits/laravel-base:8.x-php8.0
RUN docker-php-ext-install intl dom xml mbstring
COPY --from=npmbuild /var/www/html /var/www/html
RUN chmod 777 -R /var/www/html/bootstrap/cache
RUN chmod 777 -R /var/www/html/storage
