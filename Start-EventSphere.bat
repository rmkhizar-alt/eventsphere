@echo off
title EventSphere
cd /d "%~dp0"

start "EventSphere-Server" /min cmd /c "C:\php\php.exe artisan serve --host=127.0.0.1 --port=8000"
timeout /t 2 /nobreak >nul
start "" "C:\Program Files\Google\Chrome\Application\chrome.exe" "http://127.0.0.1:8000"
