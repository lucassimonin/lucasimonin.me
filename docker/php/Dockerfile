FROM composer

FROM php:7.2.8-fpm-alpine

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

ENV APCU_VERSION 5.1.8
ENV XDEBUG_VERSION 2.6.0

RUN apk add --no-cache \
        ca-certificates \
        icu-libs \
        git \
        unzip \
        zlib-dev && \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        autoconf \
        icu-dev && \
    docker-php-ext-install \
        mbstring \
        iconv \
        intl \
        zip && \
    pecl install apcu-${APCU_VERSION} && \
    docker-php-ext-enable apcu && \
    docker-php-ext-enable opcache && \
    docker-php-ext-install pdo_mysql && \
    echo "short_open_tag = off" >> /usr/local/etc/php/php.ini && \
    echo "date.timezone = Europe/Paris" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "opcache.max_accelerated_files = 20000" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "realpath_cache_size=4096K" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "realpath_cache_ttl=600" >> /usr/local/etc/php/conf.d/symfony.ini && \
    pecl install xdebug-${XDEBUG_VERSION} && docker-php-ext-enable xdebug && \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "xdebug.remote_port=9009" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "xdebug.remote_host=docker.for.mac.host.internal" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/symfony.ini && \
    echo "xdebug.max_nesting_level=1000" >> /usr/local/etc/php/conf.d/symfony.ini && \
    apk del .build-deps && \
    apk add gosu --update --no-cache --repository http://dl-3.alpinelinux.org/alpine/edge/testing/ --allow-untrusted && \
    addgroup bar && \
    adduser -D -h /home -s /bin/sh -G bar foo

# install mhsendmail
RUN wget -O /usr/sbin/mhsendmail  https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64
RUN chmod +x /usr/sbin/mhsendmail

RUN apk add gnu-libiconv --update-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/testing/ --allow-untrusted
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php
#GD
RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev libxml2-dev && \
  docker-php-ext-configure gd \
    --with-gd \
    --with-freetype-dir=/usr/include/ \
    --with-png-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ && \
  NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
  docker-php-ext-install -j${NPROC} gd && \
  apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

# Wkhtmltopdf
RUN apk add --no-cache xvfb ttf-freefont fontconfig dbus && \
    apk add qt5-qtbase-dev wkhtmltopdf --no-cache \
    --repository http://dl-3.alpinelinux.org/alpine/edge/testing/ \
    --allow-untrusted && \
    # Wrapper for xvfb
    mv /usr/bin/wkhtmltopdf /usr/bin/wkhtmltopdf-origin && \
    echo $'#!/usr/bin/env sh\n\
Xvfb :0 -screen 0 1024x768x24 -ac +extension GLX +render -noreset & \n\
DISPLAY=:0.0 wkhtmltopdf-origin $@ \n\
killall Xvfb\
' > /usr/bin/wkhtmltopdf && \
    chmod +x /usr/bin/wkhtmltopdf

ADD entrypoint.sh /entrypoint

ENTRYPOINT ["/entrypoint"]
