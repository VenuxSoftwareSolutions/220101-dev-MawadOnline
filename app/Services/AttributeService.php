<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\AttributeTranslation;
use App\Models\AttributesUnity;

class AttributeService
{
    public function store(array $data)
    {
        $collection = collect($data);

        $trim_name = trim(preg_replace('/\s+/', ' ', $data['name']));
        $trim_display_name_english = trim(preg_replace('/\s+/', ' ', $data['display_name_english']));
        $trim_display_name_arabic = trim(preg_replace('/\s+/', ' ', $data['display_name_arabic']));
        $check = Attribute::where('name', $trim_name)->first();
        if($check == null){
            $attribute = new Attribute();
            $attribute->name = $trim_name;
            $attribute->name_display_english = $trim_display_name_english;
            $attribute->name_display_arabic = $trim_display_name_arabic;
            $attribute->type_value = $data['type_value'];
            $attribute->description_english = $data['description_english'];
            $attribute->description_arabic = $data['description_arabic'];
            $attribute->save();

            $attribute_translation_english = new AttributeTranslation();
            $attribute_translation_english->attribute_id = $attribute->id;
            $attribute_translation_english->name = $trim_display_name_english;
            $attribute_translation_english->lang = 'en';
            $attribute_translation_english->save();

            $attribute_translation_arabic = new AttributeTranslation();
            $attribute_translation_arabic->attribute_id = $attribute->id;
            $attribute_translation_arabic->name = $trim_display_name_arabic;
            $attribute_translation_arabic->lang = 'ar';
            $attribute_translation_arabic->save();

            if(($data['type_value'] == "numeric") && (count($data['units']) > 0)){
                $units = array_filter($data['units']);
                foreach($units as $unite){
                    $attribute_unite = new AttributesUnity();
                    $attribute_unite->attribute_id  = $attribute->id;
                    $attribute_unite->unite_id  = $unite;
                    $attribute_unite->save();
                }
            }

            if($data['type_value'] == "color"){
                if(count($data['color_name']) > 0 ){
                    foreach($data['color_name'] as $key => $color_name){
                        if(($color_name != null) && ($data['color_code'][$key] != null)){
                            $attribute_value = new AttributeValue();
                            $attribute_value->attribute_id = $attribute->id;
                            $attribute_value->value = $color_name;
                            $attribute_value->color_code = $data['color_code'][$key];
                            $attribute_value->save();
                        }
                    }
                }
            }


            if($data['type_value'] == "list"){
                $data_values_arabic = $data['values_arabic'];
                $data_values_english = $data['values_english'];

                if((count($data_values_english) > 0 ) && (count($data_values_arabic) > 0 ) && (count($data_values_arabic) == count($data_values_english))){
                    foreach($data_values_english as $key => $values_english){
                        if(($values_english != null) && ($data_values_arabic[$key] != null)){
                            $attribute_value_english = new AttributeValue();
                            $attribute_value_english->attribute_id = $attribute->id;
                            $attribute_value_english->value = $values_english;
                            $attribute_value_english->lang = 'en';
                            $attribute_value_english->save();

                            $attribute_value_arabic = new AttributeValue();
                            $attribute_value_arabic->attribute_id = $attribute->id;
                            $attribute_value_arabic->value = $data_values_arabic[$key];
                            $attribute_value_arabic->lang = 'ar';
                            $attribute_value_arabic->save();
                        }

                    }
                }
            }
            return $attribute;
        }else{
            return null;
        }

    }

    public function update($data){

        $collection = collect($data);

        $trim_name = trim(preg_replace('/\s+/', ' ', $data['name']));
        $trim_display_name_english = trim(preg_replace('/\s+/', ' ', $data['display_name_english']));
        $trim_display_name_arabic = trim(preg_replace('/\s+/', ' ', $data['display_name_arabic']));
        $check = Attribute::where('name', $trim_name)->first();

        if($check != null){
            if($check->id != $data['attribue_id']) {
                return null;
            }
        }
        $attribute = Attribute::find($data['attribue_id']);
        //get old type value to check if changed when insert values, if changed then i'll delete all values
        $old_type = $attribute->type_value;

        $attribute->name = $trim_name;
        $attribute->name_display_english = $trim_display_name_english;
        $attribute->name_display_arabic = $trim_display_name_arabic;
        $attribute->type_value = $data['type_value'];
        $attribute->description_english = $data['description_english'];
        $attribute->description_arabic = $data['description_arabic'];
        $attribute->save();

        $attribute_translation_english = AttributeTranslation::where('attribute_id', $attribute->id)->where('lang', 'en')->first();
        $attribute_translation_english->name = $trim_display_name_english;
        $attribute_translation_english->save();

        $attribute_translation_arabic = AttributeTranslation::where('attribute_id', $attribute->id)->where('lang', 'ar')->first();
        $attribute_translation_arabic->name = $trim_display_name_arabic;
        $attribute_translation_arabic->save();

        if($old_type != $data['type_value']){
            $attribute_values_to_delete = AttributeValue::where('attribute_id', $attribute->id)->get();

            if(count($attribute_values_to_delete) > 0){
                foreach ($attribute_values_to_delete as $record) {
                    $record->delete();
                }
            }
        }

        if(($data['type_value'] == "numeric") && (count($data['units']) > 0)){
            $units = array_filter($data['units']);
            $difference_to_insert = array_diff($units, $attribute->get_attribute_units());

            if(count($difference_to_insert) > 0){
                foreach($difference_to_insert as $unite){
                    $attribute_unite = new AttributesUnity();
                    $attribute_unite->attribute_id  = $attribute->id;
                    $attribute_unite->unite_id  = $unite;
                    $attribute_unite->save();
                }
            }

            $difference_to_delete = array_diff($attribute->get_attribute_units(), $units);
            if(count($difference_to_delete) > 0){
                $units_attribute = AttributesUnity::where('attribute_id', $data['attribue_id'])->whereIn('unite_id', $difference_to_delete)->get();
                if(count($units_attribute) > 0){
                    foreach ($units_attribute as $record) {
                        $record->delete();
                    }
                }
            }
        }

        if($data['type_value'] == "color"){
            $ids_color = [];

            foreach ($data as $key => $value) {
                if (strpos($key, "colors_name") === 0) {
                    $id = explode("-", $key);
                    $id = $id[1];
                    if (!in_array($id, $ids_color)) {
                        array_push($ids_color, $id);
                    }
                }
            }
            if(count($ids_color) > 0){
                foreach ($ids_color as $id) {
                    $new_key_color_name = 'colors_name-'.$id;
                    $new_key_color_code = 'colors_code-'.$id;
                    $old_value_color = AttributeValue::find($id);
                    $old_value_color->value = $data[$new_key_color_name];
                    $old_value_color->color_code = $data[$new_key_color_code];
                    $old_value_color->save();
                }
            }

            if(array_key_exists('color_name', $data)){
                if(count($data['color_name']) > 0 ){
                    foreach($data['color_name'] as $key => $color_name){
                        if(($color_name != null) && ($data['color_code'][$key] != null)){
                            $attribute_value = new AttributeValue();
                            $attribute_value->attribute_id = $attribute->id;
                            $attribute_value->value = $color_name;
                            $attribute_value->color_code = $data['color_code'][$key];
                            $attribute_value->save();
                        }
                    }
                }
            }
        }


        if($data['type_value'] == "list"){
            $ids_values_arabic = [];
            $ids_values_english = [];

            foreach ($data as $key => $value) {
                if (strpos($key, "value_english") === 0) {
                    $id = explode("-", $key);
                    $id = $id[1];
                    if (!in_array($id, $ids_values_english)) {
                        array_push($ids_values_english, $id);
                    }
                }

                if (strpos($key, "value_arabic") === 0) {
                    $id = explode("-", $key);
                    $id = $id[1];
                    if (!in_array($id, $ids_values_arabic)) {
                        array_push($ids_values_arabic, $id);
                    }
                }
            }

            foreach ($ids_values_arabic as $key => $id) {
                $new_key_arabic_value = 'value_arabic-'.$id;
                $new_key_english_value = 'value_english-'.$ids_values_english[$key];

                $old_value_arabic_value = AttributeValue::find($id);
                $old_value_arabic_value->value = $data[$new_key_arabic_value];
                $old_value_arabic_value->save();

                $old_value_english_value = AttributeValue::find($ids_values_english[$key]);
                $old_value_english_value->value = $data[$new_key_english_value];
                $old_value_english_value->save();
            }

            if(array_key_exists('values_arabic', $data)){
                $data_values_arabic = $data['values_arabic'];
                $data_values_english = $data['values_english'];
                if((count($data_values_english) > 0 ) && (count($data_values_arabic) > 0 ) && (count($data_values_arabic) == count($data_values_english))){
                    foreach($data_values_english as $key => $values_english){
                        if(($values_english != null) && ($data_values_arabic[$key] != null)){
                            $attribute_value_english = new AttributeValue();
                            $attribute_value_english->attribute_id = $attribute->id;
                            $attribute_value_english->value = $values_english;
                            $attribute_value_english->lang = 'en';
                            $attribute_value_english->save();

                            $attribute_value_arabic = new AttributeValue();
                            $attribute_value_arabic->attribute_id = $attribute->id;
                            $attribute_value_arabic->value = $data_values_arabic[$key];
                            $attribute_value_arabic->lang = 'ar';
                            $attribute_value_arabic->save();
                        }

                    }
                }
            }
        }

        return $attribute;
    }
}

