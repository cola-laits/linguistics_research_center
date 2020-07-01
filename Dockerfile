FROM docker-registry.la.utexas.edu/base_laravel:7.x-php7.4

RUN docker-php-ext-install intl
ADD server /var/www/html/
RUN chmod 777 -R /var/www/html/bootstrap/cache
RUN chmod 777 -R /var/www/html/storage
