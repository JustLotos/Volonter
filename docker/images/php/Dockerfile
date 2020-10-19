# Исходный образ
FROM php:7.2-fpm

# RUN выполняет идущую за ней команду в контексте нашего образа.
# В данном случае мы установим некоторые зависимости и модули PHP.
# Для установки модулей используем команду docker-php-ext-install.
# На каждый RUN создается новый слой в образе, поэтому рекомендуется объединять команды.
RUN apt-get update && apt-get install -y \
        sudo \
        curl \
        wget \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        libxml2-dev \
        libpq-dev \
    && pecl install mcrypt-1.0.1 \
    && pecl install xdebug-2.7.1 \
    && docker-php-ext-install -j$(nproc) intl bcmath soap pgsql pdo_pgsql zip mbstring \
    && docker-php-ext-configure mbstring --enable-mbstring \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-enable xdebug \
    && adduser user \
    && echo "user ALL=(root) NOPASSWD:ALL" > /etc/sudoers.d/user \
    && chmod 0440 /etc/sudoers.d/user

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --quiet
# Добавим свой php.ini, можем в нем определять свои значения конфига
ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

# Указываем рабочую директорию для PHP
WORKDIR /app

CMD ["php-fpm"]
