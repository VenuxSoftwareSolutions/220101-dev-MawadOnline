<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoPricingOverlap implements Rule
{
    protected $from;
    protected $to;
    protected $is_shipping;
    protected $variant_index;
    protected $custom_message;

    public function __construct($from, $to, $is_shipping = false, $variant_index = null, $custom_message = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->is_shipping = $is_shipping;
        $this->variant_index = $variant_index;
        $this->custom_message = $custom_message;
    }

    public function passes($attribute, $value = null)
    {
        $ranges = collect($this->from)
            ->zip($this->to)
            ->sort()
            ->values();

        foreach ($ranges as $index => $range) {
            [$from_quantity, $to_quantity] = $range;

            if ($from_quantity >= $to_quantity) {
                return false;
            }

            if (isset($ranges[$index + 1])) {
                [$next_from_quantity,] = $ranges[$index + 1];
                if ($to_quantity > $next_from_quantity) {
                    return false;
                }
            }
        }

        return true;
    }

    public function message()
    {
        if ($this->is_shipping) {
            return "Shipping duration/charge ranges should not overlap.";
        } elseif (!is_null($this->variant_index)) {
            return "Variant {$this->variant_index} pricing configuration should not overlap.";
        }

        return $this->custom_message ?? 'Default pricing configuration should not be overlapped.';
    }
}

