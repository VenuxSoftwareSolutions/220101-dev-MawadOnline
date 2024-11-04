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

    // helpers 

    // Check if the discount is valid for a product/category or order
    public function isApplicable($scopeType, $scopeId = null, $orderAmount = null)
    {
        if ($this->scope === 'all_orders') {
            return true;
        }

        if ($this->scope === 'order' && $orderAmount >= $this->min_order_amount) {
            return true;
        }

        if ($this->scope === $scopeType && $this->{$scopeType . '_id'} == $scopeId) {
            return true;
        }

        return false;
    }




}
