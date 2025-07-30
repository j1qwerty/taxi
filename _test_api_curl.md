# Taxi API - Complete cURL Commands

This file contains all cURL commands for testing every API endpoint. Replace tokens with actual values from authentication.

## Base Configuration
```bash
BASE_URL="http://127.0.0.1:8000/api"
CONTENT_TYPE="Content-Type: application/json"
ACCEPT="Accept: application/json"
```

## 🔐 Authentication Endpoints

### 1. Register Rider
```bash
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
```

### 2. Register Driver (with Vehicle Type)
```bash
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
```

### 3. Login Rider
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test.rider@example.com",
    "password": "password123"
  }'
```

### 4. Login Driver
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test.driver@example.com",
    "password": "password123"
  }'
```

### 5. Get Current User (Token Required)
```bash
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### 6. Logout (Token Required)
```bash
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

## 👤 Rider Endpoints

### 7. Get Rider Profile
```bash
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Accept: application/json"
```

### 8. Update Rider Profile
```bash
curl -X POST http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "home_address": "123 Home Street, City",
    "work_address": "456 Work Avenue, City",
    "emergency_contact": "+9876543210"
  }'
```

### 9. Find Nearby Drivers
```bash
curl -X POST http://127.0.0.1:8000/api/rider/nearby-drivers \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 12.9716,
    "longitude": 77.5946,
    "radius": 5
  }'
```

### 10. Estimate Fare (All Vehicle Types)

#### Bike Fare
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "drop_latitude": 12.9352,
    "drop_longitude": 77.6245,
    "vehicle_type": "bike"
  }'
```

#### Auto Fare
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "drop_latitude": 12.9352,
    "drop_longitude": 77.6245,
    "vehicle_type": "auto"
  }'
```

#### Sedan Fare
```bash
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
```

#### SUV Fare
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "drop_latitude": 12.9352,
    "drop_longitude": 77.6245,
    "vehicle_type": "suv"
  }'
```

## 🚗 Driver Endpoints

### 11. Get Driver Profile
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
```

### 12. Update Driver Profile
```bash
curl -X POST http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "vehicle_type": "suv",
    "vehicle_number": "KA01XY9999",
    "vehicle_model": "Honda CR-V 2023",
    "vehicle_color": "White",
    "license_number": "DL987654321"
  }'
```

### 13. Update Driver Status - Online
```bash
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online",
    "latitude": 12.9716,
    "longitude": 77.5946
  }'
```

### 14. Update Driver Status - Offline
```bash
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "offline"
  }'
```

### 15. Update Driver Location
```bash
curl -X POST http://127.0.0.1:8000/api/driver/location \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 12.9750,
    "longitude": 77.5980
  }'
```

### 16. Get Driver Earnings
```bash
curl -X GET http://127.0.0.1:8000/api/driver/earnings \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
```

### 17. Get Driver Ride History
```bash
curl -X GET http://127.0.0.1:8000/api/driver/rides \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
```

### 18. Accept Ride Request
```bash
curl -X POST http://127.0.0.1:8000/api/driver/accept-ride \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID_HERE"
  }'
```

### 19. Update Ride Status - Started
```bash
curl -X POST http://127.0.0.1:8000/api/driver/update-ride-status \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID_HERE",
    "status": "started"
  }'
```

### 20. Update Ride Status - Completed
```bash
curl -X POST http://127.0.0.1:8000/api/driver/update-ride-status \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE_ID_HERE",
    "status": "completed"
  }'
```

## 🚕 Ride Management Endpoints

### 21. Request Ride - Bike
```bash
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
    "vehicle_type": "bike",
    "payment_method": "cash"
  }'
```

### 22. Request Ride - Auto
```bash
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
    "vehicle_type": "auto",
    "payment_method": "wallet"
  }'
```

### 23. Request Ride - Sedan
```bash
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
    "payment_method": "card"
  }'
```

### 24. Request Ride - SUV
```bash
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
    "vehicle_type": "suv",
    "payment_method": "cash"
  }'
```

### 25. Get Ride Status
```bash
curl -X GET http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/status \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### 26. Cancel Ride (Rider)
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/cancel \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "reason": "Changed plans"
  }'
```

### 27. Cancel Ride (Driver)
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/cancel \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "reason": "Vehicle breakdown"
  }'
```

### 28. Rate Ride (Rider Rating Driver)
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/rate \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "rating": 5,
    "review": "Excellent service! Very professional driver."
  }'
```

### 29. Rate Ride (Driver Rating Rider)
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE_ID_HERE/rate \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "rating": 4,
    "review": "Good passenger, on time."
  }'
```

### 30. Get Ride History (Rider)
```bash
curl -X GET http://127.0.0.1:8000/api/ride/history \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Accept: application/json"
```

### 31. Get Ride History (Driver)
```bash
curl -X GET http://127.0.0.1:8000/api/ride/history \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
```

## 🔧 Utility Endpoints

### 32. API Health Check
```bash
curl -X GET http://127.0.0.1:8000/api/test \
  -H "Accept: application/json"
```

### 33. Get CSRF Cookie
```bash
curl -X GET http://127.0.0.1:8000/sanctum/csrf-cookie \
  -H "Accept: application/json"
```

## 🚨 Error Testing

### 34. Invalid Login
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "invalid@test.com",
    "password": "wrongpassword"
  }'
```

### 35. Unauthorized Request
```bash
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Accept: application/json"
```

### 36. Invalid Token
```bash
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer invalid-token-here" \
  -H "Accept: application/json"
```

### 37. Invalid Vehicle Type
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 12.9716,
    "pickup_longitude": 77.5946,
    "drop_latitude": 12.9352,
    "drop_longitude": 77.6245,
    "vehicle_type": "invalid_type"
  }'
```

## 📝 Notes

1. Replace `YOUR_TOKEN_HERE`, `YOUR_RIDER_TOKEN`, `YOUR_DRIVER_TOKEN` with actual tokens from login
2. Replace `RIDE_ID_HERE` with actual ride IDs from ride requests
3. All coordinates are for Bangalore, India (adjust for your location)
4. Use `_test_fetch_tokens.md` to get valid tokens first
5. Test endpoints in order: auth → profiles → rides → ratings

## 🎯 Testing Tips

- Start with authentication endpoints to get tokens
- Test vehicle type specific features (fare estimation, ride requests)
- Test the complete ride flow: request → accept → start → complete → rate
- Test error scenarios to ensure proper validation
- Use different vehicle types to verify dynamic pricing
