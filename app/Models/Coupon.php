<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'scope',
        'product_id',
        'category_id',
        'min_order_amount',
        'discount_percentage',
        'max_discount',
        'start_date',
        'end_date',
        'status',
        'usage_limit',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
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

        $existingCoupons = self::where(function ($query) use ($newCouponData) {
            $query->whereDate('start_date', '<=', $newCouponData['end_date'])
                ->whereDate('end_date', '>=', $newCouponData['start_date']);
        })->get();

        foreach ($existingCoupons as $coupon) {
            switch ($newCouponData['scope']) {
                case 'product':
                    if ($coupon->scope == 'product' && $coupon->product_id == $newCouponData['product_id']) {
                        $overlaps[] = $coupon;
                    } elseif ($coupon->scope == 'category' && self::isProductInCategory($newCouponData['product_id'], $coupon->category_id)) {
                        $overlaps[] = $coupon;
                    } elseif (in_array($coupon->scope, ['allOrders', 'min_order_amount']) && self::isDateRangeOverlap($coupon, $newCouponData)) {
                        $overlaps[] = $coupon;
                    }
                    break;

                case 'allOrders':
                case 'min_order_amount':
                    if (in_array($coupon->scope, ['product', 'category', 'allOrders', 'min_order_amount']) && self::isDateRangeOverlap($coupon, $newCouponData)) {
                        $overlaps[] = $coupon;
                    }
                    break;

                case 'category':
                    if (in_array($coupon->scope, ['allOrders', 'min_order_amount']) && self::isDateRangeOverlap($coupon, $newCouponData)) {
                        $overlaps[] = $coupon;
                    } elseif ($coupon->scope == 'category' && $coupon->category_id == $newCouponData['category_id'] && self::isDateRangeOverlap($coupon, $newCouponData)) {
                        $overlaps[] = $coupon;
                    } elseif ($coupon->scope == 'product' && self::isProductInCategory($coupon->product_id, $newCouponData['category_id']) && self::isDateRangeOverlap($coupon, $newCouponData)) {
                        $overlaps[] = $coupon;
                    }
                    break;
            }
        }

        return $overlaps;
    }

    // Helper to check if a product is in a category
    public static function isProductInCategory($productId, $categoryId)
    {
        return ProductCategory::where('category_id', $categoryId)
            ->where('product_id', $productId)
            ->exists();
    }

    // Helper to check if two date ranges overlap
    public static function isDateRangeOverlap($existingCoupon, $newCouponData)
    {
        return ! ($newCouponData['end_date'] < $existingCoupon->start_date || $newCouponData['start_date'] > $existingCoupon->end_date);
    }

    protected function isNewCouponHigherPriority($newCouponData, $existingCoupon)
    {
        if ($newCouponData['discount_percentage'] > $existingCoupon->discount_percentage) {
            return true;
        } elseif ($newCouponData['discount_percentage'] < $existingCoupon->discount_percentage) {
            return false;
        }

        if ($newCouponData['max_discount'] > $existingCoupon->max_discount) {
            return true;
        } elseif ($newCouponData['max_discount'] < $existingCoupon->max_discount) {
            return false;
        }

        return Carbon::now()->greaterThan($existingCoupon->created_at);
    }

    public static function getDiscountDetailsByCode($couponCode, $productId)
    {
        $coupon = self::where('code', $couponCode)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if ($coupon === null) {
            throw new Exception('Coupon not found or expired.');
        }

        if ($coupon->scope === 'product' && $coupon->product_id != $productId) {
            throw new Exception('Coupon does not apply to this product.');
        }

        if ($coupon->scope === 'category' && ! self::isProductInCategory($productId, $coupon->category_id)) {
            throw new Exception('Coupon does not apply to this product category.');
        }

        return [
            'discount_percentage' => $coupon->discount_percentage,
            'max_discount_amount' => $coupon->max_discount,
        ];
    }
}
