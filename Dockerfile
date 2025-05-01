FROM php:8.4.6-apache-bullseye

# install Composer
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install dependencies
WORKDIR /var/www/html
#COPY composer.json ./

RUN a2enmod rewrite
RUN a2enmod actions

# install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev
# configure PHP
#COPY php.ini /usr/local/etc/php/php.ini
RUN docker-php-ext-install pdo_mysql mysqli zip

# set open ports
EXPOSE 9000

# set environment variables
#ENV COMPOSER_HOME=/app/vendor/composer
#ENV COMPOSER_CACHE_DIR=/app/vendor/composer/cache

# prod
#CMD ["bash", "-c", "composer install --no-dev --no-scripts && apache2-foreground" ]

# dev
CMD ["bash", "-c", "composer install && apache2-foreground" ] 
