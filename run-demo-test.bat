@echo off
echo ========================================
echo   DEMO LIVE TESTING - Mental Health
echo   Assessment Online Psychology
echo ========================================
echo.

:menu
echo.
echo Pilih Demo:
echo 1. Run ALL Tests (Quick Demo)
echo 2. Run Tests with Coverage (Full Demo)
echo 3. Generate HTML Coverage Report
echo 4. Run Unit Tests Only
echo 5. Run Feature Tests Only
echo 6. Run Integration Tests Only
echo 7. Run Specific Test (Manual Input)
echo 8. Clear Cache ^& Refresh Database
echo 9. Open HTML Coverage Report (Documentation)
echo 10. Open All Coverage Reports
echo 11. Open Demo Guide ^& Cheat Sheet
echo 0. Exit
echo.

set /p choice="Pilih menu (0-11): "

if "%choice%"=="1" goto all_tests
if "%choice%"=="2" goto coverage_tests
if "%choice%"=="3" goto html_report
if "%choice%"=="4" goto unit_tests
if "%choice%"=="5" goto feature_tests
if "%choice%"=="6" goto integration_tests
if "%choice%"=="7" goto specific_test
if "%choice%"=="8" goto refresh
if "%choice%"=="9" goto open_report
if "%choice%"=="10" goto open_all
if "%choice%"=="11" goto open_guide
if "%choice%"=="0" goto exit

echo Pilihan tidak valid!
goto menu

:all_tests
echo.
echo [1] Running ALL Tests...
echo ========================================
php artisan test
echo.
echo Tests Completed!
pause
goto menu

:coverage_tests
echo.
echo [2] Running Tests with Coverage...
echo ========================================
php artisan test --coverage-text --min=83
echo.
echo Coverage Report Completed!
pause
goto menu

:html_report
echo.
echo [3] Generating HTML Coverage Report...
echo ========================================
php artisan test --coverage-html coverage-report
echo.
echo HTML Report Generated!
echo Location: coverage-report/index.html
pause
goto menu

:unit_tests
echo.
echo [4] Running Unit Tests Only...
echo ========================================
php artisan test --testsuite=Unit
echo.
echo Unit Tests Completed!
pause
goto menu

:feature_tests
echo.
echo [5] Running Feature Tests Only...
echo ========================================
php artisan test --testsuite=Feature
echo.
echo Feature Tests Completed!
pause
goto menu

:integration_tests
echo.
echo [6] Running Integration Tests Only...
echo ========================================
php artisan test --filter=MentalHealthWorkflowIntegrationTest
echo.
echo Integration Tests Completed!
pause
goto menu

:specific_test
echo.
set /p testname="Masukkan nama test (tanpa .php): "
echo.
echo [7] Running Test: %testname%
echo ========================================
php artisan test --filter=%testname%
echo.
echo Test Completed!
pause
goto menu

:refresh
echo.
echo [8] Clearing Cache and Refreshing Database...
echo ========================================
php artisan cache:clear
php artisan config:clear
php artisan migrate:fresh --seed
echo.
echo Cache Cleared and Database Refreshed!
pause
goto menu

:open_report
echo.
echo [9] Opening HTML Coverage Report...
echo ========================================
if exist "documentation\02-testing\COVERAGE_REPORT.html" (
    start documentation\02-testing\COVERAGE_REPORT.html
    echo Coverage Report Documentation Opened!
) else if exist "coverage-report\index.html" (
    start coverage-report\index.html
    echo PHPUnit Coverage Report Opened!
) else (
    echo Report not found!
    echo Please generate it first (Option 3) or check if COVERAGE_REPORT.html exists.
)
pause
goto menu

:open_all
echo.
echo [10] Opening All Coverage Reports...
echo ========================================
if exist "documentation\02-testing\COVERAGE_REPORT.html" (
    start documentation\02-testing\COVERAGE_REPORT.html
    echo [✓] COVERAGE_REPORT.html opened
    timeout /t 1 >nul
)
if exist "documentation\02-testing\COVERAGE_REPORT_VISUAL.md" (
    start documentation\02-testing\COVERAGE_REPORT_VISUAL.md
    echo [✓] COVERAGE_REPORT_VISUAL.md opened
    timeout /t 1 >nul
)
if exist "coverage-report\index.html" (
    start coverage-report\index.html
    echo [✓] PHPUnit coverage-report opened
    timeout /t 1 >nul
)
if exist "documentation\02-testing\COVERAGE_REPORT_SUMMARY.txt" (
    start documentation\02-testing\COVERAGE_REPORT_SUMMARY.txt
    echo [✓] COVERAGE_REPORT_SUMMARY.txt opened
)
echo.
echo All available reports opened!
pause
goto menu

:open_guide
echo.
echo [11] Opening Demo Guide ^& Cheat Sheet...
echo ========================================
if exist "documentation\02-testing\PANDUAN_DEMO_LIVE_TESTING.md" (
    start documentation\02-testing\PANDUAN_DEMO_LIVE_TESTING.md
    echo [✓] PANDUAN_DEMO_LIVE_TESTING.md opened
    timeout /t 1 >nul
)
if exist "documentation\02-testing\CHEAT_SHEET_DEMO.md" (
    start documentation\02-testing\CHEAT_SHEET_DEMO.md
    echo [✓] CHEAT_SHEET_DEMO.md opened
    timeout /t 1 >nul
)
if exist "documentation\02-testing\README_COVERAGE_REPORT.md" (
    start documentation\02-testing\README_COVERAGE_REPORT.md
    echo [✓] README_COVERAGE_REPORT.md opened
)
echo.
echo All guides opened!
pause
goto menu

:exit
echo.
echo Terima kasih! Good luck dengan presentasi!
echo.
pause
exit
