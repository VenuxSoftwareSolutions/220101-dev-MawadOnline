<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CouponUser extends Pivot
{
    protected $fillable = ['coupon_id', 'user_id', 'times_used'];
}
