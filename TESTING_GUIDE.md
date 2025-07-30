# Taxi API Testing Guide

This repository contains a complete taxi booking API with comprehensive testing scripts for PowerShell.

## 🚀 Quick Start

### 1. Setup the API
```powershell
# Run this to setup everything and generate tokens
.\setup_and_test.ps1
```

### 2. Get Authentication Tokens
```powershell
# Generate fresh tokens for testing
.\get_fresh_tokens.ps1
```

### 3. Run Full API Tests
```powershell
# Test all endpoints automatically
.\test_all_apis.ps1
```

## 📁 Available Scripts

| Script | Description |
|--------|-------------|
| `setup_and_test.ps1` | Complete setup: migrations, cache clearing, token generation |
| `get_fresh_tokens.ps1` | Generate authentication tokens for rider and driver |
| `test_all_apis.ps1` | Comprehensive API testing with all endpoints |
| `curl_test_commands.sh` | Manual curl commands for testing |

## 🔑 Authentication

The API uses Laravel Sanctum for token-based authentication:

1. **Register** users (rider/driver with vehicle type)
2. **Login** to get bearer tokens
3. **Use tokens** in Authorization header for protected endpoints

## 🚗 Vehicle Type Support

The API supports different vehicle types with dynamic pricing:

- **Bike**: Base ₹25, ₹8/km
- **Auto**: Base ₹35, ₹10/km  
- **Sedan**: Base ₹50, ₹12/km
- **SUV**: Base ₹70, ₹15/km

## 📋 API Endpoints

### Authentication
- `POST /api/auth/register` - Register rider/driver with vehicle type
- `POST /api/auth/login` - Email/password login
- `POST /api/auth/logout` - Token logout
- `GET /api/auth/user` - Get current user

### Rider APIs
- `GET /api/rider/profile` - Get rider profile
- `POST /api/rider/profile` - Update rider profile
- `POST /api/rider/nearby-drivers` - Find nearby drivers
- `POST /api/rider/estimate-fare` - Get fare estimation

### Driver APIs
- `GET /api/driver/profile` - Get driver profile with vehicle info
- `POST /api/driver/profile` - Update driver profile & vehicle type
- `POST /api/driver/status` - Update online/offline status
- `POST /api/driver/location` - Update current location
- `GET /api/driver/earnings` - Get earnings summary
- `GET /api/driver/rides` - Get ride history
- `POST /api/driver/accept-ride` - Accept ride request
- `POST /api/driver/update-ride-status` - Update ride status

### Ride Management
- `POST /api/ride/request` - Request ride with vehicle type
- `GET /api/ride/{id}/status` - Get ride status
- `POST /api/ride/{id}/cancel` - Cancel ride
- `POST /api/ride/{id}/rate` - Rate completed ride
- `GET /api/ride/history` - Get ride history

## 🧪 Testing Examples

### PowerShell (Recommended)
```powershell
# Get tokens and test automatically
.\test_all_apis.ps1

# Or get tokens manually
.\get_fresh_tokens.ps1
```

### Curl Commands
```bash
# Register driver with vehicle type
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Driver",
    "email": "driver@test.com", 
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890",
    "user_type": "driver",
    "license_number": "DL123456789",
    "vehicle_number": "KA01AB1234", 
    "vehicle_type": "sedan",
    "vehicle_model": "Toyota Camry",
    "vehicle_color": "Black"
  }'

# Login and get token
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "driver@test.com", "password": "password123"}'

# Use token for authenticated requests
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 🛠 Prerequisites

- PHP 8.1+
- Laravel 10.x
- MySQL/SQLite database
- PowerShell (for testing scripts)

## 📊 Features Tested

✅ **User Management**
- Rider registration & authentication
- Driver registration with vehicle type
- Profile management with vehicle details

✅ **Ride Booking**
- Vehicle-type specific ride requests
- Dynamic pricing based on vehicle type
- Driver matching by vehicle type
- Real-time status updates

✅ **Vehicle Type Integration**
- Registration with vehicle details
- Vehicle type validation
- Pricing by vehicle category
- Driver-ride matching by vehicle type

✅ **API Security**
- Token-based authentication
- Proper authorization checks
- Error handling & validation

## 🎯 Usage Instructions

1. **Start Laravel server**:
   ```powershell
   php artisan serve
   ```

2. **Run complete setup**:
   ```powershell
   .\setup_and_test.ps1
   ```

3. **Copy generated tokens** and use in API calls

4. **Test individual endpoints** using curl commands or run full test suite

5. **Admin panel access**: http://127.0.0.1:8000/admin/login

## 🔧 Troubleshooting

- **Token errors**: Run `.\get_fresh_tokens.ps1` to generate new tokens
- **Database errors**: Run `php artisan migrate` to update schema
- **Server not running**: Ensure `php artisan serve` is running
- **Permission errors**: Check file permissions and Laravel logs

## 📈 Next Steps

- Integration with mapping APIs for real distance calculation
- Real-time WebSocket connections for live updates
- Push notifications for ride status changes
- Payment gateway integration
- Advanced driver filtering and matching algorithms
