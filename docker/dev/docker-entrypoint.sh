#!/usr/bin/env bash
set -e

APP_VERSION=$(git describe --tags)
export APP_VERSION

case $1 in
  dev)
    composer install --no-interaction
    symfony server:start --no-tls --allow-http --port=8000
    ;;
  *)
    exec "$@"
    ;;
esac

