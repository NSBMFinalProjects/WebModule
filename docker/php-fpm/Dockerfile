FROM php:8.3-rc-fpm

RUN apt-get update

RUN apt-get install -y libpq-dev  zlib1g-dev libzip-dev unzip \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql \
  && docker-php-ext-install zip

RUN pecl install mongodb \
  &&  echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongo.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer self-update

WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www
