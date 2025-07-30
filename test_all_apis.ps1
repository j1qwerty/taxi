# Taxi API Complete Testing Script - PowerShell
# This script tests all API endpoints with both email/password and token authentication

param(
    [string]$BaseUrl = "http://127.0.0.1:8000/api",
    [string]$AdminUrl = "http://127.0.0.1:8000"
)

# Colors for output
$Green = @{ForegroundColor = "Green"}
$Red = @{ForegroundColor = "Red"}
$Yellow = @{ForegroundColor = "Yellow"}
$Cyan = @{ForegroundColor = "Cyan"}
$Blue = @{ForegroundColor = "Blue"}

# Global variables for tokens
$Global:RiderToken = ""
$Global:DriverToken = ""
$Global:RideId = ""

function Write-Header {
    param([string]$Text)
    Write-Host "`n$('=' * 80)" @Cyan
    Write-Host $Text @Cyan
    Write-Host "$('=' * 80)" @Cyan
}

function Write-Step {
    param([string]$Text)
    Write-Host "`n$Text" @Yellow
}

function Write-Success {
    param([string]$Text)
    Write-Host "✅ $Text" @Green
}

function Write-Error {
    param([string]$Text)
    Write-Host "❌ $Text" @Red
}

function Test-ApiEndpoint {
    param(
        [string]$Url,
        [string]$Method = "GET",
        [hashtable]$Headers = @{},
        [string]$Body = $null,
        [string]$Description
    )
    
    try {
        Write-Host "Testing: $Description" @Blue
        Write-Host "URL: $Method $Url"
        
        $requestParams = @{
            Uri = $Url
            Method = $Method
            Headers = $Headers
            ContentType = "application/json"
        }
        
        if ($Body) {
            $requestParams.Body = $Body
            Write-Host "Body: $Body"
        }
        
        $response = Invoke-RestMethod @requestParams
        Write-Success "Success: $Description"
        
        # Pretty print JSON response
        $jsonOutput = $response | ConvertTo-Json -Depth 5
        Write-Host $jsonOutput
        
        return $response
    }
    catch {
        Write-Error "Failed: $Description"
        Write-Host "Error: $($_.Exception.Message)" @Red
        if ($_.ErrorDetails) {
            Write-Host "Details: $($_.ErrorDetails.Message)" @Red
        }
        return $null
    }
}

# Test credentials
$TestCredentials = @{
    RiderEmail = "test.rider@example.com"
    DriverEmail = "test.driver@example.com" 
    Password = "password123"
    AdminEmail = "admin@taxi.com"
    AdminPassword = "admin123"
}

Write-Header "TAXI API COMPREHENSIVE TESTING SCRIPT"
Write-Host "Testing all API endpoints with authentication" @Cyan
Write-Host "Base URL: $BaseUrl" @Blue

# 1. TEST PUBLIC ENDPOINTS
Write-Header "1. TESTING PUBLIC ENDPOINTS"

Write-Step "1.1 Testing API Health Check"
Test-ApiEndpoint -Url "$BaseUrl/test" -Description "API Health Check"

# 2. USER REGISTRATION
Write-Header "2. USER REGISTRATION TESTS"

Write-Step "2.1 Register Rider"
$riderRegisterBody = @{
    name = "Test Rider"
    email = $TestCredentials.RiderEmail
    password = $TestCredentials.Password
    password_confirmation = $TestCredentials.Password
    phone = "+1234567890"
    user_type = "rider"
} | ConvertTo-Json

$riderRegResponse = Test-ApiEndpoint -Url "$BaseUrl/auth/register" -Method "POST" -Body $riderRegisterBody -Description "Register Rider"

Write-Step "2.2 Register Driver with Vehicle Type"
$driverRegisterBody = @{
    name = "Test Driver"
    email = $TestCredentials.DriverEmail
    password = $TestCredentials.Password
    password_confirmation = $TestCredentials.Password
    phone = "+1234567891"
    user_type = "driver"
    license_number = "DL123456789"
    vehicle_number = "KA01AB1234"
    vehicle_type = "sedan"
    vehicle_model = "Toyota Camry 2023"
    vehicle_color = "Black"
} | ConvertTo-Json

$driverRegResponse = Test-ApiEndpoint -Url "$BaseUrl/auth/register" -Method "POST" -Body $driverRegisterBody -Description "Register Driver with Vehicle Details"

# 3. AUTHENTICATION TESTS
Write-Header "3. AUTHENTICATION TESTS"

Write-Step "3.1 Login as Rider (Email/Password)"
$riderLoginBody = @{
    email = $TestCredentials.RiderEmail
    password = $TestCredentials.Password
} | ConvertTo-Json

$riderLoginResponse = Test-ApiEndpoint -Url "$BaseUrl/auth/login" -Method "POST" -Body $riderLoginBody -Description "Rider Login"

if ($riderLoginResponse -and $riderLoginResponse.data.token) {
    $Global:RiderToken = $riderLoginResponse.data.token
    Write-Success "Rider Token Retrieved: $($Global:RiderToken.Substring(0,50))..."
} else {
    Write-Error "Failed to get rider token"
}

Write-Step "3.2 Login as Driver (Email/Password)"
$driverLoginBody = @{
    email = $TestCredentials.DriverEmail
    password = $TestCredentials.Password
} | ConvertTo-Json

$driverLoginResponse = Test-ApiEndpoint -Url "$BaseUrl/auth/login" -Method "POST" -Body $driverLoginBody -Description "Driver Login"

if ($driverLoginResponse -and $driverLoginResponse.data.token) {
    $Global:DriverToken = $driverLoginResponse.data.token
    Write-Success "Driver Token Retrieved: $($Global:DriverToken.Substring(0,50))..."
} else {
    Write-Error "Failed to get driver token"
}

# 4. AUTHENTICATED USER PROFILE TESTS
Write-Header "4. USER PROFILE TESTS (Using Tokens)"

if ($Global:RiderToken) {
    Write-Step "4.1 Get Rider Profile"
    $riderHeaders = @{
        "Authorization" = "Bearer $Global:RiderToken"
        "Accept" = "application/json"
    }
    Test-ApiEndpoint -Url "$BaseUrl/auth/user" -Headers $riderHeaders -Description "Get Rider Profile"
}

if ($Global:DriverToken) {
    Write-Step "4.2 Get Driver Profile" 
    $driverHeaders = @{
        "Authorization" = "Bearer $Global:DriverToken"
        "Accept" = "application/json"
    }
    Test-ApiEndpoint -Url "$BaseUrl/auth/user" -Headers $driverHeaders -Description "Get Driver Profile"
}

# 5. RIDER-SPECIFIC TESTS
Write-Header "5. RIDER API TESTS"

if ($Global:RiderToken) {
    $riderHeaders = @{
        "Authorization" = "Bearer $Global:RiderToken"
        "Accept" = "application/json"
    }
    
    Write-Step "5.1 Get Rider Profile Details"
    Test-ApiEndpoint -Url "$BaseUrl/rider/profile" -Headers $riderHeaders -Description "Get Rider Profile Details"
    
    Write-Step "5.2 Update Rider Profile"
    $updateRiderBody = @{
        home_address = "123 Home Street, City"
        work_address = "456 Work Avenue, City"
        emergency_contact = "+9876543210"
    } | ConvertTo-Json
    
    Test-ApiEndpoint -Url "$BaseUrl/rider/profile" -Method "POST" -Headers $riderHeaders -Body $updateRiderBody -Description "Update Rider Profile"
    
    Write-Step "5.3 Find Nearby Drivers"
    $nearbyDriversBody = @{
        latitude = 12.9716
        longitude = 77.5946
        radius = 5
    } | ConvertTo-Json
    
    Test-ApiEndpoint -Url "$BaseUrl/rider/nearby-drivers" -Method "POST" -Headers $riderHeaders -Body $nearbyDriversBody -Description "Find Nearby Drivers"
    
    Write-Step "5.4 Estimate Fare"
    $estimateBody = @{
        pickup_latitude = 12.9716
        pickup_longitude = 77.5946
        drop_latitude = 12.9352
        drop_longitude = 77.6245
        vehicle_type = "sedan"
    } | ConvertTo-Json
    
    Test-ApiEndpoint -Url "$BaseUrl/rider/estimate-fare" -Method "POST" -Headers $riderHeaders -Body $estimateBody -Description "Estimate Ride Fare"
}

# 6. DRIVER-SPECIFIC TESTS  
Write-Header "6. DRIVER API TESTS"

if ($Global:DriverToken) {
    $driverHeaders = @{
        "Authorization" = "Bearer $Global:DriverToken"
        "Accept" = "application/json"
    }
    
    Write-Step "6.1 Get Driver Profile Details"
    Test-ApiEndpoint -Url "$BaseUrl/driver/profile" -Headers $driverHeaders -Description "Get Driver Profile Details"
    
    Write-Step "6.2 Update Driver Profile"
    $updateDriverBody = @{
        vehicle_type = "suv"
        vehicle_number = "KA01XY9999"
        vehicle_model = "Honda CR-V 2023"
        vehicle_color = "White"
    } | ConvertTo-Json
    
    Test-ApiEndpoint -Url "$BaseUrl/driver/profile" -Method "POST" -Headers $driverHeaders -Body $updateDriverBody -Description "Update Driver Profile"
    
    Write-Step "6.3 Update Driver Status to Online"
    $statusBody = @{
        status = "online"
        latitude = 12.9716
        longitude = 77.5946
    } | ConvertTo-Json
    
    Test-ApiEndpoint -Url "$BaseUrl/driver/status" -Method "POST" -Headers $driverHeaders -Body $statusBody -Description "Update Driver Status"
    
    Write-Step "6.4 Update Driver Location"
    $locationBody = @{
        latitude = 12.9750
        longitude = 77.5980
    } | ConvertTo-Json
    
    Test-ApiEndpoint -Url "$BaseUrl/driver/location" -Method "POST" -Headers $driverHeaders -Body $locationBody -Description "Update Driver Location"
    
    Write-Step "6.5 Get Driver Earnings"
    Test-ApiEndpoint -Url "$BaseUrl/driver/earnings" -Headers $driverHeaders -Description "Get Driver Earnings"
    
    Write-Step "6.6 Get Driver Ride History"
    Test-ApiEndpoint -Url "$BaseUrl/driver/rides" -Headers $driverHeaders -Description "Get Driver Ride History"
}

# 7. RIDE MANAGEMENT TESTS
Write-Header "7. RIDE MANAGEMENT TESTS"

if ($Global:RiderToken) {
    $riderHeaders = @{
        "Authorization" = "Bearer $Global:RiderToken"
        "Accept" = "application/json"
    }
    
    Write-Step "7.1 Request a Ride"
    $rideRequestBody = @{
        pickup_latitude = 12.9716
        pickup_longitude = 77.5946
        pickup_address = "MG Road, Bangalore"
        drop_latitude = 12.9352
        drop_longitude = 77.6245
        drop_address = "Koramangala, Bangalore"
        vehicle_type = "sedan"
        payment_method = "cash"
    } | ConvertTo-Json
    
    $rideResponse = Test-ApiEndpoint -Url "$BaseUrl/ride/request" -Method "POST" -Headers $riderHeaders -Body $rideRequestBody -Description "Request a Ride"
    
    if ($rideResponse -and $rideResponse.data.id) {
        $Global:RideId = $rideResponse.data.id
        Write-Success "Ride Created with ID: $Global:RideId"
        
        Write-Step "7.2 Get Ride Status"
        Test-ApiEndpoint -Url "$BaseUrl/ride/$Global:RideId/status" -Headers $riderHeaders -Description "Get Ride Status"
        
        # Test driver accepting the ride
        if ($Global:DriverToken) {
            $driverHeaders = @{
                "Authorization" = "Bearer $Global:DriverToken"
                "Accept" = "application/json"
            }
            
            Write-Step "7.3 Driver Accept Ride"
            $acceptRideBody = @{
                ride_id = $Global:RideId
            } | ConvertTo-Json
            
            Test-ApiEndpoint -Url "$BaseUrl/driver/accept-ride" -Method "POST" -Headers $driverHeaders -Body $acceptRideBody -Description "Driver Accept Ride"
            
            Write-Step "7.4 Update Ride Status (Driver Arrived)"
            $updateRideBody = @{
                ride_id = $Global:RideId
                status = "arrived"
            } | ConvertTo-Json
            
            Test-ApiEndpoint -Url "$BaseUrl/driver/update-ride-status" -Method "POST" -Headers $driverHeaders -Body $updateRideBody -Description "Update Ride Status - Driver Arrived"
        }
        
        Write-Step "7.5 Rate Ride"
        $rateRideBody = @{
            rating = 5
            review = "Excellent service! Great driver and clean vehicle."
        } | ConvertTo-Json
        
        Test-ApiEndpoint -Url "$BaseUrl/ride/$Global:RideId/rate" -Method "POST" -Headers $riderHeaders -Body $rateRideBody -Description "Rate Ride"
        
        Write-Step "7.6 Cancel Ride (Test)"
        $cancelBody = @{
            reason = "Testing cancellation functionality"
        } | ConvertTo-Json
        
        Test-ApiEndpoint -Url "$BaseUrl/ride/$Global:RideId/cancel" -Method "POST" -Headers $riderHeaders -Body $cancelBody -Description "Cancel Ride"
    }
    
    Write-Step "7.7 Get Ride History"
    Test-ApiEndpoint -Url "$BaseUrl/ride/history" -Headers $riderHeaders -Description "Get Ride History"
}

# 8. LOGOUT TESTS
Write-Header "8. LOGOUT TESTS"

if ($Global:RiderToken) {
    Write-Step "8.1 Logout Rider"
    $riderHeaders = @{
        "Authorization" = "Bearer $Global:RiderToken"
        "Accept" = "application/json"
    }
    Test-ApiEndpoint -Url "$BaseUrl/auth/logout" -Method "POST" -Headers $riderHeaders -Description "Logout Rider"
}

if ($Global:DriverToken) {
    Write-Step "8.2 Logout Driver"
    $driverHeaders = @{
        "Authorization" = "Bearer $Global:DriverToken"
        "Accept" = "application/json"
    }
    Test-ApiEndpoint -Url "$BaseUrl/auth/logout" -Method "POST" -Headers $driverHeaders -Description "Logout Driver"
}

# 9. ERROR HANDLING TESTS
Write-Header "9. ERROR HANDLING TESTS"

Write-Step "9.1 Test Invalid Login"
$invalidLoginBody = @{
    email = "invalid@test.com"
    password = "wrongpassword"
} | ConvertTo-Json

Test-ApiEndpoint -Url "$BaseUrl/auth/login" -Method "POST" -Body $invalidLoginBody -Description "Invalid Login Test"

Write-Step "9.2 Test Unauthorized Request"
Test-ApiEndpoint -Url "$BaseUrl/rider/profile" -Description "Unauthorized Profile Access"

Write-Step "9.3 Test Invalid Token"
$invalidHeaders = @{
    "Authorization" = "Bearer invalid-token-here"
    "Accept" = "application/json"
}
Test-ApiEndpoint -Url "$BaseUrl/rider/profile" -Headers $invalidHeaders -Description "Invalid Token Test"

# 10. SUMMARY
Write-Header "10. TEST SUMMARY"

Write-Host "`n📊 TEST EXECUTION COMPLETED" @Cyan
Write-Host "Base URL: $BaseUrl" @Blue
Write-Host "Admin URL: $AdminUrl" @Blue

Write-Host "`n📋 Test Categories Covered:" @Yellow
Write-Host "✅ Public endpoints (health check)" @Green
Write-Host "✅ User registration (rider & driver with vehicle type)" @Green
Write-Host "✅ Authentication (email/password login)" @Green
Write-Host "✅ Token-based API access" @Green
Write-Host "✅ Rider profile management" @Green
Write-Host "✅ Driver profile management (with vehicle type)" @Green
Write-Host "✅ Ride request & management" @Green
Write-Host "✅ Driver status & location updates" @Green
Write-Host "✅ Fare estimation & nearby drivers" @Green
Write-Host "✅ Rating & review system" @Green
Write-Host "✅ Error handling & security" @Green
Write-Host "✅ Session management (logout)" @Green

Write-Host "`n🔑 Tokens Generated:" @Yellow
if ($Global:RiderToken) {
    Write-Host "Rider Token: $($Global:RiderToken.Substring(0,50))..." @Green
} else {
    Write-Host "Rider Token: Not generated" @Red
}

if ($Global:DriverToken) {
    Write-Host "Driver Token: $($Global:DriverToken.Substring(0,50))..." @Green
} else {
    Write-Host "Driver Token: Not generated" @Red
}

Write-Host "`n🎯 Next Steps:" @Yellow
Write-Host "1. Review any failed tests above" @Blue
Write-Host "2. Use generated tokens for manual testing" @Blue
Write-Host "3. Test admin panel at: $AdminUrl/admin/login" @Blue
Write-Host "4. Run: php artisan serve (if not already running)" @Blue

Write-Host "`n$('=' * 80)" @Cyan
Write-Host "API TESTING SCRIPT COMPLETED SUCCESSFULLY!" @Cyan
Write-Host "$('=' * 80)" @Cyan
