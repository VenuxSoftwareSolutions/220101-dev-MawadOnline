<?php

namespace App\Rules;

use App\Http\Controllers\OTPVerificationController;
use App\Mail\VerificationCodeEmail;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request; // Use the Request facade

class ReCaptchaV3 implements Rule
{
    private ?string $action;
    private ?float $minScore;
    private string $errorMessage = '';
    private string $email; // To store email


    public function __construct(?string $action = null, ?float $minScore = null)
    {
        $this->action = $action;
        $this->minScore = $minScore;
        $this->email = Request::input('email'); // Access email directly from request
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $siteVerify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha_v3.secretKey'),
            'response' => $value,
        ]);

        if ($siteVerify->failed()) {
            $this->errorMessage = 'Google reCAPTCHA was not able to verify the form, please try again.';
            return false;
        }

        $body = $siteVerify->json();

        if ($body['success'] !== true) {
            $this->errorMessage = 'Your form submission failed the Google reCAPTCHA verification, please try again.';
            return false;
        }

        if (!is_null($this->action) && $this->action != $body['action']) {
            $this->errorMessage = 'The action found in the form didn\'t match the Google reCAPTCHA action, please try again.';
            return false;
        }

        if (!is_null($this->minScore) && $this->minScore > $body['score']) {
            $verificationCode = rand(100000, 999999); // Example OTP generation, consider a more secure method
            $expirationTime = now()->addMinutes(5); // Set expiration time to 10 minutes

            if(User::where('email',$this->email)->exists())
            {
                // Save the verification code and expiration time in the database
                VerificationCode::create([
                    'email' => $this->email,
                    'code' => $verificationCode,
                    'expires_at' => $expirationTime,
                ]);

                \Mail::to($this->email)->send(new VerificationCodeEmail($verificationCode));
            }

            $this->errorMessage = 'The Google reCAPTCHA verification score was too low, please try again.';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->errorMessage;
    }
}