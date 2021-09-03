# Changelog

## v1.8.1

- Removed twig/extensions (03/09/2021)

## v1.8

- Removed versions constraints in composer (03/09/2021)

## v1.7.1

- Cosmetic changes due to Codacy review (05/03/2020)

## v1.7

- Added possibility to specify an exclude file for BackupFolders.sh (24/02/2020)

## v1.6.3

- Modified GitHookPostUpdate.sh to add dump-env and made use of --optimize-autoloader (07/10/2019)

## v1.6.2

- Added possibility to rotate image (12/08/2019)

## v1.6.1

- Updated README.md (12/08/2019)

## v1.6

- Added posiibility to resize image in square format (11/08/2019)
- Added possibility to add a stamp in the image resized (11/08/2019)

## v1.5

- Added Twig Extension TemplateExists (09/08/2019)
- Added dependency for c975L/ConfigBundle (09/08/2019)

## v1.4

- Added script to import SQL file to server (07/08/2019)
- Added list of known bots (07/08/2019)
- Added list of known file extensions (07/08/2019)

## v1.3

- Made use of `git reset --hard origin/master` to force overwrite of local changes (03/08/2019)

## v1.2.9

- Added `getPdfFilePath()` to return the url instead of the content (15/07/2019)

## v1.2.8

- Added Twig function to check if a Route exists (14/07/2019)

## v1.2.7

- Removed ">>>" echoed in email to avoid having indentation (02/07/2019)

## v1.2.6.3

- Changed Github's author reference url (08/04/2019)

## v1.2.6.2

- Added example to resize image in README.md (19/03/2019)

## v1.2.6.1

- Modified Dependencyinjection rootNode to be not empty (13/02/2019)

## v1.2.6

- Corrected BackupCleaning.sh (08/02/2019)
- Modified behaviour of sent email, for Backup scripts, to sum up the actions (08/02/2019)

## v1.2.5

- Modified the BackupFolders.sh for complete backup to occur on week day instead of month day (31/01/2019)
- Corrected Scripts for sending email (07/02/2019)
- Added `2>/dev/null` for tar actions to avoid messages in email sent (08/02/2019)

## v1.2.4

- Corrected cleaning for bash scripts (18/01/2019)

## v1.2.3

- Modified date formats in scripts (17/01/2019)
- Added duration for backup (17/01/2019)
- Modified message sent in email (17/01/2019)

## v1.2.2

- Corrected `BackupCommon.sh` (17/01/2019)
- Corrected `BackupMySql.sh` (17/01/2019)
- Added dump of whole database in `BackupMySql.sh` (17/01/2019)

## v1.2.1

- Updated composer.json (15/01/2019)

## v1.2

- Added `.sh` scripts (14/01/2019)

## v1.1.4

- Modified required versions in `composer.json` (25/12/2018)

## v1.1.3

- Added rector to composer dev part (23/12/2018)
- Modified required versions in composer (23/12/2018)

## v1.1.2

- Updated composer.json (01/09/2018)
- Added check for `null !== Request` in case called from Command (02/09/2018)

## v1.1.1

- Updated `README.md` (26/08/2018)
- Added possibility to create a flash without translation domain (27/08/2018)

## v1.1

- Added core files (26/08/2018)

## v1.0.1

- Corrected name in composer.json (26/08/2018)

## v1.0

- Creation of bundle (26/08/2018)
