# Taxi Booking Application - Complete Documentation

## Overview
This is a comprehensive taxi booking application built with Laravel 10.x, featuring rider and driver mobile apps, an admin panel, and real-time ride management capabilities.

## Features

### For Riders
- User registration and authentication
- Request rides with pickup and drop locations
- Real-time driver tracking
- Fare estimation
- Payment options (cash, wallet, card)
- Ride history and ratings
- Profile management

### For Drivers
- Driver registration and verification
- Real-time location updates
- Accept/reject ride requests
- Track earnings and ride history
- Online/offline status management
- Profile and vehicle management

### For Admins
- Complete dashboard with statistics
- User management (riders, drivers, admins)
- Ride monitoring and management
- Driver verification and approval
- Earnings and financial reports
- System settings and configuration

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js (for frontend assets)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd taxi
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Update `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=taxi_booking
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Start Development Server**
   ```bash
   php artisan serve --host=127.0.0.1 --port=8000
   ```

## Database Schema

### Core Tables

#### Users Table
- Stores all users (riders, drivers, admins)
- Fields: id, name, email, phone, password, user_type, status
- User types: rider, driver, admin

#### Riders Table
- Extended profile for rider users
- Fields: user_id, home_address, work_address, emergency_contact, referral_code

#### Drivers Table
- Extended profile for driver users
- Fields: user_id, vehicle_type, vehicle_number, license_number, status, wallet_balance

#### Rides Table
- Core ride management
- Fields: booking_id, rider_id, driver_id, pickup/drop coordinates, fare_amount, status
- Statuses: pending, accepted, started, completed, cancelled

#### Additional Tables
- `earnings` - Driver earnings tracking
- `wallet_transactions` - Financial transactions
- `zones` - Service area management
- `ride_locations` - Real-time location tracking
- `notifications` - Push notifications
- `referrals` - Referral system

## API Endpoints

### Authentication
```
POST /api/auth/register - User registration
POST /api/auth/login - User login
POST /api/auth/logout - User logout (requires auth)
GET /api/auth/user - Get current user (requires auth)
```

### Rider Endpoints
```
GET /api/rider/profile - Get rider profile
POST /api/rider/profile - Update rider profile
POST /api/rider/nearby-drivers - Find nearby drivers
POST /api/rider/estimate-fare - Get fare estimation
```

### Driver Endpoints
```
GET /api/driver/profile - Get driver profile
POST /api/driver/profile - Update driver profile
POST /api/driver/status - Update online/offline status
POST /api/driver/location - Update current location
GET /api/driver/earnings - Get earnings summary
GET /api/driver/rides - Get ride history
POST /api/driver/accept-ride - Accept a ride request
POST /api/driver/update-ride-status - Update ride status
```

### Ride Management
```
POST /api/ride/request - Request a new ride
GET /api/ride/{id}/status - Get ride status
POST /api/ride/{id}/cancel - Cancel a ride
POST /api/ride/{id}/rate - Rate a completed ride
GET /api/ride/history - Get ride history
```

## Admin Panel

### Access
- URL: `http://localhost:8000/admin/login`
- Default credentials: admin@taxi.com / admin123

### Features
- **Dashboard**: Real-time statistics and recent rides
- **User Management**: View and manage all users
- **Driver Verification**: Approve/reject driver applications
- **Ride Monitoring**: Track all rides in real-time
- **Financial Reports**: Earnings and transaction reports
- **System Settings**: Configure app parameters

### Admin Routes
```
GET /admin/login - Admin login page
GET /admin/dashboard - Main dashboard
GET /admin/users - User management
GET /admin/riders - Rider management
GET /admin/drivers - Driver management
GET /admin/rides - Ride management
GET /admin/earnings - Financial reports
GET /admin/settings - System settings
```

## Authentication & Security

### Laravel Sanctum
- API authentication using Laravel Sanctum
- Token-based authentication for mobile apps
- Session-based authentication for admin panel

### User Types
- **Rider**: Can book rides, rate drivers
- **Driver**: Can accept rides, track earnings
- **Admin**: Full system access and management

## Development Guidelines

### Code Structure
```
app/
├── Http/Controllers/
│   ├── Api/           # API controllers
│   └── Admin/         # Admin panel controllers
├── Models/            # Eloquent models
└── Middleware/        # Custom middleware

database/
├── migrations/        # Database migrations
└── seeders/          # Database seeders

resources/
├── views/
│   └── admin/        # Admin panel views
└── js/               # Frontend assets

routes/
├── api.php           # API routes
└── web.php           # Web routes
```

### Testing

#### API Testing
Use tools like Postman or curl to test API endpoints:

```bash
# Register a new user
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","phone":"+1234567890","password":"password","user_type":"rider"}'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

#### Web Testing
- Admin panel: `http://localhost:8000/admin/login`
- API test endpoint: `http://localhost:8000/api/test`

## Configuration

### Environment Variables
Key environment variables to configure:

```env
# App Configuration
APP_NAME="Taxi Booking"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taxi_booking

# Mail Configuration (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password

# Push Notifications (Firebase)
FIREBASE_SERVER_KEY=your-firebase-key
```

### System Settings
Admin can configure:
- Base fare and per-km rates
- Commission rates
- Surge pricing multipliers
- Service area zones
- Cancellation policies

## Mobile App Integration

### API Usage
Mobile apps should:
1. Authenticate users via `/api/auth/login`
2. Store the returned token
3. Include token in all subsequent requests: `Authorization: Bearer {token}`
4. Handle token expiration and refresh

### Real-time Features
For real-time functionality, consider implementing:
- WebSocket connections for live updates
- Push notifications for ride status changes
- Background location tracking for drivers

## Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure proper database credentials
4. Set up SSL certificates
5. Configure queue workers for background jobs
6. Set up cron jobs for scheduled tasks

### Server Requirements
- PHP 8.1+
- MySQL 5.7+
- Nginx or Apache
- SSL certificate
- Redis (for caching and sessions)

## Support & Maintenance

### Monitoring
- Check application logs regularly
- Monitor database performance
- Track API response times
- Monitor server resources

### Backup Strategy
- Database backups (daily)
- File system backups
- Configuration backups

### Updates
- Regular security updates
- Feature enhancements
- Performance optimizations

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `.env`
   - Ensure MySQL service is running
   - Verify database exists

2. **Authentication Issues**
   - Clear Laravel cache: `php artisan cache:clear`
   - Regenerate app key: `php artisan key:generate`
   - Check Sanctum configuration

3. **Permission Errors**
   - Set proper file permissions: `chmod -R 755 storage bootstrap/cache`
   - Check directory ownership

4. **API Token Issues**
   - Verify token is included in request headers
   - Check token expiration
   - Ensure proper Sanctum configuration

### Logs
- Application logs: `storage/logs/laravel.log`
- Web server logs: Check Apache/Nginx error logs
- Database logs: Check MySQL error logs


