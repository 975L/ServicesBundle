#!/bin/bash

# (c) 2018: 975L <contact@975l.com>
# (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
# @author Laurent Marquet <laurent.marquet@laposte.net>
# This source file is subject to the MIT license that is bundled
# with this source code in the file LICENSE.
#
# Script to intialize variables and create folders

#Date and Time variables
export YearDate=`date +"%Y"`;
export MonthDate=`date +"%Y-%m"`;
export DayDate=`date +"%F"`;
export DayDateTime=`date +"%F_-_%H-%M"`;
export WeekDayNumber=`date +"%u"`;
export HourNumber=`date +"%H"`;
export BackupFileDateTime=`date +"%Y%m%d%H%M"`;

#Creates backup folders
Folder="$( cd "$(dirname "${BASH_SOURCE[0]}")"; pwd -P )";
export SiteFolder="$Folder/../../../..";
export BackupFolder=$SiteFolder/var/backup;
export BackupFinalFolder=$BackupFolder/$YearDate/$MonthDate/$DayDate;
mkdir --parents $BackupFinalFolder;

#Files
export BackupDateTimeFile=$SiteFolder/var/BackupDateTimeFile;
export tmpEmailFile=$SiteFolder/var/tmpEmailFile;
export tmpModifiedFile=$SiteFolder/var/tmpModifiedFile;

#Gets Backup configuration
export BackupConfigFile=$SiteFolder/config/backup_config.cnf;

Database=`grep "database=" $BackupConfigFile`;
export Database=${Database#"database="};

SiteName=`grep "website=" $BackupConfigFile`;
export SiteName=${SiteName#"website="};

DayCompleteBackupWebsite=`grep "day=" $BackupConfigFile`;
export DayCompleteBackupWebsite=${DayCompleteBackupWebsite#"day="};

HourCompleteBackupWebsite=`grep "hour=" $BackupConfigFile`;
export HourCompleteBackupWebsite=${HourCompleteBackupWebsite#"hour="};

#Begin of backup
echo 'Begin of backup: '`date +"%F %T"` > $tmpEmailFile;
SECONDS=0;
