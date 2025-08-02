<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rider;
use App\Models\Driver;
use App\Models\Ride;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Demo Admin Users
        $admin1 = User::create([
            'name' => 'Admin1',
            'email' => 'admin1@taxi.com',
            'phone' => '+1234567890',
            'password' => Hash::make('admin123'),
            'user_type' => 'admin',
            'status' => 'active',
        ]);

        $admin2 = User::create([
            'name' => 'Admin2',
            'email' => 'admin2@taxi.com',
            'phone' => '+1234567899',
            'password' => Hash::make('123'),
            'user_type' => 'admin',
            'status' => 'active',
        ]);

        // Demo Rider Users
        $rider1 = User::create([
            'name' => 'Rider1',
            'email' => 'rider1@test.com',
            'phone' => '+1234567891',
            'password' => Hash::make('password'),
            'user_type' => 'rider',
            'status' => 'active',
        ]);

        $rider2 = User::create([
            'name' => 'Rider2',
            'email' => 'rider2@test.com',
            'phone' => '+1234567892',
            'password' => Hash::make('password'),
            'user_type' => 'rider',
            'status' => 'active',
        ]);

        $rider3 = User::create([
            'name' => 'Rider3',
            'email' => 'rider3@test.com',
            'phone' => '+1234567898',
            'password' => Hash::make('password'),
            'user_type' => 'rider',
            'status' => 'active',
        ]);

        // Demo Driver Users
        $driver1 = User::create([
            'name' => 'Driver1',
            'email' => 'driver1@test.com',
            'phone' => '+1234567893',
            'password' => Hash::make('password'),
            'user_type' => 'driver',
            'status' => 'active',
        ]);

        $driver2 = User::create([
            'name' => 'Driver2',
            'email' => 'driver2@test.com',
            'phone' => '+1234567894',
            'password' => Hash::make('password'),
            'user_type' => 'driver',
            'status' => 'active',
        ]);

        $driver3 = User::create([
            'name' => 'Driver3',
            'email' => 'driver3@test.com',
            'phone' => '+1234567897',
            'password' => Hash::make('password'),
            'user_type' => 'driver',
            'status' => 'active',
        ]);

        // Create Rider Profiles
        $riderProfile1 = Rider::create([
            'user_id' => $rider1->id,
            'home_address' => '123 Main St, City1',
            'work_address' => '456 Office Blvd, City1',
            'emergency_contact' => '+1234567895',
            'referral_code' => 'RIDER001',
            'wallet_balance' => 100.00,
            'rating' => 4.5,
            'total_rides' => 5,
        ]);

        $riderProfile2 = Rider::create([
            'user_id' => $rider2->id,
            'home_address' => '789 Oak Ave, City2',
            'work_address' => '101 Business St, City2',
            'emergency_contact' => '+1234567896',
            'referral_code' => 'RIDER002',
            'wallet_balance' => 50.00,
            'rating' => 4.2,
            'total_rides' => 3,
        ]);

        $riderProfile3 = Rider::create([
            'user_id' => $rider3->id,
            'home_address' => '321 Pine St, City3',
            'work_address' => '654 Corporate Ave, City3',
            'emergency_contact' => '+1234567888',
            'referral_code' => 'RIDER003',
            'wallet_balance' => 75.00,
            'rating' => 4.8,
            'total_rides' => 8,
        ]);

        // Create Driver Profiles
        DB::statement("
            INSERT INTO drivers (user_id, vehicle_type, vehicle_number, vehicle_model, vehicle_color, 
                               driving_license, license_expiry, is_online, is_available, approval_status, 
                               referral_code, wallet_balance, rating, total_rides, current_latitude, 
                               current_longitude, location, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, POINT(?, ?), NOW(), NOW())
        ", [
            $driver1->id, 'sedan', 'ABC123', 'Toyota Camry 2020', 'Black',
            'DL123456789', '2026-12-31', 1, 1, 'approved', 'DRIVER001',
            500.00, 4.5, 25, 28.6139, 77.2090, 77.2090, 28.6139
        ]);

        $driverProfile1 = Driver::where('user_id', $driver1->id)->first();

        DB::statement("
            INSERT INTO drivers (user_id, vehicle_type, vehicle_number, vehicle_model, vehicle_color, 
                               driving_license, license_expiry, is_online, is_available, approval_status, 
                               referral_code, wallet_balance, rating, total_rides, current_latitude, 
                               current_longitude, location, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, POINT(?, ?), NOW(), NOW())
        ", [
            $driver2->id, 'suv', 'XYZ789', 'Honda CR-V 2021', 'White',
            'DL987654321', '2025-06-30', 1, 1, 'approved', 'DRIVER002',
            750.00, 4.8, 40, 28.6129, 77.2295, 77.2295, 28.6129
        ]);

        $driverProfile2 = Driver::where('user_id', $driver2->id)->first();

        DB::statement("
            INSERT INTO drivers (user_id, vehicle_type, vehicle_number, vehicle_model, vehicle_color, 
                               driving_license, license_expiry, is_online, is_available, approval_status, 
                               referral_code, wallet_balance, rating, total_rides, current_latitude, 
                               current_longitude, location, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, POINT(?, ?), NOW(), NOW())
        ", [
            $driver3->id, 'bike', 'MNO456', 'Hero Splendor 2022', 'Red',
            'DL456789123', '2027-03-15', 0, 0, 'pending', 'DRIVER003',
            200.00, 4.0, 10, 28.6119, 77.2190, 77.2190, 28.6119
        ]);

        $driverProfile3 = Driver::where('user_id', $driver3->id)->first();

        // Create Demo Rides with spatial data
        DB::statement("
            INSERT INTO rides (ride_id, rider_id, driver_id, pickup_address, pickup_latitude, pickup_longitude,
                             drop_address, drop_latitude, drop_longitude, distance, estimated_fare, actual_fare,
                             base_fare, per_km_charge, status, otp, otp_verified, payment_method, payment_status,
                             ride_start_time, ride_end_time, rider_rating, driver_rating, pickup_location, 
                             drop_location, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, POINT(?, ?), POINT(?, ?), NOW(), NOW())
        ", [
            'RIDE001', $riderProfile1->id, $driverProfile1->id, '123 Main St, City1', 28.6139, 77.2090,
            '456 Office Blvd, City1', 28.6239, 77.2190, 5.5, 150.00, 145.00, 50.00, 15.00, 'completed',
            '1234', 1, 'cash', 'completed', Carbon::now()->subHours(2), Carbon::now()->subHours(1),
            5, 4, 77.2090, 28.6139, 77.2190, 28.6239
        ]);

        $ride1 = Ride::where('ride_id', 'RIDE001')->first();

        DB::statement("
            INSERT INTO rides (ride_id, rider_id, driver_id, pickup_address, pickup_latitude, pickup_longitude,
                             drop_address, drop_latitude, drop_longitude, distance, estimated_fare, actual_fare,
                             base_fare, per_km_charge, status, otp, otp_verified, payment_method, payment_status,
                             ride_start_time, pickup_location, drop_location, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, POINT(?, ?), POINT(?, ?), NOW(), NOW())
        ", [
            'RIDE002', $riderProfile2->id, $driverProfile2->id, '789 Oak Ave, City2', 28.6129, 77.2295,
            '101 Business St, City2', 28.6329, 77.2395, 8.2, 200.00, 195.00, 50.00, 18.00, 'started',
            '5678', 1, 'wallet', 'pending', Carbon::now()->subMinutes(30),
            77.2295, 28.6129, 77.2395, 28.6329
        ]);

        $ride2 = Ride::where('ride_id', 'RIDE002')->first();

        DB::statement("
            INSERT INTO rides (ride_id, rider_id, driver_id, pickup_address, pickup_latitude, pickup_longitude,
                             drop_address, drop_latitude, drop_longitude, distance, estimated_fare, actual_fare,
                             base_fare, per_km_charge, status, otp, otp_verified, payment_method, payment_status,
                             pickup_location, drop_location, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, POINT(?, ?), POINT(?, ?), NOW(), NOW())
        ", [
            'RIDE003', $riderProfile3->id, null, '321 Pine St, City3', 28.6119, 77.2190,
            '654 Corporate Ave, City3', 28.6219, 77.2290, 3.5, 120.00, null, 50.00, 15.00, 'searching',
            '9012', 0, 'online', 'pending', 77.2190, 28.6119, 77.2290, 28.6219
        ]);

        $ride3 = Ride::where('ride_id', 'RIDE003')->first();

        // Create Earnings for completed rides
        DB::table('earnings')->insert([
            [
                'driver_id' => $driverProfile1->id,
                'ride_id' => $ride1->id,
                'gross_amount' => 145.00,
                'commission_amount' => 29.00,
                'net_amount' => 116.00,
                'status' => 'settled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create Ride Locations for active ride
        DB::table('ride_locations')->insert([
            [
                'ride_id' => $ride2->id,
                'latitude' => 28.6129,
                'longitude' => 77.2295,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ride_id' => $ride2->id,
                'latitude' => 28.6139,
                'longitude' => 77.2305,
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ],
        ]);

        // Create Wallet Transactions
        DB::table('wallet_transactions')->insert([
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],
        ]);

        // Create Zones
        DB::table('zones')->insert([
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create Referrals
        DB::table('referrals')->insert([
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create Complaints
        DB::table('complaints')->insert([
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create SOS Alerts
        DB::table('sos_alerts')->insert([
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create FAQs
        DB::table('faqs')->insert([
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create Notifications
        DB::table('notifications')->insert([
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create Zone Polygons
        DB::table('zone_polygons')->insert([
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create Tasks
        DB::table('tasks')->insert([
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        echo "Demo data seeded successfully!\n";
        echo "Users: " . User::count() . "\n";
        echo "Riders: " . Rider::count() . "\n";
        echo "Drivers: " . Driver::count() . "\n";
        echo "Rides: " . Ride::count() . "\n";
    }
}

