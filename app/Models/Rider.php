<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_photo',
        'home_address',
        'work_address',
        'emergency_contact',
        'rating',
        'total_rides',
        'wallet_balance',
        'referral_code',
        'referred_by'
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'wallet_balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }
}
