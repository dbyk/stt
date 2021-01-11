FROM php:7.4-fpm-alpine

RUN apk add --no-cache --virtual build-deps ${PHPIZE_DEPS} \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo pdo_mysql \
    && cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
    && echo 'xdebug.mode=coverage' >> /usr/local/etc/php/php.ini
