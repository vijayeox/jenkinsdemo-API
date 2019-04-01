FROM php:7.2-apache

RUN apt-get update \
 && apt-get install -y graphviz \
 && apt-get install -y unzip \
 && apt-get install -y libxslt-dev \
 && apt-get install -y git zlib1g-dev \
 && docker-php-ext-install zip \
 && docker-php-ext-install pdo_mysql \
 && docker-php-ext-install xsl \
 && a2enmod rewrite \
 && apt-get install -y mysql-client \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update \
 && apt-get clean
ADD http://www.phpdoc.org/phpDocumentor.phar /usr/local/bin/phpdoc
RUN chmod 755 /usr/local/bin/phpdoc
WORKDIR /var/www
