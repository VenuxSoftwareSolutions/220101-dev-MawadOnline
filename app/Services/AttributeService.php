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

            if(($data['type_value'] == "numeric") && (count($data['units']) > 0)){
                foreach($data['units'] as $unite){
                    if($unite != null){
                        $attribute_unite = new AttributesUnity();
                        $attribute_unite->attribute_id  = $attribute->id;
                        $attribute_unite->unite_id  = $unite;
                        $attribute_unite->save();
                    }
                }
            }

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
            return $attribute;
        }else{
            return null;
        }

    }
}

