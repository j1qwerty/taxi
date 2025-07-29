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
        $driverProfile1 = Driver::create([
            'user_id' => $driver1->id,
            'vehicle_type' => 'sedan',
            'vehicle_number' => 'ABC123',
            'vehicle_model' => 'Toyota Camry 2020',
            'vehicle_color' => 'Black',
            'driving_license' => 'DL123456789',
            'license_expiry' => '2026-12-31',
            'is_online' => true,
            'is_available' => true,
            'approval_status' => 'approved',
            'referral_code' => 'DRIVER001',
            'wallet_balance' => 500.00,
            'rating' => 4.5,
            'total_rides' => 25,
            'current_latitude' => 28.6139,
            'current_longitude' => 77.2090,
        ]);

        $driverProfile2 = Driver::create([
            'user_id' => $driver2->id,
            'vehicle_type' => 'suv',
            'vehicle_number' => 'XYZ789',
            'vehicle_model' => 'Honda CR-V 2021',
            'vehicle_color' => 'White',
            'driving_license' => 'DL987654321',
            'license_expiry' => '2025-06-30',
            'is_online' => true,
            'is_available' => true,
            'approval_status' => 'approved',
            'referral_code' => 'DRIVER002',
            'wallet_balance' => 750.00,
            'rating' => 4.8,
            'total_rides' => 40,
            'current_latitude' => 28.6129,
            'current_longitude' => 77.2295,
        ]);

        $driverProfile3 = Driver::create([
            'user_id' => $driver3->id,
            'vehicle_type' => 'bike',
            'vehicle_number' => 'MNO456',
            'vehicle_model' => 'Hero Splendor 2022',
            'vehicle_color' => 'Red',
            'driving_license' => 'DL456789123',
            'license_expiry' => '2027-03-15',
            'is_online' => false,
            'is_available' => false,
            'approval_status' => 'pending',
            'referral_code' => 'DRIVER003',
            'wallet_balance' => 200.00,
            'rating' => 4.0,
            'total_rides' => 10,
            'current_latitude' => 28.6119,
            'current_longitude' => 77.2190,
        ]);

        // Create Demo Rides
        $ride1 = Ride::create([
            'ride_id' => 'RIDE001',
            'rider_id' => $riderProfile1->id,
            'driver_id' => $driverProfile1->id,
            'pickup_address' => '123 Main St, City1',
            'pickup_latitude' => 28.6139,
            'pickup_longitude' => 77.2090,
            'drop_address' => '456 Office Blvd, City1',
            'drop_latitude' => 28.6239,
            'drop_longitude' => 77.2190,
            'distance' => 5.5,
            'estimated_fare' => 150.00,
            'actual_fare' => 145.00,
            'base_fare' => 50.00,
            'per_km_charge' => 15.00,
            'status' => 'completed',
            'otp' => '1234',
            'otp_verified' => true,
            'payment_method' => 'cash',
            'payment_status' => 'completed',
            'ride_start_time' => Carbon::now()->subHours(2),
            'ride_end_time' => Carbon::now()->subHours(1),
            'rider_rating' => 5,
            'driver_rating' => 4,
        ]);

        $ride2 = Ride::create([
            'ride_id' => 'RIDE002',
            'rider_id' => $riderProfile2->id,
            'driver_id' => $driverProfile2->id,
            'pickup_address' => '789 Oak Ave, City2',
            'pickup_latitude' => 28.6129,
            'pickup_longitude' => 77.2295,
            'drop_address' => '101 Business St, City2',
            'drop_latitude' => 28.6329,
            'drop_longitude' => 77.2395,
            'distance' => 8.2,
            'estimated_fare' => 200.00,
            'actual_fare' => 195.00,
            'base_fare' => 50.00,
            'per_km_charge' => 18.00,
            'status' => 'started',
            'otp' => '5678',
            'otp_verified' => true,
            'payment_method' => 'wallet',
            'payment_status' => 'pending',
            'ride_start_time' => Carbon::now()->subMinutes(30),
        ]);

        $ride3 = Ride::create([
            'ride_id' => 'RIDE003',
            'rider_id' => $riderProfile3->id,
            'driver_id' => null,
            'pickup_address' => '321 Pine St, City3',
            'pickup_latitude' => 28.6119,
            'pickup_longitude' => 77.2190,
            'drop_address' => '654 Corporate Ave, City3',
            'drop_latitude' => 28.6219,
            'drop_longitude' => 77.2290,
            'distance' => 3.5,
            'estimated_fare' => 120.00,
            'actual_fare' => null,
            'base_fare' => 50.00,
            'per_km_charge' => 15.00,
            'status' => 'searching',
            'otp' => '9012',
            'otp_verified' => false,
            'payment_method' => 'online',
            'payment_status' => 'pending',
        ]);

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

