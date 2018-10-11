FROM php:7.2-fpm-alpine

RUN apk update \
    && apk add postgresql-dev jpeg-dev libpng-dev \
    && docker-php-ext-install gd pgsql pdo_pgsql
