FROM debian:bookworm
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
    wget \

# DEV Deps
RUN apt install -y \
    php-sqlite3 \
    php-xdebug


WORKDIR /api
COPY . /api

RUN composer install --no-interaction --no-scripts

ENTRYPOINT ["docker/dev/docker-entrypoint.sh"]
CMD ["test"]