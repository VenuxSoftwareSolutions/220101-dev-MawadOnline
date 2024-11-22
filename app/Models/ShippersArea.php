<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippersArea extends Model
{
    use HasFactory;

    function shipper() {
        return $this->belongsTo(Shipper::class);
    }
}
