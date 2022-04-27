#!/usr/bin/env bash
set -e

APP_VERSION=$(git describe --tags)
export APP_VERSION

case $1 in
  dev)
    symfony server:start --no-tls --allow-http --port=8000
    ;;
  test)
    vendor/bin/phpunit
    ;;
  coverage)
    XDEBUG_MODE=coverage vendor/bin/phpunit -d memory_limit=1G --coverage-clover coverage.xml --do-not-cache-result --process-isolation
    ;;
  *)
    exec "$@"
    ;;
esac

