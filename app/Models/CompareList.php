<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompareList extends Model
{
    protected $fillable = ['user_id', 'category_id', 'category_name', 'variants'];

    protected $casts = [
        'variants' => 'array', 
    ];
}
