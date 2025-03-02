<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function refundHistories()
    {
        return $this->hasMany(RefundHistories::class);
    }

}
