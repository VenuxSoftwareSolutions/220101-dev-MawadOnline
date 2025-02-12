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

    public function max_min_value($conditions = null, $unit = null)
    {
        $productAttributeValues = $this->attribute_values_filter($conditions);

        $attribute_max = 1;
        $attribute_min = 0;
        $unit_select = Unity::find($unit);

        foreach ($productAttributeValues as $value) {
            $unit = Unity::find($value->id_units);

            if ($unit_select && $unit) {
                $attribute_value = intval($value->value) * $unit->rate / $unit_select->rate;
            } else {
                $attribute_value = intval($value->value);
            }
            if ($attribute_value > $attribute_max) {
                $attribute_max = $attribute_value;
            } elseif ($attribute_min == 0) {
                $attribute_min = $attribute_value;
            } elseif ($attribute_value < $attribute_min) {
                $attribute_min = $attribute_value;
            }
        }

        if ($attribute_max) {
            $attribute_max = $attribute_max;
        } else {
            $attribute_max = 9999;
        }
        if ($attribute_min && ! ($attribute_max == $attribute_min)) {
            $attribute_min = $attribute_min;
        } else {
            $attribute_min = 0;
        }

        return [
            'min' => $attribute_min,
            'max' => $attribute_max,
        ];
    }
}
