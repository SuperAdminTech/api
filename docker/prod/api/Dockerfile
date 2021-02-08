FROM debian:bullseye
ENV DEBIAN_FRONTEND noninteractive
RUN apt update && apt install -y \
    php \
    php-mysql \
    php-xml \
    php-mongodb \
    php-curl \
    php-bcmath \
    php-cli \
    php-mbstring \
    php-zip \
    php-gd \
    composer \
    gettext-base \
    apache2 \
    wget

# apache2 config
RUN a2enmod rewrite php7.4
RUN sed 's/^ServerTokens OS/ServerTokens Prod/' -i /etc/apache2/conf-available/security.conf
RUN sed 's/^ServerSignature On/ServerSignature Off/' -i /etc/apache2/conf-available/security.conf

WORKDIR /api
COPY . /api

RUN composer install --no-ansi --no-interaction --no-scripts --no-dev

CMD docker/prod/api/docker-entrypoint.sh
