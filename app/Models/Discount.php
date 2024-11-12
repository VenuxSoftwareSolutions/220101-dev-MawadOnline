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
        'status' => 'boolean',

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
    
        // Fetch all discounts within the date range of the new discount
        $existingDiscounts = self::where(function ($query) use ($newDiscountData) {
            $query->whereDate('start_date', '<=', $newDiscountData['end_date'])
                  ->whereDate('end_date', '>=', $newDiscountData['start_date']);
        })->get();
    
        foreach ($existingDiscounts as $discount) {
            switch ($newDiscountData['scope']) {
                case 'product':
                    if ($discount->scope == 'product' && $discount->product_id == $newDiscountData['product_id']) {
                        // Product scope with same product and overlapping date
                        $overlaps[] = $discount;
                    } elseif ($discount->scope == 'category' && self::isProductInCategory($newDiscountData['product_id'], $discount->category_id)) {
                        // Product in an existing category discount with overlapping date
                        $overlaps[] = $discount;
                    } elseif (in_array($discount->scope, ['allOrders', 'ordersOverAmount']) && self::isDateRangeOverlap($discount, $newDiscountData)) {
                        // Overlapping with 'all_orders' or 'ordersOverAmount'
                        $overlaps[] = $discount;
                    }
                    break;
    
                case 'allOrders':
                case 'ordersOverAmount':
                    if (in_array($discount->scope, ['product', 'category', 'allOrders', 'ordersOverAmount']) && self::isDateRangeOverlap($discount, $newDiscountData)) {
                        // Overlapping with any scope
                        $overlaps[] = $discount;
                    }
                    break;
    
                case 'category':
                    if (in_array($discount->scope, ['allOrders', 'ordersOverAmount']) && self::isDateRangeOverlap($discount, $newDiscountData)) {
                        // Overlapping with 'all_orders' or 'ordersOverAmount'
                        $overlaps[] = $discount;
                    } elseif ($discount->scope == 'category' && $discount->category_id == $newDiscountData['category_id'] && self::isDateRangeOverlap($discount, $newDiscountData)) {
                        // Same category and overlapping date
                        $overlaps[] = $discount;
                    } elseif ($discount->scope == 'product' && self::isProductInCategory($discount->product_id, $newDiscountData['category_id']) && self::isDateRangeOverlap($discount, $newDiscountData)) {
                        // Product in the new category and overlapping date
                        $overlaps[] = $discount;
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
    public static function isDateRangeOverlap($existingDiscount, $newDiscountData)
    {
        return !($newDiscountData['end_date'] < $existingDiscount->start_date || $newDiscountData['start_date'] > $existingDiscount->end_date);
    }
       




}
