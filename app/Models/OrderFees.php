<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFees extends Model
{
    use HasFactory;
    protected $casts = [
        'fee_details' => 'array'
    ];
    protected $fillable = [
        'fee_details','total_fee','payment_charge_id','payment_balance_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
