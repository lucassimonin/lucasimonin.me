FROM php:7.3-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG UID=${UID}
ARG GUID=${GUID}
ARG XDEBUG=${XDEBUG}

WORKDIR /srv/

RUN apk update
RUN apk add autoconf freetype-dev g++ icu-dev libjpeg-turbo-dev libpng-dev libxml2-dev libxslt-dev libzip-dev make shadow tzdata
# Xdebug
RUN docker-php-ext-configure hash --with-mhash
RUN docker-php-ext-install -j$(nproc) bcmath gd iconv intl json opcache pdo_mysql soap xsl zip exif

RUN pecl install xdebug-2.7.1
COPY conf/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN if [ $XDEBUG = "true" ] ; then \
docker-php-ext-enable xdebug; \
fi ;

# PHP config
COPY conf/php.ini /usr/local/etc/php/conf.d
COPY conf/project.pool.conf /usr/local/etc/php/pool.d/

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
RUN echo 'http://dl-cdn.alpinelinux.org/alpine/v3.8/main' >> /etc/apk/repositories
RUN apk add --update --no-cache \
    libgcc libstdc++ libx11 glib libxrender libxext libintl \
    libcrypto1.0 libssl1.0 \
    ttf-dejavu ttf-droid ttf-freefont ttf-liberation ttf-ubuntu-font-family

COPY wkhtmltopdf /usr/bin
# Initial scripts


RUN if [ -z `getent group www-data` ]; then addgroup -g ${GUID} www-data; fi
RUN if [ -z `getent passwd www-data` ]; then useradd -m -u ${UID} -g ${GUID} www-data; fi

RUN cp /usr/share/zoneinfo/Europe/Paris /etc/localtime

RUN chown -R www-data:www-data /srv/



USER www-data

RUN echo "alias ll='ls -l'" >> ~/.bashrc

CMD ["php-fpm"]

EXPOSE 9000
