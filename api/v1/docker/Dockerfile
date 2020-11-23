FROM php:7.2-apache-stretch

RUN apt-get update \
 && apt-get install -y libpq-dev \
 && apt-get install -y pdftk \
 && apt-get install -y graphviz \
 && apt-get install -y unzip \
 && apt-get install -y libxslt-dev \
 && apt-get install -y git zlib1g-dev \
 && apt-get install -y libfreetype6-dev \
                       libjpeg62-turbo-dev \
                       libpng-dev \
 && docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install -j$(nproc) gd \
 && docker-php-ext-install zip \
 && docker-php-ext-install pdo_mysql \
 && docker-php-ext-install pdo pdo_pgsql pgsql \
 && docker-php-ext-install xsl \
 && a2enmod rewrite \
 && apt-get install -y mysql-client \
 && cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
 && sed -i 's!upload_max_filesize = 2M!upload_max_filesize = 200M!g' /usr/local/etc/php/php.ini \
 && sed -i 's!post_max_size = 8M!post_max_size = 200M!g' /usr/local/etc/php/php.ini \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.10
#debug php
RUN pecl install -f xdebug \
&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini;
#Installing nodejs
RUN rm -rf /var/lib/apt/lists/* && curl -sL https://deb.nodesource.com/setup_8.x | bash - \
 && apt-get update \
 && apt-get install -y nodejs
RUN apt-get clean
ADD http://www.phpdoc.org/phpDocumentor.phar /usr/local/bin/phpdoc
RUN chmod 755 /usr/local/bin/phpdoc
#

ARG DB_HOST
ARG DB_USERNAME
ARG DB_PASSWORD
ARG API_DB
ARG TEST_API_DB
ARG HOST
ENV HOST=${HOST}
ENV DB_USERNAME=${DB_USERNAME}
ENV DB_PASSWORD=${DB_PASSWORD}
ENV API_DB=${API_DB}
ENV TEST_API_DB=${TEST_API_DB}
ENV DB_HOST=${DB_HOST}
#
RUN mkdir -p /app/api/v1 && \
    mv /var/www /var/www2 && \
    ln -s /app/api/v1 /var/www
WORKDIR /var/www
