#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
  set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
  if [ "$APP_ENV" != 'prod' ]; then
    # On dev, remove environment variables to use .env and .env.local
    unset APP_ENV
    unset APP_DEBUG
  fi
fi

php bin/console messenger:consume async -vv

exec docker-php-entrypoint "$@"
