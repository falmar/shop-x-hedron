FROM falmar/php:8.2-mysql as prod
WORKDIR /php-app
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/entrypoint.sh /usr/share/nginx/html/docker/php/entrypoint.sh
COPY . /php-app
RUN touch /php-app/database/database.sqlite && cp /php-app/.env.example /php-app/.env
ENTRYPOINT ["sh", "/usr/share/nginx/html/docker/php/entrypoint.sh"]
CMD ["php-fpm"]

FROM falmar/php:8.2-mysql-dev as local
WORKDIR /php-app
COPY docker/php/php-local.ini /usr/local/etc/php/php.ini
