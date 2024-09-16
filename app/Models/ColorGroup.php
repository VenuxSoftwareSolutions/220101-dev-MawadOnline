<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorGroup extends Model
{
    use HasFactory;

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'color_group_color');
    }
}
