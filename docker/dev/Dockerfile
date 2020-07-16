FROM debian:buster
ENV DEBIAN_FRONTEND=noninteractive

# PROD Deps
RUN apt update && apt install -y \
    php-cli \
    php-mysql \
    php-xml \
    php-curl \
    composer \
    wget

# DEV Deps
RUN apt install -y \
    php-sqlite3 \
    php-xdebug



# To have composer and sf cache
RUN mkdir /.composer /.symfony && chmod -R 777 /.composer /.symfony
RUN echo "#!/usr/bin/env bash\n cd /api\n bin/console \"\$@\"\n cd -" > /usr/bin/app && chmod +x /usr/bin/app

RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

WORKDIR /api
ENTRYPOINT ["docker/dev/docker-entrypoint.sh"]
CMD ["dev"]