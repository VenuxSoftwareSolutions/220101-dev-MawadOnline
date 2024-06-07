<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tour extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description'];

    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $tour_translation = $this->hasMany(TourTranslation::class)->where('lang', $lang)->first();
        return $tour_translation != null ? $tour_translation->$field : $this->$field;
    }

    public function tour_translation(){
      return $this->hasMany(TourTranslation::class);
    }
}
