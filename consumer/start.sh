#!/bin/bash

./mysql.sh

rabbitmq-server -detached

php /var/www/html/index.php

exec "$@"