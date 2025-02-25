<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionVat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "commission_vat";

    public function subOrderDetail()
    {
        return $this->belongsTo(OrderDetail, "sub_order_id");
    }
}
