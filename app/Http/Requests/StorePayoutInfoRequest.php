<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StorePayoutInfoRequest extends FormRequest
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
            'bank_name' => 'nullable|string|max:128|regex:/\D/',
            'account_name' => 'nullable|string|max:128|regex:/\D/',
            'account_number' => 'nullable|string|max:30',
            'iban' => 'nullable|string|max:34',
            'swift_code' => 'nullable|string|max:16',
            'iban_certificate' => 'nullable|file|mimes:pdf,jpeg,png|max:5120',
        ];
    }
}
