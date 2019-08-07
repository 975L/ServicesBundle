#!/bin/bash

# (c) 2019: 975L <contact@975l.com>
# (c) 2019: Laurent Marquet <laurent.marquet@laposte.net>
# @author Laurent Marquet <laurent.marquet@laposte.net>
# This source file is subject to the MIT license that is bundled
# with this source code in the file LICENSE.
#
# Script to import the contents of an sql file to MySql server.

DateTime=`date +"%Y-%m-%d-%H:%M"`;
Folder="$( cd "$(dirname "${BASH_SOURCE[0]}")"; pwd -P )/../../../..";
TmpFolder=$Folder"/var/tmp/";
SqlFile=$TmpFolder"sqlFile.sql";
SqlFileImport=$TmpFolder"sqlFileImport.sql";
SqlFileImported=$TmpFolder"sqlFileImported-$DateTime.sql";
BackupConfigFile=$Folder'/config/backup_config.cnf';

#Extract information from config file
Database=`grep "database=" $BackupConfigFile`;
Database=${Database#"database="};
Host=`grep "host=" $BackupConfigFile`;
Host=${Host#"host="};

#Imports the file
if [ -f $SqlFile ]; then
    mv $SqlFile $SqlFileImport;
    mysql \
    	--defaults-extra-file=$BackupConfigFile \
        --database=$Database \
        --host=$Host < $SqlFileImport;
    mv $SqlFileImport $SqlFileImported;
fi

#Deletes tmp files older than 7 days
find $TmpFolder -type f -mtime +7 -delete;

exit 0