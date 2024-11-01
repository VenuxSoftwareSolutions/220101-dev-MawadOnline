<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoPricingOverlap implements Rule
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function passes($attribute, $value = null)
    {
        foreach ($this->from as $index => $from_quantity) {
            if (!isset($this->to[$index]) || $from_quantity >= $this->to[$index]) {
                return false;
            }

            if (isset($this->from[$index + 1]) && $this->to[$index] > $this->from[$index + 1]) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'Pricing ranges should not overlap.';
    }
}
