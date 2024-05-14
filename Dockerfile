FROM php:8.2-fpm
RUN apt-get update && apt-get install -y \
    git \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mysqli
RUN curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin --filename-composer

WORKDIR /app
COPY . .

RUN composer install --ignore-platform-reqs


CMD php artisan key:generate && php artisan serve --host=0.0.0.0