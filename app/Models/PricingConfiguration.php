<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingConfiguration extends Model
{
    use HasFactory;
    protected $fillable = ['id_products'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_products', 'id');
    }
}
