<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactPersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Auth::check()) {

            throw new AuthorizationException(translate('Your account is not verified. Please create an account and confirm it.'));
            return false;
        }

        // Check if the user's account is not verified (assuming 'verified' is a column in the users table)
        if (!Auth::user()->email_verified_at) {
            throw new AuthorizationException(translate('Your account is not verified. Please create an account and confirm it.'));
            return false;
        }
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
            'first_name' => 'nullable|string|max:64',
            'last_name' => 'nullable|string|max:64',
            'email' => 'nullable|email',
            'mobile_phone' => $this->input('mobile_phone') != '+971' ? ['nullable', 'string', 'max:16', new \App\Rules\UaeMobilePhone] :'',
            'additional_mobile_phone' => $this->input('additional_mobile_phone') != '+971' ? ['nullable', 'string', 'max:16', new \App\Rules\UaeMobilePhone]:'',
            'nationality' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:-18 years',
            'emirates_id_number' => ['nullable', 'string', 'max:15', 'regex:/^[0-9]{15}$/'],
            'emirates_id_expiry_date' => 'nullable|date|after_or_equal:today',
            'emirates_id_file' => /* !isset($this->emirates_id_file_old) ? */  'nullable|file|mimes:pdf,jpeg,png|max:5120' /* : '' */,
            'business_owner' => 'nullable|boolean',
            'designation' => 'nullable|string|max:64',
        ];
    }
}
