#!/bin/sh

set -e

# check if PHP_MIGRATION is set/not empty and dont run optimize
if [ -z "$ARTISAN_MIGRATION" ]; then
    echo "Running optimize"
    php artisan optimize
fi

exec "$@"
