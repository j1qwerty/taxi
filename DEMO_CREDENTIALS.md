# Demo Credentials - Taxi Booking Application

## Admin Panel Access
**URL:** http://127.0.0.1:8000/admin/login

### Admin Accounts
#### Admin 1
- **Email:** admin1@taxi.com
- **Password:** admin123
- **Role:** Administrator

#### Admin 2
- **Email:** admin2@taxi.com
- **Password:** 123
- **Role:** Administrator

## API Demo Accounts

### Rider Accounts
#### Rider 1
- **Name:** Rider1
- **Email:** rider1@test.com
- **Password:** password
- **Phone:** +1234567891
- **Wallet Balance:** $100.00
- **Rating:** 4.5/5 (5 rides)
- **Referral Code:** RIDER001

#### Rider 2
- **Name:** Rider2
- **Email:** rider2@test.com
- **Password:** password
- **Phone:** +1234567892
- **Wallet Balance:** $50.00
- **Rating:** 4.2/5 (3 rides)
- **Referral Code:** RIDER002

#### Rider 3
- **Name:** Rider3
- **Email:** rider3@test.com
- **Password:** password
- **Phone:** +1234567898
- **Wallet Balance:** $75.00
- **Rating:** 4.8/5 (8 rides)
- **Referral Code:** RIDER003

### Driver Accounts
#### Driver 1
- **Name:** Driver1
- **Email:** driver1@test.com
- **Password:** password
- **Phone:** +1234567893
- **Vehicle:** Toyota Camry 2020 (Black) - ABC123
- **Status:** Online & Available
- **Rating:** 4.5/5 (25 rides)
- **Wallet Balance:** $500.00
- **Referral Code:** DRIVER001

#### Driver 2
- **Name:** Driver2
- **Email:** driver2@test.com
- **Password:** password
- **Phone:** +1234567894
- **Vehicle:** Honda CR-V 2021 (White) - XYZ789
- **Status:** Online & Available
- **Rating:** 4.8/5 (40 rides)
- **Wallet Balance:** $750.00
- **Referral Code:** DRIVER002

#### Driver 3
- **Name:** Driver3
- **Email:** driver3@test.com
- **Password:** password
- **Phone:** +1234567897
- **Vehicle:** Hero Splendor 2022 (Red) - MNO456
- **Status:** Offline (Pending Approval)
- **Rating:** 4.0/5 (10 rides)
- **Wallet Balance:** $200.00
- **Referral Code:** DRIVER003

## API Testing Examples

### Authentication
```bash
# Register a new rider
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Rider",
    "email": "newrider@test.com",
    "phone": "+1234567899",
    "password": "password",
    "password_confirmation": "password",
    "user_type": "rider"
  }'

# Login as rider
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "rider1@test.com",
    "password": "password"
  }'

# Login as driver
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "driver1@test.com",
    "password": "password"
  }'
```

### Authenticated Requests
After login, use the returned token in the Authorization header:
```bash
# Get rider profile
curl -X GET http://127.0.0.1:8000/api/rider/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Get driver profile
curl -X GET http://127.0.0.1:8000/api/driver/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Database Seeding

To reset and populate the database with demo data:
```bash
php artisan migrate:fresh --seed
```

This will:
1. Drop all tables and recreate them
2. Run all migrations
3. Populate the database with the demo accounts listed above

## Quick Access Links

- **Admin Dashboard:** http://127.0.0.1:8000/admin/login
- **API Test Endpoint:** http://127.0.0.1:8000/test
- **Main Welcome Page:** http://127.0.0.1:8000/

## Notes

- All demo passwords are set to "password" except for the admin account
- Admin password is "admin123"
- All accounts are pre-approved and active
- Drivers are set to online and available status
- Demo data includes realistic profiles with addresses, vehicle details, and wallet balances
- Referral codes are automatically generated for each user
