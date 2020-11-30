# Stage 1: Build PHP container
FROM composer as build1

WORKDIR /app

COPY ./ /app

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs


# Stage 2: Build UI
FROM node as build2

WORKDIR /app

COPY --from=build1 /app /app

RUN cd ui && \
    yarn install && \
    yarn build && \
    mv dist/* /app/public && \
    mv /app/public/index.html /app/public/ui-index.html && \
    rm -rf /app/ui


# Stage 3: Build the final container
FROM php:7.4-apache

ENV APACHE_DOCUMENT_ROOT /app/public
WORKDIR /app

COPY --from=build2 --chown=www-data:www-data /app /app

RUN apt-get update -y && \
    a2enmod rewrite && \
    apt-get -y install libpq-dev wait-for-it libsodium-dev libyaml-dev && \
    docker-php-ext-install pdo pgsql pdo_pgsql && \
    pecl install libsodium && \
    docker-php-ext-enable sodium && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    pecl install yaml && \
    docker-php-ext-enable yaml && \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

ENTRYPOINT ["/app/docker/entrypoint.sh"]
