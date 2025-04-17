FROM php:8.1-apache

RUN mkdir /app
RUN mkdir /app/server
WORKDIR /app

COPY entrypoint.sh /app/entrypoint.sh
RUN chmod +x /app/entrypoint.sh

COPY server/ ./server/
COPY composer.json .
COPY composer.lock .

COPY --from=composer:2.8.6 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    libicu-dev \
    zip unzip git

RUN composer install --no-dev --optimize-autoloader

WORKDIR /
RUN a2enmod rewrite
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
WORKDIR /var/www/html
COPY view/ .

RUN chown -R www-data:www-data /var/www/html

WORKDIR /
ENTRYPOINT ["sh", "/app/entrypoint.sh"]