FROM falmar/php:8.2-mysql as prod
WORKDIR /php-app
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/entrypoint.sh /php-app/docker/php/entrypoint.sh
COPY . /php-app
RUN chown -R www-data:www-data /php-app
RUN chmod -R 777 /php-app/storage
USER www-data
RUN touch /php-app/database/database.sqlite && cp /php-app/.env.example /php-app/.env
ENTRYPOINT ["sh", "/php-app/docker/php/entrypoint.sh"]
CMD ["php-fpm"]

FROM falmar/php:8.2-mysql-dev as local
WORKDIR /php-app
COPY docker/php/php-local.ini /usr/local/etc/php/php.ini
