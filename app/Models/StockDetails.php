<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockDetails extends Model
{
    const OPERATION_TYPE_ADDITION = 'Stock addition';

    const OPERATION_TYPE_REMOVAL = 'Stock removal';

    protected $fillable = [
        'operation_type', 'variant_id', 'warehouse_id',
        'before_quantity', 'transaction_quantity',
        'after_quantity', 'user_comment',
        'order_id', 'order_detail_id', 'seller_id',
    ];

    protected $with = ["warehouse"];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(Product::class, 'variant_id')->withTrashed();
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }
}
