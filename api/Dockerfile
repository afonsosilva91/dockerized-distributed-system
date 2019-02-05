FROM php:7.3-apache

COPY apache2.conf /etc/apache2/apache2.conf
COPY . /var/www/html
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y build-essential ca-certificates \
    curl \
    git \
    && docker-php-ext-install bcmath sockets \
    && a2enmod rewrite \
    && a2enmod headers \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && composer install --prefer-dist \
    # Cleanup
    && apt-get autoremove -y \
    && apt-get clean \
    && apt-get autoclean
