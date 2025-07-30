#!/bin/bash

# Base URL
BASE_URL="http://127.0.0.1:8000"

# Function to log messages
log() {
    echo "----------------------------------------"
    echo "$1"
    echo "----------------------------------------"
}

# --- Start of Tests ---

log "Running API tests..."

# 1. Get Rider and Driver Tokens
log "Fetching Rider and Driver tokens..."
RIDER_LOGIN_RESPONSE=$(curl -s -X POST $BASE_URL/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "rider1@test.com",
    "password": "password"
  }')
RIDER_TOKEN=$(echo $RIDER_LOGIN_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)

DRIVER_LOGIN_RESPONSE=$(curl -s -X POST $BASE_URL/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "driver1@test.com",
    "password": "password"
  }')
DRIVER_TOKEN=$(echo $DRIVER_LOGIN_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)

if [ -z "$RIDER_TOKEN" ] || [ -z "$DRIVER_TOKEN" ]; then
    log "Error: Could not get tokens. Exiting."
    exit 1
fi

log "Rider Token: $RIDER_TOKEN"
log "Driver Token: $DRIVER_TOKEN"

# 2. Authentication Endpoints
log "Testing Authentication Endpoints..."

log "Register New Rider"
curl -X POST $BASE_URL/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "NewRiderTest",
    "email": "newridertest@test.com",
    "phone": "+3236549876",
    "password": "password",
    "password_confirmation": "password",
    "user_type": "rider"
  }'
echo ""

log "Register New Driver"
curl -X POST $BASE_URL/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "NewDriverTest",
    "email": "newdrivertest@test.com",
    "phone": "+1234567802",
    "password": "password",
    "password_confirmation": "password",
    "user_type": "driver"
  }'
echo ""

log "Get Current User (Rider)"
curl -X GET $BASE_URL/api/auth/user \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 3. Rider Endpoints
log "Testing Rider Endpoints..."

log "Get Rider Profile"
curl -X GET $BASE_URL/api/rider/profile \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Accept: application/json"
echo ""

log "Update Rider Profile"
curl -X POST $BASE_URL/api/rider/profile \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "home_address": "Updated Home Address Test",
    "work_address": "Updated Work Address Test",
    "emergency_contact": "+1234567998"
  }'
echo ""

log "Find Nearby Drivers"
curl -X POST $BASE_URL/api/rider/nearby-drivers \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 28.6139,
    "longitude": 77.2090,
    "radius": 10
  }'
echo ""

log "Estimate Fare"
curl -X POST $BASE_URL/api/rider/estimate-fare \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 28.6139,
    "pickup_longitude": 77.2090,
    "drop_latitude": 28.6239,
    "drop_longitude": 77.2190,
    "vehicle_type": "sedan"
  }'
echo ""

# 4. Driver Endpoints
log "Testing Driver Endpoints..."

log "Get Driver Profile"
curl -X GET $BASE_URL/api/driver/profile \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Accept: application/json"
echo ""

log "Update Driver Profile"
curl -X POST $BASE_URL/api/driver/profile \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "vehicle_type": "suv",
    "vehicle_number": "NEW456",
    "vehicle_model": "Updated Model 2024",
    "vehicle_color": "Red"
  }'
echo ""

log "Update Driver Status"
curl -X POST $BASE_URL/api/driver/status \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online"
  }'
echo ""

log "Update Driver Location"
curl -X POST $BASE_URL/api/driver/location \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 28.6155,
    "longitude": 77.2105
  }'
echo ""

log "Get Driver Earnings"
curl -X GET $BASE_URL/api/driver/earnings \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Accept: application/json"
echo ""

log "Get Driver Ride History"
curl -X GET $BASE_URL/api/driver/rides \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 5. Ride Endpoints
log "Testing Ride Endpoints..."

log "Request a Ride"
RIDE_REQUEST_RESPONSE=$(curl -s -X POST $BASE_URL/api/ride/request \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 28.6139,
    "pickup_longitude": 77.2090,
    "pickup_address": "123 Main St, Test",
    "drop_latitude": 28.6239,
    "drop_longitude": 77.2190,
    "drop_address": "456 Office Blvd, Test",
    "vehicle_type": "sedan",
    "payment_method": "cash"
  }')
RIDE_ID=$(echo $RIDE_REQUEST_RESPONSE | grep -o '"booking_id":"[^"]*' | cut -d'"' -f4)
echo $RIDE_REQUEST_RESPONSE
echo ""

if [ -z "$RIDE_ID" ]; then
    log "Error: Could not get Ride ID. Some ride tests will be skipped."
else
    log "Ride ID: $RIDE_ID"

    log "Get Ride Status"
    curl -X GET $BASE_URL/api/ride/$RIDE_ID/status \
      -H "Authorization: Bearer $RIDER_TOKEN" \
      -H "Accept: application/json"
echo ""

    log "Accept Ride"
    curl -X POST $BASE_URL/api/driver/accept-ride \
      -H "Authorization: Bearer $DRIVER_TOKEN" \
      -H "Content-Type: application/json" \
      -H "Accept: application/json" \
      -d '{
        "ride_id": "'$RIDE_ID'"
      }'
echo ""

    log "Update Ride Status (Arrived)"
    curl -X POST $BASE_URL/api/driver/update-ride-status \
      -H "Authorization: Bearer $DRIVER_TOKEN" \
      -H "Content-Type: application/json" \
      -H "Accept: application/json" \
      -d '{
        "ride_id": "'$RIDE_ID'",
        "status": "arrived"
      }'
echo ""
    
    log "Update Ride Status (Completed)"
    curl -X POST $BASE_URL/api/driver/update-ride-status \
      -H "Authorization: Bearer $DRIVER_TOKEN" \
      -H "Content-Type: application/json" \
      -H "Accept: application/json" \
      -d '{
        "ride_id": "'$RIDE_ID'",
        "status": "completed"
      }'
echo ""

    log "Rate Ride"
    curl -X POST $BASE_URL/api/ride/$RIDE_ID/rate \
      -H "Authorization: Bearer $RIDER_TOKEN" \
      -H "Content-Type: application/json" \
      -H "Accept: application/json" \
      -d '{
        "rating": 5,
        "review": "Great test ride!"
      }'
echo ""

    log "Cancel a different Ride"
    CANCEL_RIDE_RESPONSE=$(curl -s -X POST $BASE_URL/api/ride/request \
      -H "Authorization: Bearer $RIDER_TOKEN" \
      -H "Content-Type: application/json" \
      -H "Accept: application/json" \
      -d '{
        "pickup_latitude": 28.7139,
        "pickup_longitude": 77.3090,
        "pickup_address": "789 Another St, Test",
        "drop_latitude": 28.7239,
        "drop_longitude": 77.3190,
        "drop_address": "101 New Blvd, Test",
        "vehicle_type": "sedan",
        "payment_method": "cash"
      }')
    CANCEL_RIDE_ID=$(echo $CANCEL_RIDE_RESPONSE | grep -o '"booking_id":"[^"]*' | cut -d'"' -f4)
    
    if [ ! -z "$CANCEL_RIDE_ID" ]; then
        log "Cancelling Ride ID: $CANCEL_RIDE_ID"
        curl -X POST $BASE_URL/api/ride/$CANCEL_RIDE_ID/cancel \
          -H "Authorization: Bearer $RIDER_TOKEN" \
          -H "Content-Type: application/json" \
          -H "Accept: application/json" \
          -d '{
            "reason": "Test cancellation"
          }'
echo ""
    fi
fi

log "Get Ride History (Rider)"
curl -X GET $BASE_URL/api/ride/history \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 6. Public Endpoints
log "Testing Public Endpoints..."

log "Test API"
curl -X GET $BASE_URL/test \
  -H "Accept: application/json"
echo ""

log "Get CSRF Cookie"
curl -X GET $BASE_URL/sanctum/csrf-cookie \
  -H "Accept: application/json"
echo ""

# 7. Logout
log "Testing Logout..."

log "Logout Rider"
curl -X POST $BASE_URL/api/auth/logout \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Accept: application/json"
echo ""

log "Logout Driver"
curl -X POST $BASE_URL/api/auth/logout \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Accept: application/json"
echo ""


log "API tests finished."
