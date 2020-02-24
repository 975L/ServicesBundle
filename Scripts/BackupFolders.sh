#!/bin/bash

# (c) 2018: 975L <contact@975l.com>
# (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
# @author Laurent Marquet <laurent.marquet@laposte.net>
# This source file is subject to the MIT license that is bundled
# with this source code in the file LICENSE.
#
# Script to make a backup of the specified folders.

#Initializes variables and creates folders
Folder="$( cd "$(dirname "${BASH_SOURCE[0]}")"; pwd -P )";
source $Folder/BackupCommon.sh;

#Moves to backup folder
cd $BackupFinalFolder;

#Begin of backup
SECONDS=0;
echo $'\n''Folders and Files backup for "'$SiteName'": '`date +"%F %T"` >> $tmpEmailFile;

#Complete backup
if [[ ! -f $BackupDateTimeFile ]] || ([[ $WeekDayNumber == $DayCompleteBackupWebsite ]] && [[ $HourNumber == $HourCompleteBackupWebsite ]]); then
    echo 'COMPLETE Folders backup' >> $tmpEmailFile;
    #Uses exclude file if present
    if [[ -f $BackupExcludeFile ]]; then
        nice tar \
            --exclude-from=$SiteFolder/config/backup_exclude.cnf \
            --bzip2 \
            --create \
            --file "WEBSITE_-_"$SiteName"_-_"$DayDateTime"_-_Complete.tar.bz2" $SiteFolder/public/*;
    else
        nice tar \
            --bzip2 \
            --create \
            --file "WEBSITE_-_"$SiteName"_-_"$DayDateTime"_-_Complete.tar.bz2" $SiteFolder/public/*;
    fi
#Partial backup
else
    #Copies the list of modified files in a text file
    find $SiteFolder/public/ \
        -type f \
        -newer $BackupDateTimeFile \
        -print \
        > $tmpModifiedFile;
    #Compresses files if tmpModifiedFile is not empty
    if [ $(stat --format=%s "$tmpModifiedFile") != '0' ]; then
        echo 'PARTIAL Folders backup' >> $tmpEmailFile;
        cat $tmpModifiedFile >> $tmpEmailFile;
        nice tar \
            --files-from=$tmpModifiedFile \
            --bzip2 \
            --create \
            --file "WEBSITE_-_"$SiteName"_-_"$DayDateTime"_-_Partial.tar.bz2" 2>/dev/null;
    else
        echo 'NO FILE to save' >> $tmpEmailFile;
    fi

    #Deletes temporary files
    rm $tmpModifiedFile;
fi

#Cleans backup
source $Folder/BackupCleaning.sh;

#Change l'heure du fichier horaire
touch -t $BackupFileDateTime $BackupDateTimeFile

exit 0
