FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    zip \
    curl \
    postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --prefer-dist

COPY . .

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x docker/entrypoint.sh
RUN chmod +x /entrypoint.sh


ENTRYPOINT ["/entrypoint.sh"]
