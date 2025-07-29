<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_photo',
        'driving_license',
        'license_expiry',
        'vehicle_insurance',
        'insurance_expiry',
        'vehicle_rc',
        'aadhar_card',
        'police_verification',
        'bank_account_number',
        'bank_name',
        'ifsc_code',
        'vehicle_type',
        'vehicle_number',
        'vehicle_model',
        'vehicle_color',
        'is_online',
        'is_available',
        'current_latitude',
        'current_longitude',
        'last_location_update',
        'rating',
        'total_rides',
        'total_earnings',
        'wallet_balance',
        'commission_rate',
        'approval_status',
        'approval_date',
        'rejection_reason',
        'referral_code',
        'referred_by'
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'insurance_expiry' => 'date',
        'is_online' => 'boolean',
        'is_available' => 'boolean',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'last_location_update' => 'datetime',
        'rating' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'wallet_balance' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'approval_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }
}
