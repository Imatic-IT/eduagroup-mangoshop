FROM php:7.1

RUN apt-get update -y && \
    apt-get install -y libicu-dev &&  \
      rm -rf /var/cache/apt

RUN docker-php-source extract && \
    docker-php-ext-install intl && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install mysqli && \
    pecl install xdebug && \
    docker-php-source delete && \
    rm -rf /usr/src/php/ext

ADD *.ini /usr/local/etc/php/conf.d/
