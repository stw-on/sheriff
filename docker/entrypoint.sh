#!/bin/sh

set -e

run_as_www_data() {
  su -c "$*" -s /bin/sh www-data
}

exec_as_www_data() {
  exec su -c "exec $*" -s /bin/sh www-data
}

if [ "$1" = "worker" ]; then
  wait-for-it app:80 -t 3600
  exec_as_www_data php artisan queue:work --tries=5 --delay=30
elif [[ "$1" == "scheduler" ]]; then
  wait-for-it app:80 -t 3600 || exit 1
  exec_as_www_data php artisan cron -q
else
  chgrp -R www-data /app/storage
  chmod -R g+rw /app/storage

  wait-for-it -t 60 db:5432

  run_as_www_data php artisan migrate --force
  exec apache2-foreground
fi
