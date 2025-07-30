Here are the **cURL commands in proper sequence** as per your demo API token documentation dated **July 30, 2025**:

---

## ✅ 1. AUTHENTICATION TESTS

### Validate Rider Token

```bash
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
  -H "Accept: application/json"
```

### Validate Driver Tokens

```bash
# Bike
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer 15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7" \
  -H "Accept: application/json"

# Auto
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer 16|OcknatmH4Ri8FHVT0yuAqxixmQx1iAxTTHRA0Lne8a537613" \
  -H "Accept: application/json"

# Sedan
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer 14|5Jvkn1YSrEEm2OsxMHaIbjbUYEGzAARhHhhxP5pI73a4ca9f" \
  -H "Accept: application/json"

# SUV
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer 17|nKsxChw0i5aLMPapoZDBFN1gub7IOk1Ikp7BIZvb3b2d5f5a" \
  -H "Accept: application/json"
```

### Invalid & Missing Token Tests

```bash
# Invalid
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer invalid_token_here" \
  -H "Accept: application/json"

# Missing
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Accept: application/json"
```

---

## 👤 2. PROFILE TESTS

```bash
# Rider
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
  -H "Accept: application/json"

# Bike Driver
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7" \
  -H "Accept: application/json"

# Auto Driver
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 16|OcknatmH4Ri8FHVT0yuAqxixmQx1iAxTTHRA0Lne8a537613" \
  -H "Accept: application/json"

# Sedan Driver
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 14|5Jvkn1YSrEEm2OsxMHaIbjbUYEGzAARhHhhxP5pI73a4ca9f" \
  -H "Accept: application/json"

# SUV Driver
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 17|nKsxChw0i5aLMPapoZDBFN1gub7IOk1Ikp7BIZvb3b2d5f5a" \
  -H "Accept: application/json"
```

---

## 💰 3. FARE ESTIMATION TESTS

**Change vehicle\_type** accordingly for each test:

```bash
# Template: Replace vehicle_type with bike / auto / sedan / suv

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

# Repeat above with vehicle_type as "auto", "sedan", and "suv"
```

---

## 🟢 4. DRIVER STATUS ONLINE

```bash
# Bike
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer 15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status":"online","latitude":12.9716,"longitude":77.5946}'

# Auto
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer 16|OcknatmH4Ri8FHVT0yuAqxixmQx1iAxTTHRA0Lne8a537613" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status":"online","latitude":12.9720,"longitude":77.5950}'

# Sedan
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer 14|5Jvkn1YSrEEm2OsxMHaIbjbUYEGzAARhHhhxP5pI73a4ca9f" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status":"online","latitude":12.9725,"longitude":77.5955}'

# SUV
curl -X POST http://127.0.0.1:8000/api/driver/status \
  -H "Authorization: Bearer 17|nKsxChw0i5aLMPapoZDBFN1gub7IOk1Ikp7BIZvb3b2d5f5a" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status":"online","latitude":12.9730,"longitude":77.5960}'
```

---

## 🚕 5. RIDE REQUESTS

```bash
# Bike
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

# Repeat above block for auto (wallet), sedan (card), suv (cash)
```

---

## 🚫 6. AUTHORIZATION NEGATIVE TESTS

```bash
# Rider trying to access driver endpoint (should fail)
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer 13|QPwGN9WvHbhvMuGJUHjqe5O37WKt7EuQ9u1Xglxx7b391628" \
  -H "Accept: application/json"

# Driver trying to access rider endpoint (should fail)
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer 15|6dlJdXhK30X8sidWUiB16I58MjAzrOhkGSFs29Sd3efe24b7" \
  -H "Accept: application/json"
```

---

