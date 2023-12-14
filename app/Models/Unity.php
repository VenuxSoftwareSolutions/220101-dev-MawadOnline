<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Unity extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = 'unites';
    public $translatable = ['name'];
}
