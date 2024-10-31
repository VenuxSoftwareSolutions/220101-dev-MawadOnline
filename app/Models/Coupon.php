<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Coupon extends Model
{
    /* coupon older version
    protected $fillable = [
        'user_id', 'type', 'code','details','discount', 'discount_type', 'start_date', 'end_date'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class);
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }*/

    use HasFactory;

    protected $fillable = ['code', 'scope', 'product_id', 'category_id', 'min_order_amount', 'discount_percentage', 'max_discount', 'start_date', 'end_date', 'status', 'usage_limit'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user')->withPivot('times_used')->withTimestamps();
    }

}
