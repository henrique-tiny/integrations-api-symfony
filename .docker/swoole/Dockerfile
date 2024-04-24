FROM php:8.3-cli

COPY .docker/swoole/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update && apt-get install -y \
    procps \
    openssl \
    libssl-dev \
    git \
    zlib1g-dev \
    libzip-dev \
    libicu-dev  \
    zip \
    unzip \
    libonig-dev \
    libcurl4-openssl-dev \
    acl

RUN docker-php-ext-install \
    zip opcache mbstring sockets

# enable sockets supports? [no]

# openssl precisa do libssl-dev
# enable openssl support? [no]

# enable mysqlnd support? [no]

# curl precisa do curl-dev
# enable curl support? [no]
RUN echo "yes\nyes\nno\nyes\n" | pecl install swoole

RUN docker-php-ext-enable swoole

ENV APP_RUNTIME="Runtime\\Swoole\\Runtime"

WORKDIR /var/www

CMD ["php", "./public/swoole.php"]
