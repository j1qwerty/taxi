#!/bin/bash

# Base URL
BASE_URL="http://127.0.0.1:8000"

# Function to display test case name
test_case() {
    echo "========================================="
    echo "TEST: $1"
    echo "========================================="
}

# 1. Register Rider
test_case "Register Rider"
curl -X POST $BASE_URL/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "NewRider",
    "email": "newrider@test.com",
    "phone": "+3236549875",
    "password": "password",
    "password_confirmation": "password",
    "user_type": "rider"
  }'
echo ""

# 2. Register Driver
test_case "Register Driver"
curl -X POST $BASE_URL/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "NewDriver",
    "email": "newdriver@test.com",
    "phone": "+1234567801",
    "password": "password",
    "password_confirmation": "password",
    "user_type": "driver"
  }'
echo ""

# 3. Login as Rider and get token
test_case "Login as Rider"
RIDER_LOGIN_RESPONSE=$(curl -s -X POST $BASE_URL/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "rider1@test.com",
    "password": "password"
  }')
RIDER_TOKEN=$(echo $RIDER_LOGIN_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)
echo "RIDER_TOKEN: $RIDER_TOKEN"
echo ""

# 4. Login as Driver and get token
test_case "Login as Driver"
DRIVER_LOGIN_RESPONSE=$(curl -s -X POST $BASE_URL/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "driver1@test.com",
    "password": "password"
  }')
DRIVER_TOKEN=$(echo $DRIVER_LOGIN_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)
echo "DRIVER_TOKEN: $DRIVER_TOKEN"
echo ""

# 5. Get Current User (Rider)
test_case "Get Current User (Rider)"
curl -X GET $BASE_URL/api/auth/user \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 6. Get Rider Profile
test_case "Get Rider Profile"
curl -X GET $BASE_URL/api/rider/profile \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 7. Update Rider Profile
test_case "Update Rider Profile"
curl -X POST $BASE_URL/api/rider/profile \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "home_address": "Updated Home Address",
    "work_address": "Updated Work Address",
    "emergency_contact": "+1234567999"
  }'
echo ""

# 8. Find Nearby Drivers
test_case "Find Nearby Drivers"
curl -X POST $BASE_URL/api/rider/nearby-drivers \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 28.6139,
    "longitude": 77.2090,
    "radius": 5
  }'
echo ""

# 9. Estimate Fare
test_case "Estimate Fare"
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

# 10. Get Driver Profile
test_case "Get Driver Profile"
curl -X GET $BASE_URL/api/driver/profile \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 11. Update Driver Profile
test_case "Update Driver Profile"
curl -X POST $BASE_URL/api/driver/profile \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "vehicle_type": "sedan",
    "vehicle_number": "NEW123",
    "vehicle_model": "Updated Model 2023",
    "vehicle_color": "Blue"
  }'
echo ""

# 12. Update Driver Status
test_case "Update Driver Status"
curl -X POST $BASE_URL/api/driver/status \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online"
  }'
echo ""

# 13. Update Driver Location
test_case "Update Driver Location"
curl -X POST $BASE_URL/api/driver/location \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 28.6150,
    "longitude": 77.2100
  }'
echo ""

# 14. Get Driver Earnings
test_case "Get Driver Earnings"
curl -X GET $BASE_URL/api/driver/earnings \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 15. Get Driver Ride History
test_case "Get Driver Ride History"
curl -X GET $BASE_URL/api/driver/rides \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 16. Request a Ride
test_case "Request a Ride"
RIDE_REQUEST_RESPONSE=$(curl -s -X POST $BASE_URL/api/ride/request \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 28.6139,
    "pickup_longitude": 77.2090,
    "pickup_address": "123 Main St, City",
    "drop_latitude": 28.6239,
    "drop_longitude": 77.2190,
    "drop_address": "456 Office Blvd, City",
    "vehicle_type": "sedan",
    "payment_method": "cash"
  }')
RIDE_ID=$(echo $RIDE_REQUEST_RESPONSE | grep -o '"id":"[^"]*' | cut -d'"' -f4)
echo "RIDE_ID: $RIDE_ID"
echo ""

# 17. Get Ride Status
test_case "Get Ride Status"
curl -X GET $BASE_URL/api/ride/$RIDE_ID/status \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 18. Accept Ride
test_case "Accept Ride"
curl -X POST $BASE_URL/api/driver/accept-ride \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"ride_id\": \"$RIDE_ID\"
  }"
echo ""

# 19. Update Ride Status (Arrived)
test_case "Update Ride Status (Arrived)"
curl -X POST $BASE_URL/api/driver/update-ride-status \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"ride_id\": \"$RIDE_ID\",
    \"status\": \"arrived\"
  }"
echo ""

# 20. Update Ride Status (Started)
test_case "Update Ride Status (Started)"
curl -X POST $BASE_URL/api/driver/update-ride-status \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"ride_id\": \"$RIDE_ID\",
    \"status\": \"started\"
  }"
echo ""

# 21. Update Ride Status (Completed)
test_case "Update Ride Status (Completed)"
curl -X POST $BASE_URL/api/driver/update-ride-status \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"ride_id\": \"$RIDE_ID\",
    \"status\": \"completed\"
  }"
echo ""

# 22. Rate Ride
test_case "Rate Ride"
curl -X POST $BASE_URL/api/ride/$RIDE_ID/rate \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "rating": 5,
    "review": "Excellent ride!"
  }'
echo ""

# 23. Get Ride History (Rider)
test_case "Get Ride History (Rider)"
curl -X GET $BASE_URL/api/ride/history \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 24. Get Ride History (Driver)
test_case "Get Ride History (Driver)"
curl -X GET $BASE_URL/api/ride/history \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 25. Cancel Ride
test_case "Cancel Ride"
# First, create a new ride to cancel
CANCEL_RIDE_RESPONSE=$(curl -s -X POST $BASE_URL/api/ride/request \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 28.6139,
    "pickup_longitude": 77.2090,
    "pickup_address": "123 Main St, City",
    "drop_latitude": 28.6239,
    "drop_longitude": 77.2190,
    "drop_address": "456 Office Blvd, City",
    "vehicle_type": "sedan",
    "payment_method": "cash"
  }')
CANCEL_RIDE_ID=$(echo $CANCEL_RIDE_RESPONSE | grep -o '"id":"[^"]*' | cut -d'"' -f4)
echo "RIDE_TO_CANCEL_ID: $CANCEL_RIDE_ID"

curl -X POST $BASE_URL/api/ride/$CANCEL_RIDE_ID/cancel \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "reason": "Changed plans"
  }'
echo ""


# 26. Logout Rider
test_case "Logout Rider"
curl -X POST $BASE_URL/api/auth/logout \
  -H "Authorization: Bearer $RIDER_TOKEN" \
  -H "Accept: application/json"
echo ""

# 27. Logout Driver
test_case "Logout Driver"
curl -X POST $BASE_URL/api/auth/logout \
  -H "Authorization: Bearer $DRIVER_TOKEN" \
  -H "Accept: application/json"
echo ""
