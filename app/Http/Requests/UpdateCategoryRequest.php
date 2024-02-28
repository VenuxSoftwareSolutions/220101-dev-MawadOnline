<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $processedNames = [];

        $fieldName = 'name';

        if ($this->has($fieldName)) {
            // Trim the string and replace consecutive whitespaces with a single space
            $processedName = preg_replace('/\s+/', ' ', trim($this->$fieldName));
            $processedNames[$fieldName] = $processedName;
        }

        // Merge processed names back into the request data
        $this->merge($processedNames);
    }

    public function rules()
    {
        $categoryId = $this->category; // Assuming 'category' is the route parameter name for the category ID

        $rules = [
            // Adjusted rules for update
            'digital' => 'sometimes|boolean',
            'parent_id' => ['sometimes', 'exists:categories,id',$categoryId != 1 ? Rule::notIn([$categoryId]) : null], // Prevent category from being its own parent
            'thumbnail_image' => 'nullable|image',
            'cover_image' => 'nullable|image',
            'commision_rate' => 'sometimes|numeric',
            'category_attributes' => 'nullable|array',
            'category_attributes.*' => 'nullable|exists:attributes,id',
            'filtering_attributes' => 'nullable|array',
            'filtering_attributes.*' => 'nullable|exists:attributes,id',
            'featured' => 'sometimes|in:on',
            // Other static rules...
        ];


        $langCode = $this->lang;
        $maxLength = $this->lang == 'en' ? 60 : 110;
        $descMaxLength = $this->lang == 'en' ? 128 : 240;

        $rules = array_merge($rules, [
            'name'=> [
                'sometimes',
                'max:' . $maxLength,
                function ($attribute, $value, $fail) use ($langCode, $categoryId) {
                    // Adjusted check for case-insensitive uniqueness to exclude current category
                    $exists = \DB::table('category_translations')
                        ->whereRaw('LOWER(name) = LOWER(?)', [$value])
                        ->where('lang', $langCode)
                        ->where('category_id', '!=', $categoryId)
                        ->exists();

                    if ($exists) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'description'=> 'sometimes|string|max:' . $descMaxLength,
            'meta_title' => 'nullable|string|max:' . $maxLength,
            'meta_description'=> 'nullable|string|max:' . ($maxLength + 140),
            // Additional language-specific rules...
        ]);

        return $rules;
    }

   
}
