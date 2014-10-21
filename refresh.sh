#!/bin/sh

# Pull new from git
git pull

# Update schema
php app/console doctrine:schema:update --force

# Clear and warmup production cache
php app/console cache:clear --env=prod --no-debug
php app/console cache:warmup --env=prod --no-debug
