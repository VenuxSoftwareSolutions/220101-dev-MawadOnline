<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UaeMobilePhone implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^(\+971|00971)\d{9}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return translate('the_mobile_phone_number_must_be_a_valid_uae_number_including_the_country_code_971');
    }
}
