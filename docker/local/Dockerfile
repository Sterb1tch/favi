FROM php:8.4.1-fpm-alpine

COPY php.ini.dist /usr/local/etc/php/conf.d/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk update && apk add --no-cache git openssh zip icu-dev

RUN docker-php-ext-install pdo pdo_mysql

RUN docker-php-ext-configure intl && docker-php-ext-install intl

RUN apk add --update linux-headers

RUN apk add --no-cache tzdata ghostscript libjpeg-turbo-dev libpng-dev && \
	docker-php-ext-configure gd --with-jpeg && \
	docker-php-ext-install gd && \
    ln -sf /usr/share/zoneinfo/Europe/Prague /etc/localtime && \
    echo "Europe/Prague" > /etc/timezone

WORKDIR /var/www/html

RUN rm -rf var/* && \
  mkdir ./var/cache -p && \
  mkdir ./var/log -p && \
  touch ./var/log/xdebug.log && \
  chown -R 1000:1000 var/ && \
  chmod -R 777 var/
