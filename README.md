ServicesBundle
==============

ServicesBundle does the following:

- Defines services used by c975L bundles,
- Defines translations used by c975L bundles,
- As a kind of "bonus" also includes `.sh` scripts (see at the bottom),

[ServicesBundle dedicated web page](https://975l.com/en/pages/services-bundle).

[ServicesBundle API documentation](https://975l.com/apidoc/c975L/ServicesBundle.html).

Bundle installation
===================

Step 1: Download the Bundle
---------------------------
Use [Composer](https://getcomposer.org) to install the library
```bash
    composer require c975l/services-bundle
```

Step 2: Enable the Bundle
-------------------------
Then, enable the bundle by adding it to the list of registered bundles in the `app/AppKernel.php` file of your project:

```php
<?php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new c975L\ServicesBundle\c975LServicesBundle(),
        ];
    }
}
```

How to use
----------
Call the needed service via its interface and use its methods.

`.sh` scripts
-------------
These scripts are not directly related to Symfony but to its production steps for `GitHookPostUpdate.sh` and its backup `BackupXXX.sh`. **They are programmed to work on the Synfony 4(flex) structure AND on a GNU/Linux server. You can find more information on them below.

GitHookPostUpdate.sh
====================
This script is to be run after the Git repository has been updated (via `git pull`), for this, it's call should be placed in the `.git/hooks/post-update` file with the following code:
```bash
#!/bin/bash
Folder="$( cd "$(dirname "${BASH_SOURCE[0]}")"; pwd -P )";
#YOUR_PHP_VERSION is the name of the php binary you will use i.e. `php-7.3`
source $Folder/../../vendor/c975l/services-bundle/Scripts/GitHookPostUpdate.sh YOUR_PHP_VERSION;
exit 0
```

BackupXXX.sh
============
These scripts helps for the backup of a website, they are detailed below. The backup files are stored in `/var/backup/{year}/[year-month]/{year-month-day}`. The files are named using the following scheme: "[MYSQL|WEBSITE]_-_NAME_-_YYYY-MM-DD_-_HH-II_-_[WithoutArchives|Archives|Complete|Partial].tar.bz2".

You can include them in a crontab like in the following to execute each hour between 06 and 22 at the 15 minute:

```bash
MAILTO=YOUR_EMAIL_ADDRESS
15       6-22       *       *       *       bash /server_path_website/vendor/c975l/services-bundle/Scripts/BackupXXX.sh
```
An email wil be sent via cron on each error and only once a day when error less.

You have to create a config file `/config/backup_config.cnf` with the following data (without space) **Keep in mind to add this file to your `.gitignore`**:
```txt
[client]
user=DB_USER
password=DB_PASSWORD
host=DB_HOST
[config]
website=WEBSITE_NAME
database=DATABASE_NAME
day=DAY_FOR_COMPLETE_BACKUP
hour=HOUR_FOR_COMPLETE_BACKUP This hour has to be one of which the cron will be launched otherwise it will never be reached
```

BackupServer.sh
===============
This script groups calls for `BackupMysql.sh` and `BackupFiles.sh` to allow only one crontab but they can be called individually.

BackupMysql.sh
==============
This script makes a backup of the tables in MySql server. All the tables are mysqldumped (one by one, to allow restore table by table) at each run, except those named with `_archives` which occurs once a day at the hour specified in `/config/backup_config.cnf`. There is also a mysqldump of the whole database, at the same hour specified as for `*_archives`, to allow a restore with only one file. The format used for the naming is "NAME_-_TABLE.sql".

BackupFolders.sh
================
This script makes a backup of the `public` folder. There is a complete backup once a week and a partial backup (only new and newer files) other times.
