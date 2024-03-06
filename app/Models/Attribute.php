<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AttributeValue;
use App\Models\AttributesUnity;
use App;

class Attribute extends Model
{
    protected $with = ['attribute_translations'];

    public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $attribute_translation = $this->attribute_translations->where('lang', $lang)->first();
      return $attribute_translation != null ? $attribute_translation->$field : $this->$field;
    }

    public function attribute_translations(){
      return $this->hasMany(AttributeTranslation::class);
    }

    public function attribute_values() {
        return $this->hasMany(AttributeValue::class);
    }

    public function attribute_values_color() {
        $values_color = AttributeValue::where('attribute_id', $this->id)->whereNotNull('color_code')->get();
        return $values_color;
    }

    public function attribute_values_list() {
        $values_list = AttributeValue::where('attribute_id', $this->id)->whereNull('color_code')->get();
        return $values_list;
    }

    public function get_attribute_units(){
        $units = AttributesUnity::where('attribute_id', $this->id)->pluck('unite_id')->toArray();
        return $units;
    }

}
