# Token-Based API Testing with cURL

This document provides complete cURL commands for testing all API endpoints using Bearer token authentication.

## 🔑 PREREQUISITE: TOKEN GENERATION

First, generate authentication tokens for both rider and driver accounts:

### Generate Rider Token
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Rider",
    "email": "rider@test.com",
    "phone": "9876543210",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "rider"
  }'
```

### Generate Driver Token
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Driver",
    "email": "driver@test.com",
    "phone": "9876543211",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "driver",
    "license_number": "DL1234567890",
    "vehicle_number": "KA01AB1234",
    "vehicle_type": "sedan",
    "vehicle_model": "Honda City",
    "vehicle_color": "White"
  }'
```

### Login to Get Fresh Tokens
```bash
# Login as Rider
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "rider@test.com",
    "password": "password123"
  }'

# Login as Driver  
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "driver@test.com",
    "password": "password123"
  }'
```

---

## 🔐 AUTHENTICATION ENDPOINTS WITH TOKENS

### 1. Get Current User (Rider Token)
```bash
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 2. Get Current User (Driver Token)
```bash
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 3. Logout User (Rider Token)
```bash
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 4. Logout User (Driver Token)
```bash
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

---

## 👤 RIDER ENDPOINTS WITH TOKENS

### 5. Get Rider Profile
```bash
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 6. Update Rider Profile
```bash
curl -X POST http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "home_address": "123 Home Street, Bangalore",
    "work_address": "456 Office Complex, Electronic City",
    "emergency_contact": "9876543200"
  }'
```

### 7. Find Nearby Drivers (All Vehicle Types)

#### Find Bike Drivers
```bash
curl -X POST http://127.0.0.1:8000/api/rider/nearby-drivers \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 12.9716,
    "longitude": 77.5946,
    "radius": 5,
    "vehicle_type": "bike"
  }'
```

#### Find Auto Drivers
```bash
curl -X POST http://127.0.0.1:8000/api/rider/nearby-drivers \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 12.9716,
    "longitude": 77.5946,
    "radius": 10,
    "vehicle_type": "auto"
  }'
```

#### Find Sedan Drivers
```bash
curl -X POST http://127.0.0.1:8000/api/rider/nearby-drivers \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 12.9716,
    "longitude": 77.5946,
    "radius": 15,
    "vehicle_type": "sedan"
  }'
```

#### Find SUV Drivers
```bash
curl -X POST http://127.0.0.1:8000/api/rider/nearby-drivers \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 12.9716,
    "longitude": 77.5946,
    "radius": 20,
    "vehicle_type": "suv"
  }'
```

### 8. Estimate Fare (All Vehicle Types)

#### Bike Fare Estimation
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "drop_latitude": 12.9355,
    "drop_longitude": 77.6245,
    "vehicle_type": "bike"
  }'
```

#### Auto Fare Estimation
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "drop_latitude": 12.9355,
    "drop_longitude": 77.6245,
    "vehicle_type": "auto"
  }'
```

#### Sedan Fare Estimation
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "drop_latitude": 12.9355,
    "drop_longitude": 77.6245,
    "vehicle_type": "sedan"
  }'
```

#### SUV Fare Estimation
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "drop_latitude": 12.9355,
    "drop_longitude": 77.6245,
    "vehicle_type": "suv"
  }'
```

---

## 🚗 DRIVER ENDPOINTS WITH TOKENS

### 9. Get Driver Profile
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 10. Update Driver Profile (All Vehicle Types)

#### Update to Bike
```bash
curl -X POST http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "vehicle_type": "bike",
    "vehicle_number": "KA01AB1234",
    "vehicle_model": "Honda Activa",
    "vehicle_color": "Black",
    "license_number": "DL1234567890"
  }'
```

#### Update to Auto
```bash
curl -X POST http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "vehicle_type": "auto",
    "vehicle_number": "KA01AB5678",
    "vehicle_model": "Bajaj Auto",
    "vehicle_color": "Yellow",
    "license_number": "DL1234567890"
  }'
```

#### Update to Sedan
```bash
curl -X POST http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "vehicle_type": "sedan",
    "vehicle_number": "KA01AB9012",
    "vehicle_model": "Honda City",
    "vehicle_color": "White",
    "license_number": "DL1234567890"
  }'
```

#### Update to SUV
```bash
curl -X POST http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "vehicle_type": "suv",
    "vehicle_number": "KA01AB3456",
    "vehicle_model": "Toyota Innova",
    "vehicle_color": "Silver",
    "license_number": "DL1234567890"
  }'
```

### 11. Update Driver Status
```bash
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online",
    "latitude": 12.9716,
    "longitude": 77.5946
  }'
```

### 12. Update Driver Location
```bash
curl -X POST http://127.0.0.1:8000/api/driver/location \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 12.9716,
    "longitude": 77.5946
  }'
```

### 13. Get Driver Earnings
```bash
curl -X GET http://127.0.0.1:8000/api/driver/earnings \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 14. Get Driver Ride History
```bash
curl -X GET http://127.0.0.1:8000/api/driver/rides \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 15. Accept Ride Request
```bash
curl -X POST http://127.0.0.1:8000/api/driver/accept-ride \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID_HERE"
  }'
```

### 16. Update Ride Status
```bash
curl -X POST http://127.0.0.1:8000/api/driver/update-ride-status \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID_HERE",
    "status": "started"
  }'
```

---

## 🚕 RIDE MANAGEMENT WITH TOKENS

### 17. Request Ride (All Vehicle Types)

#### Request Bike Ride
```bash
curl -X POST http://127.0.0.1:8000/api/ride/request \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "pickup_address": "MG Road Metro Station, Bangalore",
    "drop_latitude": 12.9355,
    "drop_longitude": 77.6245,
    "drop_address": "Electronic City Phase 1, Bangalore",
    "vehicle_type": "bike",
    "payment_method": "cash"
  }'
```

#### Request Auto Ride
```bash
curl -X POST http://127.0.0.1:8000/api/ride/request \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "pickup_address": "MG Road Metro Station, Bangalore",
    "drop_latitude": 12.9355,
    "drop_longitude": 77.6245,
    "drop_address": "Electronic City Phase 1, Bangalore",
    "vehicle_type": "auto",
    "payment_method": "wallet"
  }'
```

#### Request Sedan Ride
```bash
curl -X POST http://127.0.0.1:8000/api/ride/request \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "pickup_address": "MG Road Metro Station, Bangalore",
    "drop_latitude": 12.9355,
    "drop_longitude": 77.6245,
    "drop_address": "Electronic City Phase 1, Bangalore",
    "vehicle_type": "sedan",
    "payment_method": "card"
  }'
```

#### Request SUV Ride
```bash
curl -X POST http://127.0.0.1:8000/api/ride/request \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "pickup_address": "MG Road Metro Station, Bangalore",
    "drop_latitude": 12.9355,
    "drop_longitude": 77.6245,
    "drop_address": "Electronic City Phase 1, Bangalore",
    "vehicle_type": "suv",
    "payment_method": "cash"
  }'
```

### 18. Get Ride Status (Rider Token)
```bash
curl -X GET http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/status \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 19. Get Ride Status (Driver Token)
```bash
curl -X GET http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/status \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 20. Cancel Ride (Rider Token)
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/cancel \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "cancellation_reason": "Change of plans"
  }'
```

### 21. Cancel Ride (Driver Token)
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/cancel \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "cancellation_reason": "Vehicle breakdown"
  }'
```

### 22. Rate Ride (Rider Token)
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/rate \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "rating": 5,
    "review": "Excellent service! Driver was punctual and professional."
  }'
```

### 23. Rate Ride (Driver Token)
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/rate \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "rating": 4,
    "review": "Good passenger, on time and polite."
  }'
```

### 24. Get Ride History (Rider Token)
```bash
curl -X GET http://127.0.0.1:8000/api/ride/history \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 25. Get Ride History (Driver Token)
```bash
curl -X GET http://127.0.0.1:8000/api/ride/history \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

---

## 🔧 COMPLETE WORKFLOW TESTING

### Full Ride Workflow with Tokens

#### Step 1: Register and Get Tokens
```bash
# Register Rider
RIDER_RESPONSE=$(curl -s -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Rider",
    "email": "rider@workflow.com",
    "phone": "9876543210",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "rider"
  }')

# Register Driver
DRIVER_RESPONSE=$(curl -s -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Driver",
    "email": "driver@workflow.com",
    "phone": "9876543211",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "driver",
    "license_number": "DL1234567890",
    "vehicle_number": "KA01AB1234",
    "vehicle_type": "sedan",
    "vehicle_model": "Honda City",
    "vehicle_color": "White"
  }')
```

#### Step 2: Make Driver Online
```bash
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online",
    "latitude": 12.9716,
    "longitude": 77.5946
  }'
```

#### Step 3: Request Ride
```bash
RIDE_RESPONSE=$(curl -s -X POST http://127.0.0.1:8000/api/ride/request \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "pickup_address": "MG Road Metro Station, Bangalore",
    "drop_latitude": 12.9355,
    "drop_longitude": 77.6245,
    "drop_address": "Electronic City Phase 1, Bangalore",
    "vehicle_type": "sedan",
    "payment_method": "cash"
  }')
```

#### Step 4: Accept Ride
```bash
curl -X POST http://127.0.0.1:8000/api/driver/accept-ride \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID_FROM_STEP_3"
  }'
```

#### Step 5: Start Ride
```bash
curl -X POST http://127.0.0.1:8000/api/driver/update-ride-status \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID_FROM_STEP_3",
    "status": "started"
  }'
```

#### Step 6: Complete Ride
```bash
curl -X POST http://127.0.0.1:8000/api/driver/update-ride-status \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID_FROM_STEP_3",
    "status": "completed"
  }'
```

#### Step 7: Rate Ride
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID_FROM_STEP_3/rate \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "rating": 5,
    "review": "Great ride!"
  }'
```

---

## 🚗 VEHICLE TYPE TESTING MATRIX

Test all combinations of vehicle types and payment methods:

| Vehicle Type | Payment Method | Rider Token | Driver Token | Expected Base Fare |
|-------------|---------------|-------------|--------------|-------------------|
| bike | cash | Required | Required | ₹25 |
| bike | wallet | Required | Required | ₹25 |
| bike | card | Required | Required | ₹25 |
| auto | cash | Required | Required | ₹35 |
| auto | wallet | Required | Required | ₹35 |
| auto | card | Required | Required | ₹35 |
| sedan | cash | Required | Required | ₹50 |
| sedan | wallet | Required | Required | ₹50 |
| sedan | card | Required | Required | ₹50 |
| suv | cash | Required | Required | ₹70 |
| suv | wallet | Required | Required | ₹70 |
| suv | card | Required | Required | ₹70 |

---

## 📊 TOKEN VALIDATION TESTS

### Valid Token Tests
```bash
# Test valid rider token
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer VALID_RIDER_TOKEN" \
  -H "Accept: application/json"

# Test valid driver token
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer VALID_DRIVER_TOKEN" \
  -H "Accept: application/json"
```

### Invalid Token Tests
```bash
# Test invalid token
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer invalid_token_here" \
  -H "Accept: application/json"

# Test missing token
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Accept: application/json"

# Test expired token (after logout)
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Authorization: Bearer TOKEN_TO_EXPIRE" \
  -H "Accept: application/json"

curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer TOKEN_TO_EXPIRE" \
  -H "Accept: application/json"
```

---

## 🔒 AUTHORIZATION TESTS

### Rider Token on Driver Endpoints
```bash
# Should fail - Rider token on driver profile
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer RIDER_TOKEN_HERE" \
  -H "Accept: application/json"
```

### Driver Token on Rider Endpoints
```bash
# Should fail - Driver token on rider profile
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer DRIVER_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

## 📝 TESTING CHECKLIST

### ✅ Authentication Endpoints (4)
- [ ] Get current user (rider token)
- [ ] Get current user (driver token)
- [ ] Logout (rider token)
- [ ] Logout (driver token)

### ✅ Rider Endpoints (4)
- [ ] Get rider profile
- [ ] Update rider profile
- [ ] Find nearby drivers (all vehicle types)
- [ ] Estimate fare (all vehicle types)

### ✅ Driver Endpoints (8)
- [ ] Get driver profile
- [ ] Update driver profile (all vehicle types)
- [ ] Update driver status
- [ ] Update driver location
- [ ] Get driver earnings
- [ ] Get driver ride history
- [ ] Accept ride request
- [ ] Update ride status

### ✅ Ride Management (5)
- [ ] Request ride (all vehicle types)
- [ ] Get ride status
- [ ] Cancel ride
- [ ] Rate ride
- [ ] Get ride history

### ✅ Token Security Tests
- [ ] Valid token authentication
- [ ] Invalid token rejection
- [ ] Missing token rejection
- [ ] Expired token rejection
- [ ] Cross-role authorization (rider/driver)

### ✅ Vehicle Type Integration
- [ ] Registration with vehicle type
- [ ] Profile update with vehicle type
- [ ] Fare estimation by vehicle type
- [ ] Ride request by vehicle type
- [ ] Driver matching by vehicle type

All 23 API endpoints covered with complete token-based authentication testing!
