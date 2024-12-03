# syntax=docker/dockerfile:1

# Create a stage for installing app dependencies defined in Composer.
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
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev
#&& rm -rf /var/lib/apt/lists/* \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg \
##    && docker-php-ext-install -j$(nproc) gd \
RUN apt-get update \
    && docker-php-source extract \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install gd \
    && docker-php-source delete

# Use the default production configuration for PHP runtime arguments, see
# https://github.com/docker-library/docs/tree/master/php#configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . .
# Copy the app dependencies from the previous install stage.
COPY --from=deps /vendor/ /vendor
RUN mv /${APP_ENV_FILE} /.env

EXPOSE 80
CMD [ "php", "./artisan", "serve", "--no-interaction", "-vvv", "--port=80", "--host=0.0.0.0" ]

