@echo off
cd /d D:\dev\shop
echo [%DATE% %TIME%] Scheduler triggered >> D:\dev\shop\log.txt
"D:\xampp\php84\php.exe" artisan schedule:run >> D:\dev\shop\log.txt 2>&1
