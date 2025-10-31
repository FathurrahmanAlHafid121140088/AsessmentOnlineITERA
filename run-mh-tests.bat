@echo off
echo ========================================
echo Mental Health Module Test Runner
echo ========================================
echo.

:menu
echo Pilih jenis test yang ingin dijalankan:
echo.
echo [1] Unit Tests (Models)
echo [2] Admin Dashboard Tests
echo [3] Cache Performance Tests
echo [4] Rate Limiting Tests
echo [5] Integration Workflow Tests
echo [6] Export Functionality Tests
echo [7] Semua Tests Mental Health
echo [8] Semua Tests (Full Suite)
echo [9] Exit
echo.

set /p choice="Masukkan pilihan (1-9): "

if "%choice%"=="1" (
    echo.
    echo Running Unit Tests...
    php artisan test --testsuite=Unit
    goto end
)

if "%choice%"=="2" (
    echo.
    echo Running Admin Dashboard Tests...
    php artisan test --filter=AdminDashboardCompleteTest
    goto end
)

if "%choice%"=="3" (
    echo.
    echo Running Cache Performance Tests...
    php artisan test --filter=CachePerformanceTest
    goto end
)

if "%choice%"=="4" (
    echo.
    echo Running Rate Limiting Tests...
    php artisan test --filter=RateLimitingTest
    goto end
)

if "%choice%"=="5" (
    echo.
    echo Running Integration Workflow Tests...
    php artisan test --filter=MentalHealthWorkflowIntegrationTest
    goto end
)

if "%choice%"=="6" (
    echo.
    echo Running Export Functionality Tests...
    php artisan test --filter=ExportFunctionalityTest
    goto end
)

if "%choice%"=="7" (
    echo.
    echo Running All Mental Health Tests...
    php artisan test --testsuite=Feature --filter="MentalHealth|HasilKuesioner|DataDiris|Dashboard|Admin|Cache|RateLimit|Export|Workflow"
    goto end
)

if "%choice%"=="8" (
    echo.
    echo Running Full Test Suite...
    php artisan test
    goto end
)

if "%choice%"=="9" (
    echo.
    echo Exiting...
    exit
)

echo.
echo Pilihan tidak valid!
goto menu

:end
echo.
echo ========================================
echo Test selesai!
echo ========================================
echo.
pause
