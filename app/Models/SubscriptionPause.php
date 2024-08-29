<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPause extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'paused_at',
        'resumed_at',
        'reduction_applied_at'
    ];

    protected $dates = ['paused_at', 'resumed_at'];

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
