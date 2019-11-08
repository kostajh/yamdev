#!/bin/sh

set -eu
cd /var/www/html

if [ -f LocalSettings.php ]
then
    mv LocalSettings.php LocalSettings.php.docker.tmp
fi

mysql -uroot -h database -e "DROP DATABASE mediawiki;"

php maintenance/install.php --dbuser root --dbname mediawiki --dbserver database --lang en --pass dockerpass yamdev admin
rm LocalSettings.php

if [ -f LocalSettings.php.docker.tmp ]
then
    mv LocalSettings.php.docker.tmp LocalSettings.php
fi

php maintenance/update.php --quick

