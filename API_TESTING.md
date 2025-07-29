# Taxi Booking API - Complete cURL Commands

**Base URL:** `http://127.0.0.1:8000`

## Authentication Endpoints

### 1. Register User (Rider)
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
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
```

### 2. Register User (Driver)
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
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
```

### 3. Login as Rider1
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "rider1@test.com",
    "password": "password"
  }'
```

### 4. Login as Driver1
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "driver1@test.com",
    "password": "password"
  }'
```

### 5. Get Current User
```bash
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### 6. Logout
```bash
curl -X POST http://127.0.0.1:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

## Rider Endpoints

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
    "home_address": "Updated Home Address",
    "work_address": "Updated Work Address",
    "emergency_contact": "+1234567999"
  }'
```

### 9. Find Nearby Drivers
```bash
curl -X POST http://127.0.0.1:8000/api/rider/nearby-drivers \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 28.6139,
    "longitude": 77.2090,
    "radius": 5
  }'
```

### 10. Estimate Fare
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "pickup_latitude": 28.6139,
    "pickup_longitude": 77.2090,
    "drop_latitude": 28.6239,
    "drop_longitude": 77.2190,
    "vehicle_type": "sedan"
  }'
```

## Driver Endpoints

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
    "vehicle_type": "sedan",
    "vehicle_number": "NEW123",
    "vehicle_model": "Updated Model 2023",
    "vehicle_color": "Blue"
  }'
```

### 13. Update Driver Status
```bash
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online"
  }'
```

### 14. Update Driver Location
```bash
curl -X POST http://127.0.0.1:8000/api/driver/location \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "latitude": 28.6150,
    "longitude": 77.2100
  }'
```

### 15. Get Driver Earnings
```bash
curl -X GET http://127.0.0.1:8000/api/driver/earnings \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
```

### 16. Get Driver Ride History
```bash
curl -X GET http://127.0.0.1:8000/api/driver/rides \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Accept: application/json"
```

### 17. Accept Ride
```bash
curl -X POST http://127.0.0.1:8000/api/driver/accept-ride \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE003"
  }'
```

### 18. Update Ride Status
```bash
curl -X POST http://127.0.0.1:8000/api/driver/update-ride-status \
  -H "Authorization: Bearer YOUR_DRIVER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "ride_id": "RIDE001",
    "status": "arrived"
  }'
```

## Ride Endpoints

### 19. Request a Ride
```bash
curl -X POST http://127.0.0.1:8000/api/ride/request \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
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
  }'
```

### 20. Get Ride Status
```bash
curl -X GET http://127.0.0.1:8000/api/ride/RIDE001/status \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### 21. Cancel Ride
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE003/cancel \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "reason": "Changed plans"
  }'
```

### 22. Rate Ride
```bash
curl -X POST http://127.0.0.1:8000/api/ride/RIDE001/rate \
  -H "Authorization: Bearer YOUR_RIDER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "rating": 5,
    "review": "Excellent ride!"
  }'
```

### 23. Get Ride History
```bash
curl -X GET http://127.0.0.1:8000/api/ride/history \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

## Public Endpoints

### 24. Test API
```bash
curl -X GET http://127.0.0.1:8000/test \
  -H "Accept: application/json"
```

### 25. Get CSRF Cookie (for web authentication)
```bash
curl -X GET http://127.0.0.1:8000/sanctum/csrf-cookie \
  -H "Accept: application/json"
```

## Admin Web Routes (For Browser Testing)

### 26. Admin Login Page
```bash
curl -X GET http://127.0.0.1:8000/admin/login
```

### 27. Admin Dashboard (requires login session)
```bash
curl -X GET http://127.0.0.1:8000/admin/dashboard \
  -H "Cookie: your_session_cookie"
```

## Demo Credentials

### Admin Accounts
- **Email:** admin1@taxi.com | **Password:** admin123
- **Email:** admin2@taxi.com | **Password:** 123

### Rider Accounts
- **Email:** rider1@test.com | **Password:** password
- **Email:** rider2@test.com | **Password:** password
- **Email:** rider3@test.com | **Password:** password

### Driver Accounts
- **Email:** driver1@test.com | **Password:** password
- **Email:** driver2@test.com | **Password:** password
- **Email:** driver3@test.com | **Password:** password

## How to Get Bearer Tokens

### PowerShell Method (Recommended)
```powershell
# Run the demo script
.\get_tokens.ps1

# Or manually:
$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/login" -Method Post -ContentType "application/json" -Body '{"email":"rider1@test.com","password":"password"}'
$token = $response.data.token
Write-Host $token
```

### Command Line Method
```bash
# For Windows PowerShell, use Invoke-RestMethod instead of curl
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/login" -Method Post -ContentType "application/json" -Body '{"email":"rider1@test.com","password":"password"}'
```

### Example Working Tokens (Generated Live)
Replace these with fresh tokens from login:
- **RIDER_TOKEN:** `2|6Xu3OroHumxYIkdaXwkJ2goW1NMq8RDmqZVfYq7y2695dad0`
- **DRIVER_TOKEN:** `3|AbC123XyZ456TokenExampleHere789`

## Working Examples

### Login & Get Token
```bash
# This returns: {"status":"success","message":"Login successful","data":{"user":{...},"token":"YOUR_TOKEN"}}
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"rider1@test.com","password":"password"}'
```

### Use Token in Requests
```bash
# Replace YOUR_ACTUAL_TOKEN with the token from login response
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer YOUR_ACTUAL_TOKEN" \
  -H "Accept: application/json"
```

## Notes

1. **✅ FIXED:** personal_access_tokens table created - API authentication now working!
2. Replace `YOUR_TOKEN_HERE`, `YOUR_RIDER_TOKEN`, `YOUR_DRIVER_TOKEN` with actual tokens received from login
3. All API endpoints return JSON responses
4. Authentication is required for most endpoints (except register, login, and test)
5. For ride operations, use the correct user type (rider for booking, driver for accepting)
6. Ride IDs in the demo data: RIDE001, RIDE002, RIDE003
7. **Use `get_tokens.ps1` script to quickly get working tokens for testing**
