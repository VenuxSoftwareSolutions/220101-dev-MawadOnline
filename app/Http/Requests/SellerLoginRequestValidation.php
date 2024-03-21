<?php

namespace App\Http\Requests;

use App\Rules\ReCaptchaV3;
use Illuminate\Foundation\Http\FormRequest;

class SellerLoginRequestValidation extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string',
            'g-recaptcha-response' => ['required', new ReCaptchaV3('submitLoginForm', 0.5)],
        ];

        // Dynamically adding the otp-code validation rule if otp-code is present in the request
        if ($this->has('otp_code')) {
            $rules['otp_code'] = ['required', 'string', function ($attribute, $value, $fail) {
                // Retrieve the OTP entry from the database that matches the email and otp-code
                $otpEntry = \DB::table('verification_codes') // Assume 'otp_table' is where you store OTPs
                ->where('email', $this->email)
                    ->where('code', $value)
                    ->first();

                // Check if an entry exists and if it's still valid (not expired)
                if (!$otpEntry || now()->greaterThan($otpEntry->expires_at)) {
                    $fail('The provided OTP is invalid or has expired.');
                }
            }];
        }

        return $rules;
    }
}
