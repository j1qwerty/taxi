<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::first();
$rider = App\Models\Rider::create([
    'user_id' => $user->id,
    'phone' => '1234567890',
    'emergency_contact' => '9876543210'
]);

echo "Created rider profile for " . $user->name . "\n";
echo "Rider ID: " . $rider->id . "\n";
