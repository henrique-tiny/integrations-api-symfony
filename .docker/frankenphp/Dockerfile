FROM dunglas/frankenphp:1-php8.3

COPY .docker/frankenphp/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

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

COPY .docker/frankenphp/Caddyfile /etc/caddy/Caddyfile
COPY .docker/frankenphp/worker.Caddyfile /etc/caddy/worker.Caddyfile

ENV FRANKENPHP_CONFIG="import worker.Caddyfile"

WORKDIR /var/www

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]
