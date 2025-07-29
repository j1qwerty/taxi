# Taxi Booking API Test Suite - PowerShell Version
# Run this script to test all API endpoints

$baseUrl = "http://127.0.0.1:8000"

Write-Host "========================================" -ForegroundColor Green
Write-Host "    Taxi Booking API Test Suite" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# Function to make API calls and display results
function Test-Endpoint {
    param(
        [string]$Name,
        [string]$Method,
        [string]$Url,
        [hashtable]$Headers = @{},
        [string]$Body = $null
    )
    
    Write-Host "Testing: $Name" -ForegroundColor Yellow
    Write-Host "URL: $Method $Url" -ForegroundColor Cyan
    
    try {
        $params = @{
            Uri = $Url
            Method = $Method
            Headers = $Headers
        }
        
        if ($Body) {
            $params.Body = $Body
        }
        
        $response = Invoke-RestMethod @params
        Write-Host "✓ Success" -ForegroundColor Green
        Write-Host ($response | ConvertTo-Json -Depth 3) -ForegroundColor Gray
    }
    catch {
        Write-Host "✗ Error: $($_.Exception.Message)" -ForegroundColor Red
    }
    
    Write-Host ("-" * 50)
}

# Test 1: API Connection
Test-Endpoint -Name "API Connection Test" -Method "GET" -Url "$baseUrl/test" -Headers @{"Accept" = "application/json"}

# Test 2: Register Rider
$registerRiderBody = @{
    name = "TestRider"
    email = "testrider@test.com"
    phone = "+1234567800"
    password = "password"
    password_confirmation = "password"
    user_type = "rider"
} | ConvertTo-Json

Test-Endpoint -Name "Register New Rider" -Method "POST" -Url "$baseUrl/api/auth/register" -Headers @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
} -Body $registerRiderBody

# Test 3: Register Driver
$registerDriverBody = @{
    name = "TestDriver"
    email = "testdriver@test.com"
    phone = "+1234567801"
    password = "password"
    password_confirmation = "password"
    user_type = "driver"
} | ConvertTo-Json

Test-Endpoint -Name "Register New Driver" -Method "POST" -Url "$baseUrl/api/auth/register" -Headers @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
} -Body $registerDriverBody

# Test 4: Login as Rider1
$loginRiderBody = @{
    email = "rider1@test.com"
    password = "password"
} | ConvertTo-Json

Write-Host "Testing: Login as Rider1" -ForegroundColor Yellow
try {
    $riderLogin = Invoke-RestMethod -Uri "$baseUrl/api/auth/login" -Method "POST" -Headers @{
        "Content-Type" = "application/json"
        "Accept" = "application/json"
    } -Body $loginRiderBody
    
    $riderToken = $riderLogin.token
    Write-Host "✓ Rider login successful" -ForegroundColor Green
    Write-Host "Token: $($riderToken.Substring(0, 20))..." -ForegroundColor Gray
}
catch {
    Write-Host "✗ Rider login failed: $($_.Exception.Message)" -ForegroundColor Red
    $riderToken = "demo_token"
}
Write-Host ("-" * 50)

# Test 5: Login as Driver1
$loginDriverBody = @{
    email = "driver1@test.com"
    password = "password"
} | ConvertTo-Json

Write-Host "Testing: Login as Driver1" -ForegroundColor Yellow
try {
    $driverLogin = Invoke-RestMethod -Uri "$baseUrl/api/auth/login" -Method "POST" -Headers @{
        "Content-Type" = "application/json"
        "Accept" = "application/json"
    } -Body $loginDriverBody
    
    $driverToken = $driverLogin.token
    Write-Host "✓ Driver login successful" -ForegroundColor Green
    Write-Host "Token: $($driverToken.Substring(0, 20))..." -ForegroundColor Gray
}
catch {
    Write-Host "✗ Driver login failed: $($_.Exception.Message)" -ForegroundColor Red
    $driverToken = "demo_token"
}
Write-Host ("-" * 50)

# Test 6: Get Current User (Rider)
Test-Endpoint -Name "Get Current User (Rider)" -Method "GET" -Url "$baseUrl/api/auth/user" -Headers @{
    "Authorization" = "Bearer $riderToken"
    "Accept" = "application/json"
}

# Test 7: Get Rider Profile
Test-Endpoint -Name "Get Rider Profile" -Method "GET" -Url "$baseUrl/api/rider/profile" -Headers @{
    "Authorization" = "Bearer $riderToken"
    "Accept" = "application/json"
}

# Test 8: Get Driver Profile
Test-Endpoint -Name "Get Driver Profile" -Method "GET" -Url "$baseUrl/api/driver/profile" -Headers @{
    "Authorization" = "Bearer $driverToken"
    "Accept" = "application/json"
}

# Test 9: Find Nearby Drivers
$nearbyDriversBody = @{
    latitude = 28.6139
    longitude = 77.2090
    radius = 5
} | ConvertTo-Json

Test-Endpoint -Name "Find Nearby Drivers" -Method "POST" -Url "$baseUrl/api/rider/nearby-drivers" -Headers @{
    "Authorization" = "Bearer $riderToken"
    "Content-Type" = "application/json"
    "Accept" = "application/json"
} -Body $nearbyDriversBody

# Test 10: Estimate Fare
$estimateFareBody = @{
    pickup_latitude = 28.6139
    pickup_longitude = 77.2090
    drop_latitude = 28.6239
    drop_longitude = 77.2190
    vehicle_type = "sedan"
} | ConvertTo-Json

Test-Endpoint -Name "Estimate Fare" -Method "POST" -Url "$baseUrl/api/rider/estimate-fare" -Headers @{
    "Authorization" = "Bearer $riderToken"
    "Content-Type" = "application/json"
    "Accept" = "application/json"
} -Body $estimateFareBody

# Test 11: Update Driver Status
$updateStatusBody = @{
    status = "online"
} | ConvertTo-Json

Test-Endpoint -Name "Update Driver Status" -Method "POST" -Url "$baseUrl/api/driver/status" -Headers @{
    "Authorization" = "Bearer $driverToken"
    "Content-Type" = "application/json"
    "Accept" = "application/json"
} -Body $updateStatusBody

# Test 12: Request a Ride
$requestRideBody = @{
    pickup_latitude = 28.6139
    pickup_longitude = 77.2090
    pickup_address = "123 Main St, City"
    drop_latitude = 28.6239
    drop_longitude = 77.2190
    drop_address = "456 Office Blvd, City"
    vehicle_type = "sedan"
    payment_method = "cash"
} | ConvertTo-Json

Test-Endpoint -Name "Request a Ride" -Method "POST" -Url "$baseUrl/api/ride/request" -Headers @{
    "Authorization" = "Bearer $riderToken"
    "Content-Type" = "application/json"
    "Accept" = "application/json"
} -Body $requestRideBody

# Test 13: Get Ride Status
Test-Endpoint -Name "Get Ride Status" -Method "GET" -Url "$baseUrl/api/ride/RIDE001/status" -Headers @{
    "Authorization" = "Bearer $riderToken"
    "Accept" = "application/json"
}

# Test 14: Admin Login Page
Test-Endpoint -Name "Admin Login Page" -Method "GET" -Url "$baseUrl/admin/login"

# Test 15: Main Welcome Page
Test-Endpoint -Name "Main Welcome Page" -Method "GET" -Url "$baseUrl/"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "       API Test Suite Completed!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Demo Credentials:" -ForegroundColor Cyan
Write-Host "Admin: admin1@taxi.com / admin123" -ForegroundColor White
Write-Host "Admin: admin2@taxi.com / 123" -ForegroundColor White
Write-Host "Riders: rider1@test.com, rider2@test.com, rider3@test.com / password" -ForegroundColor White
Write-Host "Drivers: driver1@test.com, driver2@test.com, driver3@test.com / password" -ForegroundColor White
Write-Host ""
Write-Host "Check API_TESTING.md for detailed curl commands" -ForegroundColor Yellow

Read-Host "Press Enter to exit"
