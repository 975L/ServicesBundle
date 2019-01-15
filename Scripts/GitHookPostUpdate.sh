#!/bin/bash

# (c) 2018: 975L <contact@975l.com>
# (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
# This source file is subject to the MIT license that is bundled
# with this source code in the file LICENSE.
#
# Script executed after the update of the Git repository to clean cache and delete uneeeded folders/files
# @author Laurent Marquet <laurent.marquet@laposte.net>
# @copyright 2017 975L <contact@975l.com>

#Gets php binary name from command line
PhpVersion=$1;

echo "------> Begin execution of GitHookPostUpdate";
SiteFolder="$( cd "$(dirname "${BASH_SOURCE[0]}")"; pwd -P )/../../../../";
SiteName=$SiteFolder | cut -d'/' -f 5;
cd $SiteFolder;
unset GIT_DIR;

echo "------> Pull changes from master";
git pull origin master;


if [[ $PhpVersion != '' ]]; then
    echo "------> Composer installation";
    export SYMFONY_ENV=prod;
    $PhpVersion -d allow_url_fopen=On -d memory_limit=2G ~/composer.phar install --no-dev;
    $PhpVersion -d allow_url_fopen=On -d memory_limit=2G ~/composer.phar dump-autoload --optimize --no-dev --classmap-authoritative;

    echo "------> Clear Doctrine cache";
    $PhpVersion bin/console doctrine:cache:clear-metadata --env=prod;
    $PhpVersion bin/console doctrine:cache:clear-query --env=prod;
    $PhpVersion bin/console doctrine:cache:clear-result --env=prod;

    echo "------> Clear Symfony cache";
    $PhpVersion bin/console cache:clear --no-warmup --env=prod;
    $PhpVersion bin/console cache:warmup --env=prod;
fi

echo "------> Clear APCu cache";
echo "<?php if (extension_loaded('apcu')) { apcu_clear_cache(); }" > $SiteFolder/public/apcu.php;
curl --request GET http://$SiteName/apcu.php;
rm $SiteFolder/public/apcu.php;

echo "------> Clear OPCache cache";
echo "<?php if (extension_loaded('opcache')) { opcache_reset(); }" > $SiteFolder/public/opcache.php;
curl --request GET http://$SiteName/opcache.php;
rm $SiteFolder/public/opcache.php;

echo "------> Deleting unneeded files";
Files="\
$SiteFolder/ChangeLog \
$SiteFolder/.env.test \
$SiteFolder/public/css/styles.css \
$SiteFolder/public/js/functions.js \
";
for File in $Files; do
    rm -f $File;
done

echo "------> Deleting unneeded folders";
Folders="\
$SiteFolder/config/packages/dev \
$SiteFolder/config/packages/test \
$SiteFolder/config/routes/dev \
";
for Folder in $Folders; do
    rm -Rf $Folder;
done

echo "------> End execution GitHookPostUpdate";

exit 0
