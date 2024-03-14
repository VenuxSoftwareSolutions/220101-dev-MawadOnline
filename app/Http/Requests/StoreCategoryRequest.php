<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $processedNames = [];
        foreach ($this->get_all_active_languages() as $language) {
            $langCode = '_' . $language->code;
            $fieldName = 'name' . $langCode;

            if ($this->has($fieldName)) {
                // Trim the string and replace consecutive whitespaces with a single space
                $processedName = preg_replace('/\s+/', ' ', trim($this->$fieldName));
                $processedNames[$fieldName] = $processedName;
            }
        }
        // Merge processed names back into the request data
        $this->merge($processedNames);
    }

    public function rules()
    {
        $rules = [
            // Static rules
            'digital' => 'required|boolean',
            'parent_id' => 'required|exists:categories,id',
            'thumbnail_image' => 'required|image',
            'cover_image' => 'required_if:featured,on|image',
            'category_attributes' => 'nullable|array',
            'category_attributes.*' => 'nullable|exists:attributes,id',
            'filtering_attributes' => 'nullable|array',
            'filtering_attributes.*' => 'nullable|exists:attributes,id',
            'featured' => 'sometimes|in:on',
            // Other static rules...
        ];

        // Dynamically add language-specific rules
        foreach ($this->get_all_active_languages() as $key => $language) {
            $langCode = $language->code;
            $maxLength = $language->code == 'en' ? 60 : 110;
            $descMaxLength = $language->code == 'en' ? 128 : 240;

            $rules = array_merge($rules, [
                'name_' . $langCode => [
                    'required',
                    'max:' . $maxLength,
                    function ($attribute, $value, $fail) use ($langCode) {
                        // Check for case-insensitive uniqueness
                        $exists = \DB::table('category_translations')
                            ->whereRaw('LOWER(name) = LOWER(?)', [$value])
                            ->where('lang', $langCode)
                            ->exists();

                        if ($exists) {
                            $fail('The ' . $attribute . ' has already been taken.');
                        }
                    },
                ],
                'description_' . $langCode => 'required|string|max:' . $descMaxLength,
                'meta_title_' . $langCode => 'nullable|string|max:' . $maxLength,
                'meta_description_' . $langCode => 'nullable|string|max:' . $descMaxLength,
                // Additional language-specific rules...
            ]);
        }
        return $rules;

    }

    protected function get_all_active_languages()
    {
        $language_query = Language::query();
        $language_query->where('status', 1);

        return $language_query->get();
    }
}
