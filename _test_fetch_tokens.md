# Fetch Authentication Tokens Guide

This document provides methods to obtain authentication tokens for testing the Taxi API endpoints.

## Prerequisites
- Laravel server running on `http://127.0.0.1:8000`
- Database migrations completed
- API endpoints accessible

## Method 1: PowerShell Script (Recommended)

### Quick Token Generation
```powershell
# Run the automated token generator
.\get_fresh_tokens.ps1
```

### Manual PowerShell Commands

#### Step 1: Register and Get Rider Token
```powershell
$riderRegBody = @{
    name = "Test Rider"
    email = "test.rider@example.com"
    password = "password123"
    password_confirmation = "password123"
    phone = "+1234567890"
    user_type = "rider"
} | ConvertTo-Json

try {
    $riderReg = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/register" -Method Post -ContentType "application/json" -Body $riderRegBody
    $riderToken = $riderReg.data.token
    Write-Host "Rider Token: $riderToken" -ForegroundColor Green
} catch {
    # If registration fails, try login
    $riderLoginBody = @{
        email = "test.rider@example.com"
        password = "password123"
    } | ConvertTo-Json
    
    $riderLogin = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/login" -Method Post -ContentType "application/json" -Body $riderLoginBody
    $riderToken = $riderLogin.data.token
    Write-Host "Rider Token (Login): $riderToken" -ForegroundColor Green
}
```

#### Step 2: Register and Get Driver Token
```powershell
$driverRegBody = @{
    name = "Test Driver"
    email = "test.driver@example.com"
    password = "password123"
    password_confirmation = "password123"
    phone = "+1234567891"
    user_type = "driver"
    license_number = "DL123456789"
    vehicle_number = "KA01AB1234"
    vehicle_type = "sedan"
    vehicle_model = "Toyota Camry 2023"
    vehicle_color = "Black"
} | ConvertTo-Json

try {
    $driverReg = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/register" -Method Post -ContentType "application/json" -Body $driverRegBody
    $driverToken = $driverReg.data.token
    Write-Host "Driver Token: $driverToken" -ForegroundColor Green
} catch {
    # If registration fails, try login
    $driverLoginBody = @{
        email = "test.driver@example.com"
        password = "password123"
    } | ConvertTo-Json
    
    $driverLogin = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/login" -Method Post -ContentType "application/json" -Body $driverLoginBody
    $driverToken = $driverLogin.data.token
    Write-Host "Driver Token (Login): $driverToken" -ForegroundColor Green
}
```

## Method 2: cURL Commands

### Step 1: Register Rider
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

### Step 2: Register Driver with Vehicle Type
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

### Step 3: Login Rider (if registration exists)
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test.rider@example.com",
    "password": "password123"
  }'
```

### Step 4: Login Driver (if registration exists)
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test.driver@example.com",
    "password": "password123"
  }'
```

## Method 3: Demo Credentials (if seeded)

### Login with Existing Demo Accounts

#### Demo Rider Login
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "rider1@test.com",
    "password": "password"
  }'
```

#### Demo Driver Login
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "driver1@test.com",
    "password": "password"
  }'
```

## Method 4: Using Postman

### Step 1: Create Collection
1. Open Postman
2. Create new collection "Taxi API"
3. Add base URL variable: `{{base_url}}` = `http://127.0.0.1:8000/api`

### Step 2: Register Rider
- **Method**: POST
- **URL**: `{{base_url}}/auth/register`
- **Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body (raw JSON)**:
```json
{
  "name": "Test Rider",
  "email": "test.rider@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "+1234567890",
  "user_type": "rider"
}
```

### Step 3: Register Driver
- **Method**: POST
- **URL**: `{{base_url}}/auth/register`
- **Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body (raw JSON)**:
```json
{
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
}
```

### Step 4: Extract Tokens
From response, copy the `data.token` value to use in subsequent requests.

## Expected Response Format

### Successful Registration/Login Response
```json
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "Test Rider",
      "email": "test.rider@example.com",
      "phone": "+1234567890",
      "user_type": "rider",
      "status": "active"
    },
    "token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz1234567890AbCdEf"
  }
}
```

## Vehicle Types for Driver Registration

When registering drivers, use one of these vehicle types:
- `"bike"` - Motorcycle/Scooter
- `"auto"` - Auto-rickshaw  
- `"sedan"` - 4-door car
- `"suv"` - Sport Utility Vehicle

## Token Usage

Once you have tokens, use them in the Authorization header:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

## Validation Rules

### Rider Registration
- `name`: Required, string, max 255 characters
- `email`: Required, valid email, unique
- `phone`: Required, string, unique
- `password`: Required, min 8 characters, confirmed
- `user_type`: Required, must be "rider"

### Driver Registration
- All rider fields plus:
- `license_number`: Required, string, unique
- `vehicle_number`: Required, string, unique
- `vehicle_type`: Required, one of: bike, auto, sedan, suv
- `vehicle_model`: Optional, string, max 100 characters
- `vehicle_color`: Optional, string, max 50 characters

## Troubleshooting

### Common Issues

#### Email Already Exists
If you get "email already taken" error, try logging in instead of registering:
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"your_email","password":"your_password"}'
```

#### Server Not Running
If you get connection errors:
```bash
# Start Laravel server
php artisan serve
```

#### Database Issues
If you get database errors:
```bash
# Run migrations
php artisan migrate
```

#### Token Expired
Tokens are long-lived but if they expire, simply login again to get a new token.

## Security Notes

- Tokens are personal and should not be shared
- Each login generates a new token
- Old tokens remain valid until logout or expiration
- Use HTTPS in production environments
- Store tokens securely in your application

## Next Steps

After obtaining tokens:
1. Use `_test_token_api.md` for authenticated API calls
2. Test all endpoints systematically
3. Verify vehicle type functionality
4. Test error scenarios and edge cases
