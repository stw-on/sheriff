FROM php:8.0-apache

ENV APACHE_DOCUMENT_ROOT /app/public
WORKDIR /app
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update -y && \
    a2enmod rewrite && \
    apt-get -y install libpq-dev wait-for-it unzip libyaml-dev libgmp-dev && \
    docker-php-ext-install pdo pgsql pdo_pgsql gmp && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    pecl install yaml && \
    docker-php-ext-enable yaml && \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

ENTRYPOINT ["/app/docker/entrypoint.dev.sh"]
