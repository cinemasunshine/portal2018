# https://hub.docker.com/_/php/
FROM php:7.3-fpm-alpine

# apkをupdate
# install
#    - $PHPIZE_DEPS peclのため
#    - git        composerのため
#    - zip、unzip composerのエラー解決
RUN apk update \
    && apk add -U --no-cache \
        $PHPIZE_DEPS \
        git \
        zip \
        unzip

RUN docker-php-ext-install \
        pdo_mysql

# redisはdocker-php-ext-installで対応出来ないので別途インストール
# https://github.com/phpredis/phpredis
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# https://hub.docker.com/_/composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY php.ini /usr/local/etc/php
