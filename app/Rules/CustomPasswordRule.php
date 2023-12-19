<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomPasswordRule implements Rule
{
    protected $firstName;
    protected $lastName;
    protected $email;

    protected $failures = [];

    public function __construct($firstName, $lastName, $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function passes($attribute, $value)
    {
        // Reset failures for each validation attempt
        $this->failures = [];

        // Minimum password length is 9 characters
        if (strlen($value) < 9) {
            $this->failures[] = 'Minimum length of 9 characters';
        }

        // At least one uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            $this->failures[] = 'At least one uppercase letter';
        }

        // At least one lowercase letter
        if (!preg_match('/[a-z]/', $value)) {
            $this->failures[] = 'At least one lowercase letter';
        }

        // At least one number and maximum 4 numbers
        if (!preg_match('/\d/', $value) || preg_match_all('/\d/', $value) > 4) {
            $this->failures[] = 'At least one number and maximum of 4 numbers';
        }

        // At least one sign
        if (!preg_match('/[^a-zA-Z0-9]/', $value)) {
            $this->failures[] = 'At least one special character';
        }

        // No space allowed
        if (strpos($value, ' ') !== false) {
            $this->failures[] = 'No spaces allowed';
        }

        if (preg_match('/012|123|234|345|456|567|678|789/', $value)) {
            $this->failures[] = 'No three consecutive numbers, Example 678,543,987';

        }

    //     // No three consecutive numbers allowed
    //     if (preg_match('/\d{3}/', $value)) {
    //         $this->failures[] = 'No three consecutive numbers allowed';
    //     }

    //     // No three consecutive characters of the same case allowed
    //   // No three consecutive characters of the same case allowed
    //     if (preg_match('/[a-zA-Z]{3}/', $value)) {
    //         $this->failures[] = 'No three consecutive characters of the same case allowed';
    //     }


        // // No more than 40% of the password can be uppercase, lowercase, number, or sign
        // $length = strlen($value);
        // $uppercaseCount = preg_match_all('/[A-Z]/', $value);
        // $lowercaseCount = preg_match_all('/[a-z]/', $value);
        // $numberCount = preg_match_all('/\d/', $value);
        // $signCount = preg_match_all('/[^a-zA-Z0-9]/', $value);

        // if ($uppercaseCount > ($length * 0.4) || $lowercaseCount > ($length * 0.4) || $numberCount > ($length * 0.4) || $signCount > ($length * 0.4)) {
        //     $this->failures[] = 'No more than 40% of the password can be uppercase, lowercase, number, or special character';
        // }

        // No three characters or more can be a substring of first name, last name, or email
        $firstName = $this->firstName;
        $lastName = $this->lastName;
        $email = $this->email;
        $password = $value;

        // Combine the first name, last name, and email into a single string
        $combinedStrings = $firstName . $lastName . $email;

        // Check if any substring of length 3 or more exists in the password
        for ($i = 0; $i < strlen($combinedStrings) - 2; $i++) {
            $substring = substr($combinedStrings, $i, 3);

            // Perform a case-insensitive check
            if (stripos($password, $substring) !== false) {
                $this->failures[] = 'No three characters or more can be a substring of first name, last name, or email';
                break;  // Exit the loop if a failure is detected
            }
        }


        // No substring of the password can be a common English dictionary word
        // $dictionary = file_get_contents('path/to/your/dictionary.txt'); // Replace with the actual path
        // $dictionaryWords = explode("\n", $dictionary);

        // foreach ($dictionaryWords as $word) {
        //     if (stripos($value, $word) !== false) {
        //         $this->failures[] = 'No substring of the password can be a common English dictionary word';
        //         break; // Break the loop once one dictionary word is found
        //     }
        // }

        return empty($this->failures);
    }

    public function message()
    {
        return 'The password must meet the following criteria: ' . implode(', ', $this->failures);
    }
}
