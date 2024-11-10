<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['scope', 'product_id', 'category_id', 'user_id','min_order_amount', 'discount_percentage', 'max_discount', 'start_date', 'end_date', 'status'];

    
    /*casts*/
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /*Model Relationships*/
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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

    /*helpers*/
    public static function checkForOverlappingDiscounts($newDiscountData)
    {
        $overlaps = [];

        $existingDiscounts = self::where('scope', $newDiscountData['scope'])
            ->where(function ($query) use ($newDiscountData) {
                $query->whereDate('start_date', '<=', $newDiscountData['end_date'])
                      ->whereDate('end_date', '>=', $newDiscountData['start_date']);
            })
            ->get();

        foreach ($existingDiscounts as $discount) {
            if ($discount->scope == 'all_orders' || $discount->scope == 'order') {
                if (self::isDateRangeOverlap($discount, $newDiscountData)) {
                    $overlaps[] = $discount;
                }
            } elseif ($discount->scope == 'category') {
                if (self::isCategoryOverlap($discount, $newDiscountData)) {
                    $overlaps[] = $discount;
                }
            } elseif ($discount->scope == 'product') {
                if ($discount->product_id == $newDiscountData['product_id']) {
                    $overlaps[] = $discount;
                }
            }
        }

        return $overlaps;
    }

    // Helper to check if two date ranges overlap
    public static function isDateRangeOverlap($existingDiscount, $newDiscountData)
    {
        return !($newDiscountData['end_date'] < $existingDiscount->start_date || $newDiscountData['start_date'] > $existingDiscount->end_date);
    }

    // Helper to check if a discount in the same category has overlapping products
    public static function isCategoryOverlap($existingDiscount, $newDiscountData)
    {
        if ($newDiscountData['scope'] !== 'category') {
            return false;
        }

        // Fetch products under the specified category
        $categoryProducts = \DB::table('product_categories')
            ->where('category_id', $existingDiscount->category_id)
            ->pluck('product_id')
            ->toArray();

        return in_array($newDiscountData['product_id'], $categoryProducts);
    }




}
