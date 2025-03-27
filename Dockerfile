FROM php:8.3-fpm

# Dependencias
RUN apt-get update && apt-get install -y \
    nano bash git zip unzip curl libicu-dev libonig-dev libzip-dev libpq-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libsqlite3-dev \
    && docker-php-ext-install intl pdo pdo_mysql pdo_sqlite zip gd opcache

# Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

#Redis install
RUN pecl install redis && docker-php-ext-enable redis

# COnfig Xdebug
RUN echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


# INst Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crear WORKDIR
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias Symfony
RUN composer install --no-interaction

# Permisos
RUN chown -R www-data:www-data /var/www/html/var

EXPOSE 8000

#CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
CMD ["php-fpm"]