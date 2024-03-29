FROM php:7
LABEL maintainer="Gabriel Faustino <gahfaustino@gmail.com>"
ARG env=.env
RUN apt-get update -y && \
    apt-get install -y openssl zip unzip git libpng-dev \
    libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
	&& docker-php-ext-enable imagick
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql gd
WORKDIR /app
COPY . /app
RUN chmod -Rf 777 /app/storage/
RUN composer require simplesoftwareio/simple-qrcode "~3"
RUN composer install

RUN php artisan key:generate \
    && php artisan jwt:secret \
    && php artisan config:clear \
    && php artisan config:cache


CMD php artisan serve --host=0.0.0.0 --port=8000 --env=$env
EXPOSE 8000