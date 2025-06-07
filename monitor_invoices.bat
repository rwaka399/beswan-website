@echo off
:loop
cls
echo === REAL-TIME INVOICE MONITORING ===
echo Press Ctrl+C to stop
echo.
php check_invoice_status.php
echo.
echo Refreshing in 5 seconds...
timeout /t 5 /nobreak > nul
goto loop
