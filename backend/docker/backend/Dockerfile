FROM php:8.2-fpm-alpine

# install pdo_mysql on Alpine
RUN set -eux; \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS mariadb-dev; \
    docker-php-ext-install pdo pdo_mysql; \
    apk del .build-deps

WORKDIR /srv

# keep default entrypoint (docker-php-entrypoint) and default cmd (php-fpm)
EXPOSE 9000
#CMD ["php-fpm"]
