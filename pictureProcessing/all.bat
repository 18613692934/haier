@echo off
start backupSql.bat
ping localhost -n 5
start dataCollect.bat
ping localhost -n 5 
start picPro.bat
exit