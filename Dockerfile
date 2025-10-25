# syntax=docker/dockerfile:1

FROM composer:lts as deps

WORKDIR /
COPY . .

RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-interaction --ignore-platform-reqs

################################################################################

FROM php:8.2 as final

WORKDIR /
ARG APP_ENV_FILE=.env.prod

RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libwebp-dev

RUN docker-php-source extract \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install gd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-source delete

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . .
COPY --from=deps /vendor/ /vendor
RUN mv /${APP_ENV_FILE} /.env

RUN php artisan storage:link
RUN php artisan cache:clear
RUN php artisan config:cache && php artisan route:cache

RUN apt-get install -y nodejs npm
COPY ./link-preview-server/package*.json ./link-preview-server/
RUN cd ./link-preview-server && npm install --prefer-offline --no-audit
COPY ./link-preview-server ./link-preview-server/

EXPOSE 80
COPY ./dockerEntryPoint.sh /
RUN chmod +x /dockerEntryPoint.sh
CMD ["/dockerEntryPoint.sh"]

