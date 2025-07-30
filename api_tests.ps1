# PowerShell API Test Script

# Base URL
$BASE_URL = "http://127.0.0.1:8000"

# Function to log messages
function Log-Message {
    param ([string]$Message)
    Write-Host "----------------------------------------"
    Write-Host $Message
    Write-Host "----------------------------------------"
}

# Common Headers
$headers = @{
    "Accept" = "application/json"
    "Content-Type" = "application/json"
}

# --- Start of Tests ---

Log-Message "Running API tests..."

# 1. Register New Users
Log-Message "Registering new temporary users..."

$newRiderBody = @{
    name = "NewRiderTest"
    email = "new-test-user@test.com"
    phone = "+3236549877"
    password = "password"
    password_confirmation = "password"
    user_type = "rider"
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/auth/register" -Method Post -Headers $headers -Body $newRiderBody

$newDriverBody = @{
    name = "NewDriverTest"
    email = "new-test-driver@test.com"
    phone = "+1234567803"
    password = "password"
    password_confirmation = "password"
    user_type = "driver"
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/auth/register" -Method Post -Headers $headers -Body $newDriverBody

# 2. Get Rider and Driver Tokens
Log-Message "Fetching Rider and Driver tokens..."

$riderLoginBody = @{
    email = "new-test-user@test.com"
    password = "password"
} | ConvertTo-Json

$riderLoginResponse = Invoke-RestMethod -Uri "$BASE_URL/api/auth/login" -Method Post -Headers $headers -Body $riderLoginBody
$RIDER_TOKEN = $riderLoginResponse.data.token

$driverLoginBody = @{
    email = "new-test-driver@test.com"
    password = "password"
} | ConvertTo-Json

$driverLoginResponse = Invoke-RestMethod -Uri "$BASE_URL/api/auth/login" -Method Post -Headers $headers -Body $driverLoginBody
$DRIVER_TOKEN = $driverLoginResponse.data.token

if ([string]::IsNullOrEmpty($RIDER_TOKEN) -or [string]::IsNullOrEmpty($DRIVER_TOKEN)) {
    Log-Message "Error: Could not get tokens. Exiting."
    exit 1
}

Log-Message "Rider Token: $RIDER_TOKEN"
Log-Message "Driver Token: $DRIVER_TOKEN"

# Auth Headers
$riderAuthHeaders = @{
    "Accept" = "application/json"
    "Content-Type" = "application/json"
    "Authorization" = "Bearer $RIDER_TOKEN"
}
$driverAuthHeaders = @{
    "Accept" = "application/json"
    "Content-Type" = "application/json"
    "Authorization" = "Bearer $DRIVER_TOKEN"
}


# 2. Authentication Endpoints
Log-Message "Testing Authentication Endpoints..."

Log-Message "Register New Rider"
$newRiderBody = @{
    name = "NewRiderTest"
    email = "newridertest@test.com"
    phone = "+3236549876"
    password = "password"
    password_confirmation = "password"
    user_type = "rider"
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/auth/register" -Method Post -Headers $headers -Body $newRiderBody

Log-Message "Register New Driver"
$newDriverBody = @{
    name = "NewDriverTest"
    email = "newdrivertest@test.com"
    phone = "+1234567802"
    password = "password"
    password_confirmation = "password"
    user_type = "driver"
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/auth/register" -Method Post -Headers $headers -Body $newDriverBody

Log-Message "Get Current User (Rider)"
Invoke-RestMethod -Uri "$BASE_URL/api/auth/user" -Method Get -Headers $riderAuthHeaders

# 3. Rider Endpoints
Log-Message "Testing Rider Endpoints..."

Log-Message "Get Rider Profile"
Invoke-RestMethod -Uri "$BASE_URL/api/rider/profile" -Method Get -Headers $riderAuthHeaders

Log-Message "Update Rider Profile"
$updateRiderProfileBody = @{
    home_address = "Updated Home Address Test"
    work_address = "Updated Work Address Test"
    emergency_contact = "+1234567998"
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/rider/profile" -Method Post -Headers $riderAuthHeaders -Body $updateRiderProfileBody

Log-Message "Find Nearby Drivers"
$nearbyDriversBody = @{
    latitude = 28.6139
    longitude = 77.2090
    radius = 10
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/rider/nearby-drivers" -Method Post -Headers $riderAuthHeaders -Body $nearbyDriversBody

Log-Message "Estimate Fare"
$estimateFareBody = @{
    pickup_latitude = 28.6139
    pickup_longitude = 77.2090
    drop_latitude = 28.6239
    drop_longitude = 77.2190
    vehicle_type = "sedan"
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/rider/estimate-fare" -Method Post -Headers $riderAuthHeaders -Body $estimateFareBody

# 4. Driver Endpoints
Log-Message "Testing Driver Endpoints..."

Log-Message "Get Driver Profile"
Invoke-RestMethod -Uri "$BASE_URL/api/driver/profile" -Method Get -Headers $driverAuthHeaders

Log-Message "Update Driver Profile"
$updateDriverProfileBody = @{
    vehicle_type = "suv"
    vehicle_number = "NEW456"
    vehicle_model = "Updated Model 2024"
    vehicle_color = "Red"
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/driver/profile" -Method Post -Headers $driverAuthHeaders -Body $updateDriverProfileBody

Log-Message "Update Driver Status"
$updateDriverStatusBody = @{
    status = "online"
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/driver/status" -Method Post -Headers $driverAuthHeaders -Body $updateDriverStatusBody

Log-Message "Update Driver Location"
$updateDriverLocationBody = @{
    latitude = 28.6155
    longitude = 77.2105
} | ConvertTo-Json
Invoke-RestMethod -Uri "$BASE_URL/api/driver/location" -Method Post -Headers $driverAuthHeaders -Body $updateDriverLocationBody

Log-Message "Get Driver Earnings"
Invoke-RestMethod -Uri "$BASE_URL/api/driver/earnings" -Method Get -Headers $driverAuthHeaders

Log-Message "Get Driver Ride History"
Invoke-RestMethod -Uri "$BASE_URL/api/driver/rides" -Method Get -Headers $driverAuthHeaders

# 5. Ride Endpoints
Log-Message "Testing Ride Endpoints..."

Log-Message "Request a Ride"
$requestRideBody = @{
    pickup_latitude = 28.6139
    pickup_longitude = 77.2090
    pickup_address = "123 Main St, Test"
    drop_latitude = 28.6239
    drop_longitude = 77.2190
    drop_address = "456 Office Blvd, Test"
    vehicle_type = "sedan"
    vehicle_type = "sedan"
    payment_method = "cash"
} | ConvertTo-Json
$rideRequestResponse = Invoke-RestMethod -Uri "$BASE_URL/api/ride/request" -Method Post -Headers $riderAuthHeaders -Body $requestRideBody
$RIDE_ID = $rideRequestResponse.data.ride_id

if ([string]::IsNullOrEmpty($RIDE_ID)) {
    Log-Message "Error: Could not get Ride ID. Some ride tests will be skipped."
} else {
    Log-Message "Ride ID: $RIDE_ID"

    Log-Message "Get Ride Status"
    Invoke-RestMethod -Uri "$BASE_URL/api/ride/$RIDE_ID/status" -Method Get -Headers $riderAuthHeaders

    Log-Message "Accept Ride"
    $acceptRideBody = @{ ride_id = $RIDE_ID } | ConvertTo-Json
    Invoke-RestMethod -Uri "$BASE_URL/api/driver/accept-ride" -Method Post -Headers $driverAuthHeaders -Body $acceptRideBody

    Log-Message "Update Ride Status (Arrived)"
    $updateRideStatusArrivedBody = @{ ride_id = $RIDE_ID; status = "arrived" } | ConvertTo-Json
    Invoke-RestMethod -Uri "$BASE_URL/api/driver/update-ride-status" -Method Post -Headers $driverAuthHeaders -Body $updateRideStatusArrivedBody

    Log-Message "Update Ride Status (Completed)"
    $updateRideStatusCompletedBody = @{ ride_id = $RIDE_ID; status = "completed" } | ConvertTo-Json
    Invoke-RestMethod -Uri "$BASE_URL/api/driver/update-ride-status" -Method Post -Headers $driverAuthHeaders -Body $updateRideStatusCompletedBody

    Log-Message "Rate Ride"
    $rateRideBody = @{ rating = 5; review = "Great test ride!" } | ConvertTo-Json
    Invoke-RestMethod -Uri "$BASE_URL/api/ride/$RIDE_ID/rate" -Method Post -Headers $riderAuthHeaders -Body $rateRideBody

    Log-Message "Cancel a different Ride"
    $cancelRideBody = @{
        pickup_latitude = 28.7139
        pickup_longitude = 77.3090
        pickup_address = "789 Another St, Test"
        drop_latitude = 28.7239
        drop_longitude = 77.3190
        drop_address = "101 New Blvd, Test"
        vehicle_type = "sedan"
        payment_method = "cash"
    } | ConvertTo-Json
    $cancelRideResponse = Invoke-RestMethod -Uri "$BASE_URL/api/ride/request" -Method Post -Headers $riderAuthHeaders -Body $cancelRideBody
    $CANCEL_RIDE_ID = $cancelRideResponse.data.ride_id

    if (-not [string]::IsNullOrEmpty($CANCEL_RIDE_ID)) {
        Log-Message "Cancelling Ride ID: $CANCEL_RIDE_ID"
        $cancelReasonBody = @{ reason = "Test cancellation" } | ConvertTo-Json
        Invoke-RestMethod -Uri "$BASE_URL/api/ride/$CANCEL_RIDE_ID/cancel" -Method Post -Headers $riderAuthHeaders -Body $cancelReasonBody
    }
}

Log-Message "Get Ride History (Rider)"
Invoke-RestMethod -Uri "$BASE_URL/api/ride/history" -Method Get -Headers $riderAuthHeaders

# 6. Public Endpoints
Log-Message "Testing Public Endpoints..."

Log-Message "Test API"
Invoke-RestMethod -Uri "$BASE_URL/test" -Method Get -Headers $headers

Log-Message "Get CSRF Cookie"
Invoke-RestMethod -Uri "$BASE_URL/sanctum/csrf-cookie" -Method Get -Headers $headers

# 7. Logout
Log-Message "Testing Logout..."

Log-Message "Logout Rider"
Invoke-RestMethod -Uri "$BASE_URL/api/auth/logout" -Method Post -Headers $riderAuthHeaders

Log-Message "Logout Driver"
Invoke-RestMethod -Uri "$BASE_URL/api/auth/logout" -Method Post -Headers $driverAuthHeaders

Log-Message "API tests finished."