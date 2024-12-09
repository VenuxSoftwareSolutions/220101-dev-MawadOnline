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
        'usage_limit'
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
        return \DB::table('product_categories')
            ->where('category_id', $categoryId)
            ->where('product_id', $productId)
            ->exists();
    }

    // Helper to check if two date ranges overlap
    public static function isDateRangeOverlap($existingCoupon, $newCouponData)
    {
        return !($newCouponData['end_date'] < $existingCoupon->start_date || $newCouponData['start_date'] > $existingCoupon->end_date);
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


    //check for exisiting applicable coupons a product and returns the highest one
    public static function getHighestPriorityCouponByProduct($productId)
    {
        $coupons = self::where(function ($query) use ($productId) {
            $query->where('scope', 'product')->where('product_id', $productId)
                ->orWhere(function ($subQuery) use ($productId) {
                    $subQuery->where('scope', 'category')
                             ->whereIn('category_id', function ($categoryQuery) use ($productId) {
                                 $categoryQuery->select('category_id')
                                               ->from('product_categories')
                                               ->where('product_id', $productId);
                             });
                })
                ->orWhereIn('scope', ['allOrders', 'min_order_amount']);
        })->whereDate('start_date', '<=', now())
          ->whereDate('end_date', '>=', now())
          ->get();
        if ($coupons->isEmpty()) {
            return null; 
        }
    
        $highestPriorityCoupon = $coupons->sort(function ($a, $b) {
            return [$b->discount_percentage, $b->max_discount, $b->created_at] <=> [$a->discount_percentage, $a->max_discount, $a->created_at];
        })->first();
    
        return $highestPriorityCoupon ? $highestPriorityCoupon->code : null;
    }
    
    //check for the highest exisiting coupon on a specific product and returns its  percentage

    public static function getDiscountPercentage($productId)
    {
        $highestCouponCode = self::getHighestPriorityCouponByProduct($productId);
        
        if (!$highestCouponCode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon not found or not applicable.'
            ], 404);
        }
        $highestCoupon = self::where('code', $highestCouponCode)->first();

        if ($highestCoupon->scope === 'product' && $highestCoupon->product_id != $productId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon does not apply to this product.'
            ], 404);
        }

        if ($highestCoupon->scope === 'category' && !self::isProductInCategory($productId, $highestCoupon->category_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon does not apply to this product category.'
            ], 404);
        }

        return [
            'discount_percentage' => $highestCoupon->discount_percentage,
            'max_discount_amount' => $highestCoupon->max_discount,
            'code' => $highestCoupon->code
        ];
    }

}

