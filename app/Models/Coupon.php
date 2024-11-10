<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;


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

    protected $fillable = [
        'code', 'scope', 'product_id', 'category_id', 'min_order_amount',
        'discount_percentage', 'max_discount', 'start_date', 'end_date',
        'status', 'usage_limit'
    ];

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
        return $this->belongsToMany(User::class, 'coupon_user')
                    ->withPivot('times_used')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeWithinDateRange($query)
    {
        return $query->whereDate('start_date', '<=', Carbon::now())
                     ->whereDate('end_date', '>=', Carbon::now());
    }

    // Check if the coupon is valid for usage
    public function isValidForUsage($userId, $orderAmount = null)
    {
        $usageCount = $this->users()->where('user_id', $userId)->count();

        return $this->usage_limit > $usageCount &&
               ($this->min_order_amount === null || $orderAmount >= $this->min_order_amount);
    }

    // Overlap check for coupon creation
    public static function checkForOverlappingCoupons($newCouponData)
    {
        $overlaps = [];

        $existingCoupons = self::where('scope', $newCouponData['scope'])
            ->where(function ($query) use ($newCouponData) {
                $query->whereDate('start_date', '<=', $newCouponData['end_date'])
                      ->whereDate('end_date', '>=', $newCouponData['start_date']);
            })
            ->get();

        foreach ($existingCoupons as $coupon) {
            if ($coupon->scope == 'all_orders' || $coupon->scope == 'order') {
                if (self::isDateRangeOverlap($coupon, $newCouponData)) {
                    $overlaps[] = $coupon;
                }
            } elseif ($coupon->scope == 'category') {
                if (self::isCategoryOverlap($coupon, $newCouponData)) {
                    $overlaps[] = $coupon;
                }
            } elseif ($coupon->scope == 'product') {
                if ($coupon->product_id == $newCouponData['product_id']) {
                    $overlaps[] = $coupon;
                }
            }
        }

        return $overlaps;
    }

    // Helper to check if two date ranges overlap
    public static function isDateRangeOverlap($existingCoupon, $newCouponData)
    {
        return !($newCouponData['end_date'] < $existingCoupon->start_date ||
                 $newCouponData['start_date'] > $existingCoupon->end_date);
    }

    // Helper to check if a coupon in the same category has overlapping products
    public static function isCategoryOverlap($existingCoupon, $newCouponData)
    {
        if ($newCouponData['scope'] !== 'category') {
            return false;
        }

        $categoryProducts = \DB::table('product_categories')
            ->where('category_id', $existingCoupon->category_id)
            ->pluck('product_id')
            ->toArray();

        return in_array($newCouponData['product_id'], $categoryProducts);
    }

}
