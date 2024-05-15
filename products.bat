@echo off
set php_exec="bin_php/php.exe"
set script=minicli

if not exist %php_exec% (
    echo php file doesn't exist, check source codes
    exit /b 1
)

if not exist %script% (
    echo script file doesn't exist, check source codes
    exit /b 1
)

%php_exec% %script% export product

pause