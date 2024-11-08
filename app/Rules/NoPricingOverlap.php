<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

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
        $from = Arr::sort($this->from);
        $to = Arr::sort($this->to);

        foreach ($from as $index => $from_quantity) {
            if (! isset($to[$index]) || $from_quantity >= $to[$index]) {
                return false;
            }

            if (isset($from[$index + 1]) && $to[$index] > $from[$index + 1]) {
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
