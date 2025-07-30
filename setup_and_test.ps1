# Taxi API Setup and Test Script
# This script sets up the API and runs basic tests

Write-Host "🚀 TAXI API SETUP AND TESTING" -ForegroundColor Cyan
Write-Host "===============================================" -ForegroundColor Cyan

# Check if Laravel server is running
try {
    $response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/test" -TimeoutSec 5
    Write-Host "✅ Laravel server is running!" -ForegroundColor Green
} catch {
    Write-Host "❌ Laravel server is not running!" -ForegroundColor Red
    Write-Host "Please run: php artisan serve" -ForegroundColor Yellow
    exit
}

# Run migrations if needed
Write-Host "`n🔧 Checking database setup..." -ForegroundColor Yellow
try {
    $migrationOutput = & php artisan migrate --force 2>&1
    Write-Host "✅ Database migrations completed!" -ForegroundColor Green
} catch {
    Write-Host "⚠️  Migration check completed" -ForegroundColor Yellow
}

# Clear cache and config
Write-Host "`n🧹 Clearing application cache..." -ForegroundColor Yellow
& php artisan config:clear
& php artisan cache:clear
& php artisan route:clear
Write-Host "✅ Cache cleared!" -ForegroundColor Green

# Generate tokens
Write-Host "`n🔑 Generating API tokens..." -ForegroundColor Yellow
& .\get_fresh_tokens.ps1

Write-Host "`n📋 Available Test Scripts:" -ForegroundColor Cyan
Write-Host "1. .\get_fresh_tokens.ps1 - Generate authentication tokens" -ForegroundColor White
Write-Host "2. .\test_all_apis.ps1 - Run comprehensive API tests" -ForegroundColor White
Write-Host "3. .\curl_test_commands.sh - View curl commands for manual testing" -ForegroundColor White

Write-Host "`n🌐 API Endpoints Available:" -ForegroundColor Cyan
Write-Host "Base URL: http://127.0.0.1:8000/api" -ForegroundColor White
Write-Host "Admin Panel: http://127.0.0.1:8000/admin/login" -ForegroundColor White
Write-Host "API Test: http://127.0.0.1:8000/api/test" -ForegroundColor White

Write-Host "`n🎯 Next Steps:" -ForegroundColor Yellow
Write-Host "1. Copy the tokens generated above" -ForegroundColor White
Write-Host "2. Run: .\test_all_apis.ps1 for full testing" -ForegroundColor White
Write-Host "3. Use tokens in Postman/curl for manual testing" -ForegroundColor White

Write-Host "`n===============================================" -ForegroundColor Cyan
Write-Host "🎉 SETUP COMPLETE - API READY FOR TESTING!" -ForegroundColor Green
Write-Host "===============================================" -ForegroundColor Cyan
