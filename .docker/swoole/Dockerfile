FROM php:8.3-cli

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

RUN pecl install pcntl sockets swoole
RUN docker-php-ext-enable swoole

RUN docker-php-ext-install \
    zip opcache mbstring

ENV APP_RUNTIME="Runtime\\Swoole\\Runtime"

WORKDIR /var/www

CMD ["php", "./public/swoole.php"]
