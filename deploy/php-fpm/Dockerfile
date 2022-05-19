ARG PHP_VERSION=8.1
FROM m.docker-registry.ir/php:${PHP_VERSION}-fpm

MAINTAINER Behzad Kahvand <behzad.kahvand@gmail.com>

#
#--------------------------------------------------------------------------
# Software's Installation
#--------------------------------------------------------------------------
#
# Installing tools and PHP extentions using "apt", "docker-php", "pecl",
#

# Install necessary packages
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
   iputils-ping \
   telnet \
   curl \
   libz-dev \
   libzip-dev \
   libssl-dev \
   libjpeg-dev \
   libpng-dev \
   libmcrypt-dev \
   libicu-dev \
   zip unzip \
   git \
   build-essential \
   autoconf \
   g++ gcc libxslt-dev \
   libsodium-dev \
   libgmp-dev \
&& ( \
       cd /tmp \
       && mkdir librdkafka \
       && cd librdkafka \
       && git clone https://github.com/edenhill/librdkafka.git . \
       && ./configure \
       && make \
       && make install \
  )

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql zip gd intl xsl sodium gmp

# Set timezone
ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ARG INSTALL_SOAP=false
RUN if [ ${INSTALL_SOAP} = true ]; then \
    # Install the soap extension
    rm /etc/apt/preferences.d/no-debian-php && \
    apt-get -y install libxml2-dev php-soap && \
    docker-php-ext-install soap \
;fi


#####################################
# xDebug:
#####################################
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    # Install the xdebug extension
    pecl install xdebug && \
    docker-php-ext-enable xdebug \
;fi

# Copy xdebug configuration for remote debugging
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


ARG INSTALL_AMQP=false

RUN set -eux; \
    if [ ${INSTALL_AMQP} = true ]; then \
      # # Install the amqp extension
      apt-get -yqq install librabbitmq-dev; \
      if [ $(php -r "echo PHP_MAJOR_VERSION;") = "8" ]; then \
        pecl install amqp-1.11.0beta; \
      else \
        pecl install amqp; \
      fi; \
      docker-php-ext-enable amqp; \
      php -m | grep -oiE '^amqp$'; \
    fi


ARG INSTALL_OPCACHE=false
RUN if [ ${INSTALL_OPCACHE} = true ]; then \
    docker-php-ext-configure opcache && \
    docker-php-ext-install opcache &&\
    docker-php-ext-enable opcache \
;fi

COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

#
#--------------------------------------------------------------------------
# Application's Installation
#--------------------------------------------------------------------------
#
# Installing the application and performs the commands we need to build it
#

#####################################
# Composer:
#####################################

# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer

# Create the www-data user
RUN usermod -u 1000 www-data

# Create the web root directory and change the working directory
RUN mkdir -p /var/www && chown www-data:www-data -R /var/www

USER www-data


ARG GIT_USER
ARG GIT_TOKEN
ARG GIT_PASSWORD
ARG RELEASE_BRANCH=master


#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

USER root
COPY ./pool.conf /usr/local/etc/php-fpm.d/pool.conf
COPY ./www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./php.ini /usr/local/etc/php-fpm.d/php.ini

RUN rm -r /var/lib/apt/lists/*

COPY ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www


CMD ["php-fpm"]

EXPOSE 9000
