$baseUrl = "http://127.0.0.1:8000"

function Test-Case {
    param ($Name)
    Write-Host "========================================="
    Write-Host "TEST: $Name"
    Write-Host "========================================="
}

# Common headers for API requests
$apiHeaders = @{
    "Accept" = "application/json"
    "Content-Type" = "application/json"
}

# 3. Login as Rider and get token
Test-Case "Login as Rider"
$riderLoginBody = @{
    email = "rider1@test.com"
    password = "password"
} | ConvertTo-Json
try {
    $riderLoginResponse = Invoke-RestMethod -Uri "$baseUrl/api/auth/login" -Method Post -Body $riderLoginBody -Headers $apiHeaders -ErrorAction Stop
    $riderToken = $riderLoginResponse.data.token
    Write-Host "RIDER_TOKEN: $riderToken"
} catch {
    Write-Error "Failed to login as Rider: $($_.Exception.Message)"
    exit 1
}

# 4. Login as Driver and get token
Test-Case "Login as Driver"
$driverLoginBody = @{
    email = "driver1@test.com"
    password = "password"
} | ConvertTo-Json
try {
    $driverLoginResponse = Invoke-RestMethod -Uri "$baseUrl/api/auth/login" -Method Post -Body $driverLoginBody -Headers $apiHeaders -ErrorAction Stop
    $driverToken = $driverLoginResponse.data.token
    Write-Host "DRIVER_TOKEN: $driverToken"
} catch {
    Write-Error "Failed to login as Driver: $($_.Exception.Message)"
    exit 1
}

# Helper function to merge headers
function Merge-Headers {
    param (
        [Hashtable]$baseHeaders,
        [Hashtable]$additionalHeaders
    )
    $merged = $baseHeaders.Clone()
    foreach ($key in $additionalHeaders.Keys) {
        $merged.$key = $additionalHeaders.$key
    }
    return $merged
}

# 5. Get Current User (Rider)
Test-Case "Get Current User (Rider)"
$riderAuthHeaders = @{ Authorization = "Bearer $riderToken" }
$mergedRiderHeaders = Merge-Headers -baseHeaders $apiHeaders -additionalHeaders $riderAuthHeaders
try {
    Invoke-RestMethod -Uri "$baseUrl/api/auth/user" -Method Get -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to get current Rider user: $($_.Exception.Message)"
}

# 6. Get Rider Profile
Test-Case "Get Rider Profile"
try {
    Invoke-RestMethod -Uri "$baseUrl/api/rider/profile" -Method Get -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to get Rider profile: $($_.Exception.Message)"
}

# 7. Update Rider Profile
Test-Case "Update Rider Profile"
$riderProfileBody = @{
    home_address = "Updated Home Address"
    work_address = "Updated Work Address"
    emergency_contact = "+1234567999"
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/rider/profile" -Method Post -Body $riderProfileBody -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to update Rider profile: $($_.Exception.Message)"
}

# 8. Find Nearby Drivers
Test-Case "Find Nearby Drivers"
$nearbyDriversBody = @{
    latitude = 28.6139
    longitude = 77.2090
    radius = 5
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/rider/nearby-drivers" -Method Post -Body $nearbyDriversBody -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to find nearby drivers: $($_.Exception.Message)"
}

# 9. Estimate Fare
Test-Case "Estimate Fare"
$estimateFareBody = @{
    pickup_latitude = 28.6139
    pickup_longitude = 77.2090
    drop_latitude = 28.6239
    drop_longitude = 77.2190
    vehicle_type = "sedan"
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/rider/estimate-fare" -Method Post -Body $estimateFareBody -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to estimate fare: $($_.Exception.Message)"
}

# 10. Get Driver Profile
Test-Case "Get Driver Profile"
$driverAuthHeaders = @{ Authorization = "Bearer $driverToken" }
$mergedDriverHeaders = Merge-Headers -baseHeaders $apiHeaders -additionalHeaders $driverAuthHeaders
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/profile" -Method Get -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to get Driver profile: $($_.Exception.Message)"
}

# 11. Update Driver Profile
Test-Case "Update Driver Profile"
$driverProfileBody = @{
    vehicle_type = "sedan"
    vehicle_number = "NEW123"
    vehicle_model = "Updated Model 2023"
    vehicle_color = "Blue"
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/profile" -Method Post -Body $driverProfileBody -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to update Driver profile: $($_.Exception.Message)"
}

# 12. Update Driver Status
Test-Case "Update Driver Status"
$driverStatusBody = @{
    status = "online"
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/status" -Method Post -Body $driverStatusBody -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to update Driver status: $($_.Exception.Message)"
}

# 13. Update Driver Location
Test-Case "Update Driver Location"
$driverLocationBody = @{
    latitude = 28.6150
    longitude = 77.2100
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/location" -Method Post -Body $driverLocationBody -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to update Driver location: $($_.Exception.Message)"
}

# 14. Get Driver Earnings
Test-Case "Get Driver Earnings"
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/earnings" -Method Get -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to get Driver earnings: $($_.Exception.Message)"
}

# 15. Get Driver Ride History
Test-Case "Get Driver Ride History"
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/rides" -Method Get -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to get Driver ride history: $($_.Exception.Message)"
}

# 16. Request a Ride
Test-Case "Request a Ride"
$rideRequestBody = @{
    pickup_latitude = 28.6139
    pickup_longitude = 77.2090
    pickup_address = "123 Main St, City"
    drop_latitude = 28.6239
    drop_longitude = 77.2190
    drop_address = "456 Office Blvd, City"
    vehicle_type = "sedan"
    payment_method = "cash"
} | ConvertTo-Json
try {
    $rideRequestResponse = Invoke-RestMethod -Uri "$baseUrl/api/ride/request" -Method Post -Body $rideRequestBody -Headers $mergedRiderHeaders -ErrorAction Stop
    $rideId = $rideRequestResponse.data.id
    Write-Host "RIDE_ID: $rideId"
} catch {
    Write-Error "Failed to request a ride: $($_.Exception.Message)"
    exit 1
}

# 17. Get Ride Status
Test-Case "Get Ride Status"
try {
    Invoke-RestMethod -Uri "$baseUrl/api/ride/$rideId/status" -Method Get -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to get Ride status: $($_.Exception.Message)"
}

# 18. Accept Ride
Test-Case "Accept Ride"
$acceptRideBody = @{
    ride_id = $rideId
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/accept-ride" -Method Post -Body $acceptRideBody -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to accept Ride: $($_.Exception.Message)"
}

# 19. Update Ride Status (Arrived)
Test-Case "Update Ride Status (Arrived)"
$updateRideStatusBody = @{
    ride_id = $rideId
    status = "arrived"
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/update-ride-status" -Method Post -Body $updateRideStatusBody -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to update Ride status (Arrived): $($_.Exception.Message)"
}

# 20. Update Ride Status (Started)
Test-Case "Update Ride Status (Started)"
$updateRideStatusBody = @{
    ride_id = $rideId
    status = "started"
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/update-ride-status" -Method Post -Body $updateRideStatusBody -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to update Ride status (Started): $($_.Exception.Message)"
}

# 21. Update Ride Status (Completed)
Test-Case "Update Ride Status (Completed)"
$updateRideStatusBody = @{
    ride_id = $rideId
    status = "completed"
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/driver/update-ride-status" -Method Post -Body $updateRideStatusBody -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to update Ride status (Completed): $($_.Exception.Message)"
}

# 22. Rate Ride
Test-Case "Rate Ride"
$rateRideBody = @{
    rating = 5
    review = "Excellent ride!"
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/ride/$rideId/rate" -Method Post -Body $rateRideBody -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to rate Ride: $($_.Exception.Message)"
}

# 23. Get Ride History (Rider)
Test-Case "Get Ride History (Rider)"
try {
    Invoke-RestMethod -Uri "$baseUrl/api/ride/history" -Method Get -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to get Rider ride history: $($_.Exception.Message)"
}

# 24. Get Ride History (Driver)
Test-Case "Get Ride History (Driver)"
try {
    Invoke-RestMethod -Uri "$baseUrl/api/ride/history" -Method Get -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to get Driver ride history: $($_.Exception.Message)"
}

# 25. Cancel Ride
Test-Case "Cancel Ride"
# First, create a new ride to cancel
$rideRequestBody = @{
    pickup_latitude = 28.6139
    pickup_longitude = 77.2090
    pickup_address = "123 Main St, City"
    drop_latitude = 28.6239
    drop_longitude = 77.2190
    drop_address = "456 Office Blvd, City"
    vehicle_type = "sedan"
    payment_method = "cash"
} | ConvertTo-Json
try {
    $cancelRideResponse = Invoke-RestMethod -Uri "$baseUrl/api/ride/request" -Method Post -Body $rideRequestBody -Headers $mergedRiderHeaders -ErrorAction Stop
    $cancelRideId = $cancelRideResponse.data.id
    Write-Host "RIDE_TO_CANCEL_ID: $cancelRideId"
} catch {
    Write-Error "Failed to request a ride for cancellation: $($_.Exception.Message)"
    exit 1
}

$cancelRideBody = @{
    reason = "Changed plans"
} | ConvertTo-Json
try {
    Invoke-RestMethod -Uri "$baseUrl/api/ride/$cancelRideId/cancel" -Method Post -Body $cancelRideBody -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to cancel Ride: $($_.Exception.Message)"
}

# 26. Logout Rider
Test-Case "Logout Rider"
try {
    Invoke-RestMethod -Uri "$baseUrl/api/auth/logout" -Method Post -Headers $mergedRiderHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to logout Rider: $($_.Exception.Message)"
}

# 27. Logout Driver
Test-Case "Logout Driver"
try {
    Invoke-RestMethod -Uri "$baseUrl/api/auth/logout" -Method Post -Headers $mergedDriverHeaders -ErrorAction Stop
} catch {
    Write-Error "Failed to logout Driver: $($_.Exception.Message)"
}