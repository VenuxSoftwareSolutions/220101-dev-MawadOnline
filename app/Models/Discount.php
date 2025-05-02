<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['scope', 'product_id', 'category_id', 'user_id', 'min_order_amount', 'discount_percentage', 'max_discount','min_qty',
    'max_qty','start_date', 'end_date', 'status'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
    ];

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

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeWithinDateRange($query)
    {
        return $query->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now());
    }

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

    public static function isProductInCategory($productId, $categoryId)
    {
        return \DB::table('product_categories')
            ->where('category_id', $categoryId)
            ->where('product_id', $productId)
            ->exists();
    }

    public static function isDateRangeOverlap($existingDiscount, $newDiscountData)
    {
        return ! ($newDiscountData['end_date'] < $existingDiscount->start_date || $newDiscountData['start_date'] > $existingDiscount->end_date);
    }

    protected function isNewDiscountHigherPriority($newDiscountData, $existingDiscount)
    {
        if ($newDiscountData['discount_percentage'] > $existingDiscount->discount_percentage) {
            return true;
        } elseif ($newDiscountData['discount_percentage'] < $existingDiscount->discount_percentage) {
            return false;
        }

        $ignoreMaxForExisting = in_array($existingDiscount->scope, ['product', 'category']);
        $ignoreMaxForNew = in_array($newDiscountData['scope'], ['product', 'category']);

        if (!$ignoreMaxForExisting && !$ignoreMaxForNew) {
            if ($newDiscountData['max_discount'] > $existingDiscount->max_discount) {
                return true;
            } elseif ($newDiscountData['max_discount'] < $existingDiscount->max_discount) {
                return false;
            }
        }

        return Carbon::now()->greaterThan($existingDiscount->created_at);
    }

    public static function getHighestPriorityDiscountByProduct($productId)
    {
        $discounts = self::where(function ($query) use ($productId) {
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
        })->withinDateRange()->active()->get();

        if ($discounts->isEmpty()) {
            return null;
        }

        $highestPriorityDiscount = $discounts->sort(function ($a, $b) {
            $aMax = in_array($a->scope, ['product', 'category']) ? PHP_INT_MAX : $a->max_discount;
            $bMax = in_array($b->scope, ['product', 'category']) ? PHP_INT_MAX : $b->max_discount;

            return [
                $b->discount_percentage,
                $bMax,
                $b->created_at,
            ] <=> [
                $a->discount_percentage,
                $aMax,
                $a->created_at,
            ];
        })->first();


        return $highestPriorityDiscount;
    }

    public static function getHighestPriorityOrderDiscount($vendor_id)
    {
        $discounts = self::whereIn('scope', ['allOrders'])
            ->where("user_id", $vendor_id)
            ->withinDateRange()->active()->get();

        if ($discounts->isEmpty()) {
            return null;
        }

        return $discounts->sort(function ($a, $b) {
            $aMax = $a->max_discount;
            $bMax = $b->max_discount;

            return [
                $b->discount_percentage,
                $bMax,
                $b->created_at,
            ] <=> [
                $a->discount_percentage,
                $aMax,
                $a->created_at,
            ];
        })->first();
    }

    public static function getDiscountPercentage($productId)
    {
        $highestDiscount = self::getHighestPriorityDiscountByProduct($productId);

        if (! $highestDiscount) {
            throw new \Exception('Discount not found or not applicable.');
        }


        if ($highestDiscount->scope === 'product' && $highestDiscount->product_id != $productId) {
            throw new \Exception('Discount does not apply to this product.');
        }

        if ($highestDiscount->scope === 'category' && ! self::isProductInCategory($productId, $highestDiscount->category_id)) {
            throw new \Exception('Discount does not apply to this product category.');
        }

        $result = ['discount_percentage' => $highestDiscount->discount_percentage];

        if (!in_array($highestDiscount->scope, ['product', 'category'])) {
            $result['discount_percentage'] = 0;
            $result['max_discount_amount'] = 0;
        }

        return $result;
    }

    public function isApplicableForQuantity($qty)
    {
        if ((!is_null($this->min_qty) && $qty < $this->min_qty) ||
            (!is_null($this->max_qty) && $qty > $this->max_qty)) {
            return false;
        }

        return true;
    }
}
