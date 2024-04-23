FROM php:8.3-cli as builder

COPY .docker/roadrunner/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

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

COPY ./vendor ./vendor
RUN mkdir /var/www/bin-roadrunner && ./vendor/bin/rr get --location /var/www/bin-roadrunner

FROM php:8.3-cli

COPY --from=builder /var/www/bin-roadrunner/rr /var/www/bin-roadrunner/rr

WORKDIR /var/www

CMD ["/var/www/bin-roadrunner/rr", "serve"]
