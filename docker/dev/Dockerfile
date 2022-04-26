FROM debian:bookworm
ENV DEBIAN_FRONTEND=noninteractive

# PROD Deps
RUN apt update && apt install -y \
    php-cli \
    php-mysql \
    php-xml \
    php-curl \
    php-bcmath \
    composer \
    mariadb-client \
    curl \
    npm

# DEV Deps
RUN apt install -y \
    php-sqlite3 \
    php-xdebug

# Install standard-version to manage conventional commits
RUN npm i -g standard-version

# To have composer and sf cache
RUN mkdir /.composer /.symfony /.cache && chmod -R 777 /.composer /.symfony /.cache
RUN echo "#!/usr/bin/env bash\n cd /api\n bin/console \"\$@\"\n cd -" > /usr/bin/app && chmod +x /usr/bin/app

RUN curl -sL https://get.symfony.com/cli/installer | bash -
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

WORKDIR /api
ENTRYPOINT ["docker/dev/docker-entrypoint.sh"]
CMD ["dev"]