<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'warehouse_name',
        'emirate_id',
        'area_id',
        'address_street',
        'address_building',
        'address_unit',
        'saveasdraft'
    ];

    public function checkWhHasProducts()
    {
        // Count the number of associated products in stock summaries
        $stockSummaryCount = StockSummary::where('warehouse_id', $this->id)->count();

        // If there are associated products, return true; otherwise, return false
        return $stockSummaryCount > 0;
    }



}
