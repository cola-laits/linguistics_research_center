#!/bin/sh -e
 
# Do the migration.  Migration environment is APP_ENV + '-migrate', or 'local' if APP_ENV is blank.
MIGRATE_ENV='local'
[ -n "${APP_ENV}" ] && MIGRATE_ENV=${APP_ENV}-migrate
php artisan migrate --env=${MIGRATE_ENV}
 
# Now start Apache.
apache2-foreground
