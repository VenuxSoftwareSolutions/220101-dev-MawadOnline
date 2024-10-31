<?php

namespace App\Jobs;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\BusinessInformation;
use App\Models\Category;
use App\Models\Color;
use App\Models\Country;
use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Models\ProductAttributeValues;
use App\Models\Shipping;
use App\Models\StockSummary;
use App\Models\User;
use App\Services\ProductFileService;
use App\Services\ProductService;
use Carbon\Carbon;
use DateTime;
use DB;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Request;
use Str;

class ProcessProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productGroup;
    protected $userId;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productGroup,$userId)
    {
        $this->productGroup = $productGroup;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parentProduct = $this->mapToDatabaseAttributes($this->productGroup['parent']);

        $parentProduct['is_parent'] = 1;
        $product =  $this->store($parentProduct,null,$this->userId);

        if($product)
        {
            $productFileUpload = $this->uploadProductFiles($product->id,$this->productGroup['parent']);
        }
        if ($product->is_parent == 1) {
            $product->categories()->attach($product->category_id);
        }

        if(isset($parentProduct['inventory_stock']))
        {
            $stock = new StockSummary();
            $stock->variant_id = $product->id;
            $stock->warehouse_id = $parentProduct['inventory_stock']['warehouse'];
            $stock->seller_id = $product->user_id;
            $stock->current_total_quantity = $parentProduct['inventory_stock']['quantity'];
            $stock->save();
        }
        

        // if($product){
        //     $productFileUpload = $this->uploadProductFiles($product->id,$this->productGroup['parent']);
        //     dd($productFileUpload);
        // }
        
        if(count($this->productGroup['children']) > 0) {
            foreach($this->productGroup['children'] as $child){
                $childProduct = $this->mapToDatabaseAttributes($child);


                $childProduct['is_parent'] = 0;

                $variant =  $this->store($childProduct,$product->id,$this->userId);

                if($variant)
                {
                    $productFileUpload = $this->uploadProductFiles($product->id,$this->productGroup['children']);
                }
                $variant->categories()->attach($product->category_id);

            }
        }

    }


    public function uploadProductFiles($productId,$productData)
    {

        $productFileService = new ProductFileService();

        $productFileService->uploadProductFiles($productId, $productData);

        if($productFileService){
            return true;
        }else{
            false;
        }

    }

    function getValuesBetweenRefundableAndVideoProvider(array $productData) {
        // Initialize the result array
        $result = [];
        
        // Set flag to determine when to start and stop collecting data
        $startCollecting = false;
        
        // Loop through the array
        foreach ($productData as $header => $value) {
            // Start collecting after "Refundable *"
            if ($header === "Refundable *") {
                $startCollecting = true;
                continue; // Skip "Refundable *"
            }
    
            // Stop collecting after reaching "Video Provider"
            if ($header === "Video Provider") {
                break;
            }
    
            // Collect headers and values if flag is set
            if ($startCollecting) {
                $result[$header] = $value;
            }
        }
        
        return $result;
    }
    


    public function store(array $data,$parentProductID = null,$userId)
    {
        $collection = collect($data);

        $vat_user = BusinessInformation::where('user_id', $userId)->first();
        $user_id = $userId;
        $approved = 1;

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

        if (isset($collection['refundable'])) {
            $collection['refundable'] = 1;
        } else {
            $collection['refundable'] = 0;
        }


      
        if ($collection['parent_id'] != null) {
            $collection['category_id'] = $collection['parent_id'];
        }

       
        unset($collection['parent_id']);



        if ($collection['create_stock'] == 1) {
            $collection['stock_after_create'] = 1;
        } else {
            $collection['stock_after_create'] = 0;
        }

        unset($collection['create_stock']);

        if (isset($collection['activate_third_party'])) {
            $collection['activate_third_party'] = 1;
        }

        if (isset($collection['country_code'])) {
            
            $country = Country::find($collection['country_code']);
            $collection['country_code'] = strtolower($country->code);
        }else{
            $collection['country_code'] = '';
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

        if (isset($collection['stock_visibility_state'])) {
            $collection['stock_visibility_state'] = "quantity";
        } else {
            $collection['stock_visibility_state'] = "hide";
        }

        //$published = 1;
        $is_draft = 0;

        if (isset($collection['button'])) {
            if ($collection['button'] == 'draft') {
                $is_draft = 1;
                //$published = 0;
            }
            unset($collection['button']);
        }

        if (isset($collection['submit_button'])) {
            if ($collection['submit_button'] == 'draft') {
                $is_draft = 1;
                //$published = 0;
            }
            unset($collection['submit_button']);
        }

        $pricing = [];
        if ((isset($collection['from'])) && (isset($collection['to'])) && (isset($collection['unit_price']))) {
            $pricing = [
                "from" => $collection['from'],
                "to" => $collection['to'],
                "unit_price" => $collection['unit_price'],
            ];

            if (isset($collection['discount_type'])) {
                $pricing["discount_type"] = $collection['discount_type'];
            }

            if (isset($collection['date_range_pricing'])) {
                $pricing["date_range_pricing"] = $collection['date_range_pricing'];
            }

            if (isset($collection['discount_amount'])) {
                $pricing["discount_amount"] = $collection['discount_amount'];
            }

            if (isset($collection['discount_percentage'])) {
                $pricing["discount_percentage"] = $collection['discount_percentage'];
            }

            unset($collection['from']);
            unset($collection['to']);
            unset($collection['unit_price']);
            unset($collection['discount_amount']);
            unset($collection['discount_type']);
            unset($collection['discount_percentage']);
        }

        $shipping = [];
        if ((isset($collection['from_shipping'])) && (isset($collection['to_shipping'])) && (isset($collection['shipper'])) && (isset($collection['estimated_order']))) {
            foreach ($collection['from_shipping'] as $key => $from_shipping) {

                if ((array_key_exists($key, $collection['from_shipping'])) && (array_key_exists($key, $collection['to_shipping'])) && (array_key_exists($key, $collection['shipper'])) && (array_key_exists($key, $collection['estimated_order']))) {
                    if (($from_shipping != null) && ($collection['to_shipping'][$key] != null) && ($collection['shipper'][$key] != null) && ($collection['estimated_order'][$key] != null)) {
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
            }
        }

        $shipping_sample_parent = [];
        if (isset($collection['shipper_sample'])) {
            $shipping_sample_parent['shipper_sample'] = $collection['shipper_sample'];
            $collection['shipper_sample'] = implode(',', $collection['shipper_sample']);
        } else {
            $shipping_sample_parent['shipper_sample'] = NULL;
        }

        if (isset($collection['estimated_sample'])) {
            $shipping_sample_parent['estimated_sample'] = $collection['estimated_sample'];
        } else {
            $shipping_sample_parent['estimated_sample'] = NULL;
        }

        if (isset($collection['estimated_shipping_sample'])) {
            $shipping_sample_parent['estimated_shipping_sample'] = $collection['estimated_shipping_sample'];
        } else {
            $shipping_sample_parent['estimated_shipping_sample'] = NULL;
        }

        if (isset($collection['paid_sample'])) {
            $shipping_sample_parent['paid_sample'] = $collection['paid_sample'];
        } else {
            $shipping_sample_parent['paid_sample'] = NULL;
        }

        if (isset($collection['shipping_amount'])) {
            $shipping_sample_parent['shipping_amount'] = $collection['shipping_amount'];
        } else {
            $shipping_sample_parent['shipping_amount'] = NULL;
        }

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
                if (!array_key_exists($ids[2], $variants_data)) {
                    $variants_data[$ids[2]] = [];
                }
                if (!array_key_exists('attributes', $variants_data[$ids[2]])) {
                    $variants_data[$ids[2]]['attributes'][$ids[1]] = $value;
                } else {
                    if (!array_key_exists($ids[1], $variants_data[$ids[2]]['attributes'])) {
                        $variants_data[$ids[2]]['attributes'][$ids[1]] = $value;
                    }
                }

                $key_pricing = 'variant-pricing-' . $ids[2];
                if (!isset($data[$key_pricing])) {
                    if (!array_key_exists($ids[2], $variants_data)) {
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['pricing'] = $data['variant_pricing-from' . $ids[2]];
                }

                $key_shipping = 'variant_shipping-' . $ids[2];
                if (isset($data[$key_shipping])) {
                    if (!array_key_exists($ids[2], $variants_data)) {
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['shipping_details'] = $data['variant_shipping-' . $ids[2]];
                }

                $key_sample_available = 'variant-sample-available' . $ids[2];
                if (isset($data[$key_sample_available])) {
                    if (!array_key_exists($ids[2], $variants_data)) {
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['sample_available'] = 1;
                } else {
                    if (!array_key_exists($ids[2], $variants_data)) {
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['sample_available'] = 0;
                }
            }

            if (strpos($key, 'sku') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['sku'] = $value;
            }


            if (strpos($key, 'stock-warning-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[2], $variants_data)) {
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['stock'] = $value;
            }

            if (strpos($key, 'variant-published-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[2], $variants_data)) {
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['published'] = $value;
            }

            if (strpos($key, 'variant-shipping-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[2], $variants_data)) {
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['shipping'] = 1;
            }

            if (strpos($key, 'photos_variant') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['photo'] = $value;
            }

            if (strpos($key, 'attributes_units') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[2], $variants_data)) {
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['units'][$ids[1]] = $value;
            }

            if (strpos($key, 'attribute_generale-') === 0) {
                $ids = explode('-', $key);
                $general_attributes_data[$ids[1]] = $value;
            }

            if (strpos($key, 'unit_attribute_generale-') === 0) {
                $ids = explode('-', $key);
                $unit_general_attributes_data[$ids[1]] = $value;
            }

            if (strpos($key, 'vat_sample-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['vat_sample'] = $value;
            }

            if (strpos($key, 'sample_description-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                if ($value != null) {
                    $variants_data[$ids[1]]['sample_description'] = $value;
                }
            }

            if (strpos($key, 'sample_price-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }
                if ($value != null) {
                    $variants_data[$ids[1]]['sample_price'] = $value;
                }
            }

            if (strpos($key, 'estimated_sample-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_sample'] = $value;
            }

            if (strpos($key, 'estimated_shipping_sample-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_shipping_sample'] = $value;
            }

            if (strpos($key, 'shipping_amount-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['shipping_amount'] = $value;
            }

            if (strpos($key, 'variant_shipper_sample-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['variant_shipper_sample'] = $value;
            }

            if (strpos($key, 'paid_sample-') === 0) {
                $ids = explode('-', $key);
                if (!array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['paid_sample'] = $value;
            }
        }

        //dd($variants_data);

        if (isset($collection['product_sk'])) {
            $collection['sku'] = $collection['product_sk'];
            unset($collection['product_sk']);
        } else {
            $collection['sku'] = $collection['name'];
        }
        $collection['low_stock_quantity'] = $collection['quantite_stock_warning'];

        unset($collection['product_sk']);
        unset($collection['quantite_stock_warning']);

        if ($collection['published_modal'] == 1) {
            $collection['published'] = 1;
        } else {
            $collection['published'] = 0;
        }

        unset($collection['published_modal']);

        $data = $collection->merge(compact(
            'user_id',
            'shipping_cost',
            'slug',
            'colors',
            'is_draft',
            'vat'
        ))->toArray();


        $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();
        $ids_attributes_list = Attribute::where('type_value', 'list')->pluck('id')->toArray();
        $ids_attributes_numeric = Attribute::where('type_value', 'numeric')->pluck('id')->toArray();

        $prefixToRemove = 'attribute_generale';
        $prefixToRemoveUnit = 'unit_attribute_generale';
        $prefixToRemoveAttr = 'attributes-';
        $prefixToRemoveStock = 'stock-warning-';
        $prefixToRemoveAttrUnit = 'attributes_units';

        foreach ($data as $key => $value) {
            if (strpos($key, $prefixToRemove) === 0) {
                unset($data[$key]);
            }
            if (strpos($key, $prefixToRemoveUnit) === 0) {
                unset($data[$key]);
            }
            if (strpos($key, $prefixToRemoveAttr) === 0) {
                unset($data[$key]);
            }
            if (strpos($key, $prefixToRemoveStock) === 0) {
                unset($data[$key]);
            }
            if (strpos($key, $prefixToRemoveAttrUnit) === 0) {
                unset($data[$key]);
            }
        }




        if (!isset($data['activate_attributes'])) {

            if($parentProductID !=null)
            {
                $data['parent_id'] = $parentProductID;
            }

            $product = Product::create($data);

            $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();
            if (count($pricing) > 0) {
                $all_data_to_insert = [];

                foreach ($pricing['from'] as $key => $from) {
                    $current_data = [];
                    try {
                        if (($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)) {
                            if ($pricing['date_range_pricing'][$key] != null) {
                                if (($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])) {
                                    $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));
                                    $start_to_parse = explode(" ", $date_var[0]);
                                    $end_to_parse = explode(" ", $date_var[1]);

                                    $explod_start_to_parse = explode("-", $start_to_parse[0]);
                                    $explod_end_to_parse = explode("-", $end_to_parse[0]);

                                    $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                    $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                    if (($check_start == true) && ($check_end == true)) {
                                        $current_data["discount_start_datetime"] = $discount_start_date;
                                        $current_data["discount_end_datetime"] = $discount_end_date;
                                        $current_data["discount_type"] = $pricing['discount_type'][$key];
                                    } else {
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
                                } else {
                                    $current_data["discount_start_datetime"] = null;
                                    $current_data["discount_end_datetime"] = null;
                                    $current_data["discount_type"] = null;
                                }
                            } else {
                                $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                            }

                            $current_data["id_products"] = $product->id;
                            $current_data["from"] = $from;
                            $current_data["to"] = $pricing['to'][$key];
                            $current_data["unit_price"] = $pricing['unit_price'][$key];

                            if (isset($pricing['discount_amount'])) {
                                $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                            } else {
                                $current_data["discount_amount"] = null;
                            }
                            if (isset($current_data["discount_percentage"])) {
                                $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                            } else {
                                $current_data["discount_percentage"] = null;
                            }

                            array_push($all_data_to_insert, $current_data);
                        }
                    } catch (Exception $e) {
                        // Handle the parsing error
                        dump('Error: ' . $e->getMessage());
                    }
                }

                if (count($all_data_to_insert) > 0) {
                    PricingConfiguration::insert($all_data_to_insert);
                }
            }


            if (count($general_attributes_data) > 0) {
                foreach ($general_attributes_data as $attr => $value) {
                    if ($value != null) {
                        if (in_array($attr, $ids_attributes_list)) {
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $product->id;
                            $attribute_product->id_attribute = $attr;
                            $attribute_product->is_general = 1;
                            $value_attribute = AttributeValue::find($value);
                            $attribute_product->id_values = $value;
                            $attribute_product->value = $value_attribute->value;
                            $attribute_product->save();
                        } elseif (in_array($attr, $ids_attributes_color)) {
                            if (count($value) > 0) {
                                foreach ($value as $value_color) {
                                    $attribute_product = new ProductAttributeValues();
                                    $attribute_product->id_products = $product->id;
                                    $attribute_product->id_attribute = $attr;
                                    $attribute_product->is_general = 1;
                                    $color = Color::where('id', $value_color)->first();
                                    $attribute_product->id_colors = $color->id;
                                    $attribute_product->value = $color->code;
                                    $attribute_product->save();
                                }
                            }
                        } elseif (in_array($attr, $ids_attributes_numeric)) {
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $product->id;
                            $attribute_product->id_attribute = $attr;
                            $attribute_product->is_general = 1;
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                            $attribute_product->save();
                        } else {
                            $attribute_product = new ProductAttributeValues();
                            $attribute_product->id_products = $product->id;
                            $attribute_product->id_attribute = $attr;
                            $attribute_product->is_general = 1;
                            $attribute_product->value = $value;
                            $attribute_product->save();
                        }
                    }
                }
            }

            if (count($shipping) > 0) {
                $id = $product->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function ($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;
                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            return $product;
        } else {

            // //Create Parent Product
            $data['is_parent'] = 1;
            $data['sku'] = $data['name'];
            $product_parent = Product::create($data);
            $all_data_to_insert_parent = [];

            foreach ($pricing['from'] as $key => $from) {
                $current_data = [];
                if ($pricing['from'][$key] != null && $pricing['unit_price'][$key] != null) {
                    if ($pricing['date_range_pricing'][$key] != null) {
                        if (($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])) {
                            $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                            $start_to_parse = explode(" ", $date_var[0]);
                            $end_to_parse = explode(" ", $date_var[1]);

                            $explod_start_to_parse = explode("-", $start_to_parse[0]);
                            $explod_end_to_parse = explode("-", $end_to_parse[0]);

                            $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                            $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                            if (($check_start == true) && ($check_end == true)) {
                                $current_data["discount_start_datetime"] = $discount_start_date;
                                $current_data["discount_end_datetime"] = $discount_end_date;
                                $current_data["discount_type"] = $pricing['discount_type'][$key];
                            } else {
                                $current_data["discount_start_datetime"] = null;
                                $current_data["discount_end_datetime"] = null;
                                $current_data["discount_type"] = null;
                            }
                        } else {
                            $current_data["discount_start_datetime"] = null;
                            $current_data["discount_end_datetime"] = null;
                            $current_data["discount_type"] = null;
                        }
                    } else {
                        $current_data["discount_start_datetime"] = null;
                        $current_data["discount_end_datetime"] = null;
                        $current_data["discount_type"] = null;
                    }

                    $current_data["id_products"] = $product_parent->id;
                    $current_data["from"] = $from;
                    $current_data["to"] = $pricing['to'][$key];
                    $current_data["unit_price"] = $pricing['unit_price'][$key];

                    if (isset($pricing['discount_amount'])) {
                        $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                    } else {
                        $current_data["discount_amount"] = null;
                    }
                    if (isset($current_data["discount_percentage"])) {
                        $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                    } else {
                        $current_data["discount_percentage"] = null;
                    }

                    array_push($all_data_to_insert_parent, $current_data);
                }
            }



            if (count($all_data_to_insert_parent) > 0) {
                PricingConfiguration::insert($all_data_to_insert_parent);
            }

            if (count($shipping) > 0) {
                $id = $product_parent->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function ($arr) use ($id, $keyToPush) {
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

            if (count($variants_data) > 0) {
                foreach ($variants_data as $id => $variant) {

                    if (!array_key_exists('shipping', $variant)) {
                        $data['shipping'] = 0;
                    } else {
                        $data['shipping'] = $variant['shipping'];
                    }
                    $data['low_stock_quantity'] = $variant['stock'];
                    if (!array_key_exists('sample_price', $variant)) {
                        $data['vat_sample'] = $vat;
                        $data['sample_description'] = $data_sample['sample_description'];
                        $data['sample_price'] = $data_sample['sample_price'];
                    } else {
                        $data['vat_sample'] = $vat;
                        $data['sample_description'] = $variant['sample_description'];
                        $data['sample_price'] = $variant['sample_price'];
                    }

                    if (isset($variant['variant_shipper_sample'])) {
                        $data['shipper_sample'] = implode(",", $variant['variant_shipper_sample']);
                    } else {
                        $data['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                    }

                    if (isset($variant['estimated_sample'])) {
                        $data['estimated_sample'] = $variant['estimated_sample'];
                    } else {
                        $data['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                    }

                    if (isset($variant['estimated_shipping_sample'])) {
                        $data['estimated_shipping_sample'] = $variant['estimated_shipping_sample'];
                    } else {
                        $data['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                    }

                    if (isset($variant['paid_sample'])) {
                        $data['paid_sample'] = $variant['paid_sample'];
                    } else {
                        $data['paid_sample'] = $shipping_sample_parent['paid_sample'];
                    }

                    if (isset($variant['shipping_amount'])) {
                        $data['shipping_amount'] = $variant['shipping_amount'];
                    } else {
                        $data['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                    }

                    if (isset($variant['sample_available'])) {
                        $data['sample_available'] = $variant['sample_available'];
                    } else {
                        $data['sample_available'] = 0;
                    }

                    if (isset($data['shipper_sample'])) {
                        if (is_array($data['shipper_sample'])) {
                            $data['shipper_sample'] = implode(',', $data['shipper_sample']);
                        } else {
                            $data['shipper_sample'] = $data['shipper_sample'];
                        }
                    }


                    $data['sku'] =  $variant['sku'];
                    $randomString = Str::random(5);
                    $data['slug'] =  $data['slug'] . '-' . $randomString;


                    $product = Product::create($data);

                    //attributes of variant
                    //$sku = "";
                    foreach ($variant['attributes'] as $key => $value_attribute) {
                        if ($value_attribute != null) {
                            if (in_array($key, $ids_attributes_list)) {
                                $attribute_product = new ProductAttributeValues();
                                $attribute_product->id_products = $product->id;
                                $attribute_product->id_attribute = $key;
                                $attribute_product->is_variant = 1;
                                $value = AttributeValue::find($value_attribute);
                                $attribute_product->id_values = $value_attribute;
                                $attribute_product->value = $value->value;
                                $attribute_product->save();
                            } elseif (in_array($key, $ids_attributes_color)) {
                                if (count($value_attribute) > 0) {
                                    foreach ($value_attribute as $value_color) {
                                        $attribute_product = new ProductAttributeValues();
                                        $attribute_product->id_products = $product->id;
                                        $attribute_product->id_attribute = $key;
                                        $attribute_product->is_variant = 1;
                                        $value = Color::where('code', $value_color)->first();
                                        $attribute_product->id_colors = $value->id;
                                        $attribute_product->value = $value->code;
                                        $attribute_product->save();
                                    }
                                }
                            } elseif (in_array($key, $ids_attributes_numeric)) {
                                $attribute_product = new ProductAttributeValues();
                                $attribute_product->id_products = $product->id;
                                $attribute_product->id_attribute = $key;
                                $attribute_product->is_variant = 1;
                                $attribute_product->id_units = $variant['units'][$key];
                                $attribute_product->value = $value_attribute;
                                $attribute_product->save();
                            } else {
                                $attribute_product = new ProductAttributeValues();
                                $attribute_product->id_products = $product->id;
                                $attribute_product->id_attribute = $key;
                                $attribute_product->is_variant = 1;
                                $attribute_product->value = $value_attribute;
                                $attribute_product->save();
                            }

                            $attribute_product->save();
                        }
                    }

                    // $product->sku = $product_parent->name . $sku;
                    // $product->save();

                    //Images of variant
                    if (array_key_exists('photo', $variant)) {
                        if (count($variant['photo']) > 0) {
                            $structure = public_path('upload_products');
                            if (!file_exists($structure)) {
                                mkdir(public_path('upload_products', 0777));
                            }

                            if (!file_exists(public_path('/upload_products/Product-' . $product->id))) {
                                mkdir(public_path('/upload_products/Product-' . $product->id, 0777));
                                mkdir(public_path('/upload_products/Product-' . $product->id . '/images', 0777));
                            } else {
                                if (!file_exists(public_path('/upload_products/Product-' . $product->id . '/images'))) {
                                    mkdir(public_path('/upload_products/Product-' . $product->id . '/images', 0777));
                                }
                            }

                            foreach ($variant['photo'] as $key => $image) {
                                $imageName = time() . rand(5, 15) . '.' . $image->getClientOriginalExtension();
                                $image->move(public_path('/upload_products/Product-' . $product->id . '/images'), $imageName);
                                $path = '/upload_products/Product-' . $product->id . '/images' . '/' . $imageName;

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

                        foreach ($variant['pricing']['from'] as $key => $from) {
                            $current_data = [];
                            if (($from != null) && ($variant['pricing']['to'][$key] != null) && ($variant['pricing']['unit_price'][$key] != null)) {
                                if (isset($variant['pricing']['discount_range'])) {
                                    if (($variant['pricing']['discount_range'] != null)) {
                                        if (($variant['pricing']['discount_range'][$key]) && ($variant['pricing']['discount_type'][$key])) {
                                            $date_var               = explode(" to ", $variant['pricing']['discount_range'][$key]);
                                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                            $start_to_parse = explode(" ", $date_var[0]);
                                            $end_to_parse = explode(" ", $date_var[1]);

                                            $explod_start_to_parse = explode("-", $start_to_parse[0]);
                                            $explod_end_to_parse = explode("-", $end_to_parse[0]);

                                            $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                            $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                            if (($check_start == true) && ($check_end == true)) {
                                                $current_data["discount_start_datetime"] = $discount_start_date;
                                                $current_data["discount_end_datetime"] = $discount_end_date;
                                                $current_data["discount_type"] = $variant['pricing']['discount_type'][$key];
                                            } else {
                                                $current_data["discount_start_datetime"] = null;
                                                $current_data["discount_end_datetime"] = null;
                                                $current_data["discount_type"] = null;
                                            }
                                        } else {
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    } else {
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
                                } else {
                                    $current_data["discount_start_datetime"] = null;
                                    $current_data["discount_end_datetime"] = null;
                                    $current_data["discount_type"] = null;
                                }



                                $current_data["id_products"] = $product->id;
                                $current_data["from"] = $from;
                                $current_data["to"] = $variant['pricing']['to'][$key];
                                $current_data["unit_price"] = $variant['pricing']['unit_price'][$key];

                                if (isset($variant['pricing']['discount_amount'])) {
                                    $current_data["discount_amount"] = $variant['pricing']['discount_amount'][$key];
                                } else {
                                    $current_data["discount_amount"] = null;
                                }
                                if (isset($variant['pricing']['discount_percentage'])) {
                                    $current_data["discount_percentage"] = $variant['pricing']['discount_percentage'][$key];
                                } else {
                                    $current_data["discount_percentage"] = null;
                                }


                                array_push($all_data_to_insert, $current_data);
                            }
                        }

                        if (count($all_data_to_insert) > 0) {
                            PricingConfiguration::insert($all_data_to_insert);
                        }
                    } else {
                        //get pricing by default
                        $all_data_to_insert = [];

                        foreach ($pricing['from'] as $key => $from) {
                            $current_data = [];
                            if ($pricing['from'][$key] != null && $pricing['unit_price'][$key] != null) {
                                if ($pricing['date_range_pricing'][$key] != null) {
                                    if (($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])) {
                                        $date_var               = explode(" to ", $pricing['date_range_pricing'][$key]);
                                        $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                        $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                        $start_to_parse = explode(" ", $date_var[0]);
                                        $end_to_parse = explode(" ", $date_var[1]);

                                        $explod_start_to_parse = explode("-", $start_to_parse[0]);
                                        $explod_end_to_parse = explode("-", $end_to_parse[0]);

                                        $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                        $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                        if (($check_start == true) && ($check_end == true)) {
                                            $current_data["discount_start_datetime"] = $discount_start_date;
                                            $current_data["discount_end_datetime"] = $discount_end_date;
                                            $current_data["discount_type"] = $pricing['discount_type'][$key];
                                        } else {
                                            $current_data["discount_start_datetime"] = null;
                                            $current_data["discount_end_datetime"] = null;
                                            $current_data["discount_type"] = null;
                                        }
                                    } else {
                                        $current_data["discount_start_datetime"] = null;
                                        $current_data["discount_end_datetime"] = null;
                                        $current_data["discount_type"] = null;
                                    }
                                } else {
                                    $current_data["discount_start_datetime"] = null;
                                    $current_data["discount_end_datetime"] = null;
                                    $current_data["discount_type"] = null;
                                }

                                $current_data["id_products"] = $product->id;
                                $current_data["from"] = $from;
                                $current_data["to"] = $pricing['to'][$key];
                                $current_data["unit_price"] = $pricing['unit_price'][$key];

                                if (isset($pricing['discount_amount'])) {
                                    $current_data["discount_amount"] = $pricing['discount_amount'][$key];
                                } else {
                                    $current_data["discount_amount"] = null;
                                }
                                if (isset($current_data["discount_percentage"])) {
                                    $current_data["discount_percentage"] = $pricing['discount_percentage'][$key];
                                } else {
                                    $current_data["discount_percentage"] = null;
                                }

                                array_push($all_data_to_insert, $current_data);
                            }
                        }

                        if (count($all_data_to_insert) > 0) {
                            PricingConfiguration::insert($all_data_to_insert);
                        }
                    }

                    //Shipping of variant
                    $shipping_details = [];
                    if (array_key_exists('shipping_details', $variant)) {
                        foreach ($variant['shipping_details']['from'] as $key => $from) {
                            if (($from != null) && ($variant['shipping_details']['to'][$key] != null) && ($variant['shipping_details']['shipper'][$key] != null) && ($variant['shipping_details']['estimated_order'][$key] != null)) {
                                $current_shipping = [];
                                if (is_array($variant['shipping_details']['shipper'][$key])) {
                                    $shippers = implode(',', $variant['shipping_details']['shipper'][$key]);
                                    $current_shipping['shipper'] = $shippers;
                                } else {
                                    $current_shipping['shipper'] = $variant['shipping_details']['shipper'][$key];
                                }
                                $current_shipping['from_shipping'] = $from;
                                $current_shipping['to_shipping'] = $variant['shipping_details']['to'][$key];
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

                        if (count($shipping_details) > 0) {
                            Shipping::insert($shipping_details);
                        }
                    } else {
                        if (count($shipping) > 0) {
                            $keyToRemove = 'product_id'; // For example, let's say you want to remove the element at index 1

                            // Using array_map() and array_filter()
                            $shipping = array_map(function ($arr) use ($keyToRemove) {
                                return array_filter($arr, function ($k) use ($keyToRemove) {
                                    return $k !== $keyToRemove;
                                }, ARRAY_FILTER_USE_KEY);
                            }, $shipping);

                            $id = $product->id;
                            $keyToPush = 'product_id';
                            $shipping = array_map(function ($arr) use ($id, $keyToPush) {
                                $arr[$keyToPush] = $id;
                                return $arr;
                            }, $shipping);

                            Shipping::insert($shipping);
                        }
                    }
                }

                if (count($general_attributes_data) > 0) {
                    foreach ($general_attributes_data as $attr => $value) {
                        if ($value != null) {
                            if (in_array($attr, $ids_attributes_list)) {
                                $attribute_product = new ProductAttributeValues();
                                $attribute_product->id_products = $product_parent->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                                $value_attribute = AttributeValue::find($value);
                                $attribute_product->id_values = $value;
                                $attribute_product->value = $value_attribute->value;
                                $attribute_product->save();
                            } elseif (in_array($attr, $ids_attributes_color)) {
                                if (count($value) > 0) {
                                    foreach ($value as $value_color) {
                                        $attribute_product = new ProductAttributeValues();
                                        $attribute_product->id_products = $product_parent->id;
                                        $attribute_product->id_attribute = $attr;
                                        $attribute_product->is_general = 1;
                                        $color = Color::where('code', $value_color)->first();
                                        $attribute_product->id_colors = $color->id;
                                        $attribute_product->value = $color->code;
                                        $attribute_product->save();
                                    }
                                }
                            } elseif (in_array($attr, $ids_attributes_numeric)) {
                                $attribute_product = new ProductAttributeValues();
                                $attribute_product->id_products = $product_parent->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                                $attribute_product->id_units = $unit_general_attributes_data[$attr];
                                $attribute_product->value = $value;
                                $attribute_product->save();
                            } else {
                                $attribute_product = new ProductAttributeValues();
                                $attribute_product->id_products = $product_parent->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                                $attribute_product->value = $value;
                                $attribute_product->save();
                            }
                        }
                    }
                }
            }
            return $product_parent;
        }
    }


    public function getAttributeCategorie($cat_id)
    {
        $current_categorie = Category::find($cat_id);
        $parents = [];

        // Get all parent categories
        if ($current_categorie->parent_id == 0) {
            array_push($parents, $current_categorie->id);
        } else {
            array_push($parents, $current_categorie->id);
            while ($current_categorie->parent_id != 0) {
                $parent = Category::where('id', $current_categorie->parent_id)->first();
                array_push($parents, $parent->id);
                $current_categorie = $parent;
            }
        }

        $attributes_ids = [];
        $attributes = [];

        // Get the attribute IDs based on the parent categories
        if (count($parents) > 0) {
            $attributes_ids = DB::table('categories_has_attributes')->whereIn('category_id', $parents)->pluck('attribute_id')->toArray();

            // Get the attributes associated with the attribute IDs
            if (count($attributes_ids) > 0) {
                $attributes = Attribute::whereIn('id', $attributes_ids)->get();
            }
        }

        // Format the response as id, name, and key attribute_generale-{id}
        $formattedAttributes = $attributes->map(function ($attribute) {
            return [
                'id' => $attribute->id,
                'name' => $attribute->getTranslation('name'),
                'key' => 'attribute_generale-' . $attribute->id,
                'type_value' => $attribute->type_value
            ];
        });

        
        // Return the formatted attributes as a JSON response
        return [
            'attributes' => $formattedAttributes
        ];
    }





    private function mapToDatabaseAttributes(array $data)
    {
        $attributes = $this->getAttributeCategorie($data['Product Type (Leaf Category) *']);

        $matchedKeyValuePairs  = $this->extractKeyValuePairsBetweenSkuAndParentSku($data);

        $productAttributes = $this->extractKeyValuePairsForAttributes($matchedKeyValuePairs, $attributes);


        $startDateRange = $this->extractPriceRange($data, 'Discount Start Date','Discount Start Date 2','Discount Start Date 3');
        $endDateRange = $this->extractPriceRange($data, 'Discount End Date','Discount End Date 2','Discount End Date 3');


        $convertedDate = $this->convertDate($startDateRange, $endDateRange);

        $request =  Request::merge(array_merge([

            'published_modal' => "0",
            'create_stock' => "0",

            //Product information
            'name' => $data['Product Name *'],
            'brand_id' => $this->idExtracting($data, 'Brand *'),
            'unit' => $data['Unit of Sale *'],
            'country_code' => $this->idExtracting($data,'Country of Origin *'),
            'manufacturer' => $data['Manufacturer *'],
            'tags' =>  [json_encode($this->tagsHandling($data))],
            'short_description' => $data['Short Description *'],
            'stock_visibility_state' => $data['Show Stock Quantity *'],
            'refundable' => $data['Refundable *'],
            'video_provider' => $data['Video Provider'] ?? null,
            'video_link' => $data['Video Link'] ?? null,

            //Pricinng configuration

            'from' => $this->extractPriceRange($data, 'From Quantity *', 'From Quantity', 'From Quantity 2'), // Flattened array
            'to' => $this->extractPriceRange($data, 'To Quantity *', 'To Quantity', 'To Quantity 2'),
            'unit_price' => $this->extractPriceRange($data, 'Unit Price *', 'Unit Price', 'Unit Price 2'),
            
            'discount_type' => $this->extractPriceTypeRange($data, 'Discount Type', 'Discount Type 2', 'Discount Type 3'),
            'discount_amount' => $this->extractPriceRange($data, 'Discount Amount', 'Discount Amount 2', 'Discount Amount 3'),
            'discount_percentage' => $this->extractPriceRange($data, 'Discount Percentage', 'Discount Percentage 2', 'Discount Percentage 3'),

            //Sample Configuration
            'sample_description' => $data['Sample Description'] ?? null,
            'sample_price' => $data['Sample Price'] ?? null,

            //Product Package Configuration
            'activate_third_party' => 1,
            'length' => $data['Length *'],
            'width' => $data['Width *'],
            'height' => $data['Height *'],
            'weight' => $data['Weight *'],
            'min_third_party' => $data['Min. Temperature *'],
            'max_third_party' => $data['Max. Temperature *'],
            'unit_weight' => 'kilograms',
            'breakable' => strtolower($data['Breakable *']),

            //Shipping Configuration
            'from_shipping' => $this->extractPriceRange($data, 'From Quantity * 2', null, null),
            'to_shipping' => $this->extractPriceRange($data, 'To Quantity * 2', null, null),
            'estimated_order' => $this->extractPriceRange($data, 'Order Preparation Days *', null, null),
            'estimated_shipping' => $this->extractPriceRange($data, 'Shipping Days', null, null),
            'paid' => $this->extractPaid($data, 'Paid By', null, null),
            'shipping_charge' => $this->extractChargeType($data, 'Shipping Charge Type', null, null),
            'flat_rate_shipping' => $this->extractPriceRange($data, 'Flat-rate Amount', null, null),
            'charge_per_unit_shipping' => $this->extractPriceRange($data, 'Charge per Unit of Sale', null, null),
            'shipper' => [$this->extractPaid($data, 'Shipper *', null, null)],


            //Sample Package Configuration
            'shipper_sample' => isset($data['Shipper']) ? [strtolower($data['Shipper'])] : null,
            'estimated_sample' => isset($data['Order Preparation Days']) ? $data['Order Preparation Days'] : null,
            'estimated_shipping_sample' => isset($data['Shipping Days']) ? $data['Shipping Days'] : null,
            'paid_sample' => isset($data['Paid By']) ? strtolower($data['Paid By']) : null,
            'shipping_amount' => isset($data['Shipping Charge Amount']) ? $data['Shipping Charge Amount'] : null,
            'shipper_sample' => isset($data['Shipper']) ? [strtolower($data['Shipper'])] : null,

            'activate_third_party_sample' => 1,
            'length_sample' => isset($data['Length']) ? $data['Length'] : null,
            'width_sample' => isset($data['Width']) ? $data['Width'] : null,
            'height_sample' => isset($data['Height']) ? $data['Height'] : null,
            'breakable_sample' => isset($data['Breakable']) ? strtolower($data['Breakable']) : null,
            'package_weight_sample' => isset($data['Weight']) ? $data['Weight'] : null,
            'min_third_party_sample' => isset($data['Min. Temperature']) ? $data['Min. Temperature'] : null,
            'max_third_party_sample' => isset($data['Max. Temperature']) ? $data['Max. Temperature'] : null,
        

            'parent_id' => $this->idExtracting($data, 'Product Type (Leaf Category) *'),
            'product_sk' => $data['SKU *'],
            'quantite_stock_warning' => null,




            'unit_attribute_generale-39' => "13",
            'unit_attribute_generale-49' => "17",

            'description' => $data['Long Description'] ?? null,
            'document_names' => [],


            //SEO Meta Tags
            'meta_title' => $data['Meta Title'] ?? null,
            'meta_description' => $data['Description'] ?? null,


            'inventory_stock'=>[
                'warehouse'=> isset($data['Warehouse']) ? $this->idExtracting($data,'Warehouse') : null,
                'quantity' => isset($data['Quantity']) ? $data['Quantity'] : null,
                'comment' => isset($data['Comment']) ? $data['Comment'] : null,
            ],

            'submit_button' => "draft",
        ], $productAttributes,$convertedDate));

        return Request::except('bulk_file');
    }


    private function convertDate(array $startDates, array $endDates)
    {
        $dateRangePricing = [];

        // Ensure both arrays are of equal length
        $count = min(count($startDates), count($endDates));

        // Reference date for Excel serial date conversion
        $referenceDate = new DateTime('1899-12-30');

        // Loop through the arrays
        for ($i = 0; $i < $count; $i++) {
            // Check if either start date or end date is null
            if ($startDates[$i] === null || $endDates[$i] === null) {
                // Return null if any of the dates are null
                return ['date_range_pricing' => [null]];
            }

            // Convert start date
            $startDate = clone $referenceDate;
            $startDate->modify("+{$startDates[$i]} days");
            $formattedStartDate = $startDate->format('d-m-Y');

            // Convert end date
            $endDate = clone $referenceDate;
            $endDate->modify("+{$endDates[$i]} days");
            $formattedEndDate = $endDate->format('d-m-Y');

            // Format into the desired range string
            $dateRangePricing[] = "$formattedStartDate 00:00:00 to $formattedEndDate 23:59:00";
        }

        return [
            'date_range_pricing' => $dateRangePricing,
        ];
    }



    public function extractDateRange($data, $startKey, $endKey)
    {
        // Retrieve the start and end dates from the $data array
        $startDate = isset($data[$startKey]) ? $data[$startKey] : null;
        $endDate = isset($data[$endKey]) ? $data[$endKey] : null;

        // Function to format a date to "YYYY-MM-DD HH:MM:SS" with time parts
        $formatDate = function ($date, $isStart) {
            if ($date) {
                return $isStart ? $date . ' 00:00:00' : $date . ' 23:59:00';
            }
            return null;
        };

        // Format the dates
        $formattedStartDate = $formatDate($startDate, true);
        $formattedEndDate = $formatDate($endDate, false);

        // If both dates exist, return the formatted range
        if ($formattedStartDate && $formattedEndDate) {
            return "$formattedStartDate to $formattedEndDate";
        }

        // Return null or an empty array if one or both dates are missing
        return null;
    }

    private function extractKeyValuePairsForAttributes(array $data, array $attributes)
    {
        $matchingKeyValuePairs = [];
    
        // Loop through the attributes to find the matching key-value pairs in $data
        foreach ($attributes['attributes'] as $attribute) {
            // Extract the attribute ID and name from the attribute array
            $attributeId = $attribute['id'];
            $attributeName = $attribute['name'];
    
            // Check if this attribute name exists in the $data array and the type_value is 'list'
            if (isset($data[$attributeName])) {
                if ($attribute['type_value'] == 'color') {
                    // Use your idExtracting method for 'list' type values
                    $matchingKeyValuePairs['attribute_generale-' . $attributeId] = [$this->idExtracting($data,$attributeName)];
                }else if($attribute['type_value'] == 'list'){
                    $matchingKeyValuePairs['attribute_generale-' . $attributeId] = $this->idExtracting($data,$attributeName);

                } else {
                    // Use 'attribute_generale-{id}' as the key and the matching value from $data
                    $matchingKeyValuePairs['attribute_generale-' . $attributeId] = strtolower($data[$attributeName]);
                }
            }
        }
    
        return $matchingKeyValuePairs;
    }
    


    private function extractKeyValuePairsBetweenSkuAndParentSku(array $data)
    {
        $startIndex = null;
        $endIndex = null;

        // Get an array of keys from the associative array
        $keys = array_keys($data);

        // Find the index of "SKU *" and "Parent SKU"
        foreach ($keys as $index => $key) {
            if ($key === "SKU *") {
                $startIndex = $index;
            }

            if ($key === "Parent SKU") {
                $endIndex = $index;
                break; // Stop once we find the "Parent SKU"
            }
        }

        // If both indices are found, return the key-value pairs between them
        if (!is_null($startIndex) && !is_null($endIndex)) {
            // Slice the array between "SKU *" and "Parent SKU"
            $slicedKeys = array_slice($keys, $startIndex + 1, $endIndex - $startIndex - 1);

            // Map the sliced keys back to the original array and return key-value pairs
            return array_intersect_key($data, array_flip($slicedKeys));
        }

        // If either index is not found, return an empty array
        return [];
    }


    private function extractPaid(array $data, $key1, $key2, $key3)
    {
        // Extract values from data
        $val1 = isset($data[$key1]) ? $data[$key1] : null;
        $val2 = isset($data[$key2]) ? $data[$key2] : null;
        $val3 = isset($data[$key3]) ? $data[$key3] : null;

        // Initialize an array with val1
        $result = [strtolower($val1)];

        // If val2 is not null, append it to the array
        if (!is_null($val2)) {
            $result[] = strtolower($val2);
        }

        // If val3 is not null, append it to the array
        if (!is_null($val3)) {
            $result[] = strtolower($val3);
        }

        return $result;
    }

    private function extractChargeType(array $data, $key1, $key2, $key3)
    {
        // Function to transform the value based on specific conditions
        $transformValue = function ($value) {
            if ($value === 'Flat-rate regardless of quantity') {
                return 'flat';
            } elseif ($value === 'Charge per unit of sale') {
                return 'charging';
            }
            return $value; // Return the value as is if no transformation is needed
        };

        // Extract values from data
        $val1 = isset($data[$key1]) ? $transformValue($data[$key1]) : null;
        $val2 = isset($data[$key2]) ? $transformValue($data[$key2]) : null;
        $val3 = isset($data[$key3]) ? $transformValue($data[$key3]) : null;

        // Initialize an array with val1
        $result = [$val1];

        // If val2 is not null, append it to the array
        if (!is_null($val2)) {
            $result[] = $val2;
        }

        // If val3 is not null, append it to the array
        if (!is_null($val3)) {
            $result[] = $val3;
        }

        return $result;
    }


    private function extractPriceTypeRange(array $data, $key1, $key2, $key3)
    {
        // Function to transform the value based on specific conditions
        $transformValue = function ($value) {
            if ($value === 'Percentage') {
                return 'percent';
            } elseif ($value === 'Flat') {
                return 'amount';
            }
            return $value; // Return the value as is if no transformation is needed
        };

        // Extract values from data
        $val1 = isset($data[$key1]) ? $transformValue($data[$key1]) : null;
        $val2 = isset($data[$key2]) ? $transformValue($data[$key2]) : null;
        $val3 = isset($data[$key3]) ? $transformValue($data[$key3]) : null;

        // Initialize an array with val1
        $result = [$val1];

        // If val2 is not null, append it to the array
        if (!is_null($val2)) {
            $result[] = $val2;
        }

        // If val3 is not null, append it to the array
        if (!is_null($val3)) {
            $result[] = $val3;
        }

        return $result;
    }


    /**
     * Extract the 'from quantity *' and 'to quantity *' from the product data.
     */
    private function extractPriceRange(array $data, $key1, $key2, $key3)
    {
        $val1 = isset($data[$key1]) ? $data[$key1] : null;
        $val2 = isset($data[$key2]) ? $data[$key2] : null;
        $val3 = isset($data[$key3]) ? $data[$key3] : null;

        // Initialize an array with val1
        $result = [$val1];

        // If val2 is not null, append it to the array
        if (!is_null($val2)) {
            $result[] = $val2;
        }

        // If val3 is not null, append it to the array
        if (!is_null($val3)) {
            $result[] = $val3;
        }

        return $result;
    }




    function extractData($pricingConfigurations, $key)
    {
        $data = [];

        foreach ($pricingConfigurations as $config) {
            if (isset($config[$key])) {
                $value = $config[$key];
                if ($key === 'Discount Type') {
                    if ($value === 'Percentage') {
                        $value = 'percent';
                    } elseif ($value === 'Flat') {
                        $value = 'amount';
                    }
                }
                $data[] = $value;
            }
        }

        return $data;
    }


    private function idExtracting($product, $key)
    {
        $value_string = $product[$key];
        $value_parts = explode('-', $value_string);
        $extracted_id = (int) $value_parts[0];
        return $extracted_id;
    }


    private function tagsHandling($product)
    {
        $product_tags = $product['Tags *']; // Example input for tags
        $tags_array = explode(', ', $product_tags);
        $formatted_tags = array_map(function ($tag) {
            return ['value' => trim($tag)];
        }, $tags_array);
        return $formatted_tags;
    }
}
