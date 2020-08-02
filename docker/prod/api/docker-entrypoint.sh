#!/usr/bin/env bash
set -e

env | grep -v "^_" >> /etc/apache2/envvars

composer run-script post-update-cmd
chmod +x src config public
chown -R www-data:www-data var/cache var/log

apache2ctl -DFOREGROUND
