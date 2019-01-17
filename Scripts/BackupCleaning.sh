#!/bin/bash

# (c) 2018: 975L <contact@975l.com>
# (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
# This source file is subject to the MIT license that is bundled
# with this source code in the file LICENSE.
#
# Script to clean files and achieve final things
# @author Laurent Marquet <laurent.marquet@laposte.net>
# @copyright 2017 975L <contact@975l.com>

#Sends by email once a day
if [[ $HourNumber == $HourCompleteBackupWebsite ]]; then
    #End of backup
    echo $'\n''End of backup: '`date +"%F %T"` >> $tmpEmailFile;
    Duration=$SECONDS;
    echo $'\n''Duration: $(($Duration / 60)) minutes and $(($Duration % 60)) seconds' >> $tmpEmailFile;
    cat $tmpEmailFile;
fi
rm $tmpEmailFile;

#Deletes files related to their size
find $BackupFinalFolder/ -size -50c -type f -delete;

#Deletes empty folders
find $BackupFolder/ -type d -empty -delete

#Change l'heure du fichier horaire
touch -t $BackupFileDateTime $BackupDateTimeFile
