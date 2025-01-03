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
        'saveasdraft',
    ];
    protected $with = ["area"];

    public function checkWhHasProducts()
    {
        // Count the number of associated products in stock summaries
        $stockSummaryCount = StockSummary::where('warehouse_id', $this->id)->count();

        // If there are associated products, return true; otherwise, return false
        return $stockSummaryCount > 0;
    }

    public function area()
    {
        return $this->belongsTo(State::class);
    }

    public function emirate()
    {
        return $this->belongsTo(Emirate::class);
    }
}
