<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class NonOverlappingShippingQuantityPerShipper implements InvokableRule
{
    protected $shipper;
    protected $from;
    protected $to;
    protected $is_variant;

    public function __construct($shipper, $from, $to, $is_variant = false)
    {
        $this->shipper = $shipper;
        $this->from = $from;
        $this->to = $to;
        $this->is_variant = $is_variant;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value = null, $fail)
    {
        $shipperList = $this->shipper ?? [];
        $fromList = $this->from ?? [];
        $toList = $this->to ?? [];
        $groupedByShipper = [];

        foreach ($shipperList as $index => $shipper) {
            if (!isset($groupedByShipper[$shipper])) {
                $groupedByShipper[$shipper] = [];
            }

            $groupedByShipper[$shipper][] = [
                'from' => (int) $fromList[$index],
                'to' => (int) $toList[$index],
            ];
        }

        foreach ($groupedByShipper as $shipper => $ranges) {
            for ($i = 0; $i < count($ranges); $i++) {
                for ($j = $i + 1; $j < count($ranges); $j++) {
                    if (
                        $ranges[$i]['from'] <= $ranges[$j]['to'] &&
                        $ranges[$j]['from'] <= $ranges[$i]['to']
                    ) {
                        $formattedShipper = __(
                            $shipper === "third_party" ?
                            "MawadOnline 3rd Party Shipping" : $shipper
                        );

                        if ($this->is_variant === true) {
                            $fail("Default variant shipping from/to Quantities cannot overlap for {$formattedShipper} shipper.");
                        } else {
                            $fail("Default shipping from/to Quantities cannot overlap for {$formattedShipper} shipper.");
                        }
                    }
                }
            }
        }

        return true;
    }
}
