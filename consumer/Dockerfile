FROM rabbitmq:3.7-management-alpine

ENV RABBITMQ_DEFAULT_PASS pass 
ENV RABBITMQ_DEFAULT_USER user
ENV MYSQL_ROOT_PWD "root"
ENV MYSQL_USER="user"
ENV MYSQL_USER_PWD="pass"
ENV MYSQL_USER_DB="database"

COPY . /var/www/html
WORKDIR /var/www/html

RUN apk --update add \
    wget \
    curl \
    nginx \
    php7 \
    php7-fpm \
    php7-pdo \
    php7-pdo_mysql \
    php7-mbstring \
    php7-bcmath \
    php7-json \
    php7-phar \
    php7-openssl \
    mysql \
    mysql-client \
    && addgroup mysql mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && chmod +x /usr/bin/composer start.sh mysql.sh \
    && composer install --prefer-dist

CMD [ "/var/www/html/start.sh" ]
