FROM php:8.3-fpm

RUN docker-php-ext-install bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash &&\
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN apt update && apt install -y --no-install-recommends \
    libzip-dev \
    zip \
    unzip  \
    git \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

