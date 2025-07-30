# Demo API Tokens

This file contains pre-generated authentication tokens for testing all API endpoints. These tokens are created from demo accounts with different vehicle types.

**Generated on**: July 30, 2025  
**Server**: http://127.0.0.1:8000  
**Valid**: Active and ready for testing

---

## 🔑 AUTHENTICATION TOKENS

### Demo Rider Account
```
Name: Demo Rider Fresh
Email: demo.rider.fresh@test.com
Password: password123
Phone: 9876543299
User ID: 11
User Type: rider
Token: 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628
```

**Usage Example**:
```bash
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
  -H "Accept: application/json"
```

---

### Demo Sedan Driver Account
```
Name: Demo Driver Fresh
Email: demo.driver.fresh@test.com
Password: password123
Phone: 9876543298
User ID: 12
User Type: driver
Vehicle Type: sedan
Vehicle Number: KA01AB1234
Vehicle Model: Honda City
Vehicle Color: White
License Number: DL0123456789
Token: 14|5Jvkn1YSrEEm2OsxMHaIbjbUYEGzAARhHhhxP5pI73a4ca9f
```

**Usage Example**:
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 14|5Jvkn1YSrEEm2OsxMHaIbjbUYEGzAARhHhhxP5pI73a4ca9f" \
  -H "Accept: application/json"
```

---

### Demo Bike Driver Account
```
Name: Demo Bike Driver
Email: demo.bike.driver@test.com
Password: password123
Phone: 9876543297
User ID: 13
User Type: driver
Vehicle Type: bike
Vehicle Number: KA01AB5678
Vehicle Model: Honda Activa
Vehicle Color: Black
License Number: DL0123456788
Token: 15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7
```

**Usage Example**:
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7" \
  -H "Accept: application/json"
```

---

### Demo Auto Driver Account
```
Name: Demo Auto Driver
Email: demo.auto.driver@test.com
Password: password123
Phone: 9876543296
User ID: 14
User Type: driver
Vehicle Type: auto
Vehicle Number: KA01AB9012
Vehicle Model: Bajaj Auto
Vehicle Color: Yellow
License Number: DL0123456787
Token: 16|OcknatmH4Ri8FHVT0yuAqxixmQx1iAxTTHRA0Lne8a537613
```

**Usage Example**:
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 16|OcknatmH4Ri8FHVT0yuAqxixmQx1iAxTTHRA0Lne8a537613" \
  -H "Accept: application/json"
```

---

### Demo SUV Driver Account
```
Name: Demo SUV Driver
Email: demo.suv.driver@test.com
Password: password123
Phone: 9876543295
User ID: 15
User Type: driver
Vehicle Type: suv
Vehicle Number: KA01AB3456
Vehicle Model: Toyota Innova
Vehicle Color: Silver
License Number: DL0123456786
Token: 17|nKsxChw0i5aLMPapoZDBFN1gub7IOk1Ikp7BIZvb3b2d5f5a
```

**Usage Example**:
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 17|nKsxChw0i5aLMPapoZDBFN1gub7IOk1Ikp7BIZvb3b2d5f5a" \
  -H "Accept: application/json"
```

---

## 🚗 VEHICLE TYPE TOKENS SUMMARY

| Vehicle Type | Driver Name | Token (Last 8 chars) | Vehicle Model | Color |
|-------------|-------------|----------------------|---------------|--------|
| **bike** | Demo Bike Driver | ...3efe24b7 | Honda Activa | Black |
| **auto** | Demo Auto Driver | ...8a537613 | Bajaj Auto | Yellow |
| **sedan** | Demo Driver Fresh | ...73a4ca9f | Honda City | White |
| **suv** | Demo SUV Driver | ...3b2d5f5a | Toyota Innova | Silver |

---

## 🔄 QUICK COPY TOKENS

### For PowerShell Variables
```powershell
$RIDER_TOKEN = "13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628"
$SEDAN_DRIVER_TOKEN = "14|5Jvkn1YSrEEm2OsxMHaIbjbUYEGzAARhHhhxP5pI73a4ca9f"
$BIKE_DRIVER_TOKEN = "15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7"
$AUTO_DRIVER_TOKEN = "16|OcknatmH4Ri8FHVT0yuAqxixmQx1iAxTTHRA0Lne8a537613"
$SUV_DRIVER_TOKEN = "17|nKsxChw0i5aLMPapoZDBFN1gub7IOk1Ikp7BIZvb3b2d5f5a"
```

### For Environment Variables
```bash
export RIDER_TOKEN="13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628"
export SEDAN_DRIVER_TOKEN="14|5Jvkn1YSrEEm2OsxMHaIbjbUYEGzAARhHhhxP5pI73a4ca9f"
export BIKE_DRIVER_TOKEN="15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7"
export AUTO_DRIVER_TOKEN="16|OcknatmH4Ri8FHVT0yuAqxixmQx1iAxTTHRA0Lne8a537613"
export SUV_DRIVER_TOKEN="17|nKsxChw0i5aLMPapoZDBFN1gub7IOk1Ikp7BIZvb3b2d5f5a"
```

---

## 🧪 TEST SCENARIOS WITH TOKENS

### 1. Rider Profile Test
```bash
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
  -H "Accept: application/json"
```

### 2. Fare Estimation (All Vehicle Types)

#### Bike Fare
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
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

#### Auto Fare
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
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

#### Sedan Fare
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
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

#### SUV Fare
```bash
curl -X POST http://127.0.0.1:8000/api/rider/estimate-fare \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
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

### 3. Driver Profile Tests (All Vehicle Types)

#### Bike Driver Profile
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7" \
  -H "Accept: application/json"
```

#### Auto Driver Profile
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 16|OcknatmH4Ri8FHVT0yuAqxixmQx1iAxTTHRA0Lne8a537613" \
  -H "Accept: application/json"
```

#### Sedan Driver Profile
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 14|5Jvkn1YSrEEm2OsxMHaIbjbUYEGzAARhHhhxP5pI73a4ca9f" \
  -H "Accept: application/json"
```

#### SUV Driver Profile
```bash
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 17|nKsxChw0i5aLMPapoZDBFN1gub7IOk1Ikp7BIZvb3b2d5f5a" \
  -H "Accept: application/json"
```

### 4. Driver Status Updates

#### Make Bike Driver Online
```bash
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer 15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online",
    "latitude": 12.9716,
    "longitude": 77.5946
  }'
```

#### Make Auto Driver Online
```bash
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer 16|OcknatmH4Ri8FHVT0yuAqxixmQx1iAxTTHRA0Lne8a537613" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online",
    "latitude": 12.9720,
    "longitude": 77.5950
  }'
```

#### Make Sedan Driver Online
```bash
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer 14|5Jvkn1YSrEEm2OsxMHaIbjbUYEGzAARhHhhxP5pI73a4ca9f" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online",
    "latitude": 12.9725,
    "longitude": 77.5955
  }'
```

#### Make SUV Driver Online
```bash
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer 17|nKsxChw0i5aLMPapoZDBFN1gub7IOk1Ikp7BIZvb3b2d5f5a" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "online",
    "latitude": 12.9730,
    "longitude": 77.5960
  }'
```

### 5. Ride Requests (All Vehicle Types)

#### Request Bike Ride
```bash
curl -X POST http://127.0.0.1:8000/api/ride/request \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
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
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
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
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
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
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
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

---

## 🔍 TOKEN VALIDATION TESTS

### Valid Token Test
```bash
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
  -H "Accept: application/json"
```

### Invalid Token Test
```bash
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer invalid_token_here" \
  -H "Accept: application/json"
```

### Missing Token Test
```bash
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Accept: application/json"
```

---

## 📋 EXPECTED PRICING RESULTS

### Vehicle Type Pricing Structure
- **Bike**: Base ₹25 + ₹8/km
- **Auto**: Base ₹35 + ₹10/km  
- **Sedan**: Base ₹50 + ₹12/km
- **SUV**: Base ₹70 + ₹15/km

### Distance Calculation
**MG Road to Electronic City**: ~5.8 km

### Expected Fare Results
- **Bike**: ₹25 + (5.8 × ₹8) = ₹71.40
- **Auto**: ₹35 + (5.8 × ₹10) = ₹93.00
- **Sedan**: ₹50 + (5.8 × ₹12) = ₹119.60
- **SUV**: ₹70 + (5.8 × ₹15) = ₹157.00

---

## 🎯 TESTING CHECKLIST

### ✅ Authentication Tests
- [ ] Validate rider token
- [ ] Validate all driver tokens
- [ ] Test invalid token rejection
- [ ] Test missing token rejection

### ✅ Profile Tests
- [ ] Get rider profile
- [ ] Get bike driver profile
- [ ] Get auto driver profile
- [ ] Get sedan driver profile
- [ ] Get SUV driver profile

### ✅ Fare Estimation Tests
- [ ] Bike fare estimation
- [ ] Auto fare estimation
- [ ] Sedan fare estimation
- [ ] SUV fare estimation

### ✅ Driver Status Tests
- [ ] Set bike driver online
- [ ] Set auto driver online
- [ ] Set sedan driver online
- [ ] Set SUV driver online

### ✅ Ride Flow Tests
- [ ] Request bike ride
- [ ] Request auto ride
- [ ] Request sedan ride
- [ ] Request SUV ride

### ✅ Authorization Tests
- [ ] Rider token on driver endpoints (should fail)
- [ ] Driver token on rider endpoints (should fail)
- [ ] Cross-vehicle type operations

---

## 📝 NOTES

1. **Token Security**: These tokens are for demo/testing purposes only
2. **Expiration**: Tokens remain valid until manually revoked
3. **Environment**: Designed for local development server (127.0.0.1:8000)
4. **Vehicle Types**: All 4 vehicle types (bike, auto, sedan, SUV) represented
5. **Real Data**: Uses realistic Bangalore coordinates and vehicle information

## 🔄 REFRESH TOKENS

If tokens expire or need refresh, use these login commands:

### Refresh Rider Token
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "demo.rider.fresh@test.com",
    "password": "password123"
  }'
```

### Refresh Driver Tokens
```bash
# Bike Driver
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "demo.bike.driver@test.com",
    "password": "password123"
  }'

# Auto Driver
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "demo.auto.driver@test.com",
    "password": "password123"
  }'

# Sedan Driver
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "demo.driver.fresh@test.com",
    "password": "password123"
  }'

# SUV Driver
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "demo.suv.driver@test.com",
    "password": "password123"
  }'
```

---

**Ready for comprehensive API testing with complete vehicle type coverage!** 🚗🏍️🛺🚙
