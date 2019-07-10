# PHP用Dockerfile

# https://hub.docker.com/_/php/
FROM php:7.2-fpm

# apt-getをupdate（installできなかったので）
# install
#    - git        composerのため
#    - zip、unzip composerのエラー解決
RUN apt-get update \
    && apt-get install -y \
        git \
        zip \
        unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# install PHP extensions
RUN docker-php-ext-install \
        pdo_mysql

# composerインストール
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# サーバ証明書に関する一時的な対応
RUN git config --global http.sslverify false

COPY php.ini /usr/local/etc/php
