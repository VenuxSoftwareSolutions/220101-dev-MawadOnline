<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    public function colorGroups()
    {
        return $this->belongsToMany(ColorGroup::class, 'color_group_color');
    }
}
