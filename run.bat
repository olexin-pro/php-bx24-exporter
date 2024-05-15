@echo off
set php=C:\php\php.exe
set script=./minicli
echo %php%


if not exist %php% (
    echo php file doesn't exist, check source codes
    exit /b 1
)


if not exist %script% (
    echo script file doesn't exist, check source codes
    exit /b 1
)

%php% %script% export

pause