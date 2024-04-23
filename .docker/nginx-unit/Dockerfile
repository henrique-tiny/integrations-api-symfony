FROM unit:1.32.1-php8.2

COPY .docker/nginx-unit/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

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

COPY .docker/nginx-unit/entrypoint.sh /entrypoint.sh
COPY .docker/nginx-unit/config.json /config.json

RUN chmod 544 /entrypoint.sh

ENTRYPOINT [ "sh", "-c" ]
CMD [ "/entrypoint.sh" ]
