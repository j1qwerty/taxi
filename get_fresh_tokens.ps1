# Quick Token Generation Script for Taxi API
# Run this to get fresh authentication tokens

$baseUrl = "http://127.0.0.1:8000/api"

# Test credentials
$riderEmail = "test.rider@example.com"
$driverEmail = "test.driver@example.com"
$password = "password123"

Write-Host "🚀 Generating API Tokens..." -ForegroundColor Cyan

# Function to get token
function Get-ApiToken {
    param([string]$email, [string]$password, [string]$userType)
    
    $body = @{
        email = $email
        password = $password
    } | ConvertTo-Json
    
    try {
        $response = Invoke-RestMethod -Uri "$baseUrl/auth/login" -Method Post -ContentType "application/json" -Body $body
        return $response.data.token
    } catch {
        Write-Host "❌ Failed to get $userType token: $($_.Exception.Message)" -ForegroundColor Red
        return $null
    }
}

# Register and get rider token
Write-Host "`n📱 Getting Rider Token..." -ForegroundColor Yellow
$riderRegBody = @{
    name = "Test Rider"
    email = $riderEmail
    password = $password
    password_confirmation = $password
    phone = "+1234567890"
    user_type = "rider"
} | ConvertTo-Json

try {
    $riderReg = Invoke-RestMethod -Uri "$baseUrl/auth/register" -Method Post -ContentType "application/json" -Body $riderRegBody
    $riderToken = $riderReg.data.token
    Write-Host "✅ Rider registered and token generated!" -ForegroundColor Green
} catch {
    # If registration fails, try login
    $riderToken = Get-ApiToken -email $riderEmail -password $password -userType "rider"
}

# Register and get driver token
Write-Host "`n🚗 Getting Driver Token..." -ForegroundColor Yellow
$driverRegBody = @{
    name = "Test Driver"
    email = $driverEmail
    password = $password
    password_confirmation = $password
    phone = "+1234567891"
    user_type = "driver"
    license_number = "DL123456789"
    vehicle_number = "KA01AB1234"
    vehicle_type = "sedan"
    vehicle_model = "Toyota Camry 2023"
    vehicle_color = "Black"
} | ConvertTo-Json

try {
    $driverReg = Invoke-RestMethod -Uri "$baseUrl/auth/register" -Method Post -ContentType "application/json" -Body $driverRegBody
    $driverToken = $driverReg.data.token
    Write-Host "✅ Driver registered and token generated!" -ForegroundColor Green
} catch {
    # If registration fails, try login
    $driverToken = Get-ApiToken -email $driverEmail -password $password -userType "driver"
}

# Display tokens
Write-Host "`n" + "="*80 -ForegroundColor Cyan
Write-Host "🔑 AUTHENTICATION TOKENS GENERATED" -ForegroundColor Cyan
Write-Host "="*80 -ForegroundColor Cyan

if ($riderToken) {
    Write-Host "`n👤 RIDER TOKEN:" -ForegroundColor Green
    Write-Host $riderToken -ForegroundColor White
    
    Write-Host "`n📋 Curl Example (Rider Profile):" -ForegroundColor Yellow
    Write-Host "curl -X GET http://127.0.0.1:8000/api/rider/profile \" -ForegroundColor Gray
    Write-Host "  -H `"Authorization: Bearer $riderToken`" \" -ForegroundColor Gray
    Write-Host "  -H `"Accept: application/json`"" -ForegroundColor Gray
} else {
    Write-Host "`n❌ RIDER TOKEN: Not generated" -ForegroundColor Red
}

if ($driverToken) {
    Write-Host "`n🚗 DRIVER TOKEN:" -ForegroundColor Green
    Write-Host $driverToken -ForegroundColor White
    
    Write-Host "`n📋 Curl Example (Driver Profile):" -ForegroundColor Yellow
    Write-Host "curl -X GET http://127.0.0.1:8000/api/driver/profile \" -ForegroundColor Gray
    Write-Host "  -H `"Authorization: Bearer $driverToken`" \" -ForegroundColor Gray
    Write-Host "  -H `"Accept: application/json`"" -ForegroundColor Gray
} else {
    Write-Host "`n❌ DRIVER TOKEN: Not generated" -ForegroundColor Red
}

Write-Host "`n🎯 USAGE INSTRUCTIONS:" -ForegroundColor Cyan
Write-Host "1. Copy the tokens above" -ForegroundColor White
Write-Host "2. Replace YOUR_TOKEN_HERE in API documentation" -ForegroundColor White
Write-Host "3. Run the full test script: .\test_all_apis.ps1" -ForegroundColor White
Write-Host "4. Test individual endpoints with curl or Postman" -ForegroundColor White

Write-Host "`n" + "="*80 -ForegroundColor Cyan
