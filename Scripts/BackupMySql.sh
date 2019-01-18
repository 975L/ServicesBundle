#!/bin/bash

# (c) 2018: 975L <contact@975l.com>
# (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
# This source file is subject to the MIT license that is bundled
# with this source code in the file LICENSE.
#
# Script to make a backup of the tables in MySql server. All the tables are mysqldumped at each run, except those name `_archives` which occurs once a day.
# @author Laurent Marquet <laurent.marquet@laposte.net>
# @copyright 2017 975L <contact@975l.com>

#Initializes variables and creates folders
Folder="$( cd "$(dirname "${BASH_SOURCE[0]}")"; pwd -P )";
source $Folder/BackupCommon.sh;

#Moves to backup folder
cd $BackupFinalFolder;

#Backups tables (structure + data) excepting *_archives
echo $'\n''Mysql backup for tables in '$Database >> $tmpEmailFile;
Tables=$(mysql --defaults-extra-file=$BackupConfigFile --database=$Database --execute="SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$Database' AND TABLE_NAME NOT LIKE '%_archives' AND TABLE_TYPE != 'VIEW';");
for Table in $Tables; do
    if [ $Table != 'TABLE_NAME' ]; then
        echo '- '$Table >> $tmpEmailFile;
        mysqldump \
            --defaults-extra-file=$BackupConfigFile \
            --skip-comments \
            --compact \
            --force \
            --lock-tables \
            --quick \
            --single-transaction \
            --triggers \
            --hex-blob \
            $Database $Table > $Database"_-_"$Table".sql";
    fi
done

#Compresses backups in tar.bz2
Files=$(shopt -s nullglob dotglob; echo ./*.sql);
if (( ${#Files} )); then
    nice tar \
        --remove-files \
        --bzip2 \
        --create \
        --file \
        "MYSQL_-_"$Database"_-_"$DayDateTime"_-_WithoutArchives.sql.tar.bz2" *.sql;
fi

#Backups tables (structure + data) for _archives only once a day
if [ $HourNumber == $HourCompleteBackupWebsite ]; then
    #*_archives database backup
    echo $'\n''Mysql backup for tables *_archives in '$Database >> $tmpEmailFile;
    for Table in $(mysql --defaults-extra-file=$BackupConfigFile --database=$Database --execute="SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$Database' AND TABLE_NAME LIKE '%_archives';"); do
        if [ $Table != 'TABLE_NAME' ]; then
            echo '- '$Table >> $tmpEmailFile;
            mysqldump \
                --defaults-extra-file=$BackupConfigFile \
                --skip-comments \
                --compact \
                --force \
                --lock-tables \
                --quick \
                --single-transaction \
                --triggers \
                --hex-blob \
                $Database $Table > $Database"_-_"$Table".sql";
        fi
    done

    #Whole database backup
    echo $'\n''Mysql backup for WHOLE database '$Database >> $tmpEmailFile;
    mysqldump \
        --defaults-extra-file=$BackupConfigFile \
        --skip-comments \
        --compact \
        --force \
        --lock-tables \
        --quick \
        --single-transaction \
        --triggers \
        --hex-blob \
        $Database > $Database"_-_WHOLE_DATABASE.sql";

    #Compresses backups in tar.bz2
    Files=$(shopt -s nullglob dotglob; echo ./*.sql);
    if (( ${#Files} )); then
        nice tar \
            --remove-files \
            --bzip2 \
            --create \
            --file \
            "MYSQL_-_"$Database"_-_"$DayDateTime"_-_Archives.sql.tar.bz2" *.sql;
    fi
fi

#Cleans backup
source $Folder/BackupCleaning.sh;

exit 0
