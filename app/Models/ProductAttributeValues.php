<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValues extends Model
{
    use HasFactory;


    public function attribute() {
        return $this->belongsTo(Attribute::class,'id_attribute') ;
    }

    public function unity() {
        return $this->belongsTo(Unity::class,'id_units') ;
    }

    public function color() {
        return $this->belongsTo(Color::class,'id_units') ;
    }
}
