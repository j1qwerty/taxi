# Complete API Endpoints Testing Guide

This document lists all available API endpoints with descriptions, parameters, and testing methods.

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
All protected endpoints require Bearer token authentication:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## 🔐 AUTHENTICATION ENDPOINTS

### 1. Register User
- **Endpoint**: `POST /auth/register`
- **Authentication**: None
- **Purpose**: Register new rider or driver account
- **Vehicle Type Support**: ✅ (for drivers)

**Parameters**:
- `name` (string, required): Full name
- `email` (string, required): Valid email address
- `phone` (string, required): Phone number
- `password` (string, required): Minimum 8 characters
- `password_confirmation` (string, required): Must match password
- `user_type` (string, required): "rider" or "driver"

**Driver Additional Parameters**:
- `license_number` (string, required): Driving license number
- `vehicle_number` (string, required): Vehicle registration number
- `vehicle_type` (string, required): "bike", "auto", "sedan", "suv"
- `vehicle_model` (string, optional): Vehicle model
- `vehicle_color` (string, optional): Vehicle color

**Response**: User object + authentication token

---

### 2. Login User
- **Endpoint**: `POST /auth/login`
- **Authentication**: Email + Password
- **Purpose**: Authenticate user and receive access token

**Parameters**:
- `email` (string, required): Registered email
- `password` (string, required): User password

**Response**: User object + authentication token

---

### 3. Logout User  
- **Endpoint**: `POST /auth/logout`
- **Authentication**: Bearer token required
- **Purpose**: Invalidate current access token

**Parameters**: None

**Response**: Success message

---

### 4. Get Current User
- **Endpoint**: `GET /auth/user`
- **Authentication**: Bearer token required
- **Purpose**: Get authenticated user details

**Parameters**: None

**Response**: Current user object

---

## 👤 RIDER ENDPOINTS

### 5. Get Rider Profile
- **Endpoint**: `GET /rider/profile`
- **Authentication**: Rider token required
- **Purpose**: Get rider profile with details

**Parameters**: None

**Response**: User + Rider profile data

---

### 6. Update Rider Profile
- **Endpoint**: `POST /rider/profile`
- **Authentication**: Rider token required
- **Purpose**: Update rider profile information

**Parameters**:
- `home_address` (string, optional): Home address
- `work_address` (string, optional): Work address  
- `emergency_contact` (string, optional): Emergency contact number

**Response**: Updated rider profile

---

### 7. Find Nearby Drivers
- **Endpoint**: `POST /rider/nearby-drivers`
- **Authentication**: Rider token required
- **Purpose**: Find available drivers within specified radius

**Parameters**:
- `latitude` (numeric, required): Pickup latitude
- `longitude` (numeric, required): Pickup longitude
- `radius` (numeric, optional): Search radius in km (default: 10)

**Response**: Array of nearby drivers with distance and ETA

---

### 8. Estimate Fare
- **Endpoint**: `POST /rider/estimate-fare`
- **Authentication**: Rider token required
- **Purpose**: Calculate fare based on distance and vehicle type
- **Vehicle Type Support**: ✅ Dynamic pricing

**Parameters**:
- `pickup_latitude` (numeric, required): Pickup latitude
- `pickup_longitude` (numeric, required): Pickup longitude
- `drop_latitude` (numeric, required): Drop latitude
- `drop_longitude` (numeric, required): Drop longitude
- `vehicle_type` (string, optional): "bike", "auto", "sedan", "suv"

**Response**: Fare breakdown with base fare, per-km rate, total fare

**Pricing Structure**:
- **Bike**: Base ₹25 + ₹8/km
- **Auto**: Base ₹35 + ₹10/km
- **Sedan**: Base ₹50 + ₹12/km
- **SUV**: Base ₹70 + ₹15/km

---

## 🚗 DRIVER ENDPOINTS

### 9. Get Driver Profile
- **Endpoint**: `GET /driver/profile`
- **Authentication**: Driver token required
- **Purpose**: Get driver profile with vehicle details
- **Vehicle Type Support**: ✅ Shows vehicle information

**Parameters**: None

**Response**: User + Driver profile with vehicle details

---

### 10. Update Driver Profile
- **Endpoint**: `POST /driver/profile`
- **Authentication**: Driver token required
- **Purpose**: Update driver profile and vehicle information
- **Vehicle Type Support**: ✅ Update vehicle type and details

**Parameters**:
- `vehicle_type` (string, optional): "bike", "auto", "sedan", "suv"
- `vehicle_number` (string, optional): Vehicle registration number
- `vehicle_model` (string, optional): Vehicle model
- `vehicle_color` (string, optional): Vehicle color
- `license_number` (string, optional): License number

**Response**: Updated driver profile

---

### 11. Update Driver Status
- **Endpoint**: `POST /driver/status`
- **Authentication**: Driver token required
- **Purpose**: Change driver online/offline status

**Parameters**:
- `status` (string, required): "online", "offline", "busy"
- `latitude` (numeric, optional): Current latitude
- `longitude` (numeric, optional): Current longitude

**Response**: Updated status information

---

### 12. Update Driver Location
- **Endpoint**: `POST /driver/location`
- **Authentication**: Driver token required
- **Purpose**: Update current GPS coordinates

**Parameters**:
- `latitude` (numeric, required): Current latitude
- `longitude` (numeric, required): Current longitude

**Response**: Success confirmation

---

### 13. Get Driver Earnings
- **Endpoint**: `GET /driver/earnings`
- **Authentication**: Driver token required
- **Purpose**: Get earnings summary and statistics

**Parameters**: None

**Response**: Today, week, month earnings + total rides + wallet balance

---

### 14. Get Driver Ride History
- **Endpoint**: `GET /driver/rides`
- **Authentication**: Driver token required
- **Purpose**: Get paginated list of driver's rides

**Parameters**: None (supports pagination)

**Response**: Paginated rides with rider information

---

### 15. Accept Ride Request
- **Endpoint**: `POST /driver/accept-ride`
- **Authentication**: Driver token required
- **Purpose**: Accept a pending ride request
- **Vehicle Type Support**: ✅ Validates vehicle type match

**Parameters**:
- `ride_id` (string, required): ID of ride to accept

**Response**: Updated ride with driver assignment

---

### 16. Update Ride Status
- **Endpoint**: `POST /driver/update-ride-status`
- **Authentication**: Driver token required
- **Purpose**: Update ride status (started, completed, cancelled)

**Parameters**:
- `ride_id` (string, required): ID of ride to update
- `status` (string, required): "started", "completed", "cancelled"

**Response**: Updated ride information

---

## 🚕 RIDE MANAGEMENT ENDPOINTS

### 17. Request Ride
- **Endpoint**: `POST /ride/request`
- **Authentication**: Rider token required
- **Purpose**: Create new ride request with vehicle type
- **Vehicle Type Support**: ✅ Specify vehicle type and get matching drivers

**Parameters**:
- `pickup_latitude` (numeric, required): Pickup latitude
- `pickup_longitude` (numeric, required): Pickup longitude
- `pickup_address` (string, required): Pickup address
- `drop_latitude` (numeric, required): Drop latitude
- `drop_longitude` (numeric, required): Drop longitude
- `drop_address` (string, required): Drop address
- `vehicle_type` (string, required): "bike", "auto", "sedan", "suv"
- `payment_method` (string, required): "cash", "wallet", "card"

**Response**: Created ride with ID, fare estimate, available drivers count

---

### 18. Get Ride Status
- **Endpoint**: `GET /ride/{id}/status`
- **Authentication**: Rider or Driver token required
- **Purpose**: Get current status of specific ride

**Parameters**:
- `id` (path parameter): Ride ID

**Response**: Detailed ride information with current status

---

### 19. Cancel Ride
- **Endpoint**: `POST /ride/{id}/cancel`
- **Authentication**: Rider or Driver token required
- **Purpose**: Cancel an active ride

**Parameters**:
- `id` (path parameter): Ride ID
- `cancellation_reason` (string, optional): Reason for cancellation

**Response**: Updated ride with cancellation details

---

### 20. Rate Ride
- **Endpoint**: `POST /ride/{id}/rate`
- **Authentication**: Rider or Driver token required
- **Purpose**: Rate completed ride (1-5 stars)

**Parameters**:
- `id` (path parameter): Ride ID
- `rating` (integer, required): Rating from 1 to 5
- `review` (string, optional): Written review (max 500 chars)

**Response**: Updated ride with rating information

---

### 21. Get Ride History
- **Endpoint**: `GET /ride/history`
- **Authentication**: Rider or Driver token required
- **Purpose**: Get paginated ride history

**Parameters**: None (supports pagination)

**Response**: Paginated rides based on user type

---

## 🔧 UTILITY ENDPOINTS

### 22. API Health Check
- **Endpoint**: `GET /test`
- **Authentication**: None
- **Purpose**: Check if API is running and accessible

**Parameters**: None

**Response**: API status, version, timestamp, available endpoints

---

### 23. CSRF Cookie
- **Endpoint**: `GET /sanctum/csrf-cookie`
- **Authentication**: None
- **Purpose**: Get CSRF cookie for web authentication

**Parameters**: None

**Response**: Sets CSRF cookie

---

## 📊 ENDPOINT SUMMARY

| Category | Endpoints | Vehicle Type Support |
|----------|-----------|---------------------|
| Authentication | 4 | ✅ Driver registration |
| Rider APIs | 4 | ✅ Fare estimation |
| Driver APIs | 8 | ✅ Full vehicle management |
| Ride Management | 5 | ✅ Complete ride flow |
| Utilities | 2 | N/A |
| **Total** | **23** | **15 endpoints** |

## 🚗 VEHICLE TYPE FEATURES

### Supported Vehicle Types
1. **bike** - Motorcycle/Scooter
2. **auto** - Auto-rickshaw
3. **sedan** - 4-door car
4. **suv** - Sport Utility Vehicle

### Dynamic Pricing
- Different base fare and per-km rates for each vehicle type
- Automatic fare calculation based on vehicle selection
- Real-time fare estimation before booking

### Vehicle Matching
- Drivers matched to rides based on their vehicle type
- Vehicle type validation during ride acceptance
- Prevents mismatched bookings

## 📱 PAYMENT METHODS

### Supported Payment Options
1. **cash** - Cash payment to driver
2. **wallet** - Digital wallet payment
3. **card** - Credit/Debit card payment

## 🔒 SECURITY FEATURES

### Authentication
- Laravel Sanctum token-based authentication
- Secure password hashing
- Token expiration and invalidation

### Authorization
- Role-based access control (rider/driver)
- Endpoint-specific permissions
- Ride ownership validation

### Data Validation
- Comprehensive input validation
- Vehicle type validation
- Geographic coordinate validation
- File upload validation (for documents)

## 📈 SCALABILITY FEATURES

### Performance
- Pagination for large datasets
- Efficient database queries
- Caching for static data

### Real-time Capabilities
- Location tracking
- Status updates
- Driver availability

### Integration Ready
- RESTful API design
- JSON responses
- Standard HTTP status codes
- Webhook support (future)

## 🎯 TESTING STRATEGY

### Test Order
1. **Authentication** - Register and login users
2. **Profiles** - Update rider and driver profiles
3. **Vehicle Types** - Test all vehicle type features
4. **Ride Flow** - Complete ride from request to rating
5. **Edge Cases** - Error handling and validation
6. **Performance** - Load testing with multiple requests

### Coverage Areas
- ✅ All CRUD operations
- ✅ Vehicle type functionality
- ✅ Authentication and authorization
- ✅ Input validation and error handling
- ✅ Business logic (pricing, matching)
- ✅ Data relationships and integrity

## 🔍 MONITORING

### Key Metrics
- Response times for all endpoints
- Authentication success/failure rates
- Ride completion rates by vehicle type
- Driver acceptance rates
- Error rates and types

### Logging
- All API requests and responses
- Authentication attempts
- Database operations
- Error stack traces
- Performance metrics

This comprehensive guide covers all 23 API endpoints with complete vehicle type integration and testing coverage.
