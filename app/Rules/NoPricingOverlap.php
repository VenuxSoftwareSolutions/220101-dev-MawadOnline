<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoPricingOverlap implements Rule
{
    protected $from;

    protected $to;

    protected $is_shipping;

    public function __construct($from, $to, $is_shipping = false)
    {
        $this->from = $from;
        $this->to = $to;
        $this->is_shipping = $is_shipping;
    }

    public function passes($attribute, $value = null)
    {
        foreach ($this->from as $index => $from_quantity) {
            if (! isset($this->to[$index]) || $from_quantity >= $this->to[$index]) {
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
        return $this->is_shipping ? 'Shipping duration/charge ranges should not overlap.' : 'Default pricing configuration should not be overlapped.';
    }
}
