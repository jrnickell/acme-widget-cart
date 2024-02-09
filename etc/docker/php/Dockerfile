FROM php:8.3-fpm-bookworm

WORKDIR /var/www/app

RUN apt-get update \
    && apt-get install -y \
      locales \
      # requirements for intl extension
      libicu-dev \
    && echo "en_US.UTF-8 UTF-8" >> /etc/locale.gen \
    && locale-gen en_US.UTF-8 \
    && dpkg-reconfigure --frontend=noninteractive locales \
    && docker-php-ext-install intl \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Workaround https://bugs.php.net/bug.php?id=71880
RUN mkfifo /tmp/stdout && chmod 777 /tmp/stdout

COPY ./conf/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT /entrypoint.sh
