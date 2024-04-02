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

}
