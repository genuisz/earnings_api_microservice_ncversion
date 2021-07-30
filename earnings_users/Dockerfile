FROM php:7.3-fpm
MAINTAINER keith
WORKDIR /var/www

ENV PHPREDIS_VERSION 4.0.0
ENV HIREDIS_VERSION 0.13.3
ENV MSGPACK_VERSION 2.0.3
RUN sed -i 's/9000/9001/' /usr/local/etc/php-fpm.d/zz-docker.conf
RUN apt-get update \
    && apt-get install -y \
        curl \
        wget \
        git \
        zip \
        cron \
        supervisor\
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libjpeg-dev \
        libssl-dev \
        libmcrypt-dev\
        libz-dev \
        libssl-dev \
        libnghttp2-dev \
        libpcre3-dev \
        libmemcached-dev \
        zlib1g-dev \
        libyaml-dev \
        
    && apt-get clean \
    && apt-get autoremove
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer self-update --clean-backups


RUN docker-php-ext-install mysqli

RUN docker-php-ext-install pdo_mysql

RUN docker-php-ext-install bcmath

#RUN docker-php-ext-install -j$(nproc) iconv mcrypt
RUN docker-php-ext-install iconv
RUN pecl install mcrypt-1.0.2 && docker-php-ext-enable mcrypt
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd



RUN wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tgz \
    && pecl install /tmp/redis.tgz \
    && rm -rf /tmp/redis.tgz \
    && docker-php-ext-enable redis


RUN wget http://pecl.php.net/get/msgpack-${MSGPACK_VERSION}.tgz -O /tmp/msgpack.tgz \
    && pecl install /tmp/msgpack.tgz \
    && rm -rf /tmp/msgpack.tgz \
    && docker-php-ext-enable msgpack


RUN wget https://github.com/redis/hiredis/archive/v${HIREDIS_VERSION}.tar.gz -O /tmp/hiredis.tar.gz \
  && mkdir -p /tmp/hiredis \
    && tar -xf /tmp/hiredis.tar.gz -C /tmp/hiredis --strip-components=1 \
    && rm /tmp/hiredis.tar.gz \
    && ( \
        cd /tmp/hiredis \
        && make -j$(nproc) \
        && make install \
        && ldconfig \
    ) \
    && rm -r /tmp/hiredis



COPY . /var/www

EXPOSE 9001

CMD ["php-fpm"]

