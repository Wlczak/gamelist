FROM php:8.4-fpm-alpine AS composer

WORKDIR /build

# install Composer binary
COPY --from=composer:2.9 /usr/bin/composer /usr/bin/composer

# install any extensions needed for composer install (e.g. zip)
RUN apk add --no-cache libzip-dev && docker-php-ext-install zip

COPY ./ /build/

RUN composer install --no-dev --no-scripts --optimize-autoloader

FROM nginx:1.29-alpine-slim AS lylink-nginx

COPY --from=composer /build/ /var/www/html/

COPY ./phpdocker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

FROM php:8.4-fpm-alpine AS gamelist

WORKDIR /var/www/html

# install native deps for extensions
RUN apk add --no-cache libzip-dev

# install PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli zip

COPY --from=composer /build /var/www/html

CMD ["php-fpm", "-R"]