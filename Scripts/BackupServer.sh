#!/bin/bash

# (c) 2018: 975L <contact@975l.com>
# (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
# This source file is subject to the MIT license that is bundled
# with this source code in the file LICENSE.
#
# Script that calls the Backups files to allow only one crontab but they can be called individually
# @author Laurent Marquet <laurent.marquet@laposte.net>
# @copyright 2017 975L <contact@975l.com>

Folder="$( cd "$(dirname "${BASH_SOURCE[0]}")"; pwd -P )";

#Backups MySql
bash $Folder/BackupMySql.sh;

#Backups User's folders
bash $Folder/BackupFolders.sh;

exit 0
