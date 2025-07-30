# Taxi API - Complete cURL Testing Commands
# This file contains all cURL commands for testing the API with both email/password and token authentication

# Base URL
BASE_URL="http://127.0.0.1:8000/api"

# Test credentials
RIDER_EMAIL="test.rider@example.com"
DRIVER_EMAIL="test.driver@example.com"
PASSWORD="password123"

echo "========================================"
echo "TAXI API - cURL TESTING COMMANDS"
echo "========================================"

echo -e "\n1. API HEALTH CHECK"
echo "curl -X GET $BASE_URL/test -H \"Accept: application/json\""

echo -e "\n2. REGISTER RIDER"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Rider",
    "email": "test.rider@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890",
    "user_type": "rider"
  }'
EOF

echo -e "\n3. REGISTER DRIVER (WITH VEHICLE TYPE)"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Driver",
    "email": "test.driver@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567891",
    "user_type": "driver",
    "license_number": "DL123456789",
    "vehicle_number": "KA01AB1234",
    "vehicle_type": "sedan",
    "vehicle_model": "Toyota Camry 2023",
    "vehicle_color": "Black"
  }'
EOF

echo -e "\n4. LOGIN AS RIDER"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test.rider@example.com",
    "password": "password123"
  }'
EOF

echo -e "\n5. LOGIN AS DRIVER"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test.driver@example.com",
    "password": "password123"
  }'
EOF

echo -e "\n========================================"
echo "REPLACE 'YOUR_RIDER_TOKEN' and 'YOUR_DRIVER_TOKEN' with actual tokens from login response"
echo "========================================"

echo -e "\n6. GET RIDER PROFILE (TOKEN REQUIRED)"
cat << 'EOF'
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Accept: application/json"
EOF

echo -e "\n7. GET DRIVER PROFILE (TOKEN REQUIRED)"
cat << 'EOF'
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
EOF

echo -e "\n8. UPDATE RIDER PROFILE"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "home_address": "123 Home Street, City",
    "work_address": "456 Work Avenue, City",
    "emergency_contact": "+9876543210"
  }'
EOF

echo -e "\n9. UPDATE DRIVER PROFILE (WITH VEHICLE TYPE)"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "vehicle_type": "suv",
    "vehicle_number": "KA01XY9999",
    "vehicle_model": "Honda CR-V 2023",
    "vehicle_color": "White"
  }'
EOF

echo -e "\n10. UPDATE DRIVER STATUS TO ONLINE"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online",
    "latitude": 12.9716,
    "longitude": 77.5946
  }'
EOF

echo -e "\n11. UPDATE DRIVER LOCATION"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/driver/location \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 12.9750,
    "longitude": 77.5980
  }'
EOF

echo -e "\n12. FIND NEARBY DRIVERS"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/rider/nearby-drivers \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 12.9716,
    "longitude": 77.5946,
    "radius": 5
  }'
EOF

echo -e "\n13. ESTIMATE FARE"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "drop_latitude": 12.9352,
    "drop_longitude": 77.6245,
    "vehicle_type": "sedan"
  }'
EOF

echo -e "\n14. REQUEST A RIDE"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/ride/request \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "pickup_address": "MG Road, Bangalore",
    "drop_latitude": 12.9352,
    "drop_longitude": 77.6245,
    "drop_address": "Koramangala, Bangalore",
    "vehicle_type": "sedan",
    "payment_method": "cash"
  }'
EOF

echo -e "\n15. GET RIDE STATUS (REPLACE RIDE_ID)"
cat << 'EOF'
curl -X GET http://127.0.0.1:8000/api/ride/RIDE_ID/status \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Accept: application/json"
EOF

echo -e "\n16. DRIVER ACCEPT RIDE"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/driver/accept-ride \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID"
  }'
EOF

echo -e "\n17. UPDATE RIDE STATUS (DRIVER)"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/driver/update-ride-status \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID",
    "status": "started"
  }'
EOF

echo -e "\n18. RATE RIDE"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID/rate \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "rating": 5,
    "review": "Excellent service! Great driver and clean vehicle."
  }'
EOF

echo -e "\n19. CANCEL RIDE"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID/cancel \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "reason": "Changed plans"
  }'
EOF

echo -e "\n20. GET RIDE HISTORY"
cat << 'EOF'
curl -X GET http://127.0.0.1:8000/api/ride/history \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Accept: application/json"
EOF

echo -e "\n21. GET DRIVER EARNINGS"
cat << 'EOF'
curl -X GET http://127.0.0.1:8000/api/driver/earnings \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
EOF

echo -e "\n22. GET DRIVER RIDE HISTORY"
cat << 'EOF'
curl -X GET http://127.0.0.1:8000/api/driver/rides \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
EOF

echo -e "\n23. LOGOUT RIDER"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Accept: application/json"
EOF

echo -e "\n24. LOGOUT DRIVER"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
EOF

echo -e "\n========================================"
echo "ERROR HANDLING TESTS"
echo "========================================"

echo -e "\n25. INVALID LOGIN TEST"
cat << 'EOF'
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "invalid@test.com",
    "password": "wrongpassword"
  }'
EOF

echo -e "\n26. UNAUTHORIZED REQUEST TEST"
cat << 'EOF'
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Accept: application/json"
EOF

echo -e "\n27. INVALID TOKEN TEST"
cat << 'EOF'
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer invalid-token-here" \
  -H "Accept: application/json"
EOF

echo -e "\n========================================"
echo "QUICK START GUIDE"
echo "========================================"
echo "1. Start Laravel server: php artisan serve"
echo "2. Run token generator: ./get_fresh_tokens.ps1"
echo "3. Copy tokens and replace YOUR_RIDER_TOKEN and YOUR_DRIVER_TOKEN in commands above"
echo "4. Test endpoints one by one using the curl commands"
echo "5. For full automation, run: ./test_all_apis.ps1"
echo "========================================"
