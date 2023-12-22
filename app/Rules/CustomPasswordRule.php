<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\File;
use Str;

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
        // Check for three consecutive characters or their reverses in the same case
            $patterns = [
                'abc', 'bcd', 'cde', 'def', 'efg', 'fgh', 'ghi', 'hij', 'ijk', 'jkl',
                'klm', 'lmn', 'mno', 'nop', 'opq', 'pqr', 'qrs', 'rst', 'stu', 'tuv',
                'uvw', 'vwx', 'wxy', 'xyz'
            ];

            $patternRegex = implode('|', $patterns);

            $reversedPatterns = array_map('strrev', $patterns);

            $reversedPatternRegex = implode('|', $reversedPatterns);

            if (preg_match("/$patternRegex|$reversedPatternRegex/i", $value)) {
                $this->failures[] = 'No three consecutive characters or their reverses in the same case are allowed';
            }

        if (!Str::contains($value, ['@', '#', '-', '+', '/', '=', '$', '!', '%', '*', '?', '&'])) {
            $this->failures[] = 'At least one special character';
        }

        // At least one sign
        if (!preg_match('/[^a-zA-Z0-9]/', $value)) {
            $this->failures[] = 'At least one special character';
        }

        // No space allowed
        if (strpos($value, ' ') !== false) {
            $this->failures[] = 'No spaces allowed';
        }

        if (preg_match('/012|123|234|345|456|567|678|789|987|876|765|654|543|432|321|210/', $value)) {
            $this->failures[] = 'No three consecutive numbers, Example 678,543,987';

        }


        $length = strlen($value);
        $characterCount = array_count_values(str_split($value));

        $repeatedCount = 0;
        foreach ($characterCount as $char => $count) {
            if ($count > 1) {
                $repeatedCount += $count;
            }
        }

        $repetitiveCharacterPercentage = ($repeatedCount / $length) * 100;

        if ($repetitiveCharacterPercentage > 40) {
            $this->failures[] = 'No more than 40% repetitive characters.';
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
        $length = strlen($value);
        $uniqueCharacters = count(array_count_values(str_split($value)));
        $repeatedCharacterPercentage = ($length - $uniqueCharacters) / $length * 100;

        if ($repeatedCharacterPercentage > 40) {
            $this->failures[] = 'No more than 40% of the password can be repeated characters';
        }
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
        $dictionaryPath = public_path('dictionary/dictionary.txt');
        $dictionaryWords = File::lines($dictionaryPath);

        foreach ($dictionaryWords as $word) {
            if (stripos($value, $word) !== false) {
                $this->failures[] = 'No substring of the password can be a common English dictionary word';
                break; // Break the loop once one dictionary word is found
            }
        }

        return empty($this->failures);
    }

    public function message()
    {
        return 'The password must meet the following criteria: ' . implode(', ', $this->failures);
    }
}
