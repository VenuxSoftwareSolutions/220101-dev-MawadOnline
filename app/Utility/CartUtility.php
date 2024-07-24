<?php

namespace App\Utility;

use App\Models\Cart;
use Cookie;
use DateTime;

class CartUtility
{

    public static function create_cart_variant($product, $request)
    {
        $str = null;
        if (isset($request['color'])) {
            $str = $request['color'];
        }

        if (isset($product->choice_options) && count(json_decode($product->choice_options)) > 0) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }
        return $str;
    }

    public static function get_price($product, $product_stock, $quantity)
    {
        $price = $product_stock->price;
        if ($product->auction_product == 1) {
            $price = $product->bids->max('amount');
        }

        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $quantity)
                ->where('max_qty', '>=', $quantity)
                ->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        $price = self::discount_calculation($product, $price);
        return $price;
    }

    public static function get_price_mawad($dataProduct,$variationId, $quantity) {
        $data=$dataProduct ;
        $variations = $data['detailedProduct']['variations'] ;

        // Given value
        $qty = $quantity;
        $totalDiscount = 0 ;
        $discountPrice = 0 ;
        // Iterate through the ranges
        $unitPrice = null;
        if(count($variations)>0) {

            foreach ($variations[$variationId]['variant_pricing-from']['from'] as $index => $from) {
                $to = $variations[$variationId]['variant_pricing-from']['to'][$index];

                if ($qty >= $from && $qty <= $to) {
                     $unitPrice = $variations[$variationId]['variant_pricing-from']['unit_price'][$index];
                     if( isset($variations[$variationId]['variant_pricing-from']['discount']['date'][$index]) && ($variations[$variationId]['variant_pricing-from']['discount']['date'][$index])){
                        // Extract start and end dates from the first date interval

                        $dateRange = $variations[$variationId]['variant_pricing-from']['discount']['date'][$index];
                        list($startDate, $endDate) = explode(' to ', $dateRange);

                        // Convert date strings to DateTime objects for comparison
                        $currentDate = new DateTime(); // Current date/time
                        $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                        $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                            // Check if the current date/time is within the specified date interval
                            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {


                                if($variations[$variationId]['variant_pricing-from']['discount']['type'][$index] == "percent") {
                                    $percent = $variations[$variationId]['variant_pricing-from']['discount']['percentage'][$index] ;
                                    if ($percent) {


                                        // Calculate the discount amount based on the given percentage
                                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                        $discountAmount = ($unitPrice * $discountPercent) / 100;

                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;


                                    }
                                }else if($variations[$variationId]['variant_pricing-from']['discount']['type'][$index] == "amount"){
                                    // Calculate the discount amount based on the given amount
                                    $amount = $variations[$variationId]['variant_pricing-from']['discount']['amount'][$index] ;

                                    if ($amount) {
                                        $discountAmount = $amount;
                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;

                                    }

                                }
                            }
                        }
                    break; // Stop iterating once the range is found
                }
            }

        }
        else {
            foreach ($data['detailedProduct']['from'] as $index => $from) {
                $to = $data['detailedProduct']['to'][$index];
                if ($qty >= $from && $qty <= $to) {
                    $unitPrice = $data['detailedProduct']['unit_price'][$index];

                    if( isset($data['detailedProduct']['date_range_pricing'][$index]) && ($data['detailedProduct']['date_range_pricing'][$index])){
                        // Extract start and end dates from the first date interval

                        $dateRange = $data['detailedProduct']['date_range_pricing'][$index];
                        list($startDate, $endDate) = explode(' to ', $dateRange);

                        // Convert date strings to DateTime objects for comparison
                        $currentDate = new DateTime(); // Current date/time
                        $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                        $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                            // Check if the current date/time is within the specified date interval
                            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {


                                if($data['detailedProduct']['discount_type'][$index] == "percent") {
                                    $percent = $data['detailedProduct']['discount_percentage'][$index] ;

                                    if ($percent) {


                                        // Calculate the discount amount based on the given percentage
                                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                        $discountAmount = ($unitPrice * $discountPercent) / 100;

                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;

                                    }
                                }else if($data['detailedProduct']['discount_type'][$index] == "amount"){
                                    // Calculate the discount amount based on the given

                                    $amount = $data['detailedProduct']['discount_amount'][$index] ;

                                    if ($amount) {
                                        $discountAmount = $amount;
                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;

                                    }

                                }
                            }
                        }
                    break; // Stop iterating once the range is found
                }
            }
        }


        // $total=$qty*$unitPrice;
        if (isset($discountPrice) && $discountPrice > 0) {
            // $totalDiscount=$qty*$discountPrice;
            return $discountPrice ;
        }
        return $unitPrice ;
     // Return the unit price as JSON response
    }

    public static function discount_calculation($product, $price)
    {
        $discount_applicable = false;

        if (
            $product->discount_start_date == null ||
            (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date)
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }
        return $price;
    }

    public static function tax_calculation($product, $price)
    {
        $tax = 0;
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        return $tax;
    }

    public static function save_cart_data($cart, $product, $price, $tax, $quantity)
    {
        $cart->quantity = $quantity;
        $cart->product_id = $product->id;
        $cart->owner_id = $product->user_id;
        $cart->price = $price;
        $cart->tax = $tax;
        $cart->product_referral_code = null;

        if (Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
            $cart->product_referral_code = Cookie::get('product_referral_code');
        }

        // Cart::create($data);
        $cart->save();
    }

    public static function check_auction_in_cart($carts)
    {
        foreach ($carts as $cart) {
            if ($cart->product->auction_product == 1) {
                return true;
            }
        }

        return false;
    }
}
