FROM php:7.2-fpm-stretch

COPY ./symfony.ini /usr/local/etc/php/conf.d
COPY ./symfony.pool.conf /usr/local/etc/php-fpm.d/

RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    libpq-dev \
    libmcrypt-dev \
    libxml2-dev

RUN docker-php-ext-install \
    bcmath \
    gettext \
    intl \
    opcache \
    pgsql \
    pdo_pgsql

ARG COMPOSER_VERSION
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=${COMPOSER_VERSION} \
    && chmod +x /usr/local/bin/composer && composer clear-cache

ARG XDEBUG_ENABLED=false
RUN if $XDEBUG_ENABLED; then pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.max_nesting_level=1000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    ; fi

RUN mkdir /var/log/todo-list && chown -R www-data:www-data /var/log/todo-list

RUN usermod -u 1000 www-data

WORKDIR /var/www/todo-list/symfony

CMD mkdir -p var/cache && mkdir -p var/logs && chmod 777 -R var/cache var/logs && php-fpm

EXPOSE 9000
