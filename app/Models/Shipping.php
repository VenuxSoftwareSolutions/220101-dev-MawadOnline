<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $casts = [
        'is_sample' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
