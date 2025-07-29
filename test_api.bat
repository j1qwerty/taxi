@echo off
echo ========================================
echo    Taxi Booking API Test Suite
echo ========================================
echo.

REM Set the base URL
set BASE_URL=http://127.0.0.1:8000

echo [1/27] Testing API Connection...
curl -X GET %BASE_URL%/test -H "Accept: application/json"
echo.
echo.

echo [2/27] Registering New Rider...
curl -X POST %BASE_URL%/api/auth/register ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"TestRider\",\"email\":\"testrider@test.com\",\"phone\":\"+1234567800\",\"password\":\"password\",\"password_confirmation\":\"password\",\"user_type\":\"rider\"}"
echo.
echo.

echo [3/27] Registering New Driver...
curl -X POST %BASE_URL%/api/auth/register ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"TestDriver\",\"email\":\"testdriver@test.com\",\"phone\":\"+1234567801\",\"password\":\"password\",\"password_confirmation\":\"password\",\"user_type\":\"driver\"}"
echo.
echo.

echo [4/27] Login as Rider1...
echo Getting rider token...
FOR /F "tokens=*" %%i IN ('curl -s -X POST %BASE_URL%/api/auth/login -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"email\":\"rider1@test.com\",\"password\":\"password\"}" ^| findstr /r "\"token\""') DO SET rider_response=%%i
echo Rider login response: %rider_response%
echo.

echo [5/27] Login as Driver1...
echo Getting driver token...
FOR /F "tokens=*" %%i IN ('curl -s -X POST %BASE_URL%/api/auth/login -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"email\":\"driver1@test.com\",\"password\":\"password\"}" ^| findstr /r "\"token\""') DO SET driver_response=%%i
echo Driver login response: %driver_response%
echo.

REM For simplicity, we'll use a demo token - in real testing you'd parse the JSON response
set RIDER_TOKEN=demo_rider_token
set DRIVER_TOKEN=demo_driver_token

echo [6/27] Get Current User (with demo token)...
curl -X GET %BASE_URL%/api/auth/user ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Accept: application/json"
echo.
echo.

echo [7/27] Get Rider Profile...
curl -X GET %BASE_URL%/api/rider/profile ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Accept: application/json"
echo.
echo.

echo [8/27] Update Rider Profile...
curl -X POST %BASE_URL%/api/rider/profile ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"home_address\":\"Updated Home Address\",\"work_address\":\"Updated Work Address\",\"emergency_contact\":\"+1234567999\"}"
echo.
echo.

echo [9/27] Find Nearby Drivers...
curl -X POST %BASE_URL%/api/rider/nearby-drivers ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"latitude\":28.6139,\"longitude\":77.2090,\"radius\":5}"
echo.
echo.

echo [10/27] Estimate Fare...
curl -X POST %BASE_URL%/api/rider/estimate-fare ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"pickup_latitude\":28.6139,\"pickup_longitude\":77.2090,\"drop_latitude\":28.6239,\"drop_longitude\":77.2190,\"vehicle_type\":\"sedan\"}"
echo.
echo.

echo [11/27] Get Driver Profile...
curl -X GET %BASE_URL%/api/driver/profile ^
  -H "Authorization: Bearer %DRIVER_TOKEN%" ^
  -H "Accept: application/json"
echo.
echo.

echo [12/27] Update Driver Profile...
curl -X POST %BASE_URL%/api/driver/profile ^
  -H "Authorization: Bearer %DRIVER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"vehicle_type\":\"sedan\",\"vehicle_number\":\"NEW123\",\"vehicle_model\":\"Updated Model 2023\",\"vehicle_color\":\"Blue\"}"
echo.
echo.

echo [13/27] Update Driver Status...
curl -X POST %BASE_URL%/api/driver/status ^
  -H "Authorization: Bearer %DRIVER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"status\":\"online\"}"
echo.
echo.

echo [14/27] Update Driver Location...
curl -X POST %BASE_URL%/api/driver/location ^
  -H "Authorization: Bearer %DRIVER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"latitude\":28.6150,\"longitude\":77.2100}"
echo.
echo.

echo [15/27] Get Driver Earnings...
curl -X GET %BASE_URL%/api/driver/earnings ^
  -H "Authorization: Bearer %DRIVER_TOKEN%" ^
  -H "Accept: application/json"
echo.
echo.

echo [16/27] Get Driver Ride History...
curl -X GET %BASE_URL%/api/driver/rides ^
  -H "Authorization: Bearer %DRIVER_TOKEN%" ^
  -H "Accept: application/json"
echo.
echo.

echo [17/27] Request a Ride...
curl -X POST %BASE_URL%/api/ride/request ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"pickup_latitude\":28.6139,\"pickup_longitude\":77.2090,\"pickup_address\":\"123 Main St, City\",\"drop_latitude\":28.6239,\"drop_longitude\":77.2190,\"drop_address\":\"456 Office Blvd, City\",\"vehicle_type\":\"sedan\",\"payment_method\":\"cash\"}"
echo.
echo.

echo [18/27] Get Ride Status...
curl -X GET %BASE_URL%/api/ride/RIDE001/status ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Accept: application/json"
echo.
echo.

echo [19/27] Accept Ride...
curl -X POST %BASE_URL%/api/driver/accept-ride ^
  -H "Authorization: Bearer %DRIVER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"ride_id\":\"RIDE003\"}"
echo.
echo.

echo [20/27] Update Ride Status...
curl -X POST %BASE_URL%/api/driver/update-ride-status ^
  -H "Authorization: Bearer %DRIVER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"ride_id\":\"RIDE001\",\"status\":\"arrived\"}"
echo.
echo.

echo [21/27] Rate Ride...
curl -X POST %BASE_URL%/api/ride/RIDE001/rate ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"rating\":5,\"review\":\"Excellent ride!\"}"
echo.
echo.

echo [22/27] Get Ride History...
curl -X GET %BASE_URL%/api/ride/history ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Accept: application/json"
echo.
echo.

echo [23/27] Cancel Ride...
curl -X POST %BASE_URL%/api/ride/RIDE003/cancel ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"reason\":\"Changed plans\"}"
echo.
echo.

echo [24/27] Get CSRF Cookie...
curl -X GET %BASE_URL%/sanctum/csrf-cookie ^
  -H "Accept: application/json"
echo.
echo.

echo [25/27] Test Admin Login Page...
curl -X GET %BASE_URL%/admin/login
echo.
echo.

echo [26/27] Test Main Page...
curl -X GET %BASE_URL%/
echo.
echo.

echo [27/27] Logout...
curl -X POST %BASE_URL%/api/auth/logout ^
  -H "Authorization: Bearer %RIDER_TOKEN%" ^
  -H "Accept: application/json"
echo.
echo.

echo ========================================
echo       API Test Suite Completed!
echo ========================================
echo.
echo Notes:
echo - Some endpoints require valid authentication tokens
echo - Replace demo tokens with real tokens from login responses
echo - Check API_TESTING.md for detailed documentation
echo - Demo data has been seeded with users and rides
echo.
pause
