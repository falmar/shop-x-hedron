FROM falmar/php:8.2-mysql as prod
WORKDIR /php-app
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY . /php-app
RUN cp /php-app/.env.example /php-app/.env

FROM falmar/php:8.2-mysql-dev as local
WORKDIR /php-app
COPY docker/php/php-local.ini /usr/local/etc/php/php.ini
