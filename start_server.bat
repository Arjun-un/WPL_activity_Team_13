@echo off
echo ====================================================================
echo Starting Tata CLiQ Replica Web Server...
echo ====================================================================

set "PHP_BIN=C:\Users\hp\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"

if exist "%PHP_BIN%" (
    echo Using PHP 8.2 from: %PHP_BIN%
    echo Server listening at: http://localhost:8000
    echo Press Ctrl+C to stop the server.
    "%PHP_BIN%" -S localhost:8000
) else (
    echo Using system default PHP...
    echo Server listening at: http://localhost:8000
    echo Press Ctrl+C to stop the server.
    php -S localhost:8000
)
pause
