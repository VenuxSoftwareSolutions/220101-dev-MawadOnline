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

    public function rules()
    {
        $rules = [
            // Add your static rules here
            'digital' => 'required|boolean',
            'parent_id' => 'required|exists:categories,id',
            'order_level' => 'required|numeric',
            'cover_image' => 'required|string',
            'commision_rate' => 'required|numeric',
            'category_attributes' => 'required|array',
            'category_attributes.*' => 'exists:attributes,id',
            'filtering_attributes' => 'required|array',
            'filtering_attributes.*' => 'exists:attributes,id',
            'featured' => 'sometimes|in:on',
            // Initialize other static rules here
        ];

        // Dynamically add language-specific rules
        foreach ($this->get_all_active_languages() as $key => $language) {
            $langCode = $language->code == 'en' ? '' : '_' . $language->code;
            $maxLength = $language->code == 'en' ? 60 : 110;

            $rules = array_merge($rules, [
                'name' . $langCode => ['required', Rule::unique('category_translations', 'name')->where(function ($query) use ($langCode) {
                    return $query->where('lang', $langCode);
                }), 'max:' . $maxLength],
                'description' . $langCode => 'required|string',
                'meta_title' . $langCode => 'nullable|string|max:' . $maxLength,
                'meta_description' . $langCode => 'nullable|string|max:' . ($maxLength + 140), // Assuming you want a bit longer descriptions
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
