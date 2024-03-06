<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AttributeValue extends Model
{
    use HasTranslations;
    public $translatable = ['value'];

    public function attribute() {
        return $this->belongsTo(Attribute::class);
    }
}
