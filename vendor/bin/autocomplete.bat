@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../iiifx-production/yii2-autocomplete-helper/autocomplete
php "%BIN_TARGET%" %*
