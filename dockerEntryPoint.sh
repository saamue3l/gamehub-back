#!/bin/sh

php ./artisan migrate --force

php ./artisan serve:startLinkPreview &
php ./artisan serve --no-interaction -vvv --port=80 --host=0.0.0.0
