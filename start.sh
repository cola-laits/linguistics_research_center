#!/bin/sh -e
 
# Do the migration.  Migration environment is APP_ENV + '-migrate', or 'local' if APP_ENV is blank.
MIGRATE_ENV='local'
[ -n "${APP_ENV}" ] && MIGRATE_ENV=${APP_ENV}-migrate
php artisan migrate --env=${MIGRATE_ENV}

#rerun chmod to open up permissions on any files that got written as part of the migration.
chmod -R 777 /laravel_storage
 
# Now start Apache.
apache2-foreground
