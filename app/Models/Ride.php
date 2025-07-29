<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'rider_id',
        'driver_id',
        'pickup_address',
        'pickup_latitude',
        'pickup_longitude',
        'drop_address',
        'drop_latitude',
        'drop_longitude',
        'distance',
        'estimated_fare',
        'actual_fare',
        'base_fare',
        'per_km_charge',
        'surge_multiplier',
        'status',
        'otp',
        'otp_verified',
        'payment_method',
        'payment_status',
        'transaction_id',
        'driver_arrival_time',
        'ride_start_time',
        'ride_end_time',
        'cancelled_by',
        'cancellation_reason',
        'cancellation_charge',
        'rider_rating',
        'rider_review',
        'driver_rating',
        'driver_review'
    ];

    protected $casts = [
        'pickup_latitude' => 'decimal:8',
        'pickup_longitude' => 'decimal:8',
        'drop_latitude' => 'decimal:8',
        'drop_longitude' => 'decimal:8',
        'distance' => 'decimal:2',
        'estimated_fare' => 'decimal:2',
        'actual_fare' => 'decimal:2',
        'base_fare' => 'decimal:2',
        'per_km_charge' => 'decimal:2',
        'surge_multiplier' => 'decimal:2',
        'otp_verified' => 'boolean',
        'driver_arrival_time' => 'datetime',
        'ride_start_time' => 'datetime',
        'ride_end_time' => 'datetime',
        'cancellation_charge' => 'decimal:2',
    ];

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function locations()
    {
        return $this->hasMany(RideLocation::class);
    }
}
