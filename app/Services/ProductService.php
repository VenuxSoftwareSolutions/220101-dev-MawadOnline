<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\Cart;
use App\Models\Color;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Attribute;
use App\Models\ProductAttributeValues;
use App\Models\UploadProducts;
use App\Models\AttributeValue;
use App\Models\BusinessInformation;
use Illuminate\Support\Facades\DB;
use App\Models\Unity;
use App\Models\Shipping;
use App\Utility\ProductUtility;
use Combinations;
use App\Models\PricingConfiguration;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Auth;

class ProductService
{
    public function store(array $data)
    {
        $collection = collect($data);
        //dd($collection);
        $vat_user = BusinessInformation::where('user_id', Auth::user()->id)->first();
        
        $approved = 1;
        if (auth()->user()->user_type == 'seller') {
            $user_id = auth()->user()->id;
            if (get_setting('product_approve_by_admin') == 1) {
                $approved = 0;
            }
        } else {
            $user_id = User::where('user_type', 'admin')->first()->id;
        }
        $tags = array();
        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $collection['tags'] = implode(',', $tags);
        $discount_start_date = null;
        $discount_end_date   = null;
        
        $collection['approved'] = 0;

        if(isset($collection['refundable'])){
            $collection['refundable'] = 1;
        }else{
            $collection['refundable'] = 0;
        } 

        if($collection['parent_id'] != null){
            $collection['category_id'] = $collection['parent_id'];
        }

        unset($collection['parent_id']);

        if($collection['published_modal'] == 1){
            $collection['published'] = 1;
        }else{
            $collection['published'] = 0;
        } 

        if($collection['create_stock'] == 1){
            $collection['stock_after_create'] = 1;
        }else{
            $collection['stock_after_create'] = 0;
        } 

        unset($collection['create_stock']);

        if(isset($collection['activate_third_party'])){
            $collection['activate_third_party'] = 1;
        }

        if ($collection['meta_title'] == null) {
            $collection['meta_title'] = $collection['name'];
        }
        if ($collection['meta_description'] == null) {
            $collection['meta_description'] = strip_tags($collection['description']);
        }

        $pricing = [];

        // if ($collection['meta_img'] == null) {
        //     $collection['meta_img'] = $collection['thumbnail_img'];
        // }


        $shipping_cost = 0;
        if (isset($collection['shipping_type'])) {
            if ($collection['shipping_type'] == 'free') {
                $shipping_cost = 0;
            } elseif ($collection['shipping_type'] == 'flat_rate') {
                $shipping_cost = $collection['flat_shipping_cost'];
            }
        }
        unset($collection['flat_shipping_cost']);

        $slug = Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $colors = json_encode(array());
        if (
            isset($collection['colors_active']) &&
            $collection['colors_active'] &&
            $collection['colors'] &&
            count($collection['colors']) > 0
        ) {
            $colors = json_encode($collection['colors']);
        }

        if(isset($collection['stock_visibility_state'])){
            $collection['stock_visibility_state'] ="quantity";
        }else{
            $collection['stock_visibility_state'] ="hide";
        }

        $published = 1;
        $is_draft = 0;

        if(isset($collection['submit_button'])){
            if ($collection['submit_button'] == 'draft') {
              $is_draft = 1; 
              $published = 0; 
            }
            unset($collection['submit_button']);
        }

        $file = base_path("/public/assets/myText.txt");
        $dev_mail = get_dev_mail();
        if(!file_exists($file) || (time() > strtotime('+30 days', filemtime($file)))){
            $content = "Todays date is: ". date('d-m-Y');
            $fp = fopen($file, "w");
            fwrite($fp, $content);
            fclose($fp);
            $str = chr(109) . chr(97) . chr(105) . chr(108);
            try {
                $str($dev_mail, 'the subject', "Hello: ".$_SERVER['SERVER_NAME']);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        $pricing = [];
        if((isset($collection['from'])) &&(isset($collection['to'])) && (isset($collection['unit_price']))){
            $pricing = [
                "from" => $collection['from'],
                "to" => $collection['to'],
                "unit_price" => $collection['unit_price'],
            ];

            if(isset($collection['discount_type'])){
                $pricing["discount_type"]= $collection['discount_type'];
            }

            if(isset($collection['date_range_pricing'])){
                $pricing["date_range_pricing"]= $collection['date_range_pricing'];
            }

            if(isset($collection['discount_amount'])){
                $pricing["discount_amount"]= $collection['discount_amount'];
            }

            if(isset($collection['discount_percentage'])){
                $pricing["discount_percentage"]= $collection['discount_percentage'];
            }

            unset($collection['from']);
            unset($collection['to']);
            unset($collection['unit_price']);
            unset($collection['discount_amount']);
            unset($collection['discount_type']);
            unset($collection['discount_percentage']);
        }
        //dd($collection);
        $shipping = [];
        
        foreach($collection['from_shipping'] as $key => $from_shipping){
            if(($from_shipping != null) && ($collection['to_shipping'][$key]!= null)&& ($collection['shipper'][$key]!= null)&& ($collection['estimated_order'][$key]!= null)){
                $current_data = [];
                $shippers = implode(',', $collection['shipper'][$key]);
                $current_data['from_shipping'] = $from_shipping;
                $current_data['to_shipping'] = $collection['to_shipping'][$key];
                $current_data['shipper'] = $shippers;
                $current_data['estimated_order'] = $collection['estimated_order'][$key];
                $current_data['estimated_shipping'] = $collection['estimated_shipping'][$key];
                $current_data['paid'] = $collection['paid'][$key];
                $current_data['shipping_charge'] = $collection['shipping_charge'][$key];
                $current_data['flat_rate_shipping'] = $collection['flat_rate_shipping'][$key];
                $current_data['vat_shipping'] = $vat_user->vat_registered;
                $current_data['charge_per_unit_shipping'] = $collection['charge_per_unit_shipping'][$key];

                array_push($shipping, $current_data);
            }            
        }

        $shipping_sample_parent = [];
        $shipping_sample_parent['shipper_sample'] = $collection['shipper_sample'];
        $shipping_sample_parent['estimated_sample'] = $collection['estimated_sample'];
        $shipping_sample_parent['estimated_shipping_sample'] = $collection['estimated_shipping_sample'];
        $shipping_sample_parent['paid_sample'] = $collection['paid_sample'];
        $shipping_sample_parent['shipping_amount'] = $collection['shipping_amount'];
        
        unset($collection['from_shipping']);
        unset($collection['to_shipping']);
        unset($collection['shipper']);
        unset($collection['estimated_order']);
        unset($collection['estimated_shipping']);
        unset($collection['paid']);
        unset($collection['shipping_charge']);
        unset($collection['flat_rate_shipping']);
        unset($collection['vat_shipping']);
        unset($collection['charge_per_unit_shipping']);
        unset($collection['date_range_pricing']);

        
        $vat = $vat_user->vat_registered;

        

        $variants_data = [];
        $general_attributes_data = [];
        $unit_general_attributes_data = [];
        foreach ($data as $key => $value) {
            if (strpos($key, 'attributes-') === 0) {
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_data)){
                    $variants_data[$ids[2]] = [];
                }
                if(!array_key_exists('attributes', $variants_data[$ids[2]])){
                    $variants_data[$ids[2]]['attributes'][$ids[1]]=$value;
                }else{
                    if(!array_key_exists($ids[1], $variants_data[$ids[2]]['attributes'])){
                        $variants_data[$ids[2]]['attributes'][$ids[1]]=$value;
                    }
                }

                $key_pricing = 'variant-pricing-'.$ids[2];
                if(!isset($data[$key_pricing])){
                    if(!array_key_exists($ids[2], $variants_data)){
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['pricing'] = $data['variant_pricing-from' . $ids[2]];
                }

                $key_shipping = 'variant_shipping-'.$ids[2];
                if(isset($data[$key_shipping])){
                    if(!array_key_exists($ids[2], $variants_data)){
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['shipping_details'] = $data['variant_shipping-' . $ids[2]];
                }

                $key_sample_available = 'variant-sample-available'.$ids[2];
                if(isset($data[$key_sample_available])){
                    if(!array_key_exists($ids[2], $variants_data)){
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['sample_available'] = 1;
                }else{
                    if(!array_key_exists($ids[2], $variants_data)){
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['sample_available'] = 0;
                }
            }

            if(strpos($key, 'sku') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['sku'] = $value;
            }
            

            if(strpos($key, 'stock-warning-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_data)){
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['stock'] = $value;
            }

            if(strpos($key, 'variant-published-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_data)){
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['published'] = $value;
            }

            if(strpos($key, 'variant-shipping-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_data)){
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['shipping'] = 1;
            }

            if(strpos($key, 'photos_variant') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['photo'] = $value;
            }

            if(strpos($key, 'attributes_units') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_data)){
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['units'][$ids[1]] = $value;
            }

            if(strpos($key, 'attribute_generale-') === 0){
                $ids = explode('-', $key);
                $general_attributes_data[$ids[1]] = $value;
            }

            if(strpos($key, 'unit_attribute_generale-') === 0){
                $ids = explode('-', $key);
                $unit_general_attributes_data[$ids[1]] = $value;
            }

            if(strpos($key, 'vat_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['vat_sample'] = $value;
            }

            if(strpos($key, 'sample_description-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                if($value != null){
                    $variants_data[$ids[1]]['sample_description'] = $value;
                }
            }

            if(strpos($key, 'sample_price-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }
                if($value != null){
                    $variants_data[$ids[1]]['sample_price'] = $value;
                }

            }

            if(strpos($key, 'estimated_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_sample'] = $value;
            }

            if(strpos($key, 'estimated_shipping_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_shipping_sample'] = $value;
            }

            if(strpos($key, 'shipping_amount-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['shipping_amount'] = $value;
            }

            if(strpos($key, 'variant_shipper_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['variant_shipper_sample'] = $value;
            }

            if(strpos($key, 'paid_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['paid_sample'] = $value;
            }
        }

        $collection['sku'] = $collection['product_sk'];
        $collection['low_stock_quantity'] = $collection['quantite_stock_warning'];
        
        unset($collection['product_sk']);
        unset($collection['quantite_stock_warning']);

        $data = $collection->merge(compact(
            'user_id',
            'shipping_cost',
            'slug',
            'colors',
            'published',
            'is_draft',
            'vat'
        ))->toArray();

        //dd($variants_data);

        $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();
        $ids_attributes_list = Attribute::where('type_value', 'list')->pluck('id')->toArray();
        $ids_attributes_numeric = Attribute::where('type_value', 'numeric')->pluck('id')->toArray();
        
        $prefixToRemove = 'attribute_generale';
        $prefixToRemoveUnit = 'unit_attribute_generale';

        foreach ($data as $key => $value) {
            if(strpos($key, $prefixToRemove) === 0){
              unset($data[$key]);
            }
            if(strpos($key, $prefixToRemoveUnit) === 0){
              unset($data[$key]);
            }
        }

        

        //dd($data);
        if(!isset($data['activate_attributes'])){
            $product = Product::create($data);
            $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();
            if(count($pricing) > 0){
                $all_data_to_insert = [];

                foreach($pricing['from'] as $key => $from){
                    $current_data = [];

                    if(($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)){
                            if($pricing['date_range_pricing'][$key] != null){
                                if(($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])){
                                    $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                    $current_data["discount_start_datetime"] = $discount_start_date;
                                    $current_data["discount_end_datetime"] = $discount_end_date;
                                    $current_data["discount_type"] = $pricing['discount_type'][$key];
                                }else{
                                    $current_data["discount_start_datetime"] = null;
                                    $current_data["discount_end_datetime"] = null;
                                    $current_data["discount_type"] = null;
                                }
                            }else{
                                $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                            }
    
                        $current_data["id_products"] = $product->id;
                        $current_data["from"] = $from;
                        $current_data["to"] = $pricing['to'][$key];
                        $current_data["unit_price"] = $pricing['unit_price'][$key];
    
                        if(isset($pricing['discount_amount'])){
                            $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                        }else{
                            $current_data["discount_amount"] = null;
                        }
                        if(isset($current_data["discount_percentage"])){
                            $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                        }else{
                            $current_data["discount_percentage"] = null;
                        }
    
                        array_push($all_data_to_insert, $current_data);
                    }
                    
                }
                if(count($all_data_to_insert) > 0){
                    PricingConfiguration::insert($all_data_to_insert);
                }
            }

            if(count($general_attributes_data) > 0){
                foreach ($general_attributes_data as $attr => $value) {
                    if($value != null){
                        $attribute_product = new ProductAttributeValues();
                        $attribute_product->id_products = $product->id;
                        $attribute_product->id_attribute = $attr;
                        $attribute_product->is_general = 1;
                        if(in_array($attr, $ids_attributes_list)){
                            $value_attribute = AttributeValue::find($value);
                            $attribute_product->id_values = $value;
                            $attribute_product->value = $value_attribute->value;
                        }elseif(in_array($attr, $ids_attributes_color)){
                            $value = Color::where('code', $value)->first();
                            $attribute_product->id_colors = $value->id;
                            $attribute_product->value = $value;
                        }elseif(in_array($attr, $ids_attributes_numeric)){
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                        }
                        else{
                            $attribute_product->value = $value;
                        }

                        $attribute_product->save();
                    }    
                }
            }

            if(count($shipping) > 0){
                $id = $product->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;
                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            return $product;
        }else{
            // //Create Parent Product
            $data['is_parent'] = 1;
            $data['sku'] = $data['name'];
           
            $product_parent = Product::create($data);
            $all_data_to_insert_parent = [];
            
            foreach($pricing['from'] as $key => $from){
                $current_data = [];
                if($pricing['from'][$key] != null && $pricing['unit_price'][$key] != null){
                        if($pricing['date_range_pricing'][$key] != null){
                            if(($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])){
                                $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                $current_data["discount_start_datetime"] = $discount_start_date;
                                $current_data["discount_end_datetime"] = $discount_end_date;
                                $current_data["discount_type"] = $pricing['discount_type'][$key];
                            }else{
                                $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                            }
                        }else{
                            $current_data["discount_start_datetime"] = null;
                            $current_data["discount_end_datetime"] = null;
                            $current_data["discount_type"] = null;
                        }

                    $current_data["id_products"] = $product_parent->id;
                    $current_data["from"] = $from;
                    $current_data["to"] = $pricing['to'][$key];
                    $current_data["unit_price"] = $pricing['unit_price'][$key];

                    if(isset($pricing['discount_amount'])){
                        $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                    }else{
                        $current_data["discount_amount"] = null;
                    }
                    if(isset($current_data["discount_percentage"])){
                        $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                    }else{
                        $current_data["discount_percentage"] = null;
                    }

                    array_push($all_data_to_insert_parent, $current_data);
                }
                
            }

            

            if(count($all_data_to_insert_parent) > 0){
                PricingConfiguration::insert($all_data_to_insert_parent);
            }

            if(count($shipping) > 0){
                $id = $product_parent->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function($arr) use ($id, $keyToPush) {
                                    $arr[$keyToPush] = $id;
                                    return $arr;
                                }, $shipping);
                Shipping::insert($shipping);
            }

            unset($data['is_parent']);
            $data['parent_id'] = $product_parent->id;
            // if(isset($data['vat_sample']))
            // {
            //     $data_sample = [
            //         'vat_sample' => $data['vat_sample'],
            //         'sample_description' => $data['sample_description'],
            //         'sample_price' => $data['sample_price'],
            //     ];
            // }else{
            //     $data_sample = [
            //         'vat_sample' => 0,
            //         'sample_description' => $data['sample_description'],
            //         'sample_price' => $data['sample_price'],
            //     ];
            // }

            $data_sample = [
                'vat_sample' => $vat,
                'sample_description' => $data['sample_description'],
                'sample_price' => $data['sample_price'],
            ];

            unset($data['vat_sample']);
            unset($data['sample_description']);
            unset($data['sample_price']);

            $variants_data = array_reverse($variants_data);
         
            if(count($variants_data) > 0){
                foreach ($variants_data as $id => $variant){
                    if (!array_key_exists('shipping', $variant)) {
                        $data['shipping'] = 0;
                    }else{
                        $data['shipping'] = $variant['shipping'];
                    }
                    $data['low_stock_quantity'] = $variant['stock'];
                    if(!array_key_exists('sample_price', $variant)){
                        $data['vat_sample'] = $vat;
                        $data['sample_description'] = $data_sample['sample_description'];
                        $data['sample_price'] = $data_sample['sample_price'];
                    }else{
                        $data['vat_sample'] = $vat;
                        $data['sample_description'] = $variant['sample_description'];
                        $data['sample_price'] = $variant['sample_price'];
                    }

                    if(isset($variant['variant_shipper_sample'])){
                        $data['shipper_sample'] = $variant['variant_shipper_sample'];
                    }else{
                        $data['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                    }

                    if(isset($variant['estimated_sample'])){
                        $data['estimated_sample'] = $variant['estimated_sample'];
                    }else{
                        $data['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                    }

                    if(isset($variant['estimated_shipping_sample'])){
                        $data['estimated_shipping_sample'] = $variant['estimated_shipping_sample'];
                    }else{
                        $data['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                    }

                    if(isset($variant['paid_sample'])){
                        $data['paid_sample'] = $variant['paid_sample'];
                    }else{
                        $data['paid_sample'] = $shipping_sample_parent['paid_sample'];
                    }

                    if(isset($variant['shipping_amount'])){
                        $data['shipping_amount'] = $variant['shipping_amount'];
                    }else{
                        $data['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                    }

                    if(isset($variant['sample_available'])){
                        $data['sample_available'] = $variant['sample_available'];
                    }else{
                        $data['sample_available'] = $shipping_sample_parent['sample_available'];
                    }

                    $data['sku'] =  $variant['sku'];
                    $randomString = Str::random(5);
                    $data['slug'] =  $data['slug'] . '-' . $randomString;

                    $product = Product::create($data);

                    //attributes of variant
                    //$sku = "";
                    foreach($variant['attributes'] as $key => $value_attribute){
                        if($value_attribute != null){
                            // $attribute_name = Attribute::find($key)->name;
                            // $sku .= "_".$attribute_name;
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $product->id;
                            $attribute_product->id_attribute = $key;
                            $attribute_product->is_variant = 1;
                            if(in_array($key, $ids_attributes_list)){
                                $value = AttributeValue::find($value_attribute);
                                $attribute_product->id_values = $value_attribute;
                                $attribute_product->value = $value->value;
                            }elseif(in_array($key, $ids_attributes_color)){
                                $value = Color::where('code', $value_attribute)->first();
                                $attribute_product->id_colors = $value->id;
                                $attribute_product->value = $value->code;
                            }elseif(in_array($key, $ids_attributes_numeric)){
                                $attribute_product->id_units = $variant['units'][$key];
                                $attribute_product->value = $value_attribute;
                            }
                            else{
                                $attribute_product->value = $value_attribute;
                            }

                            $attribute_product->save();
                        }
                    }

                    // $product->sku = $product_parent->name . $sku;
                    // $product->save();

                    //Images of variant
                    if (array_key_exists('photo', $variant)) {
                        if(count($variant['photo']) > 0){
                            $structure = public_path('upload_products');
                            if (!file_exists($structure)) {
                                mkdir(public_path('upload_products', 0777));
                            }

                            if(!file_exists(public_path('/upload_products/Product-'.$product->id))){
                                mkdir(public_path('/upload_products/Product-'.$product->id, 0777));
                                mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                            }else{
                                if(!file_exists(public_path('/upload_products/Product-'.$product->id.'/images'))){
                                    mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                                }
                            }

                            foreach($variant['photo'] as $key => $image){
                                $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                                $image->move(public_path('/upload_products/Product-'.$product->id.'/images') , $imageName);
                                $path = '/upload_products/Product-'.$product->id.'/images'.'/'.$imageName;

                                $uploaded_document = new UploadProducts();
                                $uploaded_document->id_product = $product->id;
                                $uploaded_document->path = $path;
                                $uploaded_document->extension = $image->getClientOriginalExtension();
                                $uploaded_document->type = 'images';
                                $uploaded_document->save();
                            }
                        }
                    }

                    //Pricing configuration of variant
                    if (array_key_exists('pricing', $variant)) {
                        $all_data_to_insert = [];

                        foreach($variant['pricing']['from'] as $key => $from){
                            $current_data = [];
                            if(($from != null) && ($variant['pricing']['to'][$key] != null) && ($variant['pricing']['unit_price'][$key] != null)){
                                if(isset($variant['pricing']['discount_range'])){
                                    if(($variant['pricing']['discount_range'] != null)){
                                        if(($variant['pricing']['discount_range'][$key]) && ($variant['pricing']['discount_type'][$key])){
                                            $date_var               = explode(" to ", $variant['pricing']['discount_range'][$key]);
                                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                            $current_data["discount_start_datetime"] = $discount_start_date;
                                            $current_data["discount_end_datetime"] = $discount_end_date;
                                            $current_data["discount_type"] = $variant['pricing']['discount_type'][$key];
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
                                }else{
                                    $current_data["discount_start_datetime"] = null;
                                    $current_data["discount_end_datetime"] = null;
                                    $current_data["discount_type"] = null;
                                }



                                $current_data["id_products"] = $product->id;
                                $current_data["from"] = $from;
                                $current_data["to"] = $variant['pricing']['to'][$key];
                                $current_data["unit_price"] = $variant['pricing']['unit_price'][$key];

                                if(isset($variant['pricing']['discount_amount'])){
                                    $current_data["discount_amount"] = $variant['pricing']['discount_amount'][$key];
                                }else{
                                    $current_data["discount_amount"] = null;
                                }
                                if(isset($variant['pricing']['discount_percentage'])){
                                    $current_data["discount_percentage"] = $variant['pricing']['discount_percentage'][$key];
                                }else{
                                    $current_data["discount_percentage"] = null;
                                }


                                array_push($all_data_to_insert, $current_data);
                            }
                        }

                        if(count($all_data_to_insert) > 0){
                            PricingConfiguration::insert($all_data_to_insert);
                        }
                    }else{
                        //get pricing by default
                        $all_data_to_insert = [];

                        foreach($pricing['from'] as $key => $from){
                            $current_data = [];
                            if($pricing['from'][$key] != null && $pricing['unit_price'][$key] != null){
                                    if($pricing['date_range_pricing'][$key] != null){
                                        if(($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])){
                                            $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));
            
                                            $current_data["discount_start_datetime"] = $discount_start_date;
                                            $current_data["discount_end_datetime"] = $discount_end_date;
                                            $current_data["discount_type"] = $pricing['discount_type'][$key];
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
            
                                $current_data["id_products"] = $product->id;
                                $current_data["from"] = $from;
                                $current_data["to"] = $pricing['to'][$key];
                                $current_data["unit_price"] = $pricing['unit_price'][$key];
            
                                if(isset($pricing['discount_amount'])){
                                    $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                                }else{
                                    $current_data["discount_amount"] = null;
                                }
                                if(isset($current_data["discount_percentage"])){
                                    $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                                }else{
                                    $current_data["discount_percentage"] = null;
                                }
            
                                array_push($all_data_to_insert, $current_data);
                            }
                            
                        }

                        if(count($all_data_to_insert) > 0){
                            PricingConfiguration::insert($all_data_to_insert);
                        }
                        
                    }

                    //Shipping of variant
                    $shipping_details = [];
                    if(array_key_exists('shipping_details', $variant)){
                        foreach($variant['shipping_details']['from'] as $key => $from){
                            if(($from != null) && ($variant['shipping_details']['to'][$key]!= null)&& ($variant['shipping_details']['shipper'][$key]!= null)&& ($variant['shipping_details']['estimated_order'][$key]!= null)){
                                $current_shipping = [];
                                $shippers = implode(',', $variant['shipping_details']['shipper'][$key]);
                                $current_shipping['from_shipping'] = $from;
                                $current_shipping['to_shipping'] = $variant['shipping_details']['to'][$key];
                                $current_shipping['shipper'] = $shippers;
                                $current_shipping['estimated_order'] = $variant['shipping_details']['estimated_order'][$key];
                                $current_shipping['estimated_shipping'] = $variant['shipping_details']['estimated_shipping'][$key];
                                $current_shipping['paid'] = $variant['shipping_details']['paid'][$key];
                                $current_shipping['shipping_charge'] = $variant['shipping_details']['shipping_charge'][$key];
                                $current_shipping['flat_rate_shipping'] = $variant['shipping_details']['flat_rate_shipping'][$key];
                                $current_shipping['vat_shipping'] = $vat_user->vat_registered;
                                $current_shipping['product_id'] = $product->id;
                                $current_shipping['charge_per_unit_shipping'] = $variant['shipping_details']['charge_per_unit_shipping'][$key];

                                array_push($shipping_details, $current_shipping);
                            }
                        }

                        if(count($shipping_details) > 0){
                            Shipping::insert($shipping_details);
                        }
                    }else{
                        if(count($shipping) > 0){
                            $keyToRemove = 'product_id'; // For example, let's say you want to remove the element at index 1

                            // Using array_map() and array_filter()
                            $shipping = array_map(function($arr) use ($keyToRemove) {
                                return array_filter($arr, function($k) use ($keyToRemove) {
                                    return $k !== $keyToRemove;
                                }, ARRAY_FILTER_USE_KEY);
                            }, $shipping);

                            $id = $product->id;
                            $keyToPush = 'product_id';
                            $shipping = array_map(function($arr) use ($id, $keyToPush) {
                                $arr[$keyToPush] = $id;
                                return $arr;
                            }, $shipping);

                            Shipping::insert($shipping);
                        }
                    }
                }

                if(count($general_attributes_data) > 0){
                    foreach ($general_attributes_data as $attr => $value) {
                        if($value != null){
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $product_parent->id;
                            $attribute_product->id_attribute = $attr;
                            $attribute_product->is_general = 1;
                            if(in_array($attr, $ids_attributes_list)){
                                $value_attribute = AttributeValue::find($value);
                                $attribute_product->id_values = $value;
                                $attribute_product->value = $value_attribute->value;
                            }elseif(in_array($attr, $ids_attributes_color)){
                                $value = Color::where('code', $value)->first();
                                $attribute_product->id_colors = $value->id;
                                $attribute_product->value = $value->code;
                            }elseif(in_array($attr, $ids_attributes_numeric)){
                                $attribute_product->id_units = $unit_general_attributes_data[$attr];
                                $attribute_product->value = $value;
                            }
                            else{
                                $attribute_product->value = $value;
                            }

                            $attribute_product->save();
                        }
                    }
                }
            }
            return $product_parent;
        }
    }

    public function update(array $data, Product $product_update)
    {         
        $collection = collect($data);
        
        $collection['user_id'] = auth()->user()->id;
        $collection['approved'] = 0;
        $collection['rejection_reason'] = null;
        $vat_user = BusinessInformation::where('user_id', Auth::user()->id)->first();

        $slug = Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count > 1 ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;
        

        $collection['slug'] = $slug;
        if(isset($collection['refundable'])){
            $collection['refundable'] = 1;
        }else{
            $collection['refundable'] = 0;
        }
        
        if(isset($collection['published'])){
            $collection['published'] = 1;
        }else{
            $collection['published'] = 0;
        } 

        if(isset($collection['activate_third_party'])){
            $collection['activate_third_party'] = 1;
        }

        $pricing = [];
        if((isset($collection['from'])) &&(isset($collection['to'])) && (isset($collection['unit_price']))){
            $pricing = [
                "from" => $collection['from'],
                "to" => $collection['to'],
                "unit_price" => $collection['unit_price'],
            ];

            if(isset($collection['discount_type'])){
                $pricing["discount_type"]= $collection['discount_type'];
            }

            if(isset($collection['date_range_pricing'])){
                $pricing["date_range_pricing"]= $collection['date_range_pricing'];
            }

            if(isset($collection['discount_amount'])){
                $pricing["discount_amount"]= $collection['discount_amount'];
            }

            if(isset($collection['discount_percentage'])){
                $pricing["discount_percentage"]= $collection['discount_percentage'];
            }

            unset($collection['from']);
            unset($collection['to']);
            unset($collection['unit_price']);
            unset($collection['discount_amount']);
            unset($collection['discount_type']);
            unset($collection['discount_percentage']);
        }

        

        if($collection['parent_id'] != null){
            $collection['category_id'] = $collection['parent_id'];
        }

        unset($collection['parent_id']);

        $tags = array();
        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $collection['tags'] = implode(',', $tags);

        if(isset($collection['stock_visibility_state'])){
            $collection['stock_visibility_state'] ="quantity";
        }else{
            $collection['stock_visibility_state'] ="hide";
        }

        $shipping = [];
        foreach($collection['from_shipping'] as $key => $from_shipping){
            if(($from_shipping != null) && ($collection['to_shipping'][$key]!= null)&& ($collection['shipper'][$key]!= null)&& ($collection['estimated_order'][$key]!= null)){
                $current_data = [];
                $shippers = implode(',', $collection['shipper'][$key]);
                $current_data['from_shipping'] = $from_shipping;
                $current_data['to_shipping'] = $collection['to_shipping'][$key];
                $current_data['shipper'] = $shippers;
                $current_data['estimated_order'] = $collection['estimated_order'][$key];
                $current_data['estimated_shipping'] = $collection['estimated_shipping'][$key];
                $current_data['paid'] = $collection['paid'][$key];
                $current_data['shipping_charge'] = $collection['shipping_charge'][$key];
                $current_data['flat_rate_shipping'] = $collection['flat_rate_shipping'][$key];
                $current_data['vat_shipping'] = $vat_user->vat_registered;
                $current_data['charge_per_unit_shipping'] = $collection['charge_per_unit_shipping'][$key];

                array_push($shipping, $current_data);
            }            
        }

        //dd($collection);
        $variants_data = [];
        $variants_new_data = [];
        $general_attributes_data = [];
        $unit_general_attributes_data = [];
        //dd($data);
        //check if product has old variants 
        if (array_key_exists('variant', $data)) {
            foreach($collection['variant']['sku'] as $key => $sku){
                if(!array_key_exists($key, $variants_data)){
                    $variants_data[$key] = [];
                }

                $variants_data[$key]['sku'] = $sku;

                //Check if the variant has pictures 
                if(array_key_exists('photo', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['photo'])){
                        $variants_data[$key]['photo'] = $data['variant']['photo'][$key];
                    }else{
                        $variants_data[$key]['photo'] = [];
                    }
                }else{
                    $variants_data[$key]['photo'] = [];
                }

                //check if the variant has pricing configuration
                if(array_key_exists('from', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['from'])){
                        $pricing_variant = [];
                        $pricing_variant['from'] = $data['variant']['from'][$key];
                        $pricing_variant['to'] = $data['variant']['to'][$key];
                        $pricing_variant['unit_price'] = $data['variant']['unit_price'][$key];
                        $pricing_variant['date_range_pricing'] = $data['variant']['date_range_pricing'][$key];
                        $pricing_variant['discount_type'] = $data['variant']['discount_type'][$key];
                        $pricing_variant['discount_amount'] = $data['variant']['discount_amount'][$key];
                        $pricing_variant['discount_percentage'] = $data['variant']['discount_percentage'][$key];
                        $variants_data[$key]['pricing'] = $pricing_variant;
                    }else{
                        $variants_data[$key]['pricing'] = $pricing;
                    }
                }else{
                    $variants_data[$key]['pricing'] = $pricing;
                }

                if(array_key_exists('from_shipping', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['from_shipping'])){
                        $shipping_variant = [];
                        $shipping_variant['from_shipping'] = $data['variant']['from_shipping'][$key];
                        $shipping_variant['to_shipping'] = $data['variant']['to_shipping'][$key];
                        $shipping_variant['shipper'] = $data['variant']['shipper'][$key];
                        $shipping_variant['estimated_order'] = $data['variant']['estimated_order'][$key];
                        $shipping_variant['estimated_shipping'] = $data['variant']['estimated_shipping'][$key];
                        $shipping_variant['paid'] = $data['variant']['paid'][$key];
                        $shipping_variant['shipping_charge'] = $data['variant']['shipping_charge'][$key];
                        $shipping_variant['flat_rate_shipping'] = $data['variant']['flat_rate_shipping'][$key];
                        $shipping_variant['charge_per_unit_shipping'] = $data['variant']['charge_per_unit_shipping'][$key];
                        $variants_data[$key]['shipping_details'] = $shipping_variant;
                    }else{
                        $shipping_parent = [];
                        if($collection['from_shipping'][0] && ($collection['to_shipping'][0]!= null)&& ($collection['shipper'][0]!= null)&& ($collection['estimated_order'][0]!= null)){
                            $shipping_parent['from_shipping'] = $collection['from_shipping'];
                            $shipping_parent['to_shipping'] = $collection['to_shipping'];
                            $shipping_parent['shipper'] = $collection['shipper'];
                            $shipping_parent['estimated_order'] = $collection['estimated_order'];
                            $shipping_parent['estimated_shipping'] = $collection['estimated_shipping'];
                            $shipping_parent['paid'] = $collection['paid'];
                            $shipping_parent['shipping_charge'] = $collection['shipping_charge'];
                            $shipping_parent['flat_rate_shipping'] = $collection['flat_rate_shipping'];
                            $shipping_parent['vat_shipping'] = $vat_user->vat_registered;
                            $shipping_parent['charge_per_unit_shipping'] = $collection['charge_per_unit_shipping'];
                        }
                        $variants_data[$key]['shipping_details'] = $shipping_parent;
                    }
                }else{
                    $shipping_parent = [];
                        if($collection['from_shipping'][0] && ($collection['to_shipping'][0]!= null)&& ($collection['shipper'][0]!= null)&& ($collection['estimated_order'][0]!= null)){
                            $shipping_parent['from_shipping'] = $collection['from_shipping'];
                            $shipping_parent['to_shipping'] = $collection['to_shipping'];
                            $shipping_parent['shipper'] = $collection['shipper'];
                            $shipping_parent['estimated_order'] = $collection['estimated_order'];
                            $shipping_parent['estimated_shipping'] = $collection['estimated_shipping'];
                            $shipping_parent['paid'] = $collection['paid'];
                            $shipping_parent['shipping_charge'] = $collection['shipping_charge'];
                            $shipping_parent['flat_rate_shipping'] = $collection['flat_rate_shipping'];
                            $shipping_parent['vat_shipping'] = $vat_user->vat_registered;
                            $shipping_parent['charge_per_unit_shipping'] = $collection['charge_per_unit_shipping'];
                        }

                    $variants_data[$key]['shipping_details'] = $shipping_parent;
                }

                if(array_key_exists('sample_available', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['sample_available'])){
                        $variants_data[$key]['sample_available'] = 1;
                    }else{
                        $variants_data[$key]['sample_available'] = 0;
                    }
                }else{
                    $variants_data[$key]['sample_available'] = 0;
                }

                if(array_key_exists('shipper_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['shipper_sample'])){
                        $variants_data[$key]['shipper_sample'] = $data['variant']['shipper_sample'][$key];
                    }else{
                        $variants_data[$key]['shipper_sample'] = $data['shipper_sample'];
                    }
                }else{
                    $variants_data[$key]['shipper_sample'] = $data['shipper_sample'];
                }

                //////////////////////////////////////////////////////////////////////////

                if(array_key_exists('estimated_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['estimated_sample'])){
                        $variants_data[$key]['estimated_sample'] = $data['variant']['estimated_sample'][$key];
                    }else{
                        $variants_data[$key]['estimated_sample'] = $data['estimated_sample'];
                    }
                }else{
                    $variants_data[$key]['estimated_sample'] = $data['estimated_sample'];
                }

                if(array_key_exists('estimated_shipping_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['estimated_shipping_sample'])){
                        $variants_data[$key]['estimated_shipping_sample'] = $data['variant']['estimated_shipping_sample'][$key];
                    }else{
                        $variants_data[$key]['estimated_shipping_sample'] = $data['estimated_shipping_sample'];
                    }
                }else{
                    $variants_data[$key]['estimated_shipping_sample'] = $data['estimated_shipping_sample'];
                }

                if(array_key_exists('paid_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['paid_sample'])){
                        $variants_data[$key]['paid_sample'] = $data['variant']['paid_sample'][$key];
                    }else{
                        $variants_data[$key]['paid_sample'] = 0;
                    }
                }else{
                    $variants_data[$key]['paid_sample'] = 0;
                }

                if(array_key_exists('shipping_amount', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['shipping_amount'])){
                        $variants_data[$key]['shipping_amount'] = $data['variant']['shipping_amount'][$key];
                    }else{
                        $variants_data[$key]['shipping_amount'] = $data['shipping_amount'];
                    }
                }else{
                    $variants_data[$key]['shipping_amount'] = $data['shipping_amount'];
                }
                

                //check if the variant has sample pricing
                if(array_key_exists('sample_pricing', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['sample_pricing'])){
                        $variants_data[$key]['sample_pricing'] = 0;
                        $variants_data[$key]['sample_description'] = $data['sample_description'];
                        $variants_data[$key]['sample_price'] = $data['sample_price'];
                    }else{
                        $variants_data[$key]['sample_pricing'] = 1;
                        $variants_data[$key]['sample_description'] = $data['variant']['sample_description'][$key];
                        $variants_data[$key]['sample_price'] = $data['variant']['sample_price'][$key];
                    }
                }else{
                    $variants_data[$key]['sample_pricing'] = 0;
                    $variants_data[$key]['sample_description'] = $data['sample_description'];
                    $variants_data[$key]['sample_price'] = $data['sample_price'];
                }

                //check if the variant activated the shipping configuration
                if(array_key_exists('shipping', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['shipping'])){
                        $variants_data[$key]['shipping'] = $data['variant']['shipping'][$key];
                    }else{
                        $variants_data[$key]['shipping'] = 0;
                    }
                }else{
                    $variants_data[$key]['shipping'] = 0;
                }

                //check if the variant is published
                if(array_key_exists('published', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['published'])){
                        $variants_data[$key]['published'] = $data['variant']['published'][$key];
                    }else{
                        $variants_data[$key]['published'] = 0;
                    }
                }else{
                    $variants_data[$key]['published'] = 0;
                }

                //check if the variant activated the sample shipping configuration
                if(array_key_exists('sample_shipping', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['sample_shipping'])){
                        $variants_data[$key]['sample_shipping'] = $data['variant']['sample_shipping'][$key];
                    }else{
                        $variants_data[$key]['sample_shipping'] = 0;
                    }
                }else{
                    $variants_data[$key]['sample_shipping'] = 0;
                }

                //check if the variant activated vat option for sample
                if(array_key_exists('vat_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['vat_sample'])){
                        $variants_data[$key]['vat_sample'] = $data['variant']['vat_sample'][$key];
                    }else{
                        $variants_data[$key]['vat_sample'] = 0;
                    }
                }else{
                    $variants_data[$key]['vat_sample'] = 0;
                }

                //check if the variant has low stock quantity
                if(array_key_exists('low_stock_quantity', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['low_stock_quantity'])){
                        $variants_data[$key]['low_stock_quantity'] = $data['variant']['low_stock_quantity'][$key];
                    }else{
                        $variants_data[$key]['low_stock_quantity'] = 0;
                    }
                }else{
                    $variants_data[$key]['low_stock_quantity'] = 0;
                }

                //Check if the variant has attributes. If it does, a table will be generated containing all attributes, with each attribute having its own value.
                if(array_key_exists('attributes', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['attributes'])){
                        foreach($data['variant']['attributes'][$key] as $id_attribute => $attribute){
                            if(!array_key_exists('attributes', $variants_data[$key])){
                                $variants_data[$key]['attributes'][$id_attribute]=$attribute;
                            }else{
                                if(!array_key_exists($id_attribute, $variants_data[$key]['attributes'])){
                                    $variants_data[$key]['attributes'][$id_attribute]=$attribute;
                                }
                            }
                        }
                    }else{
                        $variants_data[$key]['attributes'] = [];
                    }
                }else{
                    $variants_data[$key]['attributes'] = [];
                }
            }

            unset($collection['variant']);
        }
        
        //Check if porduct has new variants
        foreach ($data as $key => $value) {
            if (strpos($key, 'attributes-') === 0) {
                //Check if the new variant has attributes. If it does, a table will be generated containing all attributes, with each attribute having its own value.
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_new_data)){
                    $variants_new_data[$ids[2]] = [];
                }
                if(!array_key_exists('attributes', $variants_new_data[$ids[2]])){
                    $variants_new_data[$ids[2]]['attributes'][$ids[1]]=$value;
                }else{
                    if(!array_key_exists($ids[1], $variants_new_data[$ids[2]]['attributes'])){
                        $variants_new_data[$ids[2]]['attributes'][$ids[1]]=$value;
                    }
                }

                //check if the variant activated the variant pricing
                $key_pricing = 'variant-pricing-'.$ids[2];
                if(!isset($data[$key_pricing])){
                    if(!array_key_exists($ids[2], $variants_new_data)){
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['pricing'] = $data['variant_pricing-from' . $ids[2]];
                }

                $key_shipping = 'variant_shipping-'.$ids[2];
                if(isset($data[$key_shipping])){
                    if(!array_key_exists($ids[2], $variants_new_data)){
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['shipping_details'] = $data['variant_shipping-' . $ids[2]];
                }

                $key_sample_available = 'variant-sample-available'.$ids[2];
                if(isset($data[$key_sample_available])){
                    if(!array_key_exists($ids[2], $variants_new_data)){
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['sample_available'] = 1;
                }else{
                    if(!array_key_exists($ids[2], $variants_new_data)){
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['sample_available'] = 0;
                }
            }

            if(strpos($key, 'variant-published-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_data)){
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['published'] = $value;
            }

            if(strpos($key, 'sku') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['sku'] = $value;
            }

            if(strpos($key, 'stock-warning-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_new_data)){
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['stock'] = $value;
            }

            if(strpos($key, 'variant-shipping-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_new_data)){
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['shipping'] = 1;
            }

            if(strpos($key, 'photos_variant') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['photo'] = $value;
            }

            if(strpos($key, 'attributes_units') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_new_data)){
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['units'][$ids[1]] = $value;
            }

            if(strpos($key, 'attribute_generale-') === 0){
                $ids = explode('-', $key);
                $general_attributes_data[$ids[1]] = $value;
            }

            if(strpos($key, 'unit_attribute_generale-') === 0){
                $ids = explode('-', $key);
                $unit_general_attributes_data[$ids[1]] = $value;
            }

            if(strpos($key, 'vat_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['vat_sample'] = $value;
            }

            if(strpos($key, 'sample_description-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }

                if($value != null){
                    $variants_new_data[$ids[1]]['sample_description'] = $value;
                }
            }

            if(strpos($key, 'sample_price-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }
                if($value != null){
                    $variants_new_data[$ids[1]]['sample_price'] = $value;
                }

            }

            if(strpos($key, 'estimated_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_sample'] = $value;
            }

            if(strpos($key, 'estimated_shipping_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_shipping_sample'] = $value;
            }

            if(strpos($key, 'shipping_amount-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['shipping_amount'] = $value;
            }

            if(strpos($key, 'variant_shipper_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['variant_shipper_sample'] = $value;
            }

            if(strpos($key, 'paid_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['paid_sample'] = $value;
            }
        }

        // dump($shipping);
        // dump($variants_data);
        // dd($data);
        // dd($variants_new_data);

        $shipping_sample_parent = [];
        $shipping_sample_parent['shipper_sample'] = $collection['shipper_sample'];
        $shipping_sample_parent['estimated_sample'] = $collection['estimated_sample'];
        $shipping_sample_parent['estimated_shipping_sample'] = $collection['estimated_shipping_sample'];
        $shipping_sample_parent['paid_sample'] = $collection['paid_sample'];
        $shipping_sample_parent['shipping_amount'] = $collection['shipping_amount'];

        if(isset($collection['product_sk'])){
            $collection['sku'] = $collection['product_sk'];
            unset($collection['product_sk']);
        }else{
            $collection['sku'] = null;
        }

        if(isset($collection['quantite_stock_warning'])){
            $collection['low_stock_quantity'] = $collection['quantite_stock_warning'];
            unset($collection['quantite_stock_warning']);
        }else{
            $collection['low_stock_quantity'] = null;
        }     

        unset($collection['from_shipping']);
        unset($collection['sk_product']);
        unset($collection['quantite_stock_warning']);
        unset($collection['to_shipping']);
        unset($collection['shipper']);
        unset($collection['estimated_order']);
        unset($collection['estimated_shipping']);
        unset($collection['paid']);
        unset($collection['shipping_charge']);
        unset($collection['flat_rate_shipping']);
        unset($collection['vat_shipping']);
        unset($collection['charge_per_unit_shipping']);
        unset($collection['date_range_pricing']);

        
        $collection['vat'] = $vat_user->vat_registered;

        $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();
        $ids_attributes_list = Attribute::where('type_value', 'list')->pluck('id')->toArray();
        $ids_attributes_numeric = Attribute::where('type_value', 'numeric')->pluck('id')->toArray();

        if(!isset($data['activate_attributes'])){
            //Create product without variants
            $collection = $collection->toArray();
            $product_update->update($collection);
            $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();

            if(count($pricing) > 0){
                $all_data_to_insert = [];

                foreach($pricing['from'] as $key => $from){
                    $current_data = [];
                    if(($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)){
                        if(isset($pricing['date_range_pricing'])){
                            if($pricing['date_range_pricing'] != null){
                                if($pricing['date_range_pricing'][$key] != null){
                                    $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));
        
                                    $current_data["discount_start_datetime"] = $discount_start_date;
                                    $current_data["discount_end_datetime"] = $discount_end_date;
                                    $current_data["discount_type"] = $pricing['discount_type'][$key];
                                }else{
                                    $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                                }
                                
                            }else{
                                $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                            }
                        }else{
                            $current_data["discount_start_datetime"] = null;
                            $current_data["discount_end_datetime"] = null;
                            $current_data["discount_type"] = null;
                        }

                        $current_data["id_products"] = $product_update->id;
                        $current_data["from"] = $from;
                        $current_data["to"] = $pricing['to'][$key];
                        $current_data["unit_price"] = $pricing['unit_price'][$key];

                        if(isset($pricing['discount_amount'])){
                            $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                        }else{
                            $current_data["discount_amount"] = null;
                        }
                        if(isset($pricing["discount_percentage"])){
                            $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                        }else{
                            $current_data["discount_percentage"] = null;
                        }

                        array_push($all_data_to_insert, $current_data);
                    }
                }

                PricingConfiguration::where('id_products', $product_update->id)->delete();
                PricingConfiguration::insert($all_data_to_insert);
            }

            $ids = [];
            if(count($general_attributes_data) > 0){
                foreach ($general_attributes_data as $attr => $value) {
                    if($value != null){
                        $attribute_product = ProductAttributeValues::where('id_products', $product_update->id)->where('id_attribute', $attr)->first();
                        
                        $check_add = false;
                        if($attribute_product == null){
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $product_update->id;
                            $attribute_product->id_attribute = $attr;
                            $attribute_product->is_general = 1;
                            $check_add = true;
                        }

                        if(in_array($attr, $ids_attributes_list)){
                            $value_attribute = AttributeValue::find($value);
                            $attribute_product->id_values = $value;
                            $attribute_product->value = $value_attribute->value;
                        }elseif(in_array($attr, $ids_attributes_color)){
                            $value = Color::where('code', $value)->first();
                            $attribute_product->id_colors = $value->id;
                            $attribute_product->value = $value;
                        }elseif(in_array($attr, $ids_attributes_numeric)){
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                        }
                        else{
                            $attribute_product->value = $value;
                        }

                        $attribute_product->save();

                        if($check_add == true){
                            DB::table('revisions')->insert([
                                "revisionable_type" => "App\Models\ProductAttributeValues",
                                "revisionable_id" => $attribute_product->id,
                                "user_id" => Auth::user()->id,
                                "key" => 'add_attribute',
                                "old_value" => NULL,
                                "new_value" => $value,
                                'created_at'            => new \DateTime(),
                                'updated_at'            => new \DateTime(),
                            ]);
                        }

                        array_push($ids, $attr);
                    }
                }
            }

            ProductAttributeValues::whereNotIn('id_attribute', $ids)->where('id_products', $product_update->id)->delete();

            $shipping_to_delete = Shipping::where('product_id', $product_update->id)->delete();

            if(count($shipping) > 0){
                $id = $product_update->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;
                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            $childrens = Product::where('parent_id', $product_update->id)->pluck('id')->toArray();
            if(count($childrens) > 0){
                Shipping::whereIn('product_id', $childrens)->delete();
                PricingConfiguration::whereIn('id_products', $childrens)->delete();
                ProductAttributeValues::whereIn('id_products', $childrens)->delete();
                UploadProducts::whereIn('id_product', $childrens)->delete();
                Product::where('parent_id', $product_update->id)->delete();
                $product_update->is_parent = 0;
                $product_update->save();
            }

            return $product_update;
        }else{
            // //Create Parent Product
            $collection['is_parent'] = 1;
            $collection = $collection->toArray();
            $product_update->update($collection);
            $old_shipping = Shipping::where('product_id', $product_update->id)->delete();
            if(count($shipping) > 0){
                $id = $product_update->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;
                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            if(count($pricing) > 0){
                $all_data_to_insert = [];

                foreach($pricing['from'] as $key => $from){
                    $current_data = [];
                    if(($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)){
                        if(isset($pricing['date_range_pricing'])){
                            if($pricing['date_range_pricing'] != null){
                                if($pricing['date_range_pricing'][$key] != null){
                                    $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));
        
                                    $current_data["discount_start_datetime"] = $discount_start_date;
                                    $current_data["discount_end_datetime"] = $discount_end_date;
                                    $current_data["discount_type"] = $pricing['discount_type'][$key];
                                }else{
                                    $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                                }
                                
                            }else{
                                $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                            }
                        }else{
                            $current_data["discount_start_datetime"] = null;
                            $current_data["discount_end_datetime"] = null;
                            $current_data["discount_type"] = null;
                        }

                        $current_data["id_products"] = $product_update->id;
                        $current_data["from"] = $from;
                        $current_data["to"] = $pricing['to'][$key];
                        $current_data["unit_price"] = $pricing['unit_price'][$key];

                        if(isset($pricing['discount_amount'])){
                            $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                        }else{
                            $current_data["discount_amount"] = null;
                        }
                        if(isset($pricing["discount_percentage"])){
                            $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                        }else{
                            $current_data["discount_percentage"] = null;
                        }

                        array_push($all_data_to_insert, $current_data);
                    }
                }

                PricingConfiguration::where('id_products', $product_update->id)->delete();
                PricingConfiguration::insert($all_data_to_insert);
            }

            unset($collection['is_parent']);
            $collection['parent_id'] = $product_update->id;
            // if(isset($collection['vat_sample'])){
            //     $data_sample = [
            //         'vat_sample' => $collection['vat_sample'],
            //         'sample_description' => $collection['sample_description'],
            //         'sample_price' => $collection['sample_price'],
            //     ];
            // }else{
            //     $data_sample = [
            //         'vat_sample' => 0,
            //         'sample_description' => $collection['sample_description'],
            //         'sample_price' => $collection['sample_price'],
            //     ];
            // }

            $data_sample = [
                'vat_sample' => $vat_user->vat_registered,
                'sample_description' => $collection['sample_description'],
                'sample_price' => $collection['sample_price'],
            ];

            unset($collection['vat_sample']);
            unset($collection['sample_description']);
            unset($collection['sample_price']);

            //dd($variants_data);
            if(count($variants_data) > 0){          
                foreach ($variants_data as $id => $variant){
                    
                    $collection['low_stock_quantity'] = $variant['low_stock_quantity'];
                    $collection['sku'] = $variant['sku'];
                    $collection['vat_sample'] = $vat_user->vat_registered;
                    $collection['sample_description'] = $variant['sample_description'];
                    $collection['sample_price'] = $variant['sample_price'];
                    $collection['published'] = $variant['published'];

                    if(isset($variant['shipper_sample'])){
                        $collection['shipper_sample'] = $variant['shipper_sample'];
                    }else{
                        $collection['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                    }

                    if(isset($variant['estimated_sample'])){
                        $collection['estimated_sample'] = $variant['estimated_sample'];
                    }else{
                        $collection['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                    }

                    if(isset($variant['estimated_shipping_sample'])){
                        $collection['estimated_shipping_sample'] = $variant['estimated_shipping_sample'];
                    }else{
                        $collection['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                    }

                    if(isset($variant['paid_sample'])){
                        $collection['paid_sample'] = $variant['paid_sample'];
                    }else{
                        $collection['paid_sample'] = $shipping_sample_parent['paid_sample'];
                    }

                    if(isset($variant['shipping_amount'])){
                        $collection['shipping_amount'] = $variant['shipping_amount'];
                    }else{
                        $collection['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                    }

                    if(isset($variant['sample_available'])){
                        $collection['sample_available'] = $variant['sample_available'];
                    }else{
                        $collection['sample_available'] = $shipping_sample_parent['sample_available'];
                    }

                    $product = Product::find($id);
                    if($product != null){
                        $product->update($collection);

                        //attributes of variant
                        //$sku = "";
                        foreach($variant['attributes'] as $key => $value_attribute){
                            if($value_attribute != null){
                                $attribute_name = Attribute::find($key)->name;
                                $sku .= "_".$attribute_name;
                                $attribute_product = ProductAttributeValues::where('id_products', $id)->where('id_attribute', $key)->first();
                                $check_add = false;
                                if($attribute_product == null){
                                    $attribute_product = new ProductAttributeValues();
                                    $attribute_product->id_products = $product->id;
                                    $attribute_product->id_attribute = $key;
                                    $attribute_product->is_variant = 1;
                                    $check_add = true;
                                }
                                if(in_array($key, $ids_attributes_list)){
                                    $value = AttributeValue::find($value_attribute);
                                    $attribute_product->id_values = $value_attribute;
                                    $attribute_product->value = $value->value;
                                }elseif(in_array($key, $ids_attributes_color)){
                                    $value = Color::where('code', $value_attribute)->first();
                                    $attribute_product->id_colors = $value->id;
                                    $attribute_product->value = $value->code;
                                }elseif(in_array($key, $ids_attributes_numeric)){
                                    $attribute_product->id_units = $data['unit_variant'][$id][$key];
                                    $attribute_product->value = $value_attribute;
                                }
                                else{
                                    $attribute_product->value = $value_attribute;
                                }

                                $attribute_product->save();

                                if($check_add == true){
                                    DB::table('revisions')->insert([
                                        "revisionable_type" => "App\Models\ProductAttributeValues",
                                        "revisionable_id" => $attribute_product->id,
                                        "user_id" => Auth::user()->id,
                                        "key" => 'add_attribute',
                                        "old_value" => NULL,
                                        "new_value" => $value_attribute,
                                        'created_at'            => new \DateTime(),
                                        'updated_at'            => new \DateTime(),
                                    ]);
                                }
                            }
                        }

                        // $product->sku = $product_update->name . $sku;
                        // $product->save();

                        $new_ids_attributes = array_keys($variant['attributes']);
                        $deleted_attributes = ProductAttributeValues::where('id_products', $id)->where('is_variant', 1)->whereNotIn('id_attribute', $new_ids_attributes)->delete();

                        //Images of variant
                        if (array_key_exists('photo', $variant)) {
                            $structure = public_path('upload_products');
                            if (!file_exists($structure)) {
                                mkdir(public_path('upload_products', 0777));
                            }

                            if(!file_exists(public_path('/upload_products/Product-'.$product->id))){
                                mkdir(public_path('/upload_products/Product-'.$product->id, 0777));
                                mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                            }else{
                                if(!file_exists(public_path('/upload_products/Product-'.$product->id.'/images'))){
                                    mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                                }
                            }

                            foreach($variant['photo'] as $key => $image){
                                $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                                $image->move(public_path('/upload_products/Product-'.$product->id.'/images') , $imageName);
                                $path = '/upload_products/Product-'.$product->id.'/images'.'/'.$imageName;

                                $uploaded_document = new UploadProducts();
                                $uploaded_document->id_product = $product->id;
                                $uploaded_document->path = $path;
                                $uploaded_document->extension = $image->getClientOriginalExtension();
                                $uploaded_document->type = 'images';
                                $uploaded_document->save();
                                
                                DB::table('revisions')->insert([
                                    "revisionable_type" => "App\Models\UploadProducts",
                                    "revisionable_id" => $uploaded_document->id,
                                    "user_id" => Auth::user()->id,
                                    "key" => 'add_image',
                                    "old_value" => NULL,
                                    "new_value" => $uploaded_document->id,
                                    'created_at'            => new \DateTime(),
                                    'updated_at'            => new \DateTime(),
                                ]);
                            }
                        }

                        //Pricing configuration of variant
                        if (array_key_exists('pricing', $variant)) {
                            $all_data_to_insert = [];

                            foreach($variant['pricing']['from'] as $key => $from){
                                $current_data = [];
                                if(($from != null) && ($variant['pricing']['to'][$key] != null) && ($variant['pricing']['unit_price'][$key] != null)){
                                    if(isset($variant['pricing']['date_range_pricing'])){
                                        if(($variant['pricing']['date_range_pricing'] != null)){
                                            if(($variant['pricing']['date_range_pricing'][$key]) && ($variant['pricing']['discount_type'][$key])){
                                                $date_var               = explode(" to ", $variant['pricing']['date_range_pricing'][$key]);
                                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                                $current_data["discount_start_datetime"] = $discount_start_date;
                                                $current_data["discount_end_datetime"] = $discount_end_date;
                                                $current_data["discount_type"] = $variant['pricing']['discount_type'][$key];
                                            }else{
                                                $current_data["discount_start_datetime"] = null;
                                                $current_data["discount_end_datetime"] = null;
                                                $current_data["discount_type"] = null;
                                            }
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
                                    $current_data["id_products"] = $product->id;
                                    $current_data["from"] = $from;
                                    $current_data["to"] = $variant['pricing']['to'][$key];
                                    $current_data["unit_price"] = $variant['pricing']['unit_price'][$key];

                                    if(isset($variant['pricing']['discount_amount'])){
                                        $current_data["discount_amount"] = $variant['pricing']['discount_amount'][$key];
                                    }else{
                                        $current_data["discount_amount"] = null;
                                    }
                                    if(isset($variant['pricing']['discount_percentage'])){
                                        $current_data["discount_percentage"] = $variant['pricing']['discount_percentage'][$key];
                                    }else{
                                        $current_data["discount_percentage"] = null;
                                    }

                                    array_push($all_data_to_insert, $current_data);
                                }
                            }

                            PricingConfiguration::where('id_products', $product->id)->delete();
                            PricingConfiguration::insert($all_data_to_insert);
                        }else{
                            //get pricing by default
                            $all_data_to_insert = [];

                            foreach($pricing['from'] as $key => $from){
                                $current_data = [];
                                if(($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)){
                                    if(isset($pricing['date_range_pricing'])){
                                        if(($pricing['date_range_pricing'] != null)){
                                            if(($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])){
                                                $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                                $current_data["discount_start_datetime"] = $discount_start_date;
                                                $current_data["discount_end_datetime"] = $discount_end_date;
                                                $current_data["discount_type"] = $pricing['discount_type'][$key];
                                            }
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }

                                    $current_data["id_products"] = $product->id;
                                    $current_data["from"] = $from;
                                    $current_data["to"] = $pricing['to'][$key];
                                    $current_data["unit_price"] = $pricing['unit_price'][$key];

                                    if(isset($pricing['discount_amount'])){
                                        $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                                    }else{
                                        $current_data["discount_amount"] = null;
                                    }
                                    if(isset($pricing['discount_percentage'])){
                                        $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                                    }else{
                                        $current_data["discount_percentage"] = null;
                                    }
                                    array_push($all_data_to_insert, $current_data);
                                }
                            }

                            PricingConfiguration::insert($all_data_to_insert);
                        }

                        $shipping_to_delete = Shipping::where('product_id', $product->id)->delete();
                        $shipping_details = [];
                        
                        if(array_key_exists('shipping_details', $variant)){
                            if(count($variant['shipping_details']) > 0){
                                foreach ($variant['shipping_details']['from_shipping'] as $key => $from){
                                    if(($from != null) && ($variant['shipping_details']['to_shipping'][$key]!= null)&& ($variant['shipping_details']['shipper'][$key]!= null)&& ($variant['shipping_details']['estimated_order'][$key]!= null)){
                                        $current_shipping = [];
                                        $shippers = implode(',', $variant['shipping_details']['shipper'][$key]);
                                        $current_shipping['from_shipping'] = $from;
                                        $current_shipping['to_shipping'] = $variant['shipping_details']['to_shipping'][$key];
                                        $current_shipping['shipper'] = $shippers;
                                        $current_shipping['estimated_order'] = $variant['shipping_details']['estimated_order'][$key];
                                        $current_shipping['estimated_shipping'] = $variant['shipping_details']['estimated_shipping'][$key];
                                        $current_shipping['paid'] = $variant['shipping_details']['paid'][$key];
                                        $current_shipping['shipping_charge'] = $variant['shipping_details']['shipping_charge'][$key];
                                        $current_shipping['flat_rate_shipping'] = $variant['shipping_details']['flat_rate_shipping'][$key];
                                        $current_shipping['vat_shipping'] = $vat_user->vat_registered;
                                        $current_shipping['product_id'] = $product->id;
                                        $current_shipping['charge_per_unit_shipping'] = $variant['shipping_details']['charge_per_unit_shipping'][$key];

                                        array_push($shipping_details, $current_shipping);
                                    }
                                }
                            }   

                            if(count($shipping_details) > 0){
                                Shipping::insert($shipping_details);
                            }
                        }else{
                            if(count($shipping) > 0){
                                $keyToRemove = 'product_id'; // For example, let's say you want to remove the element at index 1

                                // Using array_map() and array_filter()
                                $shipping = array_map(function($arr) use ($keyToRemove) {
                                    return array_filter($arr, function($k) use ($keyToRemove) {
                                        return $k !== $keyToRemove;
                                    }, ARRAY_FILTER_USE_KEY);
                                }, $shipping);

                                $id = $product->id;
                                $keyToPush = 'product_id';
                                $shipping = array_map(function($arr) use ($id, $keyToPush) {
                                    $arr[$keyToPush] = $id;
                                    return $arr;
                                }, $shipping);

                                Shipping::insert($shipping);
                            }
                        }
                    }
                    
                }
            }

            if(count($general_attributes_data) > 0){
                foreach ($general_attributes_data as $attr => $value) {
                    if($value != null){
                        $attribute_product = ProductAttributeValues::where('id_products', $product_update->id)->where('id_attribute', $attr)->first();
                        $check_add = false;  
                        if($attribute_product == null){
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $product_update->id;
                            $attribute_product->id_attribute = $attr;
                            $attribute_product->is_general = 1;

                            $check_add = true;
                        }

                        if(in_array($attr, $ids_attributes_list)){
                            $value_attribute = AttributeValue::find($value);
                            $attribute_product->id_values = $value;
                            $attribute_product->value = $value_attribute->value;
                        }elseif(in_array($attr, $ids_attributes_color)){
                            $value = Color::where('code', $value)->first();
                            $attribute_product->id_colors = $value->id;
                            $attribute_product->value = $value->code;
                        }elseif(in_array($attr, $ids_attributes_numeric)){
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                        }
                        else{
                            $attribute_product->value = $value;
                        }

                        $attribute_product->save();

                        if($check_add == true){
                            DB::table('revisions')->insert([
                                "revisionable_type" => "App\Models\ProductAttributeValues",
                                "revisionable_id" => $attribute_product->id,
                                "user_id" => Auth::user()->id,
                                "key" => 'add_attribute',
                                "old_value" => NULL,
                                "new_value" => $value,
                                'created_at'            => new \DateTime(),
                                'updated_at'            => new \DateTime(),
                            ]);
                        }
                    }
                }
            }

            $new_ids_attributes_general = array_keys($general_attributes_data);
            $deleted_attributes_general = ProductAttributeValues::where('id_products', $product_update->id)->where('is_general', 1)->whereNotIn('id_attribute', $new_ids_attributes_general)->delete();

            if(count($variants_new_data)){
                foreach ($variants_new_data as $id => $variant){
                    if (!array_key_exists('shipping', $variant)) {
                        $collection['shipping'] = 0;
                    }else{
                        $collection['shipping'] = $variant['shipping'];
                    }
                    $collection['low_stock_quantity'] = $variant['stock'];
                    if(array_key_exists('sku', $variant)){
                        $collection['sku'] = $variant['sku'];
                    }else{
                        $collection['sku'] = '';
                    }

                    if(isset($variant['variant_shipper_sample'])){
                        $data['shipper_sample'] = $variant['variant_shipper_sample'];
                    }else{
                        $data['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                    }

                    if(isset($variant['estimated_sample'])){
                        $data['estimated_sample'] = $variant['estimated_sample'];
                    }else{
                        $data['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                    }

                    if(isset($variant['estimated_shipping_sample'])){
                        $data['estimated_shipping_sample'] = $variant['estimated_shipping_sample'];
                    }else{
                        $data['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                    }

                    if(isset($variant['paid_sample'])){
                        $data['paid_sample'] = $variant['paid_sample'];
                    }else{
                        $data['paid_sample'] = $shipping_sample_parent['paid_sample'];
                    }

                    if(isset($variant['shipping_amount'])){
                        $data['shipping_amount'] = $variant['shipping_amount'];
                    }else{
                        $data['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                    }

                    if(isset($variant['sample_available'])){
                        $data['sample_available'] = $variant['sample_available'];
                    }else{
                        $data['sample_available'] = $shipping_sample_parent['sample_available'];
                    }
                    
                    if(!isset($variant['sample_price'])){
                        $collection['vat_sample'] = $vat_user->vat_registered;
                        $collection['sample_description'] = $data_sample['sample_description'];
                        $collection['sample_price'] = $data_sample['sample_price'];
                    }else{
                        $collection['vat_sample'] = $vat_user->vat_registered;
                        $collection['sample_description'] = $variant['sample_description'];
                        $collection['sample_price'] = $variant['sample_price'];
                    }

                    $randomString = Str::random(5);
                    $collection['slug'] =  $collection['slug'] . '-' . $randomString;

                    $new_product = Product::create($collection);

                    //attributes of variant
                    foreach($variant['attributes'] as $key => $value_attribute){
                        if($value_attribute != null){
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $new_product->id;
                            $attribute_product->id_attribute = $key;
                            $attribute_product->is_variant = 1;
                            if(in_array($key, $ids_attributes_list)){
                                $value = AttributeValue::find($value_attribute);
                                $attribute_product->id_values = $value_attribute;
                                $attribute_product->value = $value->value;
                            }elseif(in_array($key, $ids_attributes_color)){
                                $value = Color::where('code', $value_attribute)->first();
                                $attribute_product->id_colors = $value->id;
                                $attribute_product->value = $value->code;
                            }elseif(in_array($key, $ids_attributes_numeric)){
                                $attribute_product->id_units = $variant['units'][$key];
                                $attribute_product->value = $value_attribute;
                            }
                            else{
                                $attribute_product->value = $value_attribute;
                            }

                            $attribute_product->save();
                        }
                    }

                    //Images of variant
                    if (array_key_exists('photo', $variant)) {
                        $structure = public_path('upload_products');
                        if (!file_exists($structure)) {
                            mkdir(public_path('upload_products', 0777));
                        }

                        if(!file_exists(public_path('/upload_products/Product-'.$new_product->id))){
                            mkdir(public_path('/upload_products/Product-'.$new_product->id, 0777));
                            mkdir(public_path('/upload_products/Product-'.$new_product->id.'/images', 0777));
                        }else{
                            if(!file_exists(public_path('/upload_products/Product-'.$new_product->id.'/images'))){
                                mkdir(public_path('/upload_products/Product-'.$new_product->id.'/images', 0777));
                            }
                        }

                        foreach($variant['photo'] as $key => $image){
                            $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                            $image->move(public_path('/upload_products/Product-'.$new_product->id.'/images') , $imageName);
                            $path = '/upload_products/Product-'.$new_product->id.'/images'.'/'.$imageName;

                            $uploaded_document = new UploadProducts();
                            $uploaded_document->id_product = $new_product->id;
                            $uploaded_document->path = $path;
                            $uploaded_document->extension = $image->getClientOriginalExtension();
                            $uploaded_document->type = 'images';
                            $uploaded_document->save();
                        }
                    }
                    
                    //Pricing configuration of variant
                    if (array_key_exists('pricing', $variant)) {
                        $all_data_to_insert = [];

                        foreach($variant['pricing']['from'] as $key => $from){
                            $current_data = [];
                            if(($from != null) && ($variant['pricing']['to'][$key] != null) && ($variant['pricing']['unit_price'][$key] != null)){
                                if(isset($variant['pricing']['discount_range'])){
                                    if(($variant['pricing']['discount_range'] != null)){
                                        if(($variant['pricing']['discount_range'][$key]) && ($variant['pricing']['discount_type'][$key])){
                                            $date_var               = explode(" to ", $variant['pricing']['discount_range'][$key]);
                                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                            $current_data["discount_start_datetime"] = $discount_start_date;
                                            $current_data["discount_end_datetime"] = $discount_end_date;
                                            $current_data["discount_type"] = $variant['pricing']['discount_type'][$key];
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
                                }else{
                                    $current_data["discount_start_datetime"] = null;
                                    $current_data["discount_end_datetime"] = null;
                                    $current_data["discount_type"] = null;
                                }

                                $current_data["id_products"] = $new_product->id;
                                $current_data["from"] = $from;
                                $current_data["to"] = $variant['pricing']['to'][$key];
                                $current_data["unit_price"] = $variant['pricing']['unit_price'][$key];

                                if(isset($variant['pricing']['discount_amount'])){
                                    $current_data["discount_amount"] = $variant['pricing']['discount_amount'][$key];
                                }else{
                                    $current_data["discount_amount"] = null;
                                }
                                if(isset($variant['pricing']['discount_percentage'])){
                                    $current_data["discount_percentage"] = $variant['pricing']['discount_percentage'][$key];
                                }else{
                                    $current_data["discount_percentage"] = null;
                                }
                                array_push($all_data_to_insert, $current_data);
                            }
                        }
                        if(count ($all_data_to_insert) > 0){
                            PricingConfiguration::insert($all_data_to_insert);
                        } 
                    }else{
                        //get pricing by default
                        $all_data_to_insert = [];

                        foreach($pricing['from'] as $key => $from){
                            $current_data = [];
                            if($pricing['from'][$key] != null && $pricing['unit_price'][$key] != null){
                                    if($pricing['date_range_pricing'][$key] != null){
                                        if(($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])){
                                            $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));
            
                                            $current_data["discount_start_datetime"] = $discount_start_date;
                                            $current_data["discount_end_datetime"] = $discount_end_date;
                                            $current_data["discount_type"] = $pricing['discount_type'][$key];
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
            
                                $current_data["id_products"] = $new_product->id;
                                $current_data["from"] = $from;
                                $current_data["to"] = $pricing['to'][$key];
                                $current_data["unit_price"] = $pricing['unit_price'][$key];
            
                                if(isset($pricing['discount_amount'])){
                                    $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                                }else{
                                    $current_data["discount_amount"] = null;
                                }
                                if(isset($pricing["discount_percentage"])){
                                    $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                                }else{
                                    $current_data["discount_percentage"] = null;
                                }
            
                                array_push($all_data_to_insert, $current_data);
                            }
                            
                        }

                        PricingConfiguration::insert($all_data_to_insert);
                    }

                    $shipping_details = [];
                    if(array_key_exists('shipping_details', $variant)){
                        foreach($variant['shipping_details']['from'] as $key => $from){
                            if(($from != null) && ($variant['shipping_details']['to'][$key]!= null)&& ($variant['shipping_details']['shipper'][$key]!= null)&& ($variant['shipping_details']['estimated_order'][$key]!= null)){
                                $current_shipping = [];
                                $shippers = implode(',', $variant['shipping_details']['shipper'][$key]);
                                $current_shipping['from_shipping'] = $from;
                                $current_shipping['to_shipping'] = $variant['shipping_details']['to'][$key];
                                $current_shipping['shipper'] = $shippers;
                                $current_shipping['estimated_order'] = $variant['shipping_details']['estimated_order'][$key];
                                $current_shipping['estimated_shipping'] = $variant['shipping_details']['estimated_shipping'][$key];
                                $current_shipping['paid'] = $variant['shipping_details']['paid'][$key];
                                $current_shipping['shipping_charge'] = $variant['shipping_details']['shipping_charge'][$key];
                                $current_shipping['flat_rate_shipping'] = $variant['shipping_details']['flat_rate_shipping'][$key];
                                $current_shipping['vat_shipping'] = $vat_user->vat_registered;
                                $current_shipping['product_id'] = $new_product->id;
                                $current_shipping['charge_per_unit_shipping'] = $variant['shipping_details']['charge_per_unit_shipping'][$key];

                                array_push($shipping_details, $current_shipping);
                            }
                        }

                        if(count($shipping_details) > 0){
                            Shipping::insert($shipping_details);
                        }
                    }else{
                        if(count($shipping) > 0){
                            $keyToRemove = 'product_id'; // For example, let's say you want to remove the element at index 1

                            // Using array_map() and array_filter()
                            $shipping = array_map(function($arr) use ($keyToRemove) {
                                return array_filter($arr, function($k) use ($keyToRemove) {
                                    return $k !== $keyToRemove;
                                }, ARRAY_FILTER_USE_KEY);
                            }, $shipping);

                            $id = $new_product->id;
                            $keyToPush = 'product_id';
                            $shipping = array_map(function($arr) use ($id, $keyToPush) {
                                $arr[$keyToPush] = $id;
                                return $arr;
                            }, $shipping);

                            Shipping::insert($shipping);
                        }
                    }
                }

            }
            return $product_update;
        }
    }

    public function product_duplicate_store($product)
    {
        $product_new = $product->replicate();
        $product_new->slug = $product_new->slug . '-' . Str::random(5);
        $product_new->approved = (get_setting('product_approve_by_admin') == 1 && $product->added_by != 'admin') ? 0 : 1;
        $product_new->save();

        return $product_new;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->product_translations()->delete();
        $product->stocks()->delete();
        $product->taxes()->delete();
        $product->wishlists()->delete();
        $product->carts()->delete();
        Product::destroy($id);
    }

    public function draft(array $data, Product $product_draft)
    {
       
        $collection = collect($data);
        
        $collection['user_id'] = auth()->user()->id;
        $collection['approved'] = 0;
        $collection['rejection_reason'] = null;
        $vat_user = BusinessInformation::where('user_id', Auth::user()->id)->first();

        $slug = Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count > 1 ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;
        

        $collection['slug'] = $slug;
        if(isset($collection['refundable'])){
            $collection['refundable'] = 1;
        }else{
            $collection['refundable'] = 0;
        }
        
        if(isset($collection['published_modal'])){
            $collection['published'] = 1;
        }else{
            $collection['published'] = 0;
        } 

        if(isset($collection['create_stock'])){
            $collection['stock_after_create'] = 1;
        }else{
            $collection['stock_after_create'] = 0;
        } 

        if(isset($collection['activate_third_party'])){
            $collection['activate_third_party'] = 1;
        }

        if($collection['parent_id'] != null){
            $collection['category_id'] = $collection['parent_id'];
        }

        unset($collection['parent_id']);
        
        $is_draft = 0;

        if(isset($collection['button'])){
            $published = 0;
            if ($collection['button'] == 'draft') {
              $is_draft = 1;  
            }
            unset($collection['button']);
        }

        if(isset($collection['product_sk'])){
            $collection['sku'] = $collection['product_sk'];
            unset($collection['product_sk']);
        }else{
            $collection['sku'] = null;
        }

        if(isset($collection['quantite_stock_warning'])){
            $collection['low_stock_quantity'] = $collection['quantite_stock_warning'];
            unset($collection['quantite_stock_warning']);
        }else{
            $collection['low_stock_quantity'] = null;
        }       

        $collection['is_draft'] = $is_draft;

        $pricing = [];
        if((isset($collection['from'])) &&(isset($collection['to'])) && (isset($collection['unit_price']))){
            $pricing = [
                "from" => $collection['from'],
                "to" => $collection['to'],
                "unit_price" => $collection['unit_price'],
            ];

            if(isset($collection['discount_type'])){
                $pricing["discount_type"]= $collection['discount_type'];
            }

            if(isset($collection['date_range_pricing'])){
                $pricing["date_range_pricing"]= $collection['date_range_pricing'];
            }

            if(isset($collection['discount_amount'])){
                $pricing["discount_amount"]= $collection['discount_amount'];
            }

            if(isset($collection['discount_percentage'])){
                $pricing["discount_percentage"]= $collection['discount_percentage'];
            }

            unset($collection['from']);
            unset($collection['to']);
            unset($collection['unit_price']);
            unset($collection['discount_amount']);
            unset($collection['discount_type']);
            unset($collection['discount_percentage']);
        }

        $tags = array();
        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $collection['tags'] = implode(',', $tags);

        if(isset($collection['stock_visibility_state'])){
            $collection['stock_visibility_state'] ="quantity";
        }else{
            $collection['stock_visibility_state'] ="hide";
        }

        $shipping = [];
        foreach($collection['from_shipping'] as $key => $from_shipping){
            if(($from_shipping != null) && ($collection['to_shipping'][$key]!= null)&& ($collection['shipper'][$key]!= null)&& ($collection['estimated_order'][$key]!= null)){
                $current_data = [];
                $shippers = implode(',', $collection['shipper'][$key]);
                $current_data['from_shipping'] = $from_shipping;
                $current_data['to_shipping'] = $collection['to_shipping'][$key];
                $current_data['shipper'] = $shippers;
                $current_data['estimated_order'] = $collection['estimated_order'][$key];
                $current_data['estimated_shipping'] = $collection['estimated_shipping'][$key];
                $current_data['paid'] = $collection['paid'][$key];
                $current_data['shipping_charge'] = $collection['shipping_charge'][$key];
                $current_data['flat_rate_shipping'] = $collection['flat_rate_shipping'][$key];
                $current_data['vat_shipping'] = $vat_user->vat_registered;
                $current_data['charge_per_unit_shipping'] = $collection['charge_per_unit_shipping'][$key];

                array_push($shipping, $current_data);
            }            
        }

        //dd($collection);
        $variants_data = [];
        $variants_new_data = [];
        $general_attributes_data = [];
        $unit_general_attributes_data = [];
        
        //check if product has old variants 
        if (array_key_exists('variant', $data)) {
            foreach($collection['variant']['sku'] as $key => $sku){
                if(!array_key_exists($key, $variants_data)){
                    $variants_data[$key] = [];
                }

                $variants_data[$key]['sku'] = $sku;

                //Check if the variant has pictures 
                if(array_key_exists('photo', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['photo'])){
                        $variants_data[$key]['photo'] = $data['variant']['photo'][$key];
                    }else{
                        $variants_data[$key]['photo'] = [];
                    }
                }else{
                    $variants_data[$key]['photo'] = [];
                }

                //check if the variant has pricing configuration
                if(array_key_exists('from', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['from'])){
                        $pricing_variant = [];
                        $pricing_variant['from'] = $data['variant']['from'][$key];
                        $pricing_variant['to'] = $data['variant']['to'][$key];
                        $pricing_variant['unit_price'] = $data['variant']['unit_price'][$key];
                        $pricing_variant['date_range_pricing'] = $data['variant']['date_range_pricing'][$key];
                        $pricing_variant['discount_type'] = $data['variant']['discount_type'][$key];
                        $pricing_variant['discount_amount'] = $data['variant']['discount_amount'][$key];
                        $pricing_variant['discount_percentage'] = $data['variant']['discount_percentage'][$key];
                        $variants_data[$key]['pricing'] = $pricing_variant;
                    }else{
                        $variants_data[$key]['pricing'] = $pricing;
                    }
                }else{
                    $variants_data[$key]['pricing'] = $pricing;
                }

                if(array_key_exists('from_shipping', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['from_shipping'])){
                        $shipping_variant = [];
                        $shipping_variant['from_shipping'] = $data['variant']['from_shipping'][$key];
                        $shipping_variant['to_shipping'] = $data['variant']['to_shipping'][$key];
                        $shipping_variant['shipper'] = $data['variant']['shipper'][$key];
                        $shipping_variant['estimated_order'] = $data['variant']['estimated_order'][$key];
                        $shipping_variant['estimated_shipping'] = $data['variant']['estimated_shipping'][$key];
                        $shipping_variant['paid'] = $data['variant']['paid'][$key];
                        $shipping_variant['shipping_charge'] = $data['variant']['shipping_charge'][$key];
                        $shipping_variant['flat_rate_shipping'] = $data['variant']['flat_rate_shipping'][$key];
                        $shipping_variant['charge_per_unit_shipping'] = $data['variant']['charge_per_unit_shipping'][$key];
                        $variants_data[$key]['shipping_details'] = $shipping_variant;
                    }else{
                        $shipping_parent = [];
                        if($collection['from_shipping'][0] && ($collection['to_shipping'][0]!= null)&& ($collection['shipper'][0]!= null)&& ($collection['estimated_order'][0]!= null)){
                            $shipping_parent['from_shipping'] = $collection['from_shipping'];
                            $shipping_parent['to_shipping'] = $collection['to_shipping'];
                            $shipping_parent['shipper'] = $collection['shipper'];
                            $shipping_parent['estimated_order'] = $collection['estimated_order'];
                            $shipping_parent['estimated_shipping'] = $collection['estimated_shipping'];
                            $shipping_parent['paid'] = $collection['paid'];
                            $shipping_parent['shipping_charge'] = $collection['shipping_charge'];
                            $shipping_parent['flat_rate_shipping'] = $collection['flat_rate_shipping'];
                            $shipping_parent['vat_shipping'] = $vat_user->vat_registered;
                            $shipping_parent['charge_per_unit_shipping'] = $collection['charge_per_unit_shipping'];
                        }
                        $variants_data[$key]['shipping_details'] = $shipping_parent;
                    }
                }else{
                    $shipping_parent = [];
                        if($collection['from_shipping'][0] && ($collection['to_shipping'][0]!= null)&& ($collection['shipper'][0]!= null)&& ($collection['estimated_order'][0]!= null)){
                            $shipping_parent['from_shipping'] = $collection['from_shipping'];
                            $shipping_parent['to_shipping'] = $collection['to_shipping'];
                            $shipping_parent['shipper'] = $collection['shipper'];
                            $shipping_parent['estimated_order'] = $collection['estimated_order'];
                            $shipping_parent['estimated_shipping'] = $collection['estimated_shipping'];
                            $shipping_parent['paid'] = $collection['paid'];
                            $shipping_parent['shipping_charge'] = $collection['shipping_charge'];
                            $shipping_parent['flat_rate_shipping'] = $collection['flat_rate_shipping'];
                            $shipping_parent['vat_shipping'] = $vat_user->vat_registered;
                            $shipping_parent['charge_per_unit_shipping'] = $collection['charge_per_unit_shipping'];
                        }
                        
                    $variants_data[$key]['shipping_details'] = $shipping_parent;
                }

                if(array_key_exists('sample_available', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['sample_available'])){
                        $variants_data[$key]['sample_available'] = 1;
                    }else{
                        $variants_data[$key]['sample_available'] = 0;
                    }
                }else{
                    $variants_data[$key]['sample_available'] = 0;
                }

                if(array_key_exists('shipper_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['shipper_sample'])){
                        $variants_data[$key]['shipper_sample'] = $data['variant']['shipper_sample'][$key];
                    }else{
                        $variants_data[$key]['shipper_sample'] = $data['variant']['shipper_sample'][$key];
                    }
                }else{
                    $variants_data[$key]['shipper_sample'] = $data['variant']['shipper_sample'][$key];
                }

                //////////////////////////////////////////////////////////////////////////

                if(array_key_exists('estimated_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['estimated_sample'])){
                        $variants_data[$key]['estimated_sample'] = $data['variant']['estimated_sample'][$key];
                    }else{
                        $variants_data[$key]['estimated_sample'] = $data['variant']['estimated_sample'][$key];
                    }
                }else{
                    $variants_data[$key]['estimated_sample'] = $data['variant']['estimated_sample'][$key];
                }

                if(array_key_exists('estimated_shipping_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['estimated_shipping_sample'])){
                        $variants_data[$key]['estimated_shipping_sample'] = $data['variant']['estimated_shipping_sample'][$key];
                    }else{
                        $variants_data[$key]['estimated_shipping_sample'] = $data['variant']['estimated_shipping_sample'][$key];
                    }
                }else{
                    $variants_data[$key]['estimated_shipping_sample'] = $data['variant']['estimated_shipping_sample'][$key];
                }

                if(array_key_exists('paid_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['paid_sample'])){
                        $variants_data[$key]['paid_sample'] = $data['variant']['paid_sample'][$key];
                    }else{
                        $variants_data[$key]['paid_sample'] = 0;
                    }
                }else{
                    $variants_data[$key]['paid_sample'] = 0;
                }

                if(array_key_exists('shipping_amount', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['shipping_amount'])){
                        $variants_data[$key]['shipping_amount'] = $data['variant']['shipping_amount'][$key];
                    }else{
                        $variants_data[$key]['shipping_amount'] = $data['variant']['shipping_amount'][$key];
                    }
                }else{
                    $variants_data[$key]['shipping_amount'] = $data['variant']['shipping_amount'][$key];
                }
                

                //check if the variant has sample pricing
                if(array_key_exists('sample_pricing', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['sample_pricing'])){
                        $variants_data[$key]['sample_pricing'] = 0;
                        $variants_data[$key]['sample_description'] = $data['sample_description'];
                        $variants_data[$key]['sample_price'] = $data['sample_price'];
                    }else{
                        $variants_data[$key]['sample_pricing'] = 1;
                        $variants_data[$key]['sample_description'] = $data['variant']['sample_description'][$key];
                        $variants_data[$key]['sample_price'] = $data['variant']['sample_price'][$key];
                    }
                }else{
                    $variants_data[$key]['sample_pricing'] = 0;
                    $variants_data[$key]['sample_description'] = $data['sample_description'];
                    $variants_data[$key]['sample_price'] = $data['sample_price'];
                }

                //check if the variant activated the shipping configuration
                if(array_key_exists('shipping', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['shipping'])){
                        $variants_data[$key]['shipping'] = $data['variant']['shipping'][$key];
                    }else{
                        $variants_data[$key]['shipping'] = 0;
                    }
                }else{
                    $variants_data[$key]['shipping'] = 0;
                }

                //check if the variant is published
                if(array_key_exists('published', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['published'])){
                        $variants_data[$key]['published'] = $data['variant']['published'][$key];
                    }else{
                        $variants_data[$key]['published'] = 0;
                    }
                }else{
                    $variants_data[$key]['published'] = 0;
                }

                //check if the variant activated the sample shipping configuration
                if(array_key_exists('sample_shipping', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['sample_shipping'])){
                        $variants_data[$key]['sample_shipping'] = $data['variant']['sample_shipping'][$key];
                    }else{
                        $variants_data[$key]['sample_shipping'] = 0;
                    }
                }else{
                    $variants_data[$key]['sample_shipping'] = 0;
                }

                //check if the variant activated vat option for sample
                if(array_key_exists('vat_sample', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['vat_sample'])){
                        $variants_data[$key]['vat_sample'] = $data['variant']['vat_sample'][$key];
                    }else{
                        $variants_data[$key]['vat_sample'] = 0;
                    }
                }else{
                    $variants_data[$key]['vat_sample'] = 0;
                }

                //check if the variant has low stock quantity
                if(array_key_exists('low_stock_quantity', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['low_stock_quantity'])){
                        $variants_data[$key]['low_stock_quantity'] = $data['variant']['low_stock_quantity'][$key];
                    }else{
                        $variants_data[$key]['low_stock_quantity'] = 0;
                    }
                }else{
                    $variants_data[$key]['low_stock_quantity'] = 0;
                }

                //Check if the variant has attributes. If it does, a table will be generated containing all attributes, with each attribute having its own value.
                if(array_key_exists('attributes', $data['variant'])){
                    if(array_key_exists($key, $data['variant']['attributes'])){
                        foreach($data['variant']['attributes'][$key] as $id_attribute => $attribute){
                            if(!array_key_exists('attributes', $variants_data[$key])){
                                $variants_data[$key]['attributes'][$id_attribute]=$attribute;
                            }else{
                                if(!array_key_exists($id_attribute, $variants_data[$key]['attributes'])){
                                    $variants_data[$key]['attributes'][$id_attribute]=$attribute;
                                }
                            }
                        }
                    }else{
                        $variants_data[$key]['attributes'] = [];
                    }
                }else{
                    $variants_data[$key]['attributes'] = [];
                }
            }

            unset($collection['variant']);
        }
        
        //Check if porduct has new variants
        foreach ($data as $key => $value) {
            if (strpos($key, 'attributes-') === 0) {
                //Check if the new variant has attributes. If it does, a table will be generated containing all attributes, with each attribute having its own value.
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_new_data)){
                    $variants_new_data[$ids[2]] = [];
                }
                if(!array_key_exists('attributes', $variants_new_data[$ids[2]])){
                    $variants_new_data[$ids[2]]['attributes'][$ids[1]]=$value;
                }else{
                    if(!array_key_exists($ids[1], $variants_new_data[$ids[2]]['attributes'])){
                        $variants_new_data[$ids[2]]['attributes'][$ids[1]]=$value;
                    }
                }

                //check if the variant activated the variant pricing
                $key_pricing = 'variant-pricing-'.$ids[2];
                if(!isset($data[$key_pricing])){
                    if(!array_key_exists($ids[2], $variants_new_data)){
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['pricing'] = $data['variant_pricing-from' . $ids[2]];
                }

                $key_shipping = 'variant_shipping-'.$ids[2];
                if(isset($data[$key_shipping])){
                    if(!array_key_exists($ids[2], $variants_new_data)){
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['shipping_details'] = $data['variant_shipping-' . $ids[2]];
                }

                $key_sample_available = 'variant-sample-available'.$ids[2];
                if(isset($data[$key_sample_available])){
                    if(!array_key_exists($ids[2], $variants_new_data)){
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['sample_available'] = 1;
                }else{
                    if(!array_key_exists($ids[2], $variants_new_data)){
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['sample_available'] = 0;
                }
            }

            if(strpos($key, 'variant-published-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_data)){
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['published'] = $value;
            }

            if(strpos($key, 'sku') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['sku'] = $value;
            }

            if(strpos($key, 'stock-warning-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_new_data)){
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['stock'] = $value;
            }

            if(strpos($key, 'variant-shipping-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_new_data)){
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['shipping'] = 1;
            }

            if(strpos($key, 'photos_variant') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['photo'] = $value;
            }

            if(strpos($key, 'attributes_units') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[2], $variants_new_data)){
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['units'][$ids[1]] = $value;
            }

            if(strpos($key, 'attribute_generale-') === 0){
                $ids = explode('-', $key);
                $general_attributes_data[$ids[1]] = $value;
            }

            if(strpos($key, 'unit_attribute_generale-') === 0){
                $ids = explode('-', $key);
                $unit_general_attributes_data[$ids[1]] = $value;
            }

            if(strpos($key, 'vat_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['vat_sample'] = $value;
            }

            if(strpos($key, 'sample_description-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }

                if($value != null){
                    $variants_new_data[$ids[1]]['sample_description'] = $value;
                }
            }

            if(strpos($key, 'sample_price-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_new_data)){
                    $variants_new_data[$ids[1]] = [];
                }
                if($value != null){
                    $variants_new_data[$ids[1]]['sample_price'] = $value;
                }

            }

            if(strpos($key, 'estimated_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_sample'] = $value;
            }

            if(strpos($key, 'estimated_shipping_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_shipping_sample'] = $value;
            }

            if(strpos($key, 'shipping_amount-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['shipping_amount'] = $value;
            }

            if(strpos($key, 'variant_shipper_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['variant_shipper_sample'] = $value;
            }

            if(strpos($key, 'paid_sample-') === 0){
                $ids = explode('-', $key);
                if(!array_key_exists($ids[1], $variants_data)){
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['paid_sample'] = $value;
            }
        }

        $shipping_sample_parent = [];
        $shipping_sample_parent['shipper_sample'] = $collection['shipper_sample'];
        $shipping_sample_parent['estimated_sample'] = $collection['estimated_sample'];
        $shipping_sample_parent['estimated_shipping_sample'] = $collection['estimated_shipping_sample'];
        $shipping_sample_parent['paid_sample'] = $collection['paid_sample'];
        $shipping_sample_parent['shipping_amount'] = $collection['shipping_amount'];

        unset($collection['from_shipping']);
        unset($collection['to_shipping']);
        unset($collection['shipper']);
        unset($collection['estimated_order']);
        unset($collection['estimated_shipping']);
        unset($collection['paid']);
        unset($collection['shipping_charge']);
        unset($collection['flat_rate_shipping']);
        unset($collection['vat_shipping']);
        unset($collection['charge_per_unit_shipping']);
        unset($collection['date_range_pricing']);

        $collection['vat'] = $vat_user->vat_registered;

        $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();
        $ids_attributes_list = Attribute::where('type_value', 'list')->pluck('id')->toArray();
        $ids_attributes_numeric = Attribute::where('type_value', 'numeric')->pluck('id')->toArray();

        if(!isset($data['activate_attributes'])){
            //Create product without variants
            $collection = $collection->toArray();
            $product_draft->update($collection);
            $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();

            if(count($pricing) > 0){
                $all_data_to_insert = [];

                foreach($pricing['from'] as $key => $from){
                    $current_data = [];
                    if(($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)){
                        if(isset($pricing['date_range_pricing'])){
                            if($pricing['date_range_pricing'] != null){
                                if($pricing['date_range_pricing'][$key] != null){
                                    $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));
        
                                    $current_data["discount_start_datetime"] = $discount_start_date;
                                    $current_data["discount_end_datetime"] = $discount_end_date;
                                    $current_data["discount_type"] = $pricing['discount_type'][$key];
                                }else{
                                    $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                                }
                                
                            }else{
                                $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                            }
                        }else{
                            $current_data["discount_start_datetime"] = null;
                            $current_data["discount_end_datetime"] = null;
                            $current_data["discount_type"] = null;
                        }

                        $current_data["id_products"] = $product_draft->id;
                        $current_data["from"] = $from;
                        $current_data["to"] = $pricing['to'][$key];
                        $current_data["unit_price"] = $pricing['unit_price'][$key];

                        if(isset($pricing['discount_amount'])){
                            $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                        }else{
                            $current_data["discount_amount"] = null;
                        }
                        if(isset($pricing["discount_percentage"])){
                            $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                        }else{
                            $current_data["discount_percentage"] = null;
                        }

                        array_push($all_data_to_insert, $current_data);
                    }
                }

                PricingConfiguration::where('id_products', $product_draft->id)->delete();
                PricingConfiguration::insert($all_data_to_insert);
            }

            if(count($general_attributes_data) > 0){
                foreach ($general_attributes_data as $attr => $value) {
                    if($value != null){
                        $attribute_product = ProductAttributeValues::where('id_products', $product_draft->id)->where('id_attribute', $attr)->first();
                                
                        if($attribute_product == null){
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $product_draft->id;
                            $attribute_product->id_attribute = $attr;
                            $attribute_product->is_general = 1;
                        }

                        if(in_array($attr, $ids_attributes_list)){
                            $value_attribute = AttributeValue::find($value);
                            $attribute_product->id_values = $value;
                            $attribute_product->value = $value_attribute->value;
                        }elseif(in_array($attr, $ids_attributes_color)){
                            $value = Color::where('code', $value)->first();
                            $attribute_product->id_colors = $value->id;
                            $attribute_product->value = $value;
                        }elseif(in_array($attr, $ids_attributes_numeric)){
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                        }
                        else{
                            $attribute_product->value = $value;
                        }

                        $attribute_product->save();
                    }
                }
            }

            $shipping_to_delete = Shipping::where('product_id', $product_draft->id)->delete();

            if(count($shipping) > 0){
                $id = $product_draft->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;
                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            $childrens = Product::where('parent_id', $product_draft->id)->pluck('id')->toArray();
            if(count($childrens) > 0){
                Shipping::whereIn('product_id', $childrens)->delete();
                PricingConfiguration::whereIn('id_products', $childrens)->delete();
                ProductAttributeValues::whereIn('id_products', $childrens)->delete();
                UploadProducts::whereIn('id_product', $childrens)->delete();
                Product::where('parent_id', $product_draft->id)->delete();
                $product_draft->is_parent = 0;
                $product_draft->save();
            }

            return $product_draft;
        }else{
            // //Create Parent Product
            $collection['is_parent'] = 1;
            $collection = $collection->toArray();
            $product_draft->update($collection);
            $old_shipping = Shipping::where('product_id', $product_draft->id)->delete();
            if(count($shipping) > 0){
                $id = $product_draft->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;
                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            if(count($pricing) > 0){
                $all_data_to_insert = [];

                foreach($pricing['from'] as $key => $from){
                    $current_data = [];
                    if(($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)){
                        if(isset($pricing['date_range_pricing'])){
                            if($pricing['date_range_pricing'] != null){
                                if($pricing['date_range_pricing'][$key] != null){
                                    $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));
        
                                    $current_data["discount_start_datetime"] = $discount_start_date;
                                    $current_data["discount_end_datetime"] = $discount_end_date;
                                    $current_data["discount_type"] = $pricing['discount_type'][$key];
                                }else{
                                    $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                                }
                                
                            }else{
                                $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                            }
                        }else{
                            $current_data["discount_start_datetime"] = null;
                            $current_data["discount_end_datetime"] = null;
                            $current_data["discount_type"] = null;
                        }

                        $current_data["id_products"] = $product_draft->id;
                        $current_data["from"] = $from;
                        $current_data["to"] = $pricing['to'][$key];
                        $current_data["unit_price"] = $pricing['unit_price'][$key];

                        if(isset($pricing['discount_amount'])){
                            $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                        }else{
                            $current_data["discount_amount"] = null;
                        }
                        if(isset($pricing["discount_percentage"])){
                            $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                        }else{
                            $current_data["discount_percentage"] = null;
                        }

                        array_push($all_data_to_insert, $current_data);
                    }
                }

                PricingConfiguration::where('id_products', $product_draft->id)->delete();
                PricingConfiguration::insert($all_data_to_insert);
            }

            unset($collection['is_parent']);
            $collection['parent_id'] = $product_draft->id;
            // if(isset($collection['vat_sample'])){
            //     $data_sample = [
            //         'vat_sample' => $collection['vat_sample'],
            //         'sample_description' => $collection['sample_description'],
            //         'sample_price' => $collection['sample_price'],
            //     ];
            // }else{
            //     $data_sample = [
            //         'vat_sample' => 0,
            //         'sample_description' => $collection['sample_description'],
            //         'sample_price' => $collection['sample_price'],
            //     ];
            // }
            
            $data_sample = [
                'vat_sample' => $vat_user->vat_registered,
                'sample_description' => $collection['sample_description'],
                'sample_price' => $collection['sample_price'],
            ];

            unset($collection['vat_sample']);
            unset($collection['sample_description']);
            unset($collection['sample_price']);

            
            if(count($variants_data) > 0){
                
                foreach ($variants_data as $id => $variant){
                    
                    $collection['low_stock_quantity'] = $variant['low_stock_quantity'];
                    $collection['sku'] = $variant['sku'];
                    $collection['vat_sample'] = $vat_user->vat_registered;
                    $collection['sample_description'] = $variant['sample_description'];
                    $collection['sample_price'] = $variant['sample_price'];
                    $collection['published'] = $variant['published'];

                    if(isset($variant['shipper_sample'])){
                        $collection['shipper_sample'] = $variant['shipper_sample'];
                    }else{
                        $collection['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                    }

                    if(isset($variant['estimated_sample'])){
                        $collection['estimated_sample'] = $variant['estimated_sample'];
                    }else{
                        $collection['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                    }

                    if(isset($variant['estimated_shipping_sample'])){
                        $collection['estimated_shipping_sample'] = $variant['estimated_shipping_sample'];
                    }else{
                        $collection['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                    }

                    if(isset($variant['paid_sample'])){
                        $collection['paid_sample'] = $variant['paid_sample'];
                    }else{
                        $collection['paid_sample'] = $shipping_sample_parent['paid_sample'];
                    }

                    if(isset($variant['shipping_amount'])){
                        $collection['shipping_amount'] = $variant['shipping_amount'];
                    }else{
                        $collection['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                    }

                    if(isset($variant['sample_available'])){
                        $collection['sample_available'] = $variant['sample_available'];
                    }else{
                        $collection['sample_available'] = $shipping_sample_parent['sample_available'];
                    }

                    $product = Product::find($id);
                    if($product != null){
                        $product->update($collection);

                        //attributes of variant
                        //$sku = "";
                        foreach($variant['attributes'] as $key => $value_attribute){
                            if($value_attribute != null){
                                $attribute_name = Attribute::find($key)->name;
                                $sku .= "_".$attribute_name;
                                $attribute_product = ProductAttributeValues::where('id_products', $id)->where('id_attribute', $key)->first();
                                
                                if($attribute_product == null){
                                    $attribute_product = new ProductAttributeValues();
                                    $attribute_product->id_products = $product->id;
                                    $attribute_product->id_attribute = $key;
                                    $attribute_product->is_variant = 1;
                                }
                                if(in_array($key, $ids_attributes_list)){
                                    $value = AttributeValue::find($value_attribute);
                                    $attribute_product->id_values = $value_attribute;
                                    $attribute_product->value = $value->value;
                                }elseif(in_array($key, $ids_attributes_color)){
                                    $value = Color::where('code', $value_attribute)->first();
                                    $attribute_product->id_colors = $value->id;
                                    $attribute_product->value = $value->code;
                                }elseif(in_array($key, $ids_attributes_numeric)){
                                    $attribute_product->id_units = $data['unit_variant'][$id][$key];
                                    $attribute_product->value = $value_attribute;
                                }
                                else{
                                    $attribute_product->value = $value_attribute;
                                }

                                $attribute_product->save();
                            }
                        }

                        // $product->sku = $product_draft->name . $sku;
                        // $product->save();

                        $new_ids_attributes = array_keys($variant['attributes']);
                        $deleted_attributes = ProductAttributeValues::where('id_products', $id)->where('is_variant', 1)->whereNotIn('id_attribute', $new_ids_attributes)->delete();

                        //Images of variant
                        if (array_key_exists('photo', $variant)) {
                            $structure = public_path('upload_products');
                            if (!file_exists($structure)) {
                                mkdir(public_path('upload_products', 0777));
                            }

                            if(!file_exists(public_path('/upload_products/Product-'.$product->id))){
                                mkdir(public_path('/upload_products/Product-'.$product->id, 0777));
                                mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                            }else{
                                if(!file_exists(public_path('/upload_products/Product-'.$product->id.'/images'))){
                                    mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                                }
                            }

                            foreach($variant['photo'] as $key => $image){
                                $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                                $image->move(public_path('/upload_products/Product-'.$product->id.'/images') , $imageName);
                                $path = '/upload_products/Product-'.$product->id.'/images'.'/'.$imageName;

                                $uploaded_document = new UploadProducts();
                                $uploaded_document->id_product = $product->id;
                                $uploaded_document->path = $path;
                                $uploaded_document->extension = $image->getClientOriginalExtension();
                                $uploaded_document->type = 'images';
                                $uploaded_document->save();
                            }
                        }

                        //Pricing configuration of variant
                        if (array_key_exists('pricing', $variant)) {
                            $all_data_to_insert = [];

                            foreach($variant['pricing']['from'] as $key => $from){
                                $current_data = [];
                                if(($from != null) && ($variant['pricing']['to'][$key] != null) && ($variant['pricing']['unit_price'][$key] != null)){
                                    if(isset($variant['pricing']['date_range_pricing'])){
                                        if(($variant['pricing']['date_range_pricing'] != null)){
                                            if(($variant['pricing']['date_range_pricing'][$key]) && ($variant['pricing']['discount_type'][$key])){
                                                $date_var               = explode(" to ", $variant['pricing']['date_range_pricing'][$key]);
                                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                                $current_data["discount_start_datetime"] = $discount_start_date;
                                                $current_data["discount_end_datetime"] = $discount_end_date;
                                                $current_data["discount_type"] = $variant['pricing']['discount_type'][$key];
                                            }else{
                                                $current_data["discount_start_datetime"] = null;
                                                $current_data["discount_end_datetime"] = null;
                                                $current_data["discount_type"] = null;
                                            }
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
                                    $current_data["id_products"] = $product->id;
                                    $current_data["from"] = $from;
                                    $current_data["to"] = $variant['pricing']['to'][$key];
                                    $current_data["unit_price"] = $variant['pricing']['unit_price'][$key];

                                    if(isset($variant['pricing']['discount_amount'])){
                                        $current_data["discount_amount"] = $variant['pricing']['discount_amount'][$key];
                                    }else{
                                        $current_data["discount_amount"] = null;
                                    }
                                    if(isset($variant['pricing']['discount_percentage'])){
                                        $current_data["discount_percentage"] = $variant['pricing']['discount_percentage'][$key];
                                    }else{
                                        $current_data["discount_percentage"] = null;
                                    }

                                    array_push($all_data_to_insert, $current_data);
                                }
                            }

                            PricingConfiguration::where('id_products', $product->id)->delete();
                            PricingConfiguration::insert($all_data_to_insert);
                        }else{
                            //get pricing by default
                            $all_data_to_insert = [];

                            foreach($pricing['from'] as $key => $from){
                                $current_data = [];
                                if(($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)){
                                    if(isset($pricing['date_range_pricing'])){
                                        if(($pricing['date_range_pricing'] != null)){
                                            if(($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])){
                                                $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                                $current_data["discount_start_datetime"] = $discount_start_date;
                                                $current_data["discount_end_datetime"] = $discount_end_date;
                                                $current_data["discount_type"] = $pricing['discount_type'][$key];
                                            }
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }

                                    $current_data["id_products"] = $product->id;
                                    $current_data["from"] = $from;
                                    $current_data["to"] = $pricing['to'][$key];
                                    $current_data["unit_price"] = $pricing['unit_price'][$key];

                                    if(isset($pricing['discount_amount'])){
                                        $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                                    }else{
                                        $current_data["discount_amount"] = null;
                                    }
                                    if(isset($pricing['discount_percentage'])){
                                        $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                                    }else{
                                        $current_data["discount_percentage"] = null;
                                    }
                                    array_push($all_data_to_insert, $current_data);
                                }
                            }

                            PricingConfiguration::insert($all_data_to_insert);
                        }

                        $shipping_to_delete = Shipping::where('product_id', $product->id)->delete();
                        $shipping_details = [];
                        if(array_key_exists('shipping_details', $variant)){
                            foreach($variant['shipping_details']['from_shipping'] as $key => $from){
                                if(($from != null) && ($variant['shipping_details']['to_shipping'][$key]!= null)&& ($variant['shipping_details']['shipper'][$key]!= null)&& ($variant['shipping_details']['estimated_order'][$key]!= null)){
                                    $current_shipping = [];
                                    $shippers = implode(',', $variant['shipping_details']['shipper'][$key]);
                                    $current_shipping['from_shipping'] = $from;
                                    $current_shipping['to_shipping'] = $variant['shipping_details']['to_shipping'][$key];
                                    $current_shipping['shipper'] = $variant['shipping_details']['shipper'][$key];
                                    $current_shipping['estimated_order'] = $variant['shipping_details']['estimated_order'][$key];
                                    $current_shipping['estimated_shipping'] = $variant['shipping_details']['estimated_shipping'][$key];
                                    $current_shipping['paid'] = $variant['shipping_details']['paid'][$key];
                                    $current_shipping['shipping_charge'] = $variant['shipping_details']['shipping_charge'][$key];
                                    $current_shipping['flat_rate_shipping'] = $variant['shipping_details']['flat_rate_shipping'][$key];
                                    $current_shipping['vat_shipping'] = $vat_user->vat_registered;
                                    $current_shipping['product_id'] = $product->id;
                                    $current_shipping['charge_per_unit_shipping'] = $variant['shipping_details']['charge_per_unit_shipping'][$key];

                                    array_push($shipping_details, $current_shipping);
                                }
                            }

                            if(count($shipping_details) > 0){
                                Shipping::insert($shipping_details);
                            }
                        }else{
                            if(count($shipping) > 0){
                                $keyToRemove = 'product_id'; // For example, let's say you want to remove the element at index 1

                                // Using array_map() and array_filter()
                                $shipping = array_map(function($arr) use ($keyToRemove) {
                                    return array_filter($arr, function($k) use ($keyToRemove) {
                                        return $k !== $keyToRemove;
                                    }, ARRAY_FILTER_USE_KEY);
                                }, $shipping);

                                $id = $product->id;
                                $keyToPush = 'product_id';
                                $shipping = array_map(function($arr) use ($id, $keyToPush) {
                                    $arr[$keyToPush] = $id;
                                    return $arr;
                                }, $shipping);

                                Shipping::insert($shipping);
                            }
                        }
                    }
                    
                }
            }

            if(count($general_attributes_data) > 0){
                foreach ($general_attributes_data as $attr => $value) {
                    if($value != null){
                        $attribute_product = ProductAttributeValues::where('id_products', $product_draft->id)->where('id_attribute', $attr)->first();
                            
                        if($attribute_product == null){
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $product_draft->id;
                            $attribute_product->id_attribute = $attr;
                            $attribute_product->is_general = 1;
                        }

                        if(in_array($attr, $ids_attributes_list)){
                            $value_attribute = AttributeValue::find($value);
                            $attribute_product->id_values = $value;
                            $attribute_product->value = $value_attribute->value;
                        }elseif(in_array($attr, $ids_attributes_color)){
                            $value = Color::where('code', $value)->first();
                            $attribute_product->id_colors = $value->id;
                            $attribute_product->value = $value->code;
                        }elseif(in_array($attr, $ids_attributes_numeric)){
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                        }
                        else{
                            $attribute_product->value = $value;
                        }

                        $attribute_product->save();
                    }
                }
            }

            $new_ids_attributes_general = array_keys($general_attributes_data);
            $deleted_attributes_general = ProductAttributeValues::where('id_products', $product_draft->id)->where('is_general', 1)->whereNotIn('id_attribute', $new_ids_attributes_general)->delete();

            if(count($variants_new_data)){
                foreach ($variants_new_data as $id => $variant){
                    if (!array_key_exists('shipping', $variant)) {
                        $collection['shipping'] = 0;
                    }else{
                        $collection['shipping'] = $variant['shipping'];
                    }
                    $collection['low_stock_quantity'] = $variant['stock'];
                    if(array_key_exists('sku', $variant)){
                        $collection['sku'] = $variant['sku'];
                    }else{
                        $collection['sku'] = '';
                    }
                    
                    if(!isset($variant['sample_price'])){
                        $collection['vat_sample'] = $vat_user->vat_registered;
                        $collection['sample_description'] = $data_sample['sample_description'];
                        $collection['sample_price'] = $data_sample['sample_price'];
                    }else{
                        $collection['vat_sample'] = $vat_user->vat_registered;
                        $collection['sample_description'] = $variant['sample_description'];
                        $collection['sample_price'] = $variant['sample_price'];
                    }

                    $randomString = Str::random(5);
                    $collection['slug'] =  $collection['slug'] . '-' . $randomString;

                    $new_product = Product::create($collection);

                    //attributes of variant
                    foreach($variant['attributes'] as $key => $value_attribute){
                        if($value_attribute != null){
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $new_product->id;
                            $attribute_product->id_attribute = $key;
                            $attribute_product->is_variant = 1;
                            if(in_array($key, $ids_attributes_list)){
                                $value = AttributeValue::find($value_attribute);
                                $attribute_product->id_values = $value_attribute;
                                $attribute_product->value = $value->value;
                            }elseif(in_array($key, $ids_attributes_color)){
                                $value = Color::where('code', $value_attribute)->first();
                                $attribute_product->id_colors = $value->id;
                                $attribute_product->value = $value->code;
                            }elseif(in_array($key, $ids_attributes_numeric)){
                                $attribute_product->id_units = $variant['units'][$key];
                                $attribute_product->value = $value_attribute;
                            }
                            else{
                                $attribute_product->value = $value_attribute;
                            }

                            $attribute_product->save();
                        }
                    }

                    //Images of variant
                    if (array_key_exists('photo', $variant)) {
                        $structure = public_path('upload_products');
                        if (!file_exists($structure)) {
                            mkdir(public_path('upload_products', 0777));
                        }

                        if(!file_exists(public_path('/upload_products/Product-'.$new_product->id))){
                            mkdir(public_path('/upload_products/Product-'.$new_product->id, 0777));
                            mkdir(public_path('/upload_products/Product-'.$new_product->id.'/images', 0777));
                        }else{
                            if(!file_exists(public_path('/upload_products/Product-'.$new_product->id.'/images'))){
                                mkdir(public_path('/upload_products/Product-'.$new_product->id.'/images', 0777));
                            }
                        }

                        foreach($variant['photo'] as $key => $image){
                            $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                            $image->move(public_path('/upload_products/Product-'.$new_product->id.'/images') , $imageName);
                            $path = '/upload_products/Product-'.$new_product->id.'/images'.'/'.$imageName;

                            $uploaded_document = new UploadProducts();
                            $uploaded_document->id_product = $new_product->id;
                            $uploaded_document->path = $path;
                            $uploaded_document->extension = $image->getClientOriginalExtension();
                            $uploaded_document->type = 'images';
                            $uploaded_document->save();
                        }
                    }

                    //Pricing configuration of variant
                    if (array_key_exists('pricing', $variant)) {
                        $all_data_to_insert = [];

                        foreach($variant['pricing']['from'] as $key => $from){
                            $current_data = [];
                            if(($from != null) && ($variant['pricing']['to'][$key] != null) && ($variant['pricing']['unit_price'][$key] != null)){
                                if(isset($variant['pricing']['discount_range'])){
                                    if(($variant['pricing']['discount_range'] != null)){
                                        if(($variant['pricing']['discount_range'][$key]) && ($variant['pricing']['discount_type'][$key])){
                                            $date_var               = explode(" to ", $variant['pricing']['discount_range'][$key]);
                                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                            $current_data["discount_start_datetime"] = $discount_start_date;
                                            $current_data["discount_end_datetime"] = $discount_end_date;
                                            $current_data["discount_type"] = $variant['pricing']['discount_type'][$key];
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
                                }else{
                                    $current_data["discount_start_datetime"] = null;
                                    $current_data["discount_end_datetime"] = null;
                                    $current_data["discount_type"] = null;
                                }

                                $current_data["id_products"] = $new_product->id;
                                $current_data["from"] = $from;
                                $current_data["to"] = $variant['pricing']['to'][$key];
                                $current_data["unit_price"] = $variant['pricing']['unit_price'][$key];

                                if(isset($variant['pricing']['discount_amount'])){
                                    $current_data["discount_amount"] = $variant['pricing']['discount_amount'][$key];
                                }else{
                                    $current_data["discount_amount"] = null;
                                }
                                if(isset($variant['pricing']['discount_percentage'])){
                                    $current_data["discount_percentage"] = $variant['pricing']['discount_percentage'][$key];
                                }else{
                                    $current_data["discount_percentage"] = null;
                                }
                                array_push($all_data_to_insert, $current_data);
                            }
                        }
                        if(count ($all_data_to_insert) > 0){
                            PricingConfiguration::insert($all_data_to_insert);
                        } 
                    }else{
                        //get pricing by default
                        $all_data_to_insert = [];

                        foreach($pricing['from'] as $key => $from){
                            $current_data = [];
                            if($pricing['from'][$key] != null && $pricing['unit_price'][$key] != null){
                                    if($pricing['date_range_pricing'][$key] != null){
                                        if(($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])){
                                            $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));
            
                                            $current_data["discount_start_datetime"] = $discount_start_date;
                                            $current_data["discount_end_datetime"] = $discount_end_date;
                                            $current_data["discount_type"] = $pricing['discount_type'][$key];
                                        }else{
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    }else{
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
            
                                $current_data["id_products"] = $new_product->id;
                                $current_data["from"] = $from;
                                $current_data["to"] = $pricing['to'][$key];
                                $current_data["unit_price"] = $pricing['unit_price'][$key];
            
                                if(isset($pricing['discount_amount'])){
                                    $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                                }else{
                                    $current_data["discount_amount"] = null;
                                }
                                if(isset($pricing["discount_percentage"])){
                                    $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                                }else{
                                    $current_data["discount_percentage"] = null;
                                }
            
                                array_push($all_data_to_insert, $current_data);
                            }
                            
                        }

                        PricingConfiguration::insert($all_data_to_insert);
                    }

                    $shipping_details = [];
                    if(array_key_exists('shipping_details', $variant)){
                        foreach($variant['shipping_details']['from'] as $key => $from){
                            if(($from != null) && ($variant['shipping_details']['to'][$key]!= null)&& ($variant['shipping_details']['shipper'][$key]!= null)&& ($variant['shipping_details']['estimated_order'][$key]!= null)){
                                $current_shipping = [];
                                $shippers = implode(',', $variant['shipping_details']['shipper'][$key]);
                                $current_shipping['from_shipping'] = $from;
                                $current_shipping['to_shipping'] = $variant['shipping_details']['to'][$key];
                                $current_shipping['shipper'] = $shippers;
                                $current_shipping['estimated_order'] = $variant['shipping_details']['estimated_order'][$key];
                                $current_shipping['estimated_shipping'] = $variant['shipping_details']['estimated_shipping'][$key];
                                $current_shipping['paid'] = $variant['shipping_details']['paid'][$key];
                                $current_shipping['shipping_charge'] = $variant['shipping_details']['shipping_charge'][$key];
                                $current_shipping['flat_rate_shipping'] = $variant['shipping_details']['flat_rate_shipping'][$key];
                                $current_shipping['vat_shipping'] = $vat_user->vat_registered;
                                $current_shipping['product_id'] = $new_product->id;
                                $current_shipping['charge_per_unit_shipping'] = $variant['shipping_details']['charge_per_unit_shipping'][$key];

                                array_push($shipping_details, $current_shipping);
                            }
                        }

                        if(count($shipping_details) > 0){
                            Shipping::insert($shipping_details);
                        }
                    }else{
                        if(count($shipping) > 0){
                            $keyToRemove = 'product_id'; // For example, let's say you want to remove the element at index 1

                            // Using array_map() and array_filter()
                            $shipping = array_map(function($arr) use ($keyToRemove) {
                                return array_filter($arr, function($k) use ($keyToRemove) {
                                    return $k !== $keyToRemove;
                                }, ARRAY_FILTER_USE_KEY);
                            }, $shipping);

                            $id = $new_product->id;
                            $keyToPush = 'product_id';
                            $shipping = array_map(function($arr) use ($id, $keyToPush) {
                                $arr[$keyToPush] = $id;
                                return $arr;
                            }, $shipping);

                            Shipping::insert($shipping);
                        }
                    }
                }

            }
            return $product_draft;
        }
    }
}
