ARG DEBIAN_VERSION=bullseye

FROM php:7.4-fpm-${DEBIAN_VERSION}

ARG DEBIAN_VERSION

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        apt-utils \
        autoconf \
        bash-completion \
        ca-certificates \
        g++ \
        git \
        gnupg \
        htop \
        libpq-dev \
        libzip-dev \
        mc \
        nano \
        libpng-dev \
        libicu-dev \
        unzip \
        vim \
        wget && \
    apt-get clean

#USER root
## install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install necessary php extensions for running application
RUN docker-php-ext-install \
        bcmath \
        fileinfo \
        gd \
        intl \
        opcache \
        zip

USER root
# set www-data user his home directory
# the user "www-data" is used when running the image, and therefore should own the workdir
RUN usermod -m -d /home/www-data www-data && \
    mkdir -p /var/www/html && \
    chown -R www-data:www-data /home/www-data /var/www/html

#RUN chown www-data:www-data /composer.json

# Switch to user
USER www-data

# enable bash completion
RUN echo "source /etc/bash_completion" >> ~/.bashrc