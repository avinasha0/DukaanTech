@echo off
cd /d "C:\xampp\htdocs\pos"
powershell -ExecutionPolicy Bypass -File "git-sync.ps1" %*
