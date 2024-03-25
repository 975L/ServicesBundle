#!/bin/bash

# (c) 2018: 975L <contact@975l.com>
# (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
# @author Laurent Marquet <laurent.marquet@laposte.net>
# This source file is subject to the MIT license that is bundled
# with this source code in the file LICENSE.
#
# Script to clean files and achieve final things

#Deletes files related to their size
find $BackupFinalFolder/ -size -50c -type f -delete;

#Deletes empty folders
find $BackupFolder/ -type d -empty -delete;

#End of backup
Duration=$SECONDS;
echo $'\n''End of backup: '`date +"%F %T"`' - Duration: '$(($Duration / 60))' minutes and '$(($Duration % 60))' seconds' >> $tmpEmailFile;

#Sends by email once a week (monday) at the hour specified in config file
if [[ $HourNumber == $HourCompleteBackupWebsite ]] && [[ $WeekDayNumber == 1 ]]; then
    cat $tmpEmailFile;
    rm $tmpEmailFile;
fi
