# Taxi API Testing Guide

## Overview
This document provides comprehensive testing instructions for the Taxi Booking API. The API supports riders, drivers, and administrators with full CRUD operations, authentication, and real-time features.

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
The API uses Laravel Sanctum for token-based authentication. All protected endpoints require a Bearer token in the Authorization header.

## API Endpoints Overview

### 🔐 Authentication Endpoints

#### 1. Register User
- **URL**: `POST /api/auth/register`
- **Purpose**: Register new rider or driver
- **Authentication**: None required
- **Supports**: Vehicle type for drivers

#### 2. Login User
- **URL**: `POST /api/auth/login`
- **Purpose**: Authenticate user and receive access token
- **Authentication**: Email and password
- **Returns**: Bearer token

#### 3. Logout User
- **URL**: `POST /api/auth/logout`
- **Purpose**: Invalidate current access token
- **Authentication**: Bearer token required

#### 4. Get Current User
- **URL**: `GET /api/auth/user`
- **Purpose**: Get authenticated user details
- **Authentication**: Bearer token required

### 👤 Rider Endpoints

#### 5. Get Rider Profile
- **URL**: `GET /api/rider/profile`
- **Purpose**: Get rider profile details
- **Authentication**: Rider token required

#### 6. Update Rider Profile
- **URL**: `POST /api/rider/profile`
- **Purpose**: Update rider profile information
- **Authentication**: Rider token required

#### 7. Find Nearby Drivers
- **URL**: `POST /api/rider/nearby-drivers`
- **Purpose**: Find available drivers within radius
- **Authentication**: Rider token required

#### 8. Estimate Fare
- **URL**: `POST /api/rider/estimate-fare`
- **Purpose**: Calculate fare based on distance and vehicle type
- **Authentication**: Rider token required

### 🚗 Driver Endpoints

#### 9. Get Driver Profile
- **URL**: `GET /api/driver/profile`
- **Purpose**: Get driver profile with vehicle details
- **Authentication**: Driver token required

#### 10. Update Driver Profile
- **URL**: `POST /api/driver/profile`
- **Purpose**: Update driver profile and vehicle information
- **Authentication**: Driver token required

#### 11. Update Driver Status
- **URL**: `POST /api/driver/status`
- **Purpose**: Change online/offline status
- **Authentication**: Driver token required

#### 12. Update Driver Location
- **URL**: `POST /api/driver/location`
- **Purpose**: Update current GPS coordinates
- **Authentication**: Driver token required

#### 13. Get Driver Earnings
- **URL**: `GET /api/driver/earnings`
- **Purpose**: Get earnings summary and statistics
- **Authentication**: Driver token required

#### 14. Get Driver Ride History
- **URL**: `GET /api/driver/rides`
- **Purpose**: Get paginated list of driver's rides
- **Authentication**: Driver token required

#### 15. Accept Ride Request
- **URL**: `POST /api/driver/accept-ride`
- **Purpose**: Accept a pending ride request
- **Authentication**: Driver token required

#### 16. Update Ride Status
- **URL**: `POST /api/driver/update-ride-status`
- **Purpose**: Update ride status (started, completed, cancelled)
- **Authentication**: Driver token required

### 🚕 Ride Management Endpoints

#### 17. Request Ride
- **URL**: `POST /api/ride/request`
- **Purpose**: Create new ride request with vehicle type
- **Authentication**: Rider token required

#### 18. Get Ride Status
- **URL**: `GET /api/ride/{id}/status`
- **Purpose**: Get current status of specific ride
- **Authentication**: Rider or Driver token required

#### 19. Cancel Ride
- **URL**: `POST /api/ride/{id}/cancel`
- **Purpose**: Cancel an active ride
- **Authentication**: Rider or Driver token required

#### 20. Rate Ride
- **URL**: `POST /api/ride/{id}/rate`
- **Purpose**: Rate completed ride (1-5 stars)
- **Authentication**: Rider or Driver token required

#### 21. Get Ride History
- **URL**: `GET /api/ride/history`
- **Purpose**: Get paginated ride history
- **Authentication**: Rider or Driver token required

### 🔧 Utility Endpoints

#### 22. API Health Check
- **URL**: `GET /api/test`
- **Purpose**: Check if API is running
- **Authentication**: None required

#### 23. CSRF Cookie
- **URL**: `GET /sanctum/csrf-cookie`
- **Purpose**: Get CSRF cookie for web authentication
- **Authentication**: None required

## Vehicle Types Supported
- **bike**: Motorcycle/Scooter
- **auto**: Auto-rickshaw
- **sedan**: 4-door car
- **suv**: Sport Utility Vehicle

## Payment Methods Supported
- **cash**: Cash payment
- **wallet**: Digital wallet
- **card**: Credit/Debit card

## Response Format
All API responses follow this structure:
```json
{
  "status": "success|error",
  "message": "Human readable message",
  "data": {} | [],
  "errors": {} // Only present on validation errors
}
```

## Error Handling
- **401**: Unauthorized (invalid/missing token)
- **403**: Forbidden (insufficient permissions)
- **404**: Not Found (resource doesn't exist)
- **422**: Validation Error (invalid input data)
- **500**: Internal Server Error

## Rate Limiting
- Authentication endpoints: 5 requests per minute
- Other endpoints: 60 requests per minute per user

## Testing Order
1. Register rider and driver accounts
2. Login to get authentication tokens
3. Test profile management
4. Test ride flow (request → accept → complete)
5. Test ratings and history
6. Test error scenarios

## Next Steps
- Use `_test_api_curl.md` for cURL commands
- Use `_test_fetch_tokens.md` for token generation
- Use `_test_token_api.md` for authenticated requests
