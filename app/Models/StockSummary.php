<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockSummary extends Model
{
    protected $fillable = [
        'variant_id',
        'warehouse_id',
        'current_total_quantity',
        'seller_id',
        'current_total_quantity',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(Product::class, 'variant_id', 'id');
    }
}
