#!/usr/bin/env bash
set -e

APP_VERSION=$(git describe --tags)
export APP_VERSION

envsubst < docker/prod/api/vhost.conf > /etc/apache2/sites-available/000-default.conf

composer run-script post-update-cmd
chmod +x src config public
chown -R www-data:www-data var/cache var/log

apache2ctl -DFOREGROUND
