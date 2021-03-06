#!/bin/bash
set -ue
set -o pipefail

SERVER=dan@gunnar.dannilsson.se
DEST_DIR=mag-admin.dannilsson.se

RSYNC_OPTIONS=(
	--exclude='.DS_Store'
	--exclude='.htaccess'
	--exclude='.gitignore'
	--exclude='http-error.log'
	--exclude='scss'
	--exclude='node_modules'
	--exclude='bower_components'
	--exclude='Gruntfile.js'
	--exclude='package.json'
	--exclude='package-lock.json'
	--exclude='deploy.sh'
	# --exclude='composer.json'
	--exclude='composer.lock'
	--exclude='vendor'
	--exclude='app/cache/*'
	--exclude='app/config/development.php'
	--exclude='app/config/test.php'
	--exclude='app/config/local.php'
	--exclude='public/js/src'
	--exclude='public/js/vendor'
	# --exclude='public/css/*.css'
	--exclude='logs/*'
	--exclude='misc'
	--exclude='test'
	--exclude='tests'
	--exclude='Test'
	--exclude='Tests'
	--exclude='doc'
	--exclude='docs'
)

echo "$SERVER : deploying"

rsync \
	--no-perms --no-owner --no-group \
	--recursive \
	--cvs-exclude \
	--compress \
	--human-readable \
	--verbose \
	--delete-delay \
	-e ssh ${RSYNC_OPTIONS[@]} ../ ${SERVER}:/srv/${DEST_DIR}/public_html

ssh "$SERVER" "
set -ue

cd /srv/$DEST_DIR/public_html

sudo rm -rf app/cache/*
chmod 777 app/cache

cd bin
./get_token.sh > ../app/config/api/token
chmod 700 get_token.sh

cd ../public
mv htaccess_prod.txt .htaccess

cd ../app
composer clearcache
composer install

sudo service apache2 reload
"
echo "$SERVER : ended"
exit 0
