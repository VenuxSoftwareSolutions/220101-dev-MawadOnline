<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetailsFees extends Model
{
    use HasFactory;
    protected $fillable = [
        'fee_amount',
        'order_detail_id',
        'order_fee_id',
    ];

    public function orderDetails()
    {
        return $this->hasOne(OrderDetail::class);
    }
}
