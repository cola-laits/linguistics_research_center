FROM docker-registry.la.utexas.edu/base_laravel:5.x-php7

RUN docker-php-ext-install intl
ADD server /var/www/html/
