FROM php:8.3-rc-fpm

RUN apt-get update

RUN apt-get install -y libpq-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql

RUN pecl install mongodb \
  &&  echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongo.ini
