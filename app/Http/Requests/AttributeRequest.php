<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class AttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'unique:attributes'],
            'display_name_english' => ['required' , 'max:128'],
            'display_name_arabic' => ['required' , 'max:64'],
            'type_value' => ['required' , 'max:128']
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required'             => translate('Attribute name is required'),
            'name_display_english.required'             => translate('Attribute name to display in english version is required'),
            'name_display_arabic.required'             => translate('Attribute name to display in arabic version is required'),
            'type_value.required'             => translate('Value type is required'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        // dd($this->expectsJson());
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => $validator->errors()->all(),
                'result' => false
            ], 422));
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}
