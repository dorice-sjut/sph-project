FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN npm ci && npm run build

RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

EXPOSE 10000

CMD php artisan key:generate && php artisan serve --host=0.0.0.0 --port=10000