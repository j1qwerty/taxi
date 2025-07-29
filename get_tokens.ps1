# API Authentication Demo Script
# Run this in PowerShell to get working bearer tokens

# Get Rider Token
Write-Host "=== Getting Rider Token ===" -ForegroundColor Green
$riderResponse = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/login" -Method Post -ContentType "application/json" -Body '{"email":"rider1@test.com","password":"password"}'
$riderToken = $riderResponse.data.token
Write-Host "Rider Token: $riderToken" -ForegroundColor Yellow

# Get Driver Token
Write-Host "`n=== Getting Driver Token ===" -ForegroundColor Green
$driverResponse = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/login" -Method Post -ContentType "application/json" -Body '{"email":"driver1@test.com","password":"password"}'
$driverToken = $driverResponse.data.token
Write-Host "Driver Token: $driverToken" -ForegroundColor Yellow

# Test Rider Profile
Write-Host "`n=== Testing Rider Profile ===" -ForegroundColor Green
$riderProfile = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/rider/profile" -Method Get -Headers @{"Authorization"="Bearer $riderToken"; "Accept"="application/json"}
Write-Host "Rider Profile Retrieved: $($riderProfile.status)" -ForegroundColor Cyan

# Test Driver Profile
Write-Host "`n=== Testing Driver Profile ===" -ForegroundColor Green
$driverProfile = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/driver/profile" -Method Get -Headers @{"Authorization"="Bearer $driverToken"; "Accept"="application/json"}
Write-Host "Driver Profile Retrieved: $($driverProfile.status)" -ForegroundColor Cyan

# Display tokens for manual use
Write-Host "`n=== Copy these tokens for manual testing ===" -ForegroundColor Magenta
Write-Host "RIDER_TOKEN=$riderToken" -ForegroundColor White
Write-Host "DRIVER_TOKEN=$driverToken" -ForegroundColor White

Write-Host "`n=== Example curl commands ===" -ForegroundColor Blue
Write-Host "# Get rider profile:" -ForegroundColor Gray
Write-Host "curl -X GET http://127.0.0.1:8000/api/rider/profile -H `"Authorization: Bearer $riderToken`" -H `"Accept: application/json`"" -ForegroundColor White

Write-Host "`n# Get driver profile:" -ForegroundColor Gray  
Write-Host "curl -X GET http://127.0.0.1:8000/api/driver/profile -H `"Authorization: Bearer $driverToken`" -H `"Accept: application/json`"" -ForegroundColor White
