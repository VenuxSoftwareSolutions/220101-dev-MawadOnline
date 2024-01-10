<?php

namespace App\Services;

use App\Models\PricingConfiguration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductPricingService
{
    public function store(array $data)
    {
        $collection = collect($data);

        $all_data_to_insert = [];

        foreach($collection['from'] as $key => $from){
            $current_data = [];
            $date_var               = explode(" to ", $collection['date_range_pricing'][$key]);
            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

            $current_data["id_products"] = $collection['product']->id;
            $current_data["from"] = $from;
            $current_data["to"] = $collection['to'][$key];
            $current_data["unit_price"] = $collection['unit_price'][$key];
            $current_data["discount_start_datetime"] = $discount_start_date;
            $current_data["discount_end_datetime"] = $discount_end_date;
            $current_data["discount_type"] = $collection['discount_type'][$key];
            $current_data["discount_amount"] = $collection['discount_amount'][$key];
            $current_data["discount_percentage"] = $collection['discount_percentage'][$key];

            array_push($all_data_to_insert, $current_data);
        }

        PricingConfiguration::insert($all_data_to_insert);
    }
}
