<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AttributeValue;
use App\Models\AttributesUnity;
use App\Models\Unity;
use App\Utility\CategoryUtility;
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

    public function attribute_values_filter($id_products = null) {

        if($this->type_value == "text" || $this->type_value == "numeric"){
            // dd(productAttributeValues::whereIn('id_products', $id_products)->where('id_attribute',$this->id)->first()->value);
            return productAttributeValues::whereIn('id_products', $id_products)->where('id_attribute',$this->id)->get()->unique('value');
        }elseif($this->type_value == "color"){
            // $ids_attribute_value = AttributeValue::where('attribute_id', $this->id)->pluck('color_code')->toArray();
            // return Color::whereIn('id', $ids_attribute_value)->get();
            // dd(Color::all());
            return Color::all();
        }elseif($this->type_value == "list"){
            return $this->attribute_values_list();
        }
        // else{

        // }
        else{
            return [];
        }
    }

    public function get_attribute_units(){
        $units = AttributesUnity::where('attribute_id', $this->id)->pluck('unite_id')->toArray();
        return $units;
    }

    public function get_units(){
        $units = $this->get_attribute_units();
        $units = Unity::whereIn('id',$units)->get();
        return $units;
    }

    public function max_min_value($id_products = null,$unit= null){
        if(!$id_products){
            $id_products = [];
        }
        $productAttributeValues = ProductAttributeValues::whereIn('id_products', $id_products)->where('id_attribute',$this->id)->get();
        
        $attribute_max = 1;
        $attribute_min = 0;
        $unit_select = Unity::find($unit);
        // dd($productAttributeValues);
        foreach($productAttributeValues as $value){
            $unit = Unity::find($value->id_units);
            
            if($unit_select && $unit){
                $attribute_value = intval($value->value) * $unit->rate / $unit_select->rate;
            }else{
                $attribute_value = intval($value->value) ;
            }
            if($attribute_value>$attribute_max){
                $attribute_max = $attribute_value;
            }elseif($attribute_min == 0){
                $attribute_min = $attribute_value;
            }elseif($attribute_value<$attribute_min){
                $attribute_min = $attribute_value;
            }
        }

        if($attribute_max){
            $attribute_max = $attribute_max ;
        }else{
            $attribute_max = 9999 ;
        }
        if($attribute_min && !($attribute_max == $attribute_min)){
            $attribute_min = $attribute_min ;
        }else{
            $attribute_min = 0 ;
        }
        
        return [
            'min' => $attribute_min,
            'max' => $attribute_max
        ];

    }

}
