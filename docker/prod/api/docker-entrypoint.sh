#!/usr/bin/env bash
set -e

envsubst < docker/prod/api/vhost.conf > /etc/apache2/sites-available/000-default.conf

chmod +x public src config
chown -R www-data:www-data var/cache

apache2ctl -DFOREGROUND
