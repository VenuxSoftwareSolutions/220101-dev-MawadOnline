<?php

namespace App\Models;

use App;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $with = ['attribute_translations'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $attribute_translation = $this->attribute_translations->where('lang', $lang)->first();

        return $attribute_translation != null ? $attribute_translation->$field : $this->$field;
    }

    public function attribute_translations()
    {
        return $this->hasMany(AttributeTranslation::class);
    }

    public function attribute_values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function attribute_values_color()
    {
        $values_color = AttributeValue::where('attribute_id', $this->id)->whereNotNull('color_code')->get();

        return $values_color;
    }

    public function attribute_values_list()
    {
        $values_list = AttributeValue::where('attribute_id', $this->id)->whereNull('color_code')->get();

        return $values_list;
    }

    public function attribute_values_filter($conditions = null)
    {
        if ($this->type_value == 'text' || $this->type_value == 'numeric') {

            $products = Product::where('published', '1')->where('auction_product', 0)->where('approved', '1');
            if (isset($conditions['categories']) && $conditions['categories']) {
                $products->whereIn('category_id', $conditions['categories']);
            }

            if (isset($conditions['query']) && $conditions['query']) {
                $products->where('name', 'like', '%'.$conditions['query'].'%');
            }
            $products->join('product_attribute_values', 'products.id', 'product_attribute_values.id_products')->where('id_attribute', $this->id)->distinct('product_attribute_values.value');

            return $products->get();
        } elseif ($this->type_value == 'color') {
            return Color::all();
        } elseif ($this->type_value == 'list') {
            return $this->attribute_values_list();
        } else {
            return [];
        }
    }

    public function get_attribute_units()
    {
        return AttributesUnity::where('attribute_id', $this->id)
            ->pluck('unite_id')
            ->toArray();
    }

    public function get_units()
    {
        $units = $this->get_attribute_units();

        return Unity::whereIn('id', $units)->get();
    }

    public function getDefaultUnit()
    {
        return $this->get_units()
                   ->filter(fn ($unit) => $unit->default_unit === $unit->id)
                   ->first();
    }

    function max_min_value($conditions, $unit_id) {
        $result = \DB::table('product_attribute_values')
            ->where('id_attribute', $this->id)
            ->where('id_units', $unit_id)
            ->selectRaw('MIN(value) as min_value, MAX(value) as max_value')
            ->first();
    
        return [
            'min' => $result->min_value ?? 0,
            'max' => $result->max_value ?? 1,
        ];
    }
}
