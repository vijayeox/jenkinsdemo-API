#rainloop/orocrm/calendar

FROM php:7.3.13-apache

RUN apt-get update \
  && apt-get install -y --no-install-recommends apt-utils \
  && apt-get install -y graphviz \
  && apt-get install -y libzip-dev \
  && apt-get install -y libxslt-dev \
  && apt-get install -y git zlib1g-dev \
  && apt-get install -y supervisor \
  && apt-get install -y unzip \
  && apt-get install -y libtidy-dev \
  && docker-php-ext-install zip \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install xsl \
  && apt-get install -y openssl libssl-dev libcurl4-openssl-dev pkg-config
RUN apt-get install -y pkg-config
RUN docker-php-ext-install xml
RUN docker-php-ext-install json
RUN docker-php-ext-install iconv
RUN docker-php-ext-install curl
RUN docker-php-ext-install dom
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install tidy
RUN docker-php-ext-install opcache
RUN apt-get update -y && apt-get install -y libpng-dev
RUN docker-php-ext-install gd
RUN docker-php-ext-install intl
RUN apt-get update && apt-get install -y zlib1g-dev \
  && docker-php-ext-install zip
RUN apt-get update && \
  apt-get install -y libxml2-dev
RUN docker-php-ext-install soap
RUN a2enmod rewrite \
  && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
  && sed -i 's!Listen\ 80!Listen\ 8075!g' /etc/apache2/ports.conf \
  && sed -i 's!VirtualHost\ \*:80!VirtualHost\ \*:8075!g' /etc/apache2/sites-enabled/000-default.conf \
  && mv /var/www/html /var/www/public \
  && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && \
  apt-get clean
RUN apt-get install -y redis-server
RUN apt-get install -y gnupg && \
  curl -sL https://deb.nodesource.com/setup_8.x | bash - && \
  apt-get update \
  && apt-get install -y nodejs npm\
  && npm install gulp -g

RUN mkdir /integrations \
  && mkdir -p /var/www/public/rainloop/data \
  && mkdir -p /var/www/public/crm \
  && mkdir -p /var/www/public/calendar \
  && chown -R www-data:www-data /var/www/ 

WORKDIR /integrations

COPY "custom_php.ini" "/usr/local/etc/php/conf.d/custom_php.ini"

EXPOSE 80 8075

ENTRYPOINT  ["./docker-entrypoint.sh"]
