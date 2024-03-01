<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreStaffRequest extends FormRequest
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
        return [
            'first_name' => 'required|regex:/^[\pL\s]+$/u',
            'last_name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email',
            'mobile' => 'required|numeric|digits_between:9,14',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json($validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }
        flash(translate($validator->errors()->first()))->error();
        throw (new HttpResponseException(redirect()->back()->withErrors($validator)->withInput()));
    }
}
