FROM php:8.3-fpm

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update && apt-get install -y \
    procps \
    openssl \
    git \
    zlib1g-dev \
    libzip-dev \
    libicu-dev  \
    zip \
    unzip \
    libonig-dev \
    acl

RUN docker-php-ext-install \
    zip opcache mbstring

WORKDIR /var/www

CMD ["./bin/rr", "serve"]
