#!/bin/bash

# (c) 2018: 975L <contact@975l.com>
# (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
# This source file is subject to the MIT license that is bundled
# with this source code in the file LICENSE.
#
# Script to make a backup of the specified folders.
# @author Laurent Marquet <laurent.marquet@laposte.net>
# @copyright 2017 975L <contact@975l.com>

#Initializes variables and creates folders
source "$( cd "$(dirname "${BASH_SOURCE[0]}")"; pwd -P )/BackupCommon.sh";

#Moves to backup folder
cd $BackupFinalFolder;

#Begin of backup
echo 'Begin of backup: '$BackupDateTime >> $tmpEmailFile;

#Complete backup
if [[ ! -f $BackupDateTimeFile ]] || ([[ $DayNumber == $DayCompleteBackupWebsite ]] && [[ $HourNumber == $HourCompleteBackupWebsite ]]); then
    echo 'Complete Folders backup for ' $SiteName >> $tmpEmailFile;
    nice tar \
        --bzip2 \
        --create \
        --file "WEBSITE_-_"$SiteName"_-_"$DayDateTime"_-_Complete.tar.bz2" $SiteFolder/public/*;
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
        echo 'Partial Folders backup for ' $SiteName >> $tmpEmailFile;
        cat $tmpModifiedFile >> $tmpEmailFile;
        nice tar \
            --files-from=$tmpModifiedFile \
            --bzip2 \
            --create \
            --file "WEBSITE_-_"$SiteName"_-_"$DayDateTime"_-_Partial.tar.bz2";
    fi

    #Deletes temporary files
    rm $tmpModifiedFile;
fi

#End of backup
echo 'End of backup' >> $tmpEmailFile;

#Cleans backup
bash "$( cd "$(dirname "${BASH_SOURCE[0]}")"; pwd -P )/BackupCleaning.sh";

exit 0
