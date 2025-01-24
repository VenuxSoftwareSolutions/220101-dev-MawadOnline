<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\BusinessInformation;
use App\Models\Category;
use App\Models\Color;
use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Models\ProductAttributeValues;
use App\Models\Review;
use App\Models\Shipping;
use App\Models\StockSummary;
use App\Models\Unity;
use App\Models\UploadProducts;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Log;
use Mail;
use App\Models\Revision;
use App\Mail\ApprovalProductMail;
use App\Models\ProductCatalog;
use App\Models\ProductAttributeValueCatalog;
use App\Models\UploadProductCatalog;
use File;

class ProductService
{
    public function store(array $data)
    {
        try {
            $collection = collect($data);
            $vat_user = BusinessInformation::where('user_id', auth()->user()->owner_id)->first();

            $approved = 1;

            if (auth()->user()->user_type == 'seller') {
                $user_id = Auth::user()->owner_id;
                if (get_setting('product_approve_by_admin') == 1) {
                    $approved = 0;
                }
            } else {
                $user_id = User::where('user_type', 'admin')->first()->id;
            }

            $tags = [];

            if ($collection['tags'][0] != null) {
                foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                    array_push($tags, $tag->value);
                }
            }

            $collection['tags'] = implode(',', $tags);

            $discount_start_date = null;
            $discount_end_date = null;

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

            if (! isset($collection['country_code'])) {
                $collection['country_code'] = '';
            }

            if ($collection['meta_title'] == null) {
                $collection['meta_title'] = $collection['name'];
            }
            if ($collection['meta_description'] == null) {
                $collection['meta_description'] = strip_tags($collection['description']);
            }

            $pricing = [];

            $shipping_cost = 0;

            if (isset($collection['shipping_type'])) {
                if ($collection['shipping_type'] == 'free') {
                    $shipping_cost = 0;
                } elseif ($collection['shipping_type'] == 'flat_rate') {
                    $shipping_cost = $collection['flat_shipping_cost'];
                }
            }

            unset($collection['flat_shipping_cost']);

            $slug = generateUniqueSlug(Product::class, $collection['name']);

            $colors = json_encode([]);

            if (
                isset($collection['colors_active']) &&
                $collection['colors_active'] &&
                $collection['colors'] &&
                count($collection['colors']) > 0
            ) {
                $colors = json_encode($collection['colors']);
            }

            if (isset($collection['stock_visibility_state'])) {
                $collection['stock_visibility_state'] = 'quantity';
            } else {
                $collection['stock_visibility_state'] = 'hide';
            }

            $is_draft = 0;

            if (isset($collection['button'])) {
                if ($collection['button'] == 'draft') {
                    $is_draft = 1;
                }
                unset($collection['button']);
            }

            if (isset($collection['submit_button'])) {
                if ($collection['submit_button'] == 'draft') {
                    $is_draft = 1;
                }
                unset($collection['submit_button']);
            }

            $pricing = [];

            if ((isset($collection['from'])) && (isset($collection['to'])) && (isset($collection['unit_price']))) {
                $pricing = [
                    'from' => $collection['from'],
                    'to' => $collection['to'],
                    'unit_price' => $collection['unit_price'],
                ];

                if (isset($collection['discount_type'])) {
                    $pricing['discount_type'] = $collection['discount_type'];
                }

                if (isset($collection['date_range_pricing'])) {
                    $pricing['date_range_pricing'] = $collection['date_range_pricing'];
                }

                if (isset($collection['discount_amount'])) {
                    $pricing['discount_amount'] = $collection['discount_amount'];
                }

                if (isset($collection['discount_percentage'])) {
                    $pricing['discount_percentage'] = $collection['discount_percentage'];
                }

                unset($collection['from']);
                unset($collection['to']);
                unset($collection['unit_price']);
                unset($collection['discount_amount']);
                unset($collection['discount_type']);
                unset($collection['discount_percentage']);
            }

            $shipping = [];

            if (
                (isset($collection['from_shipping'])) &&
                (isset($collection['to_shipping'])) &&
                (isset($collection['shipper'])) &&
                (isset($collection['estimated_order']))
            ) {
                foreach ($collection['from_shipping'] as $key => $from_shipping) {
                    if (
                        (array_key_exists($key, $collection['from_shipping'])) &&
                        (array_key_exists($key, $collection['to_shipping'])) &&
                        (array_key_exists($key, $collection['shipper'])) &&
                        (array_key_exists($key, $collection['estimated_order']))
                    ) {
                        if (
                            ($from_shipping != null) &&
                            ($collection['to_shipping'][$key] != null) &&
                            ($collection['shipper'][$key] != null) &&
                            ($collection['estimated_order'][$key] != null)
                        ) {
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
                $shipping_sample_parent['shipper_sample'] = null;
            }

            if (isset($collection['estimated_sample'])) {
                $shipping_sample_parent['estimated_sample'] = $collection['estimated_sample'];
            } else {
                $shipping_sample_parent['estimated_sample'] = null;
            }

            if (isset($collection['estimated_shipping_sample'])) {
                $shipping_sample_parent['estimated_shipping_sample'] = $collection['estimated_shipping_sample'];
            } else {
                $shipping_sample_parent['estimated_shipping_sample'] = null;
            }

            if (isset($collection['paid_sample'])) {
                $shipping_sample_parent['paid_sample'] = $collection['paid_sample'];
            } else {
                $shipping_sample_parent['paid_sample'] = null;
            }

            if (isset($collection['shipping_amount'])) {
                $shipping_sample_parent['shipping_amount'] = $collection['shipping_amount'];
            } else {
                $shipping_sample_parent['shipping_amount'] = null;
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
                    if (! array_key_exists($ids[2], $variants_data)) {
                        $variants_data[$ids[2]] = [];
                    }
                    if (! array_key_exists('attributes', $variants_data[$ids[2]])) {
                        $variants_data[$ids[2]]['attributes'][$ids[1]] = $value;
                    } else {
                        if (! array_key_exists($ids[1], $variants_data[$ids[2]]['attributes'])) {
                            $variants_data[$ids[2]]['attributes'][$ids[1]] = $value;
                        }
                    }

                    $key_pricing = 'variant-pricing-'.$ids[2];
                    if (! isset($data[$key_pricing])) {
                        if (! array_key_exists($ids[2], $variants_data)) {
                            $variants_data[$ids[2]] = [];
                        }

                        $variants_data[$ids[2]]['pricing'] = $data['variant_pricing-from'.$ids[2]];
                    }

                    $key_shipping = 'variant_shipping-'.$ids[2];
                    if (isset($data[$key_shipping])) {
                        if (! array_key_exists($ids[2], $variants_data)) {
                            $variants_data[$ids[2]] = [];
                        }

                        $variants_data[$ids[2]]['shipping_details'] = $data['variant_shipping-'.$ids[2]];
                    }

                    $key_sample_available = 'variant-sample-available'.$ids[2];
                    if (isset($data[$key_sample_available])) {
                        if (! array_key_exists($ids[2], $variants_data)) {
                            $variants_data[$ids[2]] = [];
                        }

                        $variants_data[$ids[2]]['sample_available'] = 1;
                    } else {
                        if (! array_key_exists($ids[2], $variants_data)) {
                            $variants_data[$ids[2]] = [];
                        }

                        $variants_data[$ids[2]]['sample_available'] = 0;
                    }
                }

                if (strpos($key, 'sku') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }

                    $variants_data[$ids[1]]['sku'] = $value;
                }

                if (strpos($key, 'stock-warning-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[2], $variants_data)) {
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['stock'] = $value;
                }

                if (strpos($key, 'variant-published-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[2], $variants_data)) {
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['published'] = $value;
                }

                if (strpos($key, 'variant-shipping-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[2], $variants_data)) {
                        $variants_data[$ids[2]] = [];
                    }

                    $variants_data[$ids[2]]['shipping'] = 1;
                }

                if (strpos($key, 'photos_variant') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }

                    $variants_data[$ids[1]]['photo'] = $value;
                }

                if (strpos($key, 'attributes_units') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[2], $variants_data)) {
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
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }

                    $variants_data[$ids[1]]['vat_sample'] = $value;
                }

                if (strpos($key, 'sample_description-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }

                    if ($value != null) {
                        $variants_data[$ids[1]]['sample_description'] = $value;
                    }
                }

                if (strpos($key, 'sample_price-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }
                    if ($value != null) {
                        $variants_data[$ids[1]]['sample_price'] = $value;
                    }
                }

                if (strpos($key, 'estimated_sample-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }

                    $variants_data[$ids[1]]['estimated_sample'] = $value;
                }

                if (strpos($key, 'estimated_shipping_sample-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }

                    $variants_data[$ids[1]]['estimated_shipping_sample'] = $value;
                }

                if (strpos($key, 'shipping_amount-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }

                    $variants_data[$ids[1]]['shipping_amount'] = $value;
                }

                if (strpos($key, 'variant_shipper_sample-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }

                    $variants_data[$ids[1]]['variant_shipper_sample'] = $value;
                }

                if (strpos($key, 'paid_sample-') === 0) {
                    $ids = explode('-', $key);
                    if (! array_key_exists($ids[1], $variants_data)) {
                        $variants_data[$ids[1]] = [];
                    }

                    $variants_data[$ids[1]]['paid_sample'] = $value;
                }
            }

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

            if (! isset($data['activate_attributes'])) {
                return $this->storeProductWithDependencies(
                    $data, $pricing, $general_attributes_data,
                    $ids_attributes_list, $ids_attributes_numeric,
                    $unit_general_attributes_data, $shipping
                );
            } else {
                return $this->storeParentProductWithDependencies(
                    $data, $pricing, $shipping,
                    $vat, $variants_data, $shipping_sample_parent,
                    $ids_attributes_list, $ids_attributes_color,
                    $ids_attributes_numeric, $vat_user,
                    $general_attributes_data, $unit_general_attributes_data
                );
            }
        } catch (Exception $e) {
            Log::error('Error while store product data, with message: '.$e->getMessage());
            return null;
        }
    }

    public function update(array $data, Product $product_update)
    {
        $collection = collect($data);

        $collection['user_id'] = Auth::user()->owner_id;

        $collection['rejection_reason'] = null;
        $vat_user = BusinessInformation::where('user_id', Auth::user()->owner_id)->first();

        $slug = Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug.'%')->count();
        $slug_suffix = $same_slug_count > 1 ? '-'.$same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $collection['slug'] = $slug;
        if (isset($collection['refundable'])) {
            $collection['refundable'] = 1;
        } else {
            $collection['refundable'] = 0;
        }

        if (isset($collection['published'])) {
            $collection['published'] = 1;
        } else {
            $collection['published'] = 0;
        }

        if (! isset($collection['country_code'])) {
            $collection['country_code'] = '';
        }

        if (isset($collection['activate_third_party'])) {
            $collection['activate_third_party'] = 1;
        }

        $pricing = [];
        if ((isset($collection['from'])) && (isset($collection['to'])) && (isset($collection['unit_price']))) {
            $pricing = [
                'from' => $collection['from'],
                'to' => $collection['to'],
                'unit_price' => $collection['unit_price'],
            ];

            if (isset($collection['discount_type'])) {
                $pricing['discount_type'] = $collection['discount_type'];
            }

            if (isset($collection['date_range_pricing'])) {
                $pricing['date_range_pricing'] = $collection['date_range_pricing'];
            }

            if (isset($collection['discount_amount'])) {
                $pricing['discount_amount'] = $collection['discount_amount'];
            }

            if (isset($collection['discount_percentage'])) {
                $pricing['discount_percentage'] = $collection['discount_percentage'];
            }

            unset($collection['from']);
            unset($collection['to']);
            unset($collection['unit_price']);
            unset($collection['discount_amount']);
            unset($collection['discount_type']);
            unset($collection['discount_percentage']);
        }

        if ($collection['parent_id'] != null) {
            $collection['category_id'] = $collection['parent_id'];
        }

        unset($collection['parent_id']);

        $tags = [];
        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $collection['tags'] = implode(',', $tags);

        if (isset($collection['stock_visibility_state'])) {
            $collection['stock_visibility_state'] = 'quantity';
        } else {
            $collection['stock_visibility_state'] = 'hide';
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
            $shipping_sample_parent['shipper_sample'] = null;
        }

        if (isset($collection['estimated_sample'])) {
            $shipping_sample_parent['estimated_sample'] = $collection['estimated_sample'];
        } else {
            $shipping_sample_parent['estimated_sample'] = null;
        }

        if (isset($collection['estimated_shipping_sample'])) {
            $shipping_sample_parent['estimated_shipping_sample'] = $collection['estimated_shipping_sample'];
        } else {
            $shipping_sample_parent['estimated_shipping_sample'] = null;
        }

        if (isset($collection['paid_sample'])) {
            $shipping_sample_parent['paid_sample'] = $collection['paid_sample'];
        } else {
            $shipping_sample_parent['paid_sample'] = null;
        }

        if (isset($collection['shipping_amount'])) {
            $shipping_sample_parent['shipping_amount'] = $collection['shipping_amount'];
        } else {
            $shipping_sample_parent['shipping_amount'] = null;
        }

        $variants_data = [];
        $variants_new_data = [];
        $general_attributes_data = [];
        $unit_general_attributes_data = [];

        //check if product has old variants
        if (array_key_exists('variant', $data)) {
            foreach ($collection['variant']['sku'] as $key => $sku) {
                if (! array_key_exists($key, $variants_data)) {
                    $variants_data[$key] = [];
                }

                $variants_data[$key]['sku'] = $sku;

                //Check if the variant has pictures
                if (array_key_exists('photo', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['photo'])) {
                        $variants_data[$key]['photo'] = $data['variant']['photo'][$key];
                    } else {
                        $variants_data[$key]['photo'] = [];
                    }
                } else {
                    $variants_data[$key]['photo'] = [];
                }

                //check if the variant has pricing configuration
                if (array_key_exists('from', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['from'])) {
                        $pricing_variant = [];
                        $pricing_variant['from'] = $data['variant']['from'][$key];
                        $pricing_variant['to'] = $data['variant']['to'][$key];
                        $pricing_variant['unit_price'] = $data['variant']['unit_price'][$key];
                        $pricing_variant['date_range_pricing'] = $data['variant']['date_range_pricing'][$key];
                        $pricing_variant['discount_type'] = $data['variant']['discount_type'][$key];
                        $pricing_variant['discount_amount'] = $data['variant']['discount_amount'][$key];
                        $pricing_variant['discount_percentage'] = $data['variant']['discount_percentage'][$key];
                        $variants_data[$key]['pricing'] = $pricing_variant;
                    } else {
                        $variants_data[$key]['pricing'] = $pricing;
                    }
                } else {
                    $variants_data[$key]['pricing'] = $pricing;
                }

                if (array_key_exists('from_shipping', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['from_shipping'])) {
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
                    } else {
                        $shipping_parent = [];
                        if ((isset($collection['from_shipping'])) && (isset($collection['to_shipping'])) && (isset($collection['shipper'])) && (isset($collection['estimated_order']))) {
                            if ($collection['from_shipping'][0] && ($collection['to_shipping'][0] != null) && ($collection['shipper'][0] != null) && ($collection['estimated_order'][0] != null)) {
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
                        }
                        $variants_data[$key]['shipping_details'] = $shipping_parent;
                    }
                } else {
                    $shipping_parent = [];
                    if ((isset($collection['from_shipping'])) && (isset($collection['to_shipping'])) && (isset($collection['shipper'])) && (isset($collection['estimated_order']))) {
                        if ($collection['from_shipping'][0] && ($collection['to_shipping'][0] != null) && ($collection['shipper'][0] != null) && ($collection['estimated_order'][0] != null)) {
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
                    }
                    $variants_data[$key]['shipping_details'] = $shipping_parent;
                }

                if (array_key_exists('sample_available', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['sample_available'])) {
                        $variants_data[$key]['sample_available'] = 1;
                    } else {
                        $variants_data[$key]['sample_available'] = 0;
                    }
                } else {
                    $variants_data[$key]['sample_available'] = 0;
                }

                if (array_key_exists('shipper_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['shipper_sample'])) {
                        $variants_data[$key]['shipper_sample'] = $data['variant']['shipper_sample'][$key];
                    } else {
                        $variants_data[$key]['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                    }
                } else {
                    $variants_data[$key]['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                }

                if (array_key_exists('estimated_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['estimated_sample'])) {
                        $variants_data[$key]['estimated_sample'] = $data['variant']['estimated_sample'][$key];
                    } else {
                        $variants_data[$key]['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                    }
                } else {
                    $variants_data[$key]['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                }

                if (array_key_exists('estimated_shipping_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['estimated_shipping_sample'])) {
                        $variants_data[$key]['estimated_shipping_sample'] = $data['variant']['estimated_shipping_sample'][$key];
                    } else {
                        $variants_data[$key]['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                    }
                } else {
                    $variants_data[$key]['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                }

                if (array_key_exists('paid_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['paid_sample'])) {
                        $variants_data[$key]['paid_sample'] = $data['variant']['paid_sample'][$key];
                    } else {
                        $variants_data[$key]['paid_sample'] = 0;
                    }
                } else {
                    $variants_data[$key]['paid_sample'] = 0;
                }

                if (array_key_exists('shipping_amount', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['shipping_amount'])) {
                        $variants_data[$key]['shipping_amount'] = $data['variant']['shipping_amount'][$key];
                    } else {
                        $variants_data[$key]['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                    }
                } else {
                    $variants_data[$key]['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                }

                //check if the variant has sample pricing
                if (array_key_exists('sample_pricing', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['sample_pricing'])) {
                        $variants_data[$key]['sample_pricing'] = 0;
                        $variants_data[$key]['sample_description'] = $data['sample_description'];
                        $variants_data[$key]['sample_price'] = $data['sample_price'];
                    } else {
                        $variants_data[$key]['sample_pricing'] = 1;
                        $variants_data[$key]['sample_description'] = $data['variant']['sample_description'][$key];
                        $variants_data[$key]['sample_price'] = $data['variant']['sample_price'][$key];
                    }
                } else {
                    $variants_data[$key]['sample_pricing'] = 0;
                    $variants_data[$key]['sample_description'] = $data['sample_description'];
                    $variants_data[$key]['sample_price'] = $data['sample_price'];
                }

                //check if the variant activated the shipping configuration
                if (array_key_exists('shipping', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['shipping'])) {
                        $variants_data[$key]['shipping'] = $data['variant']['shipping'][$key];
                    } else {
                        $variants_data[$key]['shipping'] = 0;
                    }
                } else {
                    $variants_data[$key]['shipping'] = 0;
                }

                //check if the variant is published
                if (array_key_exists('published', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['published'])) {
                        $variants_data[$key]['published'] = $data['variant']['published'][$key];
                    } else {
                        $variants_data[$key]['published'] = 0;
                    }
                } else {
                    $variants_data[$key]['published'] = 0;
                }

                //check if the variant activated the sample shipping configuration
                if (array_key_exists('sample_shipping', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['sample_shipping'])) {
                        $variants_data[$key]['sample_shipping'] = $data['variant']['sample_shipping'][$key];
                    } else {
                        $variants_data[$key]['sample_shipping'] = 0;
                    }
                } else {
                    $variants_data[$key]['sample_shipping'] = 0;
                }

                //check if the variant activated vat option for sample
                if (array_key_exists('vat_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['vat_sample'])) {
                        $variants_data[$key]['vat_sample'] = $data['variant']['vat_sample'][$key];
                    } else {
                        $variants_data[$key]['vat_sample'] = 0;
                    }
                } else {
                    $variants_data[$key]['vat_sample'] = 0;
                }

                //check if the variant has low stock quantity
                if (array_key_exists('low_stock_quantity', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['low_stock_quantity'])) {
                        $variants_data[$key]['low_stock_quantity'] = $data['variant']['low_stock_quantity'][$key];
                    } else {
                        $variants_data[$key]['low_stock_quantity'] = 0;
                    }
                } else {
                    $variants_data[$key]['low_stock_quantity'] = 0;
                }

                //Check if the variant has attributes. If it does, a table will be generated containing all attributes, with each attribute having its own value.
                if (array_key_exists('attributes', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['attributes'])) {
                        foreach ($data['variant']['attributes'][$key] as $id_attribute => $attribute) {
                            if (! array_key_exists('attributes', $variants_data[$key])) {
                                $variants_data[$key]['attributes'][$id_attribute] = $attribute;
                            } else {
                                if (! array_key_exists($id_attribute, $variants_data[$key]['attributes'])) {
                                    $variants_data[$key]['attributes'][$id_attribute] = $attribute;
                                }
                            }
                        }
                    } else {
                        $variants_data[$key]['attributes'] = [];
                    }
                } else {
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
                if (! array_key_exists($ids[2], $variants_new_data)) {
                    $variants_new_data[$ids[2]] = [];
                }
                if (! array_key_exists('attributes', $variants_new_data[$ids[2]])) {
                    $variants_new_data[$ids[2]]['attributes'][$ids[1]] = $value;
                } else {
                    if (! array_key_exists($ids[1], $variants_new_data[$ids[2]]['attributes'])) {
                        $variants_new_data[$ids[2]]['attributes'][$ids[1]] = $value;
                    }
                }

                //check if the variant activated the variant pricing
                $key_pricing = 'variant-pricing-'.$ids[2];
                if (! isset($data[$key_pricing])) {
                    if (! array_key_exists($ids[2], $variants_new_data)) {
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['pricing'] = $data['variant_pricing-from'.$ids[2]];
                }

                $key_shipping = 'variant_shipping-'.$ids[2];
                if (isset($data[$key_shipping])) {
                    if (! array_key_exists($ids[2], $variants_new_data)) {
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['shipping_details'] = $data['variant_shipping-'.$ids[2]];
                }

                $key_sample_available = 'variant-sample-available'.$ids[2];
                if (isset($data[$key_sample_available])) {
                    if (! array_key_exists($ids[2], $variants_new_data)) {
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['sample_available'] = 1;
                } else {
                    if (! array_key_exists($ids[2], $variants_new_data)) {
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['sample_available'] = 0;
                }
            }

            if (strpos($key, 'variant-published-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[2], $variants_data)) {
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['published'] = $value;
            }

            if (strpos($key, 'sku') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['sku'] = $value;
            }

            if (strpos($key, 'stock-warning-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[2], $variants_new_data)) {
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['stock'] = $value;
            }

            if (strpos($key, 'variant-shipping-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[2], $variants_new_data)) {
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['shipping'] = 1;
            }

            if (strpos($key, 'photos_variant') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['photo'] = $value;
            }

            if (strpos($key, 'attributes_units') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[2], $variants_new_data)) {
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['units'][$ids[1]] = $value;
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
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['vat_sample'] = $value;
            }

            if (strpos($key, 'sample_description-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }

                if ($value != null) {
                    $variants_new_data[$ids[1]]['sample_description'] = $value;
                }
            }

            if (strpos($key, 'sample_price-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }
                if ($value != null) {
                    $variants_new_data[$ids[1]]['sample_price'] = $value;
                }

            }

            if (strpos($key, 'estimated_sample-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_sample'] = $value;
            }

            if (strpos($key, 'estimated_shipping_sample-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_shipping_sample'] = $value;
            }

            if (strpos($key, 'shipping_amount-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['shipping_amount'] = $value;
            }

            if (strpos($key, 'variant_shipper_sample-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['variant_shipper_sample'] = $value;
            }

            if (strpos($key, 'paid_sample-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['paid_sample'] = $value;
            }
        }

        if (isset($collection['product_sk'])) {
            $collection['sku'] = $collection['product_sk'];
            unset($collection['product_sk']);
        } else {
            $collection['sku'] = $collection['name'];
        }

        if (isset($collection['quantite_stock_warning'])) {
            $collection['low_stock_quantity'] = $collection['quantite_stock_warning'];
            unset($collection['quantite_stock_warning']);
        } else {
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

        if (! isset($data['activate_attributes'])) {
            //Create product without variants
            $collection = $collection->toArray();

            unset($collection["unit_price"]);
            $collection["unit_price"] = $collection["unit_sale_price"];

            $product_update->update($collection);
            $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();
            if (count($pricing) > 0) {
                $all_data_to_insert = [];
                foreach ($pricing['from'] as $key => $from) {
                    $current_data = [];
                    if (($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)) {
                        if (isset($pricing['date_range_pricing'])) {
                            if ($pricing['date_range_pricing'][$key] != null) {
                                if ($pricing['date_range_pricing'][$key] != null) {
                                    $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                    $start_to_parse = explode(' ', $date_var[0]);
                                    $end_to_parse = explode(' ', $date_var[1]);

                                    $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                    $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                    $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                    $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                    if (($check_start == true) && ($check_end == true)) {
                                        $current_data['discount_start_datetime'] = $discount_start_date;
                                        $current_data['discount_end_datetime'] = $discount_end_date;
                                        $current_data['discount_type'] = $pricing['discount_type'][$key];
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }

                            } else {
                                $current_data['discount_start_datetime'] = null;
                                $current_data['discount_end_datetime'] = null;
                                $current_data['discount_type'] = null;
                                $current_data['discount_amount'] = null;
                                $current_data['discount_percentage'] = null;
                            }
                        } else {
                            $current_data['discount_start_datetime'] = null;
                            $current_data['discount_end_datetime'] = null;
                            $current_data['discount_type'] = null;
                            $current_data['discount_amount'] = null;
                            $current_data['discount_percentage'] = null;
                        }

                        $current_data['id_products'] = $product_update->id;
                        $current_data['from'] = $from;
                        $current_data['to'] = $pricing['to'][$key];
                        $current_data['unit_price'] = $pricing['unit_price'][$key];

                        if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                        } else {
                            $current_data['discount_amount'] = null;
                        }
                        if (isset($pricing['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                        } else {
                            $current_data['discount_percentage'] = null;
                        }

                        array_push($all_data_to_insert, $current_data);
                    }
                }

                PricingConfiguration::where('id_products', $product_update->id)->delete();
                PricingConfiguration::insert($all_data_to_insert);
            }

            $ids = [];
            $ids_product_attribute_values = [];
            if (count($general_attributes_data) > 0) {

                foreach ($general_attributes_data as $attr => $value) {
                    if ($value != null) {
                        if (in_array($attr, $ids_attributes_color)) {
                            ProductAttributeValues::where('id_products', $product_update->id)->where('id_attribute', $attr)->whereNotIn('value', $value)->delete();
                        } else {
                            $attribute_product = ProductAttributeValues::where('id_products', $product_update->id)->where('id_attribute', $attr)->first();
                        }

                        if (in_array($attr, $ids_attributes_list)) {
                            $check_add = false;
                            if ($attribute_product == null) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_update->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                                $check_add = true;
                            }
                            $value_attribute = AttributeValue::find($value);
                            $attribute_product->id_values = $value;
                            $attribute_product->value = $value_attribute->value;
                            $attribute_product->save();
                        } elseif (in_array($attr, $ids_attributes_color)) {
                            if (count($value) > 0) {
                                foreach ($value as $value_color) {
                                    $attribute_product = ProductAttributeValues::where('id_products', $product_update->id)->where('id_attribute', $attr)->where('value', $value_color)->first();
                                    $check_add = false;
                                    if ($attribute_product == null) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $product_update->id;
                                        $attribute_product->id_attribute = $attr;
                                        $attribute_product->is_general = 1;
                                        $check_add = true;
                                    }
                                    $color = Color::where('code', $value_color)->first();
                                    $attribute_product->id_colors = $color->id;
                                    $attribute_product->value = $color->code;
                                    $attribute_product->save();

                                    if ($check_add == true) {
                                        DB::table('revisions')->insert([
                                            'revisionable_type' => "App\Models\ProductAttributeValues",
                                            'revisionable_id' => $attribute_product->id,
                                            'user_id' => Auth::user()->owner_id,
                                            'key' => 'add_attribute',
                                            'old_value' => null,
                                            'new_value' => $value_color,
                                            'created_at' => new DateTime,
                                            'updated_at' => new DateTime,
                                        ]);
                                    }
                                }
                            }

                        } elseif (in_array($attr, $ids_attributes_numeric)) {
                            $check_add = false;
                            if ($attribute_product == null) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_update->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                                $check_add = true;
                            }
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                            $attribute_product->save();
                        } else {
                            $check_add = false;
                            if ($attribute_product == null) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_update->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                                $check_add = true;
                            }
                            $attribute_product->value = $value;
                            $attribute_product->save();
                        }

                        array_push($ids_product_attribute_values, $attribute_product->id);
                        if (! in_array($attr, $ids_attributes_color)) {
                            if ($check_add == true) {
                                DB::table('revisions')->insert([
                                    'revisionable_type' => "App\Models\ProductAttributeValues",
                                    'revisionable_id' => $attribute_product->id,
                                    'user_id' => Auth::user()->owner_id,
                                    'key' => 'add_attribute',
                                    'old_value' => null,
                                    'new_value' => $value,
                                    'created_at' => new DateTime,
                                    'updated_at' => new DateTime,
                                ]);
                            }
                        }

                        array_push($ids, $attr);
                    }
                }
            }

            ProductAttributeValues::whereNotIn('id_attribute', $ids)->where('id_products', $product_update->id)->delete();

            $shipping_to_delete = Shipping::where('product_id', $product_update->id)->delete();

            if (count($shipping) > 0) {
                $id = $product_update->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function ($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;

                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            $childrens = Product::where('parent_id', $product_update->id)->pluck('id')->toArray();
            if (count($childrens) > 0) {
                Shipping::whereIn('product_id', $childrens)->delete();
                PricingConfiguration::whereIn('id_products', $childrens)->delete();
                ProductAttributeValues::whereIn('id_products', $childrens)->delete();
                UploadProducts::whereIn('id_product', $childrens)->delete();
                Product::where('parent_id', $product_update->id)->delete();
                $product_update->is_parent = 0;
                $product_update->save();
            }

            $historique = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_id', $product_update->id)->where('revisionable_type', 'App\Models\Product')->get();

            $historique_attributes = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $ids_product_attribute_values)->where('revisionable_type', 'App\Models\ProductAttributeValues')->get();
            if (($product_update->product_added_from_catalog == 1) && (count($historique) == 0) && (count($historique_attributes) == 0)) {
                $product_update->approved = 1;
                $product_update->save();
            } else {
                // Update the approved field in the parent product
                $product_update->update(['approved' => 0, 'published' => $collection['last_version']]);
            }

            return $product_update;
        } else {
            // //Create Parent Product
            $collection['is_parent'] = 1;
            $collection = $collection->toArray();
            $product_update->update($collection);
            $historique = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_id', $product_update->id)->where('revisionable_type', 'App\Models\Product')->get();
            if (($product_update->product_added_from_catalog == 1) && (count($historique) == 0)) {
                $product_update->approved = 1;
                $product_update->save();
            } else {
                $product_update->approved = 0;
                $product_update->save();
            }
            $old_shipping = Shipping::where('product_id', $product_update->id)->delete();
            if (count($shipping) > 0) {
                $id = $product_update->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function ($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;

                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            if (count($pricing) > 0) {
                $all_data_to_insert = [];

                foreach ($pricing['from'] as $key => $from) {
                    $current_data = [];
                    if (($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)) {
                        if (isset($pricing['date_range_pricing'])) {
                            if ($pricing['date_range_pricing'] != null) {
                                if ($pricing['date_range_pricing'][$key] != null) {
                                    $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                    $start_to_parse = explode(' ', $date_var[0]);
                                    $end_to_parse = explode(' ', $date_var[1]);

                                    $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                    $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                    $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                    $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                    if (($check_start == true) && ($check_end == true)) {
                                        $current_data['discount_start_datetime'] = $discount_start_date;
                                        $current_data['discount_end_datetime'] = $discount_end_date;
                                        $current_data['discount_type'] = $pricing['discount_type'][$key];
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }

                            } else {
                                $current_data['discount_start_datetime'] = null;
                                $current_data['discount_end_datetime'] = null;
                                $current_data['discount_type'] = null;
                                $current_data['discount_amount'] = null;
                                $current_data['discount_percentage'] = null;
                            }
                        } else {
                            $current_data['discount_start_datetime'] = null;
                            $current_data['discount_end_datetime'] = null;
                            $current_data['discount_type'] = null;
                            $current_data['discount_amount'] = null;
                            $current_data['discount_percentage'] = null;
                        }

                        $current_data['id_products'] = $product_update->id;
                        $current_data['from'] = $from;
                        $current_data['to'] = $pricing['to'][$key];
                        $current_data['unit_price'] = $pricing['unit_price'][$key];

                        if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                        } else {
                            $current_data['discount_amount'] = null;
                        }
                        if (isset($pricing['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                        } else {
                            $current_data['discount_percentage'] = null;
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

            if (count($variants_data) > 0) {
                foreach ($variants_data as $id => $variant) {

                    $collection['low_stock_quantity'] = $variant['low_stock_quantity'];
                    $collection['sku'] = $variant['sku'];
                    $collection['vat_sample'] = $vat_user->vat_registered;
                    $collection['sample_description'] = $variant['sample_description'];
                    $collection['sample_price'] = $variant['sample_price'];
                    $collection['published'] = $variant['published'];

                    if (isset($variant['shipper_sample'])) {
                        $collection['shipper_sample'] = implode(',', $variant['shipper_sample']);
                    } else {
                        $collection['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                    }

                    if (isset($variant['estimated_sample'])) {
                        $collection['estimated_sample'] = $variant['estimated_sample'];
                    } else {
                        $collection['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                    }

                    if (isset($variant['estimated_shipping_sample'])) {
                        $collection['estimated_shipping_sample'] = $variant['estimated_shipping_sample'];
                    } else {
                        $collection['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                    }

                    if (isset($variant['paid_sample'])) {
                        $collection['paid_sample'] = $variant['paid_sample'];
                    } else {
                        $collection['paid_sample'] = $shipping_sample_parent['paid_sample'];
                    }

                    if (isset($variant['shipping_amount'])) {
                        $collection['shipping_amount'] = $variant['shipping_amount'];
                    } else {
                        $collection['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                    }

                    if (isset($variant['sample_available'])) {
                        $collection['sample_available'] = $variant['sample_available'];
                    } else {
                        $collection['sample_available'] = 0;
                    }

                    $product = Product::find($id);
                    if ($product != null) {
                        $product->update($collection);

                        //attributes of variant
                        //$sku = "";
                        $ids_product_attribute_values = [];
                        foreach ($variant['attributes'] as $key => $value_attribute) {
                            if ($value_attribute != null) {
                                // $attribute_name = Attribute::find($key)->name;
                                // $sku .= "_".$attribute_name;
                                // $attribute_product = ProductAttributeValues::where('id_products', $id)->where('id_attribute', $key)->first();
                                if (in_array($key, $ids_attributes_color)) {
                                    ProductAttributeValues::where('id_products', $id)->where('id_attribute', $key)->whereNotIn('value', $value_attribute)->delete();
                                } else {
                                    $attribute_product = ProductAttributeValues::where('id_products', $id)->where('id_attribute', $key)->first();
                                }

                                if (in_array($key, $ids_attributes_list)) {
                                    $check_add_attribute = false;
                                    if ($attribute_product == null) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $product->id;
                                        $attribute_product->id_attribute = $key;
                                        $attribute_product->is_variant = 1;
                                        $check_add_attribute = true;
                                    }
                                    $value = AttributeValue::find($value_attribute);
                                    $attribute_product->id_values = $value_attribute;
                                    $attribute_product->value = $value->value;
                                    $attribute_product->save();
                                } elseif (in_array($key, $ids_attributes_color)) {
                                    foreach ($value_attribute as $value_color) {
                                        $attribute_product = ProductAttributeValues::where('id_products', $id)->where('id_attribute', $key)->where('value', $value_color)->first();
                                        $check_add_attribute = false;
                                        if ($attribute_product == null) {
                                            $attribute_product = new ProductAttributeValues;
                                            $attribute_product->id_products = $product->id;
                                            $attribute_product->id_attribute = $key;
                                            $attribute_product->is_variant = 1;
                                            $check_add_attribute = true;
                                        }
                                        $value = Color::where('code', $value_color)->first();
                                        $attribute_product->id_colors = $value->id;
                                        $attribute_product->value = $value->code;
                                        $attribute_product->save();

                                        if ($check_add_attribute == true) {
                                            DB::table('revisions')->insert([
                                                'revisionable_type' => "App\Models\ProductAttributeValues",
                                                'revisionable_id' => $attribute_product->id,
                                                'user_id' => Auth::user()->owner_id,
                                                'key' => 'add_attribute',
                                                'old_value' => null,
                                                'new_value' => $value_color,
                                                'created_at' => new DateTime,
                                                'updated_at' => new DateTime,
                                            ]);
                                        }
                                    }
                                } elseif (in_array($key, $ids_attributes_numeric)) {
                                    $check_add_attribute = false;
                                    if ($attribute_product == null) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $product->id;
                                        $attribute_product->id_attribute = $key;
                                        $attribute_product->is_variant = 1;
                                        $check_add_attribute = true;
                                    }
                                    $attribute_product->id_units = $data['unit_variant'][$id][$key];
                                    $attribute_product->value = $value_attribute;
                                    $attribute_product->save();
                                } else {
                                    $check_add_attribute = false;
                                    if ($attribute_product == null) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $product->id;
                                        $attribute_product->id_attribute = $key;
                                        $attribute_product->is_variant = 1;
                                        $check_add_attribute = true;
                                    }
                                    $attribute_product->value = $value_attribute;
                                    $attribute_product->save();
                                }

                                array_push($ids_product_attribute_values, $attribute_product->id);
                                if (! in_array($key, $ids_attributes_color)) {
                                    if ($check_add_attribute == true) {
                                        DB::table('revisions')->insert([
                                            'revisionable_type' => "App\Models\ProductAttributeValues",
                                            'revisionable_id' => $attribute_product->id,
                                            'user_id' => Auth::user()->owner_id,
                                            'key' => 'add_attribute',
                                            'old_value' => null,
                                            'new_value' => $value_attribute,
                                            'created_at' => new DateTime,
                                            'updated_at' => new DateTime,
                                        ]);
                                    }
                                }

                            }
                        }

                        // $product->sku = $product_update->name . $sku;
                        // $product->save();

                        $new_ids_attributes = array_keys($variant['attributes']);
                        $deleted_attributes = ProductAttributeValues::where('id_products', $id)->where('is_variant', 1)->whereNotIn('id_attribute', $new_ids_attributes)->delete();

                        //Images of variant
                        $ids_images = [];
                        if (array_key_exists('photo', $variant)) {
                            $structure = public_path('upload_products');
                            if (! file_exists($structure)) {
                                mkdir(public_path('upload_products', 0777));
                            }

                            if (! file_exists(public_path('/upload_products/Product-'.$product->id))) {
                                mkdir(public_path('/upload_products/Product-'.$product->id, 0777));
                                mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                            } else {
                                if (! file_exists(public_path('/upload_products/Product-'.$product->id.'/images'))) {
                                    mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                                }
                            }

                            foreach ($variant['photo'] as $key => $image) {
                                $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                                $image->move(public_path('/upload_products/Product-'.$product->id.'/images'), $imageName);
                                $path = '/upload_products/Product-'.$product->id.'/images'.'/'.$imageName;

                                $uploaded_document = new UploadProducts;
                                $uploaded_document->id_product = $product->id;
                                $uploaded_document->path = $path;
                                $uploaded_document->extension = $image->getClientOriginalExtension();
                                $uploaded_document->type = 'images';
                                $uploaded_document->save();

                                array_push($ids_images, $uploaded_document->id);

                                DB::table('revisions')->insert([
                                    'revisionable_type' => "App\Models\UploadProducts",
                                    'revisionable_id' => $uploaded_document->id,
                                    'user_id' => Auth::user()->owner_id,
                                    'key' => 'add_image',
                                    'old_value' => null,
                                    'new_value' => $uploaded_document->id,
                                    'created_at' => new DateTime,
                                    'updated_at' => new DateTime,
                                ]);
                            }
                        }

                        //Pricing configuration of variant
                        if (array_key_exists('pricing', $variant)) {
                            $all_data_to_insert = [];

                            foreach ($variant['pricing']['from'] as $key => $from) {
                                $current_data = [];
                                if (($from != null) && ($variant['pricing']['to'][$key] != null) && ($variant['pricing']['unit_price'][$key] != null)) {
                                    if (isset($variant['pricing']['date_range_pricing'])) {
                                        if (($variant['pricing']['date_range_pricing'] != null)) {
                                            if (($variant['pricing']['date_range_pricing'][$key]) && ($variant['pricing']['discount_type'][$key])) {
                                                $date_var = explode(' to ', $variant['pricing']['date_range_pricing'][$key]);
                                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                                $start_to_parse = explode(' ', $date_var[0]);
                                                $end_to_parse = explode(' ', $date_var[1]);

                                                $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                                $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                                $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                                $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                                if (($check_start == true) && ($check_end == true)) {
                                                    $current_data['discount_start_datetime'] = $discount_start_date;
                                                    $current_data['discount_end_datetime'] = $discount_end_date;
                                                    $current_data['discount_type'] = $variant['pricing']['discount_type'][$key];
                                                } else {
                                                    $current_data['discount_start_datetime'] = null;
                                                    $current_data['discount_end_datetime'] = null;
                                                    $current_data['discount_type'] = null;
                                                    $current_data['discount_amount'] = null;
                                                    $current_data['discount_percentage'] = null;
                                                }
                                            } else {
                                                $current_data['discount_start_datetime'] = null;
                                                $current_data['discount_end_datetime'] = null;
                                                $current_data['discount_type'] = null;
                                                $current_data['discount_amount'] = null;
                                                $current_data['discount_percentage'] = null;
                                            }
                                        } else {
                                            $current_data['discount_start_datetime'] = null;
                                            $current_data['discount_end_datetime'] = null;
                                            $current_data['discount_type'] = null;
                                            $current_data['discount_amount'] = null;
                                            $current_data['discount_percentage'] = null;
                                        }
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                    $current_data['id_products'] = $product->id;
                                    $current_data['from'] = $from;
                                    $current_data['to'] = $variant['pricing']['to'][$key];
                                    $current_data['unit_price'] = $variant['pricing']['unit_price'][$key];

                                    if (isset($variant['pricing']['discount_amount']) && ($variant['pricing']['date_range_pricing'][$key] != null)) {
                                        $current_data['discount_amount'] = $variant['pricing']['discount_amount'][$key];
                                    } else {
                                        $current_data['discount_amount'] = null;
                                    }
                                    if (isset($variant['pricing']['discount_percentage']) && ($variant['pricing']['date_range_pricing'][$key] != null)) {
                                        $current_data['discount_percentage'] = $variant['pricing']['discount_percentage'][$key];
                                    } else {
                                        $current_data['discount_percentage'] = null;
                                    }

                                    array_push($all_data_to_insert, $current_data);
                                }
                            }

                            PricingConfiguration::where('id_products', $product->id)->delete();
                            PricingConfiguration::insert($all_data_to_insert);
                        } else {
                            //get pricing by default
                            $all_data_to_insert = [];

                            foreach ($pricing['from'] as $key => $from) {
                                $current_data = [];
                                if (($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)) {
                                    if (isset($pricing['date_range_pricing'])) {
                                        if (($pricing['date_range_pricing'] != null)) {
                                            if (($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])) {
                                                $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                                $start_to_parse = explode(' ', $date_var[0]);
                                                $end_to_parse = explode(' ', $date_var[1]);

                                                $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                                $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                                $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                                $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                                if (($check_start == true) && ($check_end == true)) {
                                                    $current_data['discount_start_datetime'] = $discount_start_date;
                                                    $current_data['discount_end_datetime'] = $discount_end_date;
                                                    $current_data['discount_type'] = $pricing['discount_type'][$key];
                                                } else {
                                                    $current_data['discount_start_datetime'] = null;
                                                    $current_data['discount_end_datetime'] = null;
                                                    $current_data['discount_type'] = null;
                                                    $current_data['discount_amount'] = null;
                                                    $current_data['discount_percentage'] = null;
                                                }
                                            }
                                        } else {
                                            $current_data['discount_start_datetime'] = null;
                                            $current_data['discount_end_datetime'] = null;
                                            $current_data['discount_type'] = null;
                                            $current_data['discount_amount'] = null;
                                            $current_data['discount_percentage'] = null;
                                        }
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }

                                    $current_data['id_products'] = $product->id;
                                    $current_data['from'] = $from;
                                    $current_data['to'] = $pricing['to'][$key];
                                    $current_data['unit_price'] = $pricing['unit_price'][$key];

                                    if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                                        $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                                    } else {
                                        $current_data['discount_amount'] = null;
                                    }
                                    if (isset($pricing['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                                        $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                                    } else {
                                        $current_data['discount_percentage'] = null;
                                    }
                                    array_push($all_data_to_insert, $current_data);
                                }
                            }

                            PricingConfiguration::insert($all_data_to_insert);
                        }

                        $shipping_to_delete = Shipping::where('product_id', $product->id)->delete();
                        $shipping_details = [];

                        if (array_key_exists('shipping_details', $variant)) {
                            if (count($variant['shipping_details']) > 0) {
                                foreach ($variant['shipping_details']['from_shipping'] as $key => $from) {
                                    if (($from != null) && ($variant['shipping_details']['to_shipping'][$key] != null) && ($variant['shipping_details']['shipper'][$key] != null) && ($variant['shipping_details']['estimated_order'][$key] != null)) {
                                        $current_shipping = [];

                                        if (is_array($variant['shipping_details']['shipper'][$key])) {
                                            $shippers = implode(',', $variant['shipping_details']['shipper'][$key]);
                                            $current_shipping['shipper'] = $shippers;
                                        } else {
                                            $current_shipping['shipper'] = $variant['shipping_details']['shipper'][$key];
                                        }

                                        $current_shipping['from_shipping'] = $from;
                                        $current_shipping['to_shipping'] = $variant['shipping_details']['to_shipping'][$key];
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

                        $historique_children = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_id', $product->id)->where('revisionable_type', 'App\Models\Product')->get();
                        $historique_image = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $ids_images)->where('revisionable_type', 'App\Models\UploadProducts')->get();
                        $historique_attributes = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $new_ids_attributes)->where('revisionable_type', 'App\Models\ProductAttributeValues')->get();
                        if (($product->product_added_from_catalog == 1) && (count($historique) == 0) && (count($historique_children) == 0) && (count($historique_attributes) == 0) && (count($historique_image) == 0)) {
                            if ($product_update->hasUnapprovedChildren()) {
                                // Update the approved field in the parent product
                                $product_update->update(['approved' => 0]);
                                $product_update->children()->update(['approved' => 0]);
                            } else {
                                $product->approved = 1;
                                $product->save();
                            }
                        } else {
                            // Update the approved field in the parent product
                            $product_update->update(['approved' => 0, 'published' => $collection['last_version']]);

                            // Update the approved field in the children related to the parent
                            $product_update->children()->update(['approved' => 0, 'published' => $collection['last_version']]);
                        }
                    }

                }
            }

            if (count($general_attributes_data) > 0) {
                $ids_general = [];
                foreach ($general_attributes_data as $attr => $value) {
                    if ($value != null) {
                        if (in_array($attr, $ids_attributes_color)) {
                            ProductAttributeValues::where('id_products', $product_update->id)->where('id_attribute', $attr)->whereNotIn('value', $value)->delete();
                        } else {
                            $attribute_product = ProductAttributeValues::where('id_products', $product_update->id)->where('id_attribute', $attr)->first();
                        }

                        if (in_array($attr, $ids_attributes_list)) {
                            $check_add = false;
                            if ($attribute_product == null) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_update->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;

                                $check_add = true;
                            }
                            $value_attribute = AttributeValue::find($value);
                            $attribute_product->id_values = $value;
                            $attribute_product->value = $value_attribute->value;
                            $attribute_product->save();
                        } elseif (in_array($attr, $ids_attributes_color)) {
                            if (count($value) > 0) {
                                foreach ($value as $value_color) {
                                    $attribute_product = ProductAttributeValues::where('id_products', $product_update->id)->where('id_attribute', $attr)->where('value', $value_color)->first();
                                    $check_add = false;
                                    if ($attribute_product == null) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $product_update->id;
                                        $attribute_product->id_attribute = $attr;
                                        $attribute_product->is_general = 1;
                                        $check_add = true;
                                    }
                                    $color = Color::where('code', $value_color)->first();
                                    $attribute_product->id_colors = $color->id;
                                    $attribute_product->value = $color->code;
                                    $attribute_product->save();

                                    if ($check_add == true) {
                                        DB::table('revisions')->insert([
                                            'revisionable_type' => "App\Models\ProductAttributeValues",
                                            'revisionable_id' => $attribute_product->id,
                                            'user_id' => Auth::user()->owner_id,
                                            'key' => 'add_attribute',
                                            'old_value' => null,
                                            'new_value' => $value_color,
                                            'created_at' => new DateTime,
                                            'updated_at' => new DateTime,
                                        ]);
                                    }
                                }
                            }
                        } elseif (in_array($attr, $ids_attributes_numeric)) {
                            $check_add = false;
                            if ($attribute_product == null) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_update->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;

                                $check_add = true;
                            }
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                            $attribute_product->save();
                        } else {
                            $check_add = false;
                            if ($attribute_product == null) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_update->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;

                                $check_add = true;
                            }
                            $attribute_product->value = $value;
                            $attribute_product->save();
                        }

                        array_push($ids_general, $attribute_product->id);

                        if (! in_array($attr, $ids_attributes_color)) {
                            if ($check_add == true) {
                                DB::table('revisions')->insert([
                                    'revisionable_type' => "App\Models\ProductAttributeValues",
                                    'revisionable_id' => $attribute_product->id,
                                    'user_id' => Auth::user()->owner_id,
                                    'key' => 'add_attribute',
                                    'old_value' => null,
                                    'new_value' => $value,
                                    'created_at' => new DateTime,
                                    'updated_at' => new DateTime,
                                ]);
                            }
                        }
                    }
                }

                $historique_attributes = DB::table('revisions')->whereNull('deleted_at')->whereIn('revisionable_id', $ids_general)->where('revisionable_type', 'App\Models\ProductAttributeValues')->get();
                if (($product_update->product_added_from_catalog == 1) && (count($historique) == 0) && (count($historique_attributes) == 0)) {
                    if ($product_update->hasUnapprovedChildren()) {
                        // Update the approved field in the parent product
                        $product_update->update(['approved' => 0]);
                    } else {
                        // Update the approved field in the parent product
                        $product_update->update(['approved' => 1]);
                    }
                } else {
                    // Update the approved field in the parent product
                    $product_update->update(['approved' => 0]);

                    // Update the approved field in the children related to the parent
                    $product_update->children()->update(['approved' => 0]);
                }
            }

            $new_ids_attributes_general = array_keys($general_attributes_data);
            $deleted_attributes_general = ProductAttributeValues::where('id_products', $product_update->id)->where('is_general', 1)->whereNotIn('id_attribute', $new_ids_attributes_general)->delete();

            if (count($variants_new_data)) {
                foreach ($variants_new_data as $id => $variant) {
                    if (! array_key_exists('shipping', $variant)) {
                        $collection['shipping'] = 0;
                    } else {
                        $collection['shipping'] = $variant['shipping'];
                    }
                    $collection['low_stock_quantity'] = $variant['stock'];
                    if (array_key_exists('sku', $variant)) {
                        $collection['sku'] = $variant['sku'];
                    } else {
                        $collection['sku'] = '';
                    }

                    if (isset($variant['variant_shipper_sample'])) {
                        $data['shipper_sample'] = $variant['variant_shipper_sample'];
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

                    if (! isset($variant['sample_price'])) {
                        $collection['vat_sample'] = $vat_user->vat_registered;
                        $collection['sample_description'] = $data_sample['sample_description'];
                        $collection['sample_price'] = $data_sample['sample_price'];
                    } else {
                        $collection['vat_sample'] = $vat_user->vat_registered;
                        $collection['sample_description'] = $variant['sample_description'];
                        $collection['sample_price'] = $variant['sample_price'];
                    }

                    $slug = Str::slug($collection['name']);

                    $same_slug_count = Product::where('slug', 'LIKE', $slug.'%')->count();
                    $slug_suffix = $same_slug_count > 1 ? '-'.$same_slug_count + 1 : '';
                    $slug .= $slug_suffix;

                    $randomString = Str::random(5);
                    $collection['slug'] = $collection['slug'].'-'.$randomString;

                    $new_product = Product::create($collection);

                    //attributes of variant
                    foreach ($variant['attributes'] as $key => $value_attribute) {
                        if ($value_attribute != null) {

                            if (in_array($key, $ids_attributes_list)) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $new_product->id;
                                $attribute_product->id_attribute = $key;
                                $attribute_product->is_variant = 1;
                                $value = AttributeValue::find($value_attribute);
                                $attribute_product->id_values = $value_attribute;
                                $attribute_product->value = $value->value;
                                $attribute_product->save();
                            } elseif (in_array($key, $ids_attributes_color)) {
                                if (count($value_attribute) > 0) {
                                    foreach ($value_attribute as $value_color) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $new_product->id;
                                        $attribute_product->id_attribute = $key;
                                        $attribute_product->is_variant = 1;
                                        $value = Color::where('code', $value_color)->first();
                                        $attribute_product->id_colors = $value->id;
                                        $attribute_product->value = $value->code;
                                        $attribute_product->save();
                                    }
                                }
                            } elseif (in_array($key, $ids_attributes_numeric)) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $new_product->id;
                                $attribute_product->id_attribute = $key;
                                $attribute_product->is_variant = 1;
                                $attribute_product->id_units = $variant['units'][$key];
                                $attribute_product->value = $value_attribute;
                                $attribute_product->save();
                            } else {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $new_product->id;
                                $attribute_product->id_attribute = $key;
                                $attribute_product->is_variant = 1;
                                $attribute_product->value = $value_attribute;
                                $attribute_product->save();
                            }
                        }
                    }

                    //Images of variant
                    if (array_key_exists('photo', $variant)) {
                        $structure = public_path('upload_products');
                        if (! file_exists($structure)) {
                            mkdir(public_path('upload_products', 0777));
                        }

                        if (! file_exists(public_path('/upload_products/Product-'.$new_product->id))) {
                            mkdir(public_path('/upload_products/Product-'.$new_product->id, 0777));
                            mkdir(public_path('/upload_products/Product-'.$new_product->id.'/images', 0777));
                        } else {
                            if (! file_exists(public_path('/upload_products/Product-'.$new_product->id.'/images'))) {
                                mkdir(public_path('/upload_products/Product-'.$new_product->id.'/images', 0777));
                            }
                        }

                        foreach ($variant['photo'] as $key => $image) {
                            $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                            $image->move(public_path('/upload_products/Product-'.$new_product->id.'/images'), $imageName);
                            $path = '/upload_products/Product-'.$new_product->id.'/images'.'/'.$imageName;

                            $uploaded_document = new UploadProducts;
                            $uploaded_document->id_product = $new_product->id;
                            $uploaded_document->path = $path;
                            $uploaded_document->extension = $image->getClientOriginalExtension();
                            $uploaded_document->type = 'images';
                            $uploaded_document->save();

                            if ($check_add == true) {
                                DB::table('revisions')->insert([
                                    'revisionable_type' => "App\Models\UploadProducts",
                                    'revisionable_id' => $uploaded_document->id,
                                    'user_id' => Auth::user()->owner_id,
                                    'key' => 'add_image',
                                    'old_value' => null,
                                    'new_value' => $uploaded_document->id,
                                    'created_at' => new DateTime,
                                    'updated_at' => new DateTime,
                                ]);
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
                                            $date_var = explode(' to ', $variant['pricing']['discount_range'][$key]);
                                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                            $start_to_parse = explode(' ', $date_var[0]);
                                            $end_to_parse = explode(' ', $date_var[1]);

                                            $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                            $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                            $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                            $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                            if (($check_start == true) && ($check_end == true)) {
                                                $current_data['discount_start_datetime'] = $discount_start_date;
                                                $current_data['discount_end_datetime'] = $discount_end_date;
                                                $current_data['discount_type'] = $variant['pricing']['discount_type'][$key];
                                            } else {
                                                $current_data['discount_start_datetime'] = null;
                                                $current_data['discount_end_datetime'] = null;
                                                $current_data['discount_type'] = null;
                                                $current_data['discount_amount'] = null;
                                                $current_data['discount_percentage'] = null;
                                            }
                                        } else {
                                            $current_data['discount_start_datetime'] = null;
                                            $current_data['discount_end_datetime'] = null;
                                            $current_data['discount_type'] = null;
                                            $current_data['discount_amount'] = null;
                                            $current_data['discount_percentage'] = null;
                                        }
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }

                                $current_data['id_products'] = $new_product->id;
                                $current_data['from'] = $from;
                                $current_data['to'] = $variant['pricing']['to'][$key];
                                $current_data['unit_price'] = $variant['pricing']['unit_price'][$key];

                                if (isset($variant['pricing']['discount_amount']) && ($variant['pricing']['discount_range'][$key] != null)) {
                                    $current_data['discount_amount'] = $variant['pricing']['discount_amount'][$key];
                                } else {
                                    $current_data['discount_amount'] = null;
                                }
                                if (isset($variant['pricing']['discount_percentage']) && ($variant['pricing']['discount_range'][$key] != null)) {
                                    $current_data['discount_percentage'] = $variant['pricing']['discount_percentage'][$key];
                                } else {
                                    $current_data['discount_percentage'] = null;
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
                                        $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                        $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                        $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                        $start_to_parse = explode(' ', $date_var[0]);
                                        $end_to_parse = explode(' ', $date_var[1]);

                                        $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                        $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                        $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                        $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                        if (($check_start == true) && ($check_end == true)) {
                                            $current_data['discount_start_datetime'] = $discount_start_date;
                                            $current_data['discount_end_datetime'] = $discount_end_date;
                                            $current_data['discount_type'] = $pricing['discount_type'][$key];
                                        } else {
                                            $current_data['discount_start_datetime'] = null;
                                            $current_data['discount_end_datetime'] = null;
                                            $current_data['discount_type'] = null;
                                            $current_data['discount_amount'] = null;
                                            $current_data['discount_percentage'] = null;
                                        }
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }

                                $current_data['id_products'] = $new_product->id;
                                $current_data['from'] = $from;
                                $current_data['to'] = $pricing['to'][$key];
                                $current_data['unit_price'] = $pricing['unit_price'][$key];

                                if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                                    $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                                } else {
                                    $current_data['discount_amount'] = null;
                                }
                                if (isset($pricing['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                                    $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                                } else {
                                    $current_data['discount_percentage'] = null;
                                }

                                array_push($all_data_to_insert, $current_data);
                            }

                        }

                        PricingConfiguration::insert($all_data_to_insert);
                    }

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
                                $current_shipping['product_id'] = $new_product->id;
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

                            $id = $new_product->id;
                            $keyToPush = 'product_id';
                            $shipping = array_map(function ($arr) use ($id, $keyToPush) {
                                $arr[$keyToPush] = $id;

                                return $arr;
                            }, $shipping);

                            Shipping::insert($shipping);
                        }
                    }
                }

                // Update the approved field in the parent product
                $product_update->update(['approved' => 0]);

                // Update the approved field in the children related to the parent
                $product_update->children()->update(['approved' => 0]);

            }

            return $product_update;
        }
    }

    public function product_duplicate_store($product)
    {
        $product_new = $product->replicate();
        $product_new->slug = $product_new->slug.'-'.Str::random(5);
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

        $collection['user_id'] = Auth::user()->owner_id;
        $collection['approved'] = 0;
        $collection['rejection_reason'] = null;
        $vat_user = BusinessInformation::where('user_id', Auth::user()->owner_id)->first();

        $slug = Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug.'%')->count();
        $slug_suffix = $same_slug_count > 1 ? '-'.$same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $collection['slug'] = $slug;

        if (isset($collection['refundable'])) {
            $collection['refundable'] = 1;
        } else {
            $collection['refundable'] = 0;
        }

        if (isset($collection['published_modal'])) {
            $collection['published'] = 1;
        } else {
            $collection['published'] = 0;
        }

        if (! isset($collection['country_code'])) {
            $collection['country_code'] = '';
        }

        if (isset($collection['create_stock'])) {
            $collection['stock_after_create'] = 1;
        } else {
            $collection['stock_after_create'] = 0;
        }

        if (isset($collection['activate_third_party'])) {
            $collection['activate_third_party'] = 1;
        }

        if ($collection['parent_id'] != null) {
            $collection['category_id'] = $collection['parent_id'];
        }

        unset($collection['parent_id']);

        $is_draft = 0;

        if (isset($collection['button'])) {
            if ($collection['button'] == 'draft') {
                $is_draft = 1;
            }
            unset($collection['button']);
        }

        if (isset($collection['product_sk'])) {
            $collection['sku'] = $collection['product_sk'];
            unset($collection['product_sk']);
        } else {
            $collection['sku'] = $collection['name'];
        }

        if (isset($collection['quantite_stock_warning'])) {
            $collection['low_stock_quantity'] = $collection['quantite_stock_warning'];
            unset($collection['quantite_stock_warning']);
        } else {
            $collection['low_stock_quantity'] = null;
        }

        $collection['is_draft'] = $is_draft;

        $pricing = [];

        if ((isset($collection['from'])) && (isset($collection['to'])) && (isset($collection['unit_price']))) {
            $pricing = [
                'from' => $collection['from'],
                'to' => $collection['to'],
                'unit_price' => $collection['unit_price'],
            ];

            if (isset($collection['discount_type'])) {
                $pricing['discount_type'] = $collection['discount_type'];
            }

            if (isset($collection['date_range_pricing'])) {
                $pricing['date_range_pricing'] = $collection['date_range_pricing'];
            }

            if (isset($collection['discount_amount'])) {
                $pricing['discount_amount'] = $collection['discount_amount'];
            }

            if (isset($collection['discount_percentage'])) {
                $pricing['discount_percentage'] = $collection['discount_percentage'];
            }

            unset($collection['from']);
            unset($collection['to']);
            unset($collection['unit_price']);
            unset($collection['discount_amount']);
            unset($collection['discount_type']);
            unset($collection['discount_percentage']);
        }

        $tags = [];

        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }

        $collection['tags'] = implode(',', $tags);

        if (isset($collection['stock_visibility_state'])) {
            $collection['stock_visibility_state'] = 'quantity';
        } else {
            $collection['stock_visibility_state'] = 'hide';
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
            $shipping_sample_parent['shipper_sample'] = null;
        }

        if (isset($collection['estimated_sample'])) {
            $shipping_sample_parent['estimated_sample'] = $collection['estimated_sample'];
        } else {
            $shipping_sample_parent['estimated_sample'] = null;
        }

        if (isset($collection['estimated_shipping_sample'])) {
            $shipping_sample_parent['estimated_shipping_sample'] = $collection['estimated_shipping_sample'];
        } else {
            $shipping_sample_parent['estimated_shipping_sample'] = null;
        }

        if (isset($collection['paid_sample'])) {
            $shipping_sample_parent['paid_sample'] = $collection['paid_sample'];
        } else {
            $shipping_sample_parent['paid_sample'] = null;
        }

        if (isset($collection['shipping_amount'])) {
            $shipping_sample_parent['shipping_amount'] = $collection['shipping_amount'];
        } else {
            $shipping_sample_parent['shipping_amount'] = null;
        }

        $variants_data = [];
        $variants_new_data = [];
        $general_attributes_data = [];
        $unit_general_attributes_data = [];

        //check if product has old variants
        if (array_key_exists('variant', $data)) {
            foreach ($collection['variant']['sku'] as $key => $sku) {
                if (! array_key_exists($key, $variants_data)) {
                    $variants_data[$key] = [];
                }

                $variants_data[$key]['sku'] = $sku;

                //Check if the variant has pictures
                if (array_key_exists('photo', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['photo'])) {
                        $variants_data[$key]['photo'] = $data['variant']['photo'][$key];
                    } else {
                        $variants_data[$key]['photo'] = [];
                    }
                } else {
                    $variants_data[$key]['photo'] = [];
                }

                //check if the variant has pricing configuration
                if (array_key_exists('from', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['from'])) {
                        $pricing_variant = [];
                        $pricing_variant['from'] = $data['variant']['from'][$key];
                        $pricing_variant['to'] = $data['variant']['to'][$key];
                        $pricing_variant['unit_price'] = $data['variant']['unit_price'][$key];
                        $pricing_variant['date_range_pricing'] = $data['variant']['date_range_pricing'][$key];
                        $pricing_variant['discount_type'] = $data['variant']['discount_type'][$key];
                        $pricing_variant['discount_amount'] = $data['variant']['discount_amount'][$key];
                        $pricing_variant['discount_percentage'] = $data['variant']['discount_percentage'][$key];
                        $variants_data[$key]['pricing'] = $pricing_variant;
                    } else {
                        $variants_data[$key]['pricing'] = $pricing;
                    }
                } else {
                    $variants_data[$key]['pricing'] = $pricing;
                }

                if (array_key_exists('from_shipping', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['from_shipping'])) {
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
                    } else {
                        $shipping_parent = [];
                        if ((isset($collection['from_shipping'])) && (isset($collection['to_shipping'])) && (isset($collection['shipper'])) && (isset($collection['estimated_order']))) {
                            if ($collection['from_shipping'][0] && ($collection['to_shipping'][0] != null) && ($collection['shipper'][0] != null) && ($collection['estimated_order'][0] != null)) {
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
                        }
                        $variants_data[$key]['shipping_details'] = $shipping_parent;
                    }
                } else {
                    $shipping_parent = [];
                    if ((isset($collection['from_shipping'])) && (isset($collection['to_shipping'])) && (isset($collection['shipper'])) && (isset($collection['estimated_order']))) {
                        if ($collection['from_shipping'][0] && ($collection['to_shipping'][0] != null) && ($collection['shipper'][0] != null) && ($collection['estimated_order'][0] != null)) {
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
                    }
                    $variants_data[$key]['shipping_details'] = $shipping_parent;
                }

                if (array_key_exists('sample_available', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['sample_available'])) {
                        $variants_data[$key]['sample_available'] = 1;
                    } else {
                        $variants_data[$key]['sample_available'] = 0;
                    }
                } else {
                    $variants_data[$key]['sample_available'] = 0;
                }

                if (array_key_exists('shipper_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['shipper_sample'])) {
                        $variants_data[$key]['shipper_sample'] = $data['variant']['shipper_sample'][$key];
                    } else {
                        $variants_data[$key]['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                    }
                } else {
                    $variants_data[$key]['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                }

                if (array_key_exists('estimated_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['estimated_sample'])) {
                        $variants_data[$key]['estimated_sample'] = $data['variant']['estimated_sample'][$key];
                    } else {
                        $variants_data[$key]['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                    }
                } else {
                    $variants_data[$key]['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                }

                if (array_key_exists('estimated_shipping_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['estimated_shipping_sample'])) {
                        $variants_data[$key]['estimated_shipping_sample'] = $data['variant']['estimated_shipping_sample'][$key];
                    } else {
                        $variants_data[$key]['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                    }
                } else {
                    $variants_data[$key]['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                }

                if (array_key_exists('paid_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['paid_sample'])) {
                        $variants_data[$key]['paid_sample'] = $data['variant']['paid_sample'][$key];
                    } else {
                        $variants_data[$key]['paid_sample'] = 0;
                    }
                } else {
                    $variants_data[$key]['paid_sample'] = 0;
                }

                if (array_key_exists('shipping_amount', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['shipping_amount'])) {
                        $variants_data[$key]['shipping_amount'] = $data['variant']['shipping_amount'][$key];
                    } else {
                        $variants_data[$key]['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                    }
                } else {
                    $variants_data[$key]['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                }

                //check if the variant has sample pricing
                if (array_key_exists('sample_pricing', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['sample_pricing'])) {
                        $variants_data[$key]['sample_pricing'] = 0;
                        $variants_data[$key]['sample_description'] = $data['sample_description'];
                        $variants_data[$key]['sample_price'] = $data['sample_price'];
                    } else {
                        $variants_data[$key]['sample_pricing'] = 1;
                        $variants_data[$key]['sample_description'] = $data['variant']['sample_description'][$key];
                        $variants_data[$key]['sample_price'] = $data['variant']['sample_price'][$key];
                    }
                } else {
                    $variants_data[$key]['sample_pricing'] = 0;
                    $variants_data[$key]['sample_description'] = $data['sample_description'];
                    $variants_data[$key]['sample_price'] = $data['sample_price'];
                }

                //check if the variant activated the shipping configuration
                if (array_key_exists('shipping', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['shipping'])) {
                        $variants_data[$key]['shipping'] = $data['variant']['shipping'][$key];
                    } else {
                        $variants_data[$key]['shipping'] = 0;
                    }
                } else {
                    $variants_data[$key]['shipping'] = 0;
                }

                //check if the variant is published
                if (array_key_exists('published', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['published'])) {
                        $variants_data[$key]['published'] = $data['variant']['published'][$key];
                    } else {
                        $variants_data[$key]['published'] = 0;
                    }
                } else {
                    $variants_data[$key]['published'] = 0;
                }

                //check if the variant activated the sample shipping configuration
                if (array_key_exists('sample_shipping', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['sample_shipping'])) {
                        $variants_data[$key]['sample_shipping'] = $data['variant']['sample_shipping'][$key];
                    } else {
                        $variants_data[$key]['sample_shipping'] = 0;
                    }
                } else {
                    $variants_data[$key]['sample_shipping'] = 0;
                }

                //check if the variant activated vat option for sample
                if (array_key_exists('vat_sample', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['vat_sample'])) {
                        $variants_data[$key]['vat_sample'] = $data['variant']['vat_sample'][$key];
                    } else {
                        $variants_data[$key]['vat_sample'] = 0;
                    }
                } else {
                    $variants_data[$key]['vat_sample'] = 0;
                }

                //check if the variant has low stock quantity
                if (array_key_exists('low_stock_quantity', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['low_stock_quantity'])) {
                        $variants_data[$key]['low_stock_quantity'] = $data['variant']['low_stock_quantity'][$key];
                    } else {
                        $variants_data[$key]['low_stock_quantity'] = 0;
                    }
                } else {
                    $variants_data[$key]['low_stock_quantity'] = 0;
                }

                //Check if the variant has attributes. If it does, a table will be generated containing all attributes, with each attribute having its own value.
                if (array_key_exists('attributes', $data['variant'])) {
                    if (array_key_exists($key, $data['variant']['attributes'])) {
                        foreach ($data['variant']['attributes'][$key] as $id_attribute => $attribute) {
                            if (! array_key_exists('attributes', $variants_data[$key])) {
                                $variants_data[$key]['attributes'][$id_attribute] = $attribute;
                            } else {
                                if (! array_key_exists($id_attribute, $variants_data[$key]['attributes'])) {
                                    $variants_data[$key]['attributes'][$id_attribute] = $attribute;
                                }
                            }
                        }
                    } else {
                        $variants_data[$key]['attributes'] = [];
                    }
                } else {
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
                if (! array_key_exists($ids[2], $variants_new_data)) {
                    $variants_new_data[$ids[2]] = [];
                }
                if (! array_key_exists('attributes', $variants_new_data[$ids[2]])) {
                    $variants_new_data[$ids[2]]['attributes'][$ids[1]] = $value;
                } else {
                    if (! array_key_exists($ids[1], $variants_new_data[$ids[2]]['attributes'])) {
                        $variants_new_data[$ids[2]]['attributes'][$ids[1]] = $value;
                    }
                }

                //check if the variant activated the variant pricing
                $key_pricing = 'variant-pricing-'.$ids[2];
                if (! isset($data[$key_pricing])) {
                    if (! array_key_exists($ids[2], $variants_new_data)) {
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['pricing'] = $data['variant_pricing-from'.$ids[2]];
                }

                $key_shipping = 'variant_shipping-'.$ids[2];
                if (isset($data[$key_shipping])) {
                    if (! array_key_exists($ids[2], $variants_new_data)) {
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['shipping_details'] = $data['variant_shipping-'.$ids[2]];
                }

                $key_sample_available = 'variant-sample-available'.$ids[2];
                if (isset($data[$key_sample_available])) {
                    if (! array_key_exists($ids[2], $variants_new_data)) {
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['sample_available'] = 1;
                } else {
                    if (! array_key_exists($ids[2], $variants_new_data)) {
                        $variants_new_data[$ids[2]] = [];
                    }

                    $variants_new_data[$ids[2]]['sample_available'] = 0;
                }
            }

            if (strpos($key, 'variant-published-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[2], $variants_data)) {
                    $variants_data[$ids[2]] = [];
                }

                $variants_data[$ids[2]]['published'] = $value;
            }

            if (strpos($key, 'sku') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['sku'] = $value;
            }

            if (strpos($key, 'stock-warning-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[2], $variants_new_data)) {
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['stock'] = $value;
            }

            if (strpos($key, 'variant-shipping-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[2], $variants_new_data)) {
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['shipping'] = 1;
            }

            if (strpos($key, 'photos_variant') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['photo'] = $value;
            }

            if (strpos($key, 'attributes_units') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[2], $variants_new_data)) {
                    $variants_new_data[$ids[2]] = [];
                }

                $variants_new_data[$ids[2]]['units'][$ids[1]] = $value;
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
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }

                $variants_new_data[$ids[1]]['vat_sample'] = $value;
            }

            if (strpos($key, 'sample_description-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }

                if ($value != null) {
                    $variants_new_data[$ids[1]]['sample_description'] = $value;
                }
            }

            if (strpos($key, 'sample_price-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_new_data)) {
                    $variants_new_data[$ids[1]] = [];
                }
                if ($value != null) {
                    $variants_new_data[$ids[1]]['sample_price'] = $value;
                }

            }

            if (strpos($key, 'estimated_sample-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_sample'] = $value;
            }

            if (strpos($key, 'estimated_shipping_sample-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['estimated_shipping_sample'] = $value;
            }

            if (strpos($key, 'shipping_amount-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['shipping_amount'] = $value;
            }

            if (strpos($key, 'variant_shipper_sample-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['variant_shipper_sample'] = $value;
            }

            if (strpos($key, 'paid_sample-') === 0) {
                $ids = explode('-', $key);
                if (! array_key_exists($ids[1], $variants_data)) {
                    $variants_data[$ids[1]] = [];
                }

                $variants_data[$ids[1]]['paid_sample'] = $value;
            }
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

        $collection['vat'] = $vat_user->vat_registered;

        $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();
        $ids_attributes_list = Attribute::where('type_value', 'list')->pluck('id')->toArray();
        $ids_attributes_numeric = Attribute::where('type_value', 'numeric')->pluck('id')->toArray();

        if (! isset($data['activate_attributes'])) {
            //Create product without variants
            $collection = $collection->toArray();
            unset($collection["unit_price"]);
            $collection["unit_price"] = $collection["unit_sale_price"];
            $product_draft->update($collection);
            $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();

            if (count($pricing) > 0) {
                $all_data_to_insert = [];

                foreach ($pricing['from'] as $key => $from) {
                    $current_data = [];
                    if (($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)) {
                        if (isset($pricing['date_range_pricing'])) {
                            if ($pricing['date_range_pricing'] != null) {
                                if ($pricing['date_range_pricing'][$key] != null) {
                                    $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                    $start_to_parse = explode(' ', $date_var[0]);
                                    $end_to_parse = explode(' ', $date_var[1]);

                                    $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                    $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                    $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                    $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                    if (($check_start == true) && ($check_end == true)) {
                                        $current_data['discount_start_datetime'] = $discount_start_date;
                                        $current_data['discount_end_datetime'] = $discount_end_date;
                                        $current_data['discount_type'] = $pricing['discount_type'][$key];
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }

                            } else {
                                $current_data['discount_start_datetime'] = null;
                                $current_data['discount_end_datetime'] = null;
                                $current_data['discount_type'] = null;
                                $current_data['discount_amount'] = null;
                                $current_data['discount_percentage'] = null;
                            }
                        } else {
                            $current_data['discount_start_datetime'] = null;
                            $current_data['discount_end_datetime'] = null;
                            $current_data['discount_type'] = null;
                            $current_data['discount_amount'] = null;
                            $current_data['discount_percentage'] = null;
                        }

                        $current_data['id_products'] = $product_draft->id;
                        $current_data['from'] = $from;
                        $current_data['to'] = $pricing['to'][$key];
                        $current_data['unit_price'] = $pricing['unit_price'][$key];

                        if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                        } else {
                            $current_data['discount_amount'] = null;
                        }
                        if (isset($pricing['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                        } else {
                            $current_data['discount_percentage'] = null;
                        }

                        array_push($all_data_to_insert, $current_data);
                    }
                }

                PricingConfiguration::where('id_products', $product_draft->id)->delete();
                PricingConfiguration::insert($all_data_to_insert);
            }

            if (count($general_attributes_data) > 0) {
                foreach ($general_attributes_data as $attr => $value) {
                    if ($value != null) {
                        if (in_array($attr, $ids_attributes_color)) {
                            ProductAttributeValues::where('id_products', $product_draft->id)->where('id_attribute', $attr)->whereNotIn('value', $value)->delete();
                        } else {
                            $attribute_product = ProductAttributeValues::where('id_products', $product_draft->id)->where('id_attribute', $attr)->first();
                        }

                        if (in_array($attr, $ids_attributes_list)) {
                            if ($attribute_product == null) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_draft->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                            }
                            $value_attribute = AttributeValue::find($value);
                            $attribute_product->id_values = $value;
                            $attribute_product->value = $value_attribute->value;
                            $attribute_product->save();
                        } elseif (in_array($attr, $ids_attributes_color)) {
                            if (count($value) > 0) {
                                foreach ($value as $value_color) {
                                    $attribute_product = ProductAttributeValues::where('id_products', $product_draft->id)->where('id_attribute', $attr)->where('value', $value_color)->first();
                                    if ($attribute_product == null) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $product_draft->id;
                                        $attribute_product->id_attribute = $attr;
                                        $attribute_product->is_general = 1;
                                    }
                                    $color = Color::where('code', $value_color)->first();
                                    $attribute_product->id_colors = $color->id;
                                    $attribute_product->value = $color->code;
                                    $attribute_product->save();
                                }
                            }
                        } elseif (in_array($attr, $ids_attributes_numeric)) {
                            if ($attribute_product == null) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_draft->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                            }
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                            $attribute_product->save();
                        } else {
                            if ($attribute_product == null) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_draft->id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                            }
                            $attribute_product->value = $value;
                            $attribute_product->save();
                        }
                    }
                }
            }

            Shipping::where('product_id', $product_draft->id)->delete();

            if (count($shipping) > 0) {
                $id = $product_draft->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function ($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;

                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            $childrens = Product::where('parent_id', $product_draft->id)->pluck('id')->toArray();

            if (count($childrens) > 0) {
                Shipping::whereIn('product_id', $childrens)->delete();
                PricingConfiguration::whereIn('id_products', $childrens)->delete();
                ProductAttributeValues::whereIn('id_products', $childrens)->delete();
                UploadProducts::whereIn('id_product', $childrens)->delete();
                Product::where('parent_id', $product_draft->id)->delete();
                $product_draft->is_parent = 0;
                $product_draft->save();
            }

            return $product_draft;
        } else {
            // Create Parent Product
            $collection['is_parent'] = 1;
            $collection = $collection->toArray();
            $product_draft->update($collection);
            Shipping::where('product_id', $product_draft->id)->delete();
            if (count($shipping) > 0) {
                $id = $product_draft->id;
                $keyToPush = 'product_id';
                $shipping = array_map(function ($arr) use ($id, $keyToPush) {
                    $arr[$keyToPush] = $id;

                    return $arr;
                }, $shipping);
                Shipping::insert($shipping);
            }

            if (count($pricing) > 0) {
                $all_data_to_insert = [];

                foreach ($pricing['from'] as $key => $from) {
                    $current_data = [];
                    if (($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)) {
                        if (isset($pricing['date_range_pricing'])) {
                            if ($pricing['date_range_pricing'] != null) {
                                if ($pricing['date_range_pricing'][$key] != null) {
                                    $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                    $start_to_parse = explode(' ', $date_var[0]);
                                    $end_to_parse = explode(' ', $date_var[1]);

                                    $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                    $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                    $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                    $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                    if (($check_start == true) && ($check_end == true)) {
                                        $current_data['discount_start_datetime'] = $discount_start_date;
                                        $current_data['discount_end_datetime'] = $discount_end_date;
                                        $current_data['discount_type'] = $pricing['discount_type'][$key];
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }

                            } else {
                                $current_data['discount_start_datetime'] = null;
                                $current_data['discount_end_datetime'] = null;
                                $current_data['discount_type'] = null;
                                $current_data['discount_amount'] = null;
                                $current_data['discount_percentage'] = null;
                            }
                        } else {
                            $current_data['discount_start_datetime'] = null;
                            $current_data['discount_end_datetime'] = null;
                            $current_data['discount_type'] = null;
                            $current_data['discount_amount'] = null;
                            $current_data['discount_percentage'] = null;
                        }

                        $current_data['id_products'] = $product_draft->id;
                        $current_data['from'] = $from;
                        $current_data['to'] = $pricing['to'][$key];
                        $current_data['unit_price'] = $pricing['unit_price'][$key];

                        if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                        } else {
                            $current_data['discount_amount'] = null;
                        }
                        if (isset($pricing['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                        } else {
                            $current_data['discount_percentage'] = null;
                        }

                        array_push($all_data_to_insert, $current_data);
                    }
                }

                PricingConfiguration::where('id_products', $product_draft->id)->delete();
                PricingConfiguration::insert($all_data_to_insert);
            }

            unset($collection['is_parent']);
            $collection['parent_id'] = $product_draft->id;

            $data_sample = [
                'vat_sample' => $vat_user->vat_registered,
                'sample_description' => $collection['sample_description'],
                'sample_price' => $collection['sample_price'],
            ];

            unset($collection['vat_sample']);
            unset($collection['sample_description']);
            unset($collection['sample_price']);

            if (count($variants_data) > 0) {
                foreach ($variants_data as $id => $variant) {
                    $collection['low_stock_quantity'] = $variant['low_stock_quantity'];
                    $collection['sku'] = $variant['sku'];
                    $collection['vat_sample'] = $vat_user->vat_registered;
                    $collection['sample_description'] = $variant['sample_description'];
                    $collection['sample_price'] = $variant['sample_price'];
                    $collection['published'] = $variant['published'];

                    if (isset($variant['shipper_sample'])) {
                        $collection['shipper_sample'] = implode(',', $variant['shipper_sample']);
                    } else {
                        $collection['shipper_sample'] = $shipping_sample_parent['shipper_sample'];
                    }

                    if (isset($variant['estimated_sample'])) {
                        $collection['estimated_sample'] = $variant['estimated_sample'];
                    } else {
                        $collection['estimated_sample'] = $shipping_sample_parent['estimated_sample'];
                    }

                    if (isset($variant['estimated_shipping_sample'])) {
                        $collection['estimated_shipping_sample'] = $variant['estimated_shipping_sample'];
                    } else {
                        $collection['estimated_shipping_sample'] = $shipping_sample_parent['estimated_shipping_sample'];
                    }

                    if (isset($variant['paid_sample'])) {
                        $collection['paid_sample'] = $variant['paid_sample'];
                    } else {
                        $collection['paid_sample'] = $shipping_sample_parent['paid_sample'];
                    }

                    if (isset($variant['shipping_amount'])) {
                        $collection['shipping_amount'] = $variant['shipping_amount'];
                    } else {
                        $collection['shipping_amount'] = $shipping_sample_parent['shipping_amount'];
                    }

                    if (isset($variant['sample_available'])) {
                        $collection['sample_available'] = $variant['sample_available'];
                    } else {
                        $collection['sample_available'] = 0;
                    }

                    $product = Product::find($id);

                    if ($product != null) {
                        $product->update($collection);

                        //attributes of variant
                        foreach ($variant['attributes'] as $key => $value_attribute) {
                            if ($value_attribute != null) {
                                if (in_array($key, $ids_attributes_color)) {

                                    ProductAttributeValues::where('id_products', $id)->where('id_attribute', $key)->whereNotIn('value', $value_attribute)->delete();
                                } else {
                                    $attribute_product = ProductAttributeValues::where('id_products', $id)->where('id_attribute', $key)->first();
                                }

                                if (in_array($key, $ids_attributes_list)) {
                                    if ($attribute_product == null) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $product->id;
                                        $attribute_product->id_attribute = $key;
                                        $attribute_product->is_variant = 1;
                                    }
                                    $value = AttributeValue::find($value_attribute);
                                    $attribute_product->id_values = $value_attribute;
                                    $attribute_product->value = $value->value;
                                    $attribute_product->save();
                                } elseif (in_array($key, $ids_attributes_color)) {
                                    if (count($value_attribute) > 0) {
                                        foreach ($value_attribute as $value_color) {
                                            $attribute_product = ProductAttributeValues::where('id_products', $id)->where('id_attribute', $key)->where('value', $value_color)->first();
                                            if ($attribute_product == null) {
                                                $attribute_product = new ProductAttributeValues;
                                                $attribute_product->id_products = $product->id;
                                                $attribute_product->id_attribute = $key;
                                                $attribute_product->is_variant = 1;
                                            }
                                            $color = Color::where('code', $value_color)->first();
                                            $attribute_product->id_colors = $color->id;
                                            $attribute_product->value = $color->code;
                                            $attribute_product->save();
                                        }
                                    }
                                } elseif (in_array($key, $ids_attributes_numeric)) {
                                    if ($attribute_product == null) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $product->id;
                                        $attribute_product->id_attribute = $key;
                                        $attribute_product->is_variant = 1;
                                    }
                                    $attribute_product->id_units = $data['unit_variant'][$id][$key];
                                    $attribute_product->value = $value_attribute;
                                    $attribute_product->save();
                                } else {
                                    if ($attribute_product == null) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $product->id;
                                        $attribute_product->id_attribute = $key;
                                        $attribute_product->is_variant = 1;
                                    }
                                    $attribute_product->value = $value_attribute;
                                    $attribute_product->save();
                                }
                            }
                        }

                        $new_ids_attributes = array_keys($variant['attributes']);
                        ProductAttributeValues::where('id_products', $id)
                            ->where('is_variant', 1)
                            ->whereNotIn('id_attribute', $new_ids_attributes)
                            ->delete();

                        //Images of variant
                        if (array_key_exists('photo', $variant)) {
                            $structure = public_path('upload_products');
                            if (! file_exists($structure)) {
                                mkdir(public_path('upload_products', 0777));
                            }

                            if (! file_exists(public_path('/upload_products/Product-'.$product->id))) {
                                mkdir(public_path('/upload_products/Product-'.$product->id, 0777));
                                mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                            } else {
                                if (! file_exists(public_path('/upload_products/Product-'.$product->id.'/images'))) {
                                    mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                                }
                            }

                            foreach ($variant['photo'] as $key => $image) {
                                $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                                $image->move(public_path('/upload_products/Product-'.$product->id.'/images'), $imageName);
                                $path = '/upload_products/Product-'.$product->id.'/images'.'/'.$imageName;

                                $uploaded_document = new UploadProducts;
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

                            foreach ($variant['pricing']['from'] as $key => $from) {
                                $current_data = [];
                                if (($from != null) && ($variant['pricing']['to'][$key] != null) && ($variant['pricing']['unit_price'][$key] != null)) {
                                    if (isset($variant['pricing']['date_range_pricing'])) {
                                        if (($variant['pricing']['date_range_pricing'] != null)) {
                                            if (($variant['pricing']['date_range_pricing'][$key]) && ($variant['pricing']['discount_type'][$key])) {
                                                $date_var = explode(' to ', $variant['pricing']['date_range_pricing'][$key]);
                                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                                $start_to_parse = explode(' ', $date_var[0]);
                                                $end_to_parse = explode(' ', $date_var[1]);

                                                $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                                $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                                $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                                $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                                if (($check_start == true) && ($check_end == true)) {
                                                    $current_data['discount_start_datetime'] = $discount_start_date;
                                                    $current_data['discount_end_datetime'] = $discount_end_date;
                                                    $current_data['discount_type'] = $variant['pricing']['discount_type'][$key];
                                                } else {
                                                    $current_data['discount_start_datetime'] = null;
                                                    $current_data['discount_end_datetime'] = null;
                                                    $current_data['discount_type'] = null;
                                                    $current_data['discount_amount'] = null;
                                                    $current_data['discount_percentage'] = null;
                                                }
                                            } else {
                                                $current_data['discount_start_datetime'] = null;
                                                $current_data['discount_end_datetime'] = null;
                                                $current_data['discount_type'] = null;
                                                $current_data['discount_amount'] = null;
                                                $current_data['discount_percentage'] = null;
                                            }
                                        } else {
                                            $current_data['discount_start_datetime'] = null;
                                            $current_data['discount_end_datetime'] = null;
                                            $current_data['discount_type'] = null;
                                            $current_data['discount_amount'] = null;
                                            $current_data['discount_percentage'] = null;
                                        }
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                    $current_data['id_products'] = $product->id;
                                    $current_data['from'] = $from;
                                    $current_data['to'] = $variant['pricing']['to'][$key];
                                    $current_data['unit_price'] = $variant['pricing']['unit_price'][$key];

                                    if (isset($variant['pricing']['discount_amount']) && ($variant['pricing']['date_range_pricing'][$key] != null)) {
                                        $current_data['discount_amount'] = $variant['pricing']['discount_amount'][$key];
                                    } else {
                                        $current_data['discount_amount'] = null;
                                    }
                                    if (isset($variant['pricing']['discount_percentage']) && ($variant['pricing']['date_range_pricing'][$key] != null)) {
                                        $current_data['discount_percentage'] = $variant['pricing']['discount_percentage'][$key];
                                    } else {
                                        $current_data['discount_percentage'] = null;
                                    }

                                    array_push($all_data_to_insert, $current_data);
                                }
                            }

                            PricingConfiguration::where('id_products', $product->id)->delete();
                            PricingConfiguration::insert($all_data_to_insert);
                        } else {
                            //get pricing by default
                            $all_data_to_insert = [];

                            foreach ($pricing['from'] as $key => $from) {
                                $current_data = [];
                                if (($from != null) && ($pricing['to'][$key] != null) && ($pricing['unit_price'][$key] != null)) {
                                    if (isset($pricing['date_range_pricing'])) {
                                        if (($pricing['date_range_pricing'] != null)) {
                                            if (($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])) {
                                                $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                                $start_to_parse = explode(' ', $date_var[0]);
                                                $end_to_parse = explode(' ', $date_var[1]);

                                                $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                                $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                                $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                                $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                                if (($check_start == true) && ($check_end == true)) {
                                                    $current_data['discount_start_datetime'] = $discount_start_date;
                                                    $current_data['discount_end_datetime'] = $discount_end_date;
                                                    $current_data['discount_type'] = $pricing['discount_type'][$key];
                                                } else {
                                                    $current_data['discount_start_datetime'] = null;
                                                    $current_data['discount_end_datetime'] = null;
                                                    $current_data['discount_type'] = null;
                                                    $current_data['discount_amount'] = null;
                                                    $current_data['discount_percentage'] = null;
                                                }
                                            }
                                        } else {
                                            $current_data['discount_start_datetime'] = null;
                                            $current_data['discount_end_datetime'] = null;
                                            $current_data['discount_type'] = null;
                                            $current_data['discount_amount'] = null;
                                            $current_data['discount_percentage'] = null;
                                        }
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }

                                    $current_data['id_products'] = $product->id;
                                    $current_data['from'] = $from;
                                    $current_data['to'] = $pricing['to'][$key];
                                    $current_data['unit_price'] = $pricing['unit_price'][$key];

                                    if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                                        $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                                    } else {
                                        $current_data['discount_amount'] = null;
                                    }
                                    if (isset($pricing['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                                        $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                                    } else {
                                        $current_data['discount_percentage'] = null;
                                    }
                                    array_push($all_data_to_insert, $current_data);
                                }
                            }

                            PricingConfiguration::insert($all_data_to_insert);
                        }

                        Shipping::where('product_id', $product->id)->delete();

                        $shipping_details = [];
                        if (array_key_exists('shipping_details', $variant)) {
                            if (count($variant['shipping_details']) > 0) {
                                foreach ($variant['shipping_details']['from_shipping'] as $key => $from) {
                                    if (($from != null) && ($variant['shipping_details']['to_shipping'][$key] != null) && ($variant['shipping_details']['shipper'][$key] != null) && ($variant['shipping_details']['estimated_order'][$key] != null)) {
                                        $current_shipping = [];
                                        if (is_array($variant['shipping_details']['shipper'][$key])) {
                                            $shippers = implode(',', $variant['shipping_details']['shipper'][$key]);
                                            $current_shipping['shipper'] = $shippers;
                                        } else {
                                            $current_shipping['shipper'] = $variant['shipping_details']['shipper'][$key];
                                        }
                                        $current_shipping['from_shipping'] = $from;
                                        $current_shipping['to_shipping'] = $variant['shipping_details']['to_shipping'][$key];
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
                }
            }

            if (count($general_attributes_data) > 0) {
                if (count($general_attributes_data) > 0) {
                    foreach ($general_attributes_data as $attr => $value) {
                        if ($value != null) {
                            if (in_array($attr, $ids_attributes_color)) {
                                ProductAttributeValues::where('id_products', $product_draft->id)->where('id_attribute', $attr)->whereNotIn('value', $value)->delete();
                            } else {
                                $attribute_product = ProductAttributeValues::where('id_products', $product_draft->id)->where('id_attribute', $attr)->first();
                            }

                            if (in_array($attr, $ids_attributes_list)) {
                                if ($attribute_product == null) {
                                    $attribute_product = new ProductAttributeValues;
                                    $attribute_product->id_products = $product_draft->id;
                                    $attribute_product->id_attribute = $attr;
                                    $attribute_product->is_general = 1;
                                }
                                $value_attribute = AttributeValue::find($value);
                                $attribute_product->id_values = $value;
                                $attribute_product->value = $value_attribute->value;
                                $attribute_product->save();
                            } elseif (in_array($attr, $ids_attributes_color)) {
                                if (count($value) > 0) {
                                    foreach ($value as $value_color) {
                                        $attribute_product = ProductAttributeValues::where('id_products', $product_draft->id)->where('id_attribute', $attr)->where('value', $value_color)->first();
                                        if ($attribute_product == null) {
                                            $attribute_product = new ProductAttributeValues;
                                            $attribute_product->id_products = $product_draft->id;
                                            $attribute_product->id_attribute = $attr;
                                            $attribute_product->is_general = 1;
                                        }
                                        $color = Color::where('code', $value_color)->first();
                                        $attribute_product->id_colors = $color->id;
                                        $attribute_product->value = $color->code;
                                        $attribute_product->save();
                                    }
                                }
                            } elseif (in_array($attr, $ids_attributes_numeric)) {
                                if ($attribute_product == null) {
                                    $attribute_product = new ProductAttributeValues;
                                    $attribute_product->id_products = $product_draft->id;
                                    $attribute_product->id_attribute = $attr;
                                    $attribute_product->is_general = 1;
                                }
                                $attribute_product->id_units = $unit_general_attributes_data[$attr];
                                $attribute_product->value = $value;
                                $attribute_product->save();
                            } else {
                                if ($attribute_product == null) {
                                    $attribute_product = new ProductAttributeValues;
                                    $attribute_product->id_products = $product_draft->id;
                                    $attribute_product->id_attribute = $attr;
                                    $attribute_product->is_general = 1;
                                }
                                $attribute_product->value = $value;
                                $attribute_product->save();
                            }
                        }
                    }
                }
            }

            $new_ids_attributes_general = array_keys($general_attributes_data);
            ProductAttributeValues::where('id_products', $product_draft->id)
                ->where('is_general', 1)
                ->whereNotIn('id_attribute', $new_ids_attributes_general)
                ->delete();

            if (count($variants_new_data)) {
                foreach ($variants_new_data as $id => $variant) {
                    if (! array_key_exists('shipping', $variant)) {
                        $collection['shipping'] = 0;
                    } else {
                        $collection['shipping'] = $variant['shipping'];
                    }
                    $collection['low_stock_quantity'] = $variant['stock'];
                    if (array_key_exists('sku', $variant)) {
                        $collection['sku'] = $variant['sku'];
                    } else {
                        $collection['sku'] = '';
                    }

                    if (! isset($variant['sample_price'])) {
                        $collection['vat_sample'] = $vat_user->vat_registered;
                        $collection['sample_description'] = $data_sample['sample_description'];
                        $collection['sample_price'] = $data_sample['sample_price'];
                    } else {
                        $collection['vat_sample'] = $vat_user->vat_registered;
                        $collection['sample_description'] = $variant['sample_description'];
                        $collection['sample_price'] = $variant['sample_price'];
                    }

                    $slug = Str::slug($collection['name']);

                    $same_slug_count = Product::where('slug', 'LIKE', $slug.'%')->count();
                    $slug_suffix = $same_slug_count > 1 ? '-'.$same_slug_count + 1 : '';
                    $slug .= $slug_suffix;

                    $randomString = Str::random(5);
                    $collection['slug'] = $collection['slug'].'-'.$randomString;

                    $new_product = Product::create($collection);

                    //attributes of variant
                    foreach ($variant['attributes'] as $key => $value_attribute) {
                        if ($value_attribute != null) {
                            if (in_array($key, $ids_attributes_list)) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $new_product->id;
                                $attribute_product->id_attribute = $key;
                                $attribute_product->is_variant = 1;
                                $value = AttributeValue::find($value_attribute);
                                $attribute_product->id_values = $value_attribute;
                                $attribute_product->value = $value->value;
                                $attribute_product->save();
                            } elseif (in_array($key, $ids_attributes_color)) {
                                if (count($value_attribute) > 0) {
                                    foreach ($value_attribute as $value_color) {
                                        $attribute_product = new ProductAttributeValues;
                                        $attribute_product->id_products = $new_product->id;
                                        $attribute_product->id_attribute = $key;
                                        $attribute_product->is_variant = 1;
                                        $color = Color::where('code', $value_color)->first();
                                        $attribute_product->id_colors = $color->id;
                                        $attribute_product->value = $color->code;
                                        $attribute_product->save();
                                    }
                                }
                            } elseif (in_array($key, $ids_attributes_numeric)) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $new_product->id;
                                $attribute_product->id_attribute = $key;
                                $attribute_product->is_variant = 1;
                                $attribute_product->id_units = $variant['units'][$key];
                                $attribute_product->value = $value_attribute;
                                $attribute_product->save();
                            } else {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $new_product->id;
                                $attribute_product->id_attribute = $key;
                                $attribute_product->is_variant = 1;
                                $attribute_product->value = $value_attribute;
                                $attribute_product->save();
                            }
                        }
                    }

                    //Images of variant
                    if (array_key_exists('photo', $variant)) {
                        $structure = public_path('upload_products');
                        if (! file_exists($structure)) {
                            mkdir(public_path('upload_products', 0777));
                        }

                        if (! file_exists(public_path('/upload_products/Product-'.$new_product->id))) {
                            mkdir(public_path('/upload_products/Product-'.$new_product->id, 0777));
                            mkdir(public_path('/upload_products/Product-'.$new_product->id.'/images', 0777));
                        } else {
                            if (! file_exists(public_path('/upload_products/Product-'.$new_product->id.'/images'))) {
                                mkdir(public_path('/upload_products/Product-'.$new_product->id.'/images', 0777));
                            }
                        }

                        foreach ($variant['photo'] as $key => $image) {
                            $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                            $image->move(public_path('/upload_products/Product-'.$new_product->id.'/images'), $imageName);
                            $path = '/upload_products/Product-'.$new_product->id.'/images'.'/'.$imageName;

                            $uploaded_document = new UploadProducts;
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

                        foreach ($variant['pricing']['from'] as $key => $from) {
                            $current_data = [];
                            if (($from != null) && ($variant['pricing']['to'][$key] != null) && ($variant['pricing']['unit_price'][$key] != null)) {
                                if (isset($variant['pricing']['discount_range'])) {
                                    if (($variant['pricing']['discount_range'] != null)) {
                                        if (($variant['pricing']['discount_range'][$key]) && ($variant['pricing']['discount_type'][$key])) {
                                            $date_var = explode(' to ', $variant['pricing']['discount_range'][$key]);
                                            $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                            $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                            $start_to_parse = explode(' ', $date_var[0]);
                                            $end_to_parse = explode(' ', $date_var[1]);

                                            $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                            $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                            $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                            $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                            if (($check_start == true) && ($check_end == true)) {
                                                $current_data['discount_start_datetime'] = $discount_start_date;
                                                $current_data['discount_end_datetime'] = $discount_end_date;
                                                $current_data['discount_type'] = $variant['pricing']['discount_type'][$key];
                                            } else {
                                                $current_data['discount_start_datetime'] = null;
                                                $current_data['discount_end_datetime'] = null;
                                                $current_data['discount_type'] = null;
                                                $current_data['discount_amount'] = null;
                                                $current_data['discount_percentage'] = null;
                                            }
                                        } else {
                                            $current_data['discount_start_datetime'] = null;
                                            $current_data['discount_end_datetime'] = null;
                                            $current_data['discount_type'] = null;
                                            $current_data['discount_amount'] = null;
                                            $current_data['discount_percentage'] = null;
                                        }
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }

                                $current_data['id_products'] = $new_product->id;
                                $current_data['from'] = $from;
                                $current_data['to'] = $variant['pricing']['to'][$key];
                                $current_data['unit_price'] = $variant['pricing']['unit_price'][$key];

                                if (isset($variant['pricing']['discount_amount']) && ($variant['pricing']['discount_range'][$key] != null)) {
                                    $current_data['discount_amount'] = $variant['pricing']['discount_amount'][$key];
                                } else {
                                    $current_data['discount_amount'] = null;
                                }
                                if (isset($variant['pricing']['discount_percentage']) && ($variant['pricing']['discount_range'][$key] != null)) {
                                    $current_data['discount_percentage'] = $variant['pricing']['discount_percentage'][$key];
                                } else {
                                    $current_data['discount_percentage'] = null;
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
                                        $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                        $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                        $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                        $start_to_parse = explode(' ', $date_var[0]);
                                        $end_to_parse = explode(' ', $date_var[1]);

                                        $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                        $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                        $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                        $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                        if (($check_start == true) && ($check_end == true)) {
                                            $current_data['discount_start_datetime'] = $discount_start_date;
                                            $current_data['discount_end_datetime'] = $discount_end_date;
                                            $current_data['discount_type'] = $pricing['discount_type'][$key];
                                        } else {
                                            $current_data['discount_start_datetime'] = null;
                                            $current_data['discount_end_datetime'] = null;
                                            $current_data['discount_type'] = null;
                                            $current_data['discount_amount'] = null;
                                            $current_data['discount_percentage'] = null;
                                        }
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }

                                $current_data['id_products'] = $new_product->id;
                                $current_data['from'] = $from;
                                $current_data['to'] = $pricing['to'][$key];
                                $current_data['unit_price'] = $pricing['unit_price'][$key];

                                if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                                    $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                                } else {
                                    $current_data['discount_amount'] = null;
                                }
                                if (isset($pricing['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                                    $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                                } else {
                                    $current_data['discount_percentage'] = null;
                                }

                                array_push($all_data_to_insert, $current_data);
                            }

                        }

                        PricingConfiguration::insert($all_data_to_insert);
                    }

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
                                $current_shipping['product_id'] = $new_product->id;
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

                            $id = $new_product->id;
                            $keyToPush = 'product_id';
                            $shipping = array_map(function ($arr) use ($id, $keyToPush) {
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

    public function storePricingConfiguration($product_id, $pricing)
    {
        if (count($pricing) > 0) {
            $all_data_to_insert = [];

            foreach ($pricing['from'] as $key => $from) {
                $current_data = [];
                try {
                    if (
                        ($from != null) && ($pricing['to'][$key] != null) &&
                        ($pricing['unit_price'][$key] != null)
                    ) {
                        if ($pricing['date_range_pricing'][$key] != null) {
                            if (
                                ($pricing['date_range_pricing'][$key]) &&
                                ($pricing['discount_type'][$key])
                            ) {
                                $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));
                                $start_to_parse = explode(' ', $date_var[0]);
                                $end_to_parse = explode(' ', $date_var[1]);

                                $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                if (($check_start == true) && ($check_end == true)) {
                                    $current_data['discount_start_datetime'] = $discount_start_date;
                                    $current_data['discount_end_datetime'] = $discount_end_date;
                                    $current_data['discount_type'] = $pricing['discount_type'][$key];
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }
                            } else {
                                $current_data['discount_start_datetime'] = null;
                                $current_data['discount_end_datetime'] = null;
                                $current_data['discount_type'] = null;
                                $current_data['discount_amount'] = null;
                                $current_data['discount_percentage'] = null;
                            }
                        } else {
                            $current_data['discount_start_datetime'] = null;
                            $current_data['discount_end_datetime'] = null;
                            $current_data['discount_type'] = null;
                            $current_data['discount_amount'] = null;
                            $current_data['discount_percentage'] = null;
                        }

                        $current_data['id_products'] = $product_id;
                        $current_data['from'] = $from;
                        $current_data['to'] = $pricing['to'][$key];
                        $current_data['unit_price'] = $pricing['unit_price'][$key];

                        if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                        } else {
                            $current_data['discount_amount'] = null;
                        }

                        if (isset($pricing['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                            $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                        } else {
                            $current_data['discount_percentage'] = null;
                        }

                        array_push($all_data_to_insert, $current_data);
                    }
                } catch (Exception $e) {
                    // Handle the parsing error
                    Log::error('Error while storing product, with message: '.$e->getMessage());
                }
            }

            if (count($all_data_to_insert) > 0) {
                PricingConfiguration::insert($all_data_to_insert);
            }
        }
    }

    public function storeGeneralAttributes($product_id, $general_attributes_data, $ids_attributes_list, $ids_attributes_numeric, $unit_general_attributes_data)
    {
        $ids_attributes_color = Attribute::where('type_value', 'color')->pluck('id')->toArray();

        if (count($general_attributes_data) > 0) {
            foreach ($general_attributes_data as $attr => $value) {
                if ($value != null) {
                    if (in_array($attr, $ids_attributes_list)) {
                        $attribute_product = new ProductAttributeValues;
                        $attribute_product->id_products = $product_id;
                        $attribute_product->id_attribute = $attr;
                        $attribute_product->is_general = 1;
                        $value_attribute = AttributeValue::find($value);
                        $attribute_product->id_values = $value;
                        $attribute_product->value = $value_attribute->value;
                        $attribute_product->save();
                    } elseif (in_array($attr, $ids_attributes_color)) {
                        if (count($value) > 0) {
                            foreach ($value as $value_color) {
                                $attribute_product = new ProductAttributeValues;
                                $attribute_product->id_products = $product_id;
                                $attribute_product->id_attribute = $attr;
                                $attribute_product->is_general = 1;
                                $color = Color::where('code', $value_color)->first();
                                $attribute_product->id_colors = $color->id;
                                $attribute_product->value = $color->code;
                                $attribute_product->save();
                            }
                        }
                    } elseif (in_array($attr, $ids_attributes_numeric)) {
                        $attribute_product = new ProductAttributeValues;
                        $attribute_product->id_products = $product_id;
                        $attribute_product->id_attribute = $attr;
                        $attribute_product->is_general = 1;
                        $attribute_product->id_units = $unit_general_attributes_data[$attr];
                        $attribute_product->value = $value;
                        $attribute_product->save();
                    } else {
                        $attribute_product = new ProductAttributeValues;
                        $attribute_product->id_products = $product_id;
                        $attribute_product->id_attribute = $attr;
                        $attribute_product->is_general = 1;
                        $attribute_product->value = $value;
                        $attribute_product->save();
                    }
                }
            }
        }
    }

    public function storeShipping($product_id, $shipping)
    {
        if (count($shipping) > 0) {
            $id = $product_id;
            $keyToPush = 'product_id';
            $shipping = array_map(function ($arr) use ($id, $keyToPush) {
                $arr[$keyToPush] = $id;

                return $arr;
            }, $shipping);

            Shipping::insert($shipping);
        }
    }

    public function storeProductWithDependencies(
        $data, $pricing, $general_attributes_data,
        $ids_attributes_list, $ids_attributes_numeric,
        $unit_general_attributes_data, $shipping
    ) {
        $data["unit_price"] = $data['unit_sale_price'];

        $product = Product::create($data);

        $this->storePricingConfiguration($product->id, $pricing);

        $this->storeGeneralAttributes(
            $product->id, $general_attributes_data,
            $ids_attributes_list, $ids_attributes_numeric,
            $unit_general_attributes_data
        );

        $this->storeShipping($product->id, $shipping);

        return $product;
    }

    public function storeParentProductWithDependencies(
        $data, $pricing, $shipping, $vat,
        $variants_data, $shipping_sample_parent,
        $ids_attributes_list, $ids_attributes_color,
        $ids_attributes_numeric, $vat_user,
        $general_attributes_data, $unit_general_attributes_data
    ) {
        // Create Parent Product
        $data['is_parent'] = 1;
        $data['sku'] = $data['name'];
        $data["unit_price"] = $data["unit_sale_price"];
        $product_parent = Product::create($data);
        $all_data_to_insert_parent = [];

        foreach ($pricing['from'] as $key => $from) {
            $current_data = [];
            if ($pricing['from'][$key] != null && $pricing['unit_price'][$key] != null) {
                if ($pricing['date_range_pricing'][$key] != null) {
                    if (($pricing['date_range_pricing'][$key]) && ($pricing['discount_type'][$key])) {
                        $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                        $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                        $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                        $start_to_parse = explode(' ', $date_var[0]);
                        $end_to_parse = explode(' ', $date_var[1]);

                        $explod_start_to_parse = explode('-', $start_to_parse[0]);
                        $explod_end_to_parse = explode('-', $end_to_parse[0]);

                        $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                        $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                        if (($check_start == true) && ($check_end == true)) {
                            $current_data['discount_start_datetime'] = $discount_start_date;
                            $current_data['discount_end_datetime'] = $discount_end_date;
                            $current_data['discount_type'] = $pricing['discount_type'][$key];
                        } else {
                            $current_data['discount_start_datetime'] = null;
                            $current_data['discount_end_datetime'] = null;
                            $current_data['discount_type'] = null;
                            $current_data['discount_amount'] = null;
                            $current_data['discount_percentage'] = null;
                        }
                    } else {
                        $current_data['discount_start_datetime'] = null;
                        $current_data['discount_end_datetime'] = null;
                        $current_data['discount_type'] = null;
                        $current_data['discount_amount'] = null;
                        $current_data['discount_percentage'] = null;
                    }
                } else {
                    $current_data['discount_start_datetime'] = null;
                    $current_data['discount_end_datetime'] = null;
                    $current_data['discount_type'] = null;
                    $current_data['discount_amount'] = null;
                    $current_data['discount_percentage'] = null;
                }

                $current_data['id_products'] = $product_parent->id;
                $current_data['from'] = $from;
                $current_data['to'] = $pricing['to'][$key];
                $current_data['unit_price'] = $pricing['unit_price'][$key];

                if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null) && ($pricing['date_range_pricing'][$key] != null)) {
                    $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                } else {
                    $current_data['discount_amount'] = null;
                }
                if (isset($current_data['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null) && ($pricing['date_range_pricing'][$key] != null)) {
                    $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                } else {
                    $current_data['discount_percentage'] = null;
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
                if (! array_key_exists('shipping', $variant)) {
                    $data['shipping'] = 0;
                } else {
                    $data['shipping'] = $variant['shipping'];
                }
                $data['low_stock_quantity'] = $variant['stock'];
                if (! array_key_exists('sample_price', $variant)) {
                    $data['vat_sample'] = $vat;
                    $data['sample_description'] = $data_sample['sample_description'];
                    $data['sample_price'] = $data_sample['sample_price'];
                } else {
                    $data['vat_sample'] = $vat;
                    $data['sample_description'] = $variant['sample_description'];
                    $data['sample_price'] = $variant['sample_price'];
                }

                if (isset($variant['variant_shipper_sample'])) {
                    $data['shipper_sample'] = implode(',', $variant['variant_shipper_sample']);
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

                $data['sku'] = $variant['sku'];
                $randomString = Str::random(5);
                $data['slug'] = $data['slug'].'-'.$randomString;
                $data["unit_price"] = $data["unit_sale_price"];
                $product = Product::create($data);

                //attributes of variant
                foreach ($variant['attributes'] as $key => $value_attribute) {
                    if ($value_attribute != null) {
                        if (in_array($key, $ids_attributes_list)) {
                            $attribute_product = new ProductAttributeValues;
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
                                    $attribute_product = new ProductAttributeValues;
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
                            $attribute_product = new ProductAttributeValues;
                            $attribute_product->id_products = $product->id;
                            $attribute_product->id_attribute = $key;
                            $attribute_product->is_variant = 1;
                            $attribute_product->id_units = $variant['units'][$key];
                            $attribute_product->value = $value_attribute;
                            $attribute_product->save();
                        } else {
                            $attribute_product = new ProductAttributeValues;
                            $attribute_product->id_products = $product->id;
                            $attribute_product->id_attribute = $key;
                            $attribute_product->is_variant = 1;
                            $attribute_product->value = $value_attribute;
                            $attribute_product->save();
                        }

                        $attribute_product->save();
                    }
                }

                //Images of variant
                if (array_key_exists('photo', $variant)) {
                    if (count($variant['photo']) > 0) {
                        $structure = public_path('upload_products');
                        if (! file_exists($structure)) {
                            mkdir(public_path('upload_products', 0777));
                        }

                        if (! file_exists(public_path('/upload_products/Product-'.$product->id))) {
                            mkdir(public_path('/upload_products/Product-'.$product->id, 0777));
                            mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                        } else {
                            if (! file_exists(public_path('/upload_products/Product-'.$product->id.'/images'))) {
                                mkdir(public_path('/upload_products/Product-'.$product->id.'/images', 0777));
                            }
                        }

                        foreach ($variant['photo'] as $key => $image) {
                            $imageName = time().rand(5, 15).'.'.$image->getClientOriginalExtension();
                            $image->move(public_path('/upload_products/Product-'.$product->id.'/images'), $imageName);
                            $path = '/upload_products/Product-'.$product->id.'/images'.'/'.$imageName;

                            $uploaded_document = new UploadProducts;
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
                        if (
                            ($from != null) &&
                            ($variant['pricing']['to'][$key] != null) &&
                            ($variant['pricing']['unit_price'][$key] != null)
                        ) {
                            if (isset($variant['pricing']['discount_range'])) {
                                if (($variant['pricing']['discount_range'] != null)) {
                                    if (($variant['pricing']['discount_range'][$key]) && ($variant['pricing']['discount_type'][$key])) {
                                        $date_var = explode(' to ', $variant['pricing']['discount_range'][$key]);
                                        $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                        $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                        $start_to_parse = explode(' ', $date_var[0]);
                                        $end_to_parse = explode(' ', $date_var[1]);

                                        $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                        $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                        $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                        $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                        if (($check_start == true) && ($check_end == true)) {
                                            $current_data['discount_start_datetime'] = $discount_start_date;
                                            $current_data['discount_end_datetime'] = $discount_end_date;
                                            $current_data['discount_type'] = $variant['pricing']['discount_type'][$key];
                                        } else {
                                            $current_data['discount_start_datetime'] = null;
                                            $current_data['discount_end_datetime'] = null;
                                            $current_data['discount_type'] = null;
                                            $current_data['discount_amount'] = null;
                                            $current_data['discount_percentage'] = null;
                                        }
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }
                            } else {
                                $current_data['discount_start_datetime'] = null;
                                $current_data['discount_end_datetime'] = null;
                                $current_data['discount_type'] = null;
                                $current_data['discount_amount'] = null;
                                $current_data['discount_percentage'] = null;
                            }

                            $current_data['id_products'] = $product->id;
                            $current_data['from'] = $from;
                            $current_data['to'] = $variant['pricing']['to'][$key];
                            $current_data['unit_price'] = $variant['pricing']['unit_price'][$key];

                            $current_data['id_products'] = $product->id;
                            $current_data['from'] = $from;
                            $current_data['to'] = $variant['pricing']['to'][$key];
                            $current_data['unit_price'] = $variant['pricing']['unit_price'][$key];

                            if (isset($variant['pricing']['discount_amount']) && ($variant['pricing']['discount_range'][$key] != null)) {
                                $current_data['discount_amount'] = $variant['pricing']['discount_amount'][$key];
                            } else {
                                $current_data['discount_amount'] = null;
                            }
                            if (isset($variant['pricing']['discount_percentage']) && ($variant['pricing']['discount_range'][$key] != null)) {
                                $current_data['discount_percentage'] = $variant['pricing']['discount_percentage'][$key];
                            } else {
                                $current_data['discount_percentage'] = null;
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
                                    $date_var = explode(' to ', $pricing['date_range_pricing'][$key]);
                                    $discount_start_date = Carbon::createFromTimestamp(strtotime($date_var[0]));
                                    $discount_end_date = Carbon::createFromTimestamp(strtotime($date_var[1]));

                                    $start_to_parse = explode(' ', $date_var[0]);
                                    $end_to_parse = explode(' ', $date_var[1]);

                                    $explod_start_to_parse = explode('-', $start_to_parse[0]);
                                    $explod_end_to_parse = explode('-', $end_to_parse[0]);

                                    $check_start = checkdate(intval($explod_start_to_parse[1]), intval($explod_start_to_parse[0]), intval($explod_start_to_parse[2]));
                                    $check_end = checkdate(intval($explod_end_to_parse[1]), intval($explod_end_to_parse[0]), intval($explod_end_to_parse[2]));

                                    if (($check_start == true) && ($check_end == true)) {
                                        $current_data['discount_start_datetime'] = $discount_start_date;
                                        $current_data['discount_end_datetime'] = $discount_end_date;
                                        $current_data['discount_type'] = $pricing['discount_type'][$key];
                                    } else {
                                        $current_data['discount_start_datetime'] = null;
                                        $current_data['discount_end_datetime'] = null;
                                        $current_data['discount_type'] = null;
                                        $current_data['discount_amount'] = null;
                                        $current_data['discount_percentage'] = null;
                                    }
                                } else {
                                    $current_data['discount_start_datetime'] = null;
                                    $current_data['discount_end_datetime'] = null;
                                    $current_data['discount_type'] = null;
                                    $current_data['discount_amount'] = null;
                                    $current_data['discount_percentage'] = null;
                                }
                            } else {
                                $current_data['discount_start_datetime'] = null;
                                $current_data['discount_end_datetime'] = null;
                                $current_data['discount_type'] = null;
                                $current_data['discount_amount'] = null;
                                $current_data['discount_percentage'] = null;
                            }

                            $current_data['id_products'] = $product->id;
                            $current_data['from'] = $from;
                            $current_data['to'] = $pricing['to'][$key];
                            $current_data['unit_price'] = $pricing['unit_price'][$key];

                            if (isset($pricing['discount_amount']) && ($pricing['date_range_pricing'][$key] != null)) {
                                $current_data['discount_amount'] = $pricing['discount_amount'][$key];
                            } else {
                                $current_data['discount_amount'] = null;
                            }
                            if (isset($current_data['discount_percentage']) && ($pricing['date_range_pricing'][$key] != null)) {
                                $current_data['discount_percentage'] = $pricing['discount_percentage'][$key];
                            } else {
                                $current_data['discount_percentage'] = null;
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
                            $attribute_product = new ProductAttributeValues;
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
                                    $attribute_product = new ProductAttributeValues;
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
                            $attribute_product = new ProductAttributeValues;
                            $attribute_product->id_products = $product_parent->id;
                            $attribute_product->id_attribute = $attr;
                            $attribute_product->is_general = 1;
                            $attribute_product->id_units = $unit_general_attributes_data[$attr];
                            $attribute_product->value = $value;
                            $attribute_product->save();
                        } else {
                            $attribute_product = new ProductAttributeValues;
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

    public function getProductDetailsBySlug($parent, string $slug)
    {
        $outStock = false;

        if ($parent->is_parent == 0) {
            if ($parent->parent_id != 0) {
                $parent = Product::find($parent->parent_id);
            }
        }

        $revision_parent_name = Revision::whereNull('deleted_at')
            ->where('revisionable_type', 'App\Models\Product')
            ->where('revisionable_id', $parent->id)
            ->where('key', 'name')
            ->latest()
            ->first();

        $name = '';

        if ($revision_parent_name != null && $parent->last_version == 1) {
            $name = $revision_parent_name->old_value;
        } else {
            $name = $parent->name;
        }

        $revision_parent_brand = Revision::whereNull('deleted_at')
            ->where('revisionable_type', 'App\Models\Product')
            ->where('revisionable_id', $parent->id)
            ->where('key', 'brand_id')
            ->latest()
            ->first();

        if ($revision_parent_brand != null && $parent->last_version == 1) {
            $brand_id = $revision_parent_brand->old_value;
        } else {
            $brand_id = $parent->brand_id;
        }

        $revision_parent_description = Revision::whereNull('deleted_at')
            ->where('revisionable_type', 'App\Models\Product')
            ->where('revisionable_id', $parent->id)
            ->where('key', 'description')
            ->latest()
            ->first();

        $description = '';

        if ($revision_parent_description != null && $parent->last_version == 1) {
            $description = $revision_parent_description->old_value;
        } else {
            $description = $parent->description;
        }

        $short_description = '';

        if ($revision_parent_description != null && $parent->last_version == 1) {
            $short_description = $revision_parent_description->old_value;
        } else {
            $short_description = $parent->short_description;
        }

        $revision_parent_unit = Revision::whereNull('deleted_at')
            ->where('revisionable_type', 'App\Models\Product')
            ->where('revisionable_id', $parent->id)
            ->where('key', 'unit')
            ->latest()
            ->first();

        $unit = '';

        if ($revision_parent_unit != null && $parent->last_version == 1) {
            $unit = $revision_parent_unit->old_value;
        } else {
            $unit = $parent->unit;
        }

        $brand = Brand::find($brand_id);
        $pricing = PricingConfiguration::where('id_products', $parent->id)->get();
        $pricing = [];

        $pricing['from'] = PricingConfiguration::where('id_products', $parent->id)
            ->pluck('from')
            ->toArray();
        $pricing['to'] = PricingConfiguration::where('id_products', $parent->id)
            ->pluck('to')
            ->toArray();
        $pricing['unit_price'] = PricingConfiguration::where('id_products', $parent->id)
            ->pluck('unit_price')
            ->toArray();
        $pricing['discount_type'] = PricingConfiguration::where('id_products', $parent->id)
            ->pluck('discount_type')
            ->toArray();
        $pricing['discount_amount'] = PricingConfiguration::where('id_products', $parent->id)
            ->pluck('discount_amount')
            ->toArray();
        $pricing['discount_percentage'] = PricingConfiguration::where('id_products', $parent->id)
            ->pluck('discount_percentage')
            ->toArray();

        $startDates = PricingConfiguration::where('id_products', $parent->id)
            ->pluck('discount_start_datetime')
            ->toArray();
        $endDates = PricingConfiguration::where('id_products', $parent->id)
            ->pluck('discount_end_datetime')
            ->toArray();
        $pricing['date_range_pricing'] = [];

        foreach ($startDates as $index => $startDate) {
            if (isset($endDates[$index])) {
                $endDate = $endDates[$index];
                $formattedStartDate = date('d-m-Y H:i:s', strtotime($startDate));
                $formattedEndDate = date('d-m-Y H:i:s', strtotime($endDate));
                $pricing['date_range_pricing'][$index] = "$formattedStartDate to $formattedEndDate";
            }
        }

        $variations = [];
        $storedFilePaths = [];

        if ($parent->last_version == 1) {
            $images_parent = UploadProducts::where('id_product', $parent->id)
                ->where('type', 'images')
                ->get();

            if (count($images_parent) > 0) {
                $path = [];
                foreach ($images_parent as $image) {
                    $revision_parent_image = Revision::whereNull('deleted_at')
                        ->where('revisionable_type', 'App\Models\UploadProducts')
                        ->where('revisionable_id', $image->id)
                        ->latest()
                        ->first();

                    if ($revision_parent_image == null) {
                        array_push($path, $image->path);
                    }
                }
                $storedFilePaths = $path;
            }
        } else {
            $storedFilePaths = UploadProducts::where('id_product', $parent->id)
                ->where('type', 'images')
                ->pluck('path')
                ->toArray();
        }

        if (count($storedFilePaths) == 0) {
            $url = public_path().'/assets/img/placeholder.jpg';
            array_push($storedFilePaths, $url);
        }

        if ($parent->is_parent == 1) {
            $childrens_ids = Product::where('parent_id', $parent->id)
                ->pluck('id')
                ->toArray();

            foreach ($childrens_ids as $children_id) {
                $product = Product::find($children_id);
                $variations[$children_id]['sku'] = $product ? $product->sku : null;
                $variations[$children_id]['slug'] = $product ? $product->slug : null;

                $variations[$children_id]['variant_pricing-from']['from'] = PricingConfiguration::where('id_products', $children_id)->pluck('from')->toArray();
                $variations[$children_id]['variant_pricing-from']['to'] = PricingConfiguration::where('id_products', $children_id)->pluck('to')->toArray();
                $variations[$children_id]['variant_pricing-from']['unit_price'] = PricingConfiguration::where('id_products', $children_id)->pluck('unit_price')->toArray();
                $startDates = PricingConfiguration::where('id_products', $children_id)->pluck('discount_start_datetime')->toArray();
                $endDates = PricingConfiguration::where('id_products', $children_id)->pluck('discount_end_datetime')->toArray();
                $discountPeriods = [];

                foreach ($startDates as $index => $startDate) {
                    if (isset($endDates[$index])) {
                        $endDate = $endDates[$index];
                        $formattedStartDate = date('d-m-Y H:i:s', strtotime($startDate));
                        $formattedEndDate = date('d-m-Y H:i:s', strtotime($endDate));
                        $discountPeriods[$index] = "$formattedStartDate to $formattedEndDate";
                    }
                }

                $variations[$children_id]['variant_pricing-from']['discount'] = [
                    'type' => PricingConfiguration::where('id_products', $children_id)->pluck('discount_type')->toArray(),
                    'amount' => PricingConfiguration::where('id_products', $children_id)->pluck('discount_amount')->toArray(),
                    'percentage' => PricingConfiguration::where('id_products', $children_id)->pluck('discount_percentage')->toArray(),
                    'date' => $discountPeriods,
                ];

                $attributes_variant = ProductAttributeValues::where('id_products', $children_id)
                    ->where('is_variant', 1)
                    ->get();

                foreach ($attributes_variant as $attribute) {
                    $revision_children_attribute = Revision::whereNull('deleted_at')
                        ->where('revisionable_type', 'App\Models\ProductAttributeValues')
                        ->where('revisionable_id', $attribute->id)
                        ->latest()
                        ->first();

                    if ($revision_children_attribute != null && $parent->last_version == 1) {
                        if ($attribute->id_units != null) {
                            $unit = null;
                            if ($revision_children_attribute->key = 'id_units') {
                                $unit = Unity::find($revision_children_attribute->old_value);
                            } else {
                                $unit = Unity::find($attribute->id_units);
                            }

                            if ($unit) {
                                $variations[$children_id][$attribute->id_attribute] = $attribute->value.' '.$unit->name;
                            }
                        } elseif ($attribute->id_colors != null) {
                            // Check if the attribute does not exist, initialize it as an array
                            if (! isset($variations[$children_id][$attribute->id_attribute])) {
                                $variations[$children_id][$attribute->id_attribute] = [];
                            }

                            // Append the new value to the array
                            $variations[$children_id][$attribute->id_attribute][] = $revision_children_attribute->old_value;
                        } else {
                            if ($revision_children_attribute->key != 'add_attribute') {
                                $variations[$children_id][$attribute->id_attribute] = $revision_children_attribute->old_value;
                            }
                        }
                    } else {
                        if ($attribute->id_units != null) {
                            $unit = Unity::find($attribute->id_units);

                            if ($unit) {
                                $variations[$children_id][$attribute->id_attribute] = $attribute->value.' '.$unit->name;
                            }
                        } elseif ($attribute->id_colors != null) {
                            // Check if the attribute does not exist, initialize it as an array
                            if (! isset($variations[$children_id][$attribute->id_attribute])) {
                                $variations[$children_id][$attribute->id_attribute] = [];
                            }

                            // Append the new value to the array
                            $variations[$children_id][$attribute->id_attribute][] = $attribute->value;
                        } else {
                            $variations[$children_id][$attribute->id_attribute] = $attribute->value;
                        }
                    }
                }

                if ($parent->last_version == 1) {
                    $images_children = UploadProducts::where('id_product', $children_id)
                        ->where('type', 'images')
                        ->get();

                    if (count($images_children) > 0) {
                        $path = [];
                        foreach ($images_children as $image) {
                            $revision_children_image = Revision::whereNull('deleted_at')
                                ->where('revisionable_type', 'App\Models\UploadProducts')
                                ->where('revisionable_id', $image->id)
                                ->latest()
                                ->first();

                            if ($revision_children_image == null) {
                                array_push($path, $image->path);
                            }
                        }

                        $variations[$children_id]['storedFilePaths'] = $path;
                    }
                } else {
                    $variations[$children_id]['storedFilePaths'] = UploadProducts::where('id_product', $children_id)
                        ->where('type', 'images')
                        ->pluck('path')
                        ->toArray();
                }

                if (count($storedFilePaths) > 0) {
                    if (isset($variations[$children_id]['storedFilePaths'])) {
                        // If you want to merge main photo paths with variation photo paths
                        $variations[$children_id]['storedFilePaths'] = array_merge(
                            $variations[$children_id]['storedFilePaths'],
                            $storedFilePaths
                        );
                    }
                }
            }
        }

        $attributes_general = ProductAttributeValues::where('id_products', $parent->id)
            ->where('is_general', 1)
            ->get();

        $attributesGeneralArray = [];

        foreach ($attributes_general as $attribute_general) {
            $revision_parent_attribute = Revision::whereNull('deleted_at')
                ->where('revisionable_type', 'App\Models\ProductAttributeValues')
                ->where('revisionable_id', $attribute_general->id)
                ->latest()
                ->first();

            if ($revision_parent_attribute != null && $parent->last_version == 1) {
                if ($attribute_general->id_units != null) {

                    $unit = null;
                    if ($revision_parent_attribute->key = 'id_units') {
                        $unit = Unity::find($revision_parent_attribute->old_value);
                    } else {
                        $unit = Unity::find($attribute_general->id_units);
                    }

                    if ($unit) {
                        $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value.' '.$unit->name;
                    }
                } else {
                    if ($revision_parent_attribute->key != 'add_attribute') {
                        $attributesGeneralArray[$attribute_general->id_attribute] = $revision_parent_attribute->old_value;
                    }
                }
            } else {
                if ($attribute_general->id_units != null) {
                    $unit = Unity::find($attribute_general->id_units);
                    if ($unit) {
                        $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value.' '.$unit->name;
                    }
                } else {
                    $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value;
                }
            }
        }

        $attributes = [];

        if (count($variations) > 0) {
            foreach ($variations as $variation) {
                foreach ($variation as $attributeId => $value) {
                    if ($attributeId != 'storedFilePaths' && $attributeId != 'variant_pricing-from' && $attributeId != 'sku' && $attributeId != 'slug') {
                        if (! isset($attributes[$attributeId])) {
                            $attributes[$attributeId] = [];
                        }

                        // Add value to the unique attributes array if it doesn't already exist
                        if (! in_array($value, $attributes[$attributeId])) {
                            $attributes[$attributeId][] = $value;
                        }
                    }
                }
            }
        }

        if (is_array($variations) && ! empty($variations)) {
            foreach ($variations as $variationId => $variation) {
                if (isset($variation['slug']) && $variation['slug'] === $slug) {
                    $lastItem = $variation; // Store the matching variation
                    $variationId = $variationId;

                    break; // Stop the loop once a match is found
                }
            }

            if (! isset($lastItem)) {
                $lastItem = end($variations);
                $variationId = key($variations);
            }

            if (count($lastItem['variant_pricing-from']['to']) > 0) {
                $max = max($lastItem['variant_pricing-from']['to']);
            }

            if (count($lastItem['variant_pricing-from']['from']) > 0) {
                $min = min($lastItem['variant_pricing-from']['from']);
                $product_stock = StockSummary::where('variant_id', $variationId)->sum('current_total_quantity');

                if ($product_stock < $min) {
                    $outStock = true;
                }
            }
        }

        if (count($variations) == 0) {
            if (isset($pricing['from']) && is_array($pricing['from']) && count($pricing['from']) > 0) {
                if (! isset($min)) {
                    $min = min($pricing['from']);
                    $product_stock = StockSummary::where('variant_id', $parent->id)->sum('current_total_quantity');

                    if ($product_stock < $min) {
                        $outStock = true;
                    }
                }
            }

            if (isset($pricing['to']) && is_array($pricing['to']) && count($pricing['to']) > 0) {
                if (! isset($max)) {
                    $max = max($pricing['to']);
                }
            }
        }

        $revision_parent_video_provider = Revision::whereNull('deleted_at')
            ->where('revisionable_type', 'App\Models\Product')
            ->where('revisionable_id', $parent->id)
            ->where('key', 'video_provider')
            ->latest()
            ->first();

        $video_provider = '';

        if ($revision_parent_video_provider != null && $parent->last_version == 1) {
            $old_link = Revision::whereNull('deleted_at')
                ->where('revisionable_type', 'App\Models\Product')
                ->where('revisionable_id', $parent->id)
                ->where('key', 'video_link')
                ->latest()
                ->first();

            $video_provider = $revision_parent_video_provider->old_value;
            if ($revision_parent_video_provider->old_value === 'youtube') {
                $getYoutubeVideoId = null;
                if ($old_link != null) {
                    $getYoutubeVideoId = $this->getYoutubeVideoId($old_link->old_value);
                }
            } else {
                $getVimeoVideoId = null;
                if ($old_link != null) {
                    $getVimeoVideoId = $this->getVimeoVideoId($old_link->old_value);
                }
            }
        } else {
            $video_provider = $parent->video_provider;
            if ($parent->video_provider === 'youtube') {
                $getYoutubeVideoId = $this->getYoutubeVideoId($parent->video_link);
            } else {
                $getVimeoVideoId = $this->getVimeoVideoId($parent->video_link);
            }
        }

        $total = isset($pricing['from'][0]) && isset($pricing['unit_price'][0]) ? $pricing['from'][0] * $pricing['unit_price'][0] : '';

        if (
            isset($lastItem['variant_pricing-from']['discount']['date']) &&
            is_array($lastItem['variant_pricing-from']['discount']['date']) &&
            ! empty($lastItem['variant_pricing-from']['discount']['date']) &&
            isset($lastItem['variant_pricing-from']['discount']['date'][0]) &&
            $lastItem['variant_pricing-from']['discount']['date'][0] !== null
        ) {
            // Extract start and end dates from the first date interval
            $dateRange = $lastItem['variant_pricing-from']['discount']['date'][0];
            [$startDate, $endDate] = explode(' to ', $dateRange);

            // Convert date strings to DateTime objects for comparison
            $currentDate = new DateTime; // Current date/time
            $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
            $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

            // Check if the current date/time is within the specified date interval
            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                // Assuming $lastItem is your array containing the pricing information
                $unitPrice = $lastItem['variant_pricing-from']['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                // Calculate the total price based on quantity and unit price
                $variantPricing = $unitPrice;

                if ($lastItem['variant_pricing-from']['discount']['type'][0] == 'percent') {
                    $percent = $lastItem['variant_pricing-from']['discount']['percentage'][0];
                    if ($percent) {
                        // Calculate the discount amount based on the given percentage
                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                        $discountAmount = ($variantPricing * $discountPercent) / 100;

                        // Calculate the discounted price
                        $discountedPrice = $variantPricing - $discountAmount;
                    }
                } elseif ($lastItem['variant_pricing-from']['discount']['type'][0] == 'amount') {
                    // Calculate the discount amount based on the given amount
                    $amount = $lastItem['variant_pricing-from']['discount']['amount'][0];

                    if ($amount) {
                        $discountAmount = $amount;
                        // Calculate the discounted price
                        $discountedPrice = $variantPricing - $discountAmount;
                    }
                }
            }
        }

        if (isset($discountedPrice) && $discountedPrice > 0 && isset($lastItem['variant_pricing-from']['from'][0])) {
            $totalDiscount = $lastItem['variant_pricing-from']['from'][0] * $discountedPrice;
        }

        if (count($variations) == 0) {
            if (
                isset($pricing['date_range_pricing']) &&
                is_array($pricing['date_range_pricing']) &&
                ! empty($pricing['date_range_pricing']) &&
                isset($pricing['date_range_pricing'][0]) &&
                $pricing['date_range_pricing'][0] !== null
            ) {
                // Extract start and end dates from the first date interval
                $dateRange = $pricing['date_range_pricing'][0];
                [$startDate, $endDate] = explode(' to ', $dateRange);

                // Convert date strings to DateTime objects for comparison
                $currentDate = new DateTime; // Current date/time
                $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                // Check if the current date/time is within the specified date interval
                if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                    // Assuming $lastItem is your array containing the pricing information
                    $unitPrice = $pricing['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                    // Calculate the total price based on quantity and unit price
                    $variantPricing = $unitPrice;

                    if ($pricing['discount_type'][0] == 'percent') {
                        $percent = $pricing['discount_percentage'][0];
                        if ($percent) {
                            // Calculate the discount amount based on the given percentage
                            $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                            $discountAmount = ($variantPricing * $discountPercent) / 100;

                            // Calculate the discounted price
                            $discountedPrice = $variantPricing - $discountAmount;
                        }
                    } elseif ($pricing['discount_type'][0] == 'amount') {
                        // Calculate the discount amount based on the given amount
                        $amount = $pricing['discount_amount'][0];

                        if ($amount) {
                            $discountAmount = $amount;
                            // Calculate the discounted price
                            $discountedPrice = $variantPricing - $discountAmount;
                        }
                    }
                }
            }

            if (isset($discountedPrice) && $discountedPrice > 0 && isset($pricing['from'][0])) {
                $totalDiscount = $pricing['from'][0] * $discountedPrice;
            }
        }

        // Get all reviews for the specified product
        $reviews = Review::where('product_id', $parent->id)->where('status', 1)->get();

        // Total number of reviews
        $totalReviews = $reviews->count();

        // Initialize rating counts
        $ratingCounts = array_fill(0, 6, 0); // Index 0-5

        // Count each rating
        foreach ($reviews as $review) {
            $ratingCounts[$review->rating]++;
        }

        // Calculate percentage
        $ratingPercentages = [];
        for ($i = 0; $i <= 5; $i++) {
            $percentage = $totalReviews > 0 ? ($ratingCounts[$i] / $totalReviews) * 100 : 0;
            $ratingPercentages[$i] = [
                'rating' => $i,
                'percentage' => round($percentage, 2), // Round to 2 decimal places
                'count' => $ratingCounts[$i],
            ];
        }

        return [
            'name' => $name,
            'brand' => $brand ? $brand->name : '',
            'unit' => $unit,
            'description' => $description,
            'short_description' => $short_description,
            'main_photos' => $lastItem['storedFilePaths'] ?? $storedFilePaths, // Add stored file paths to the detailed product data
            'quantity' => $lastItem['variant_pricing-from']['from'][0] ?? $pricing['from'][0] ?? '',
            'price' => $lastItem['variant_pricing-from']['unit_price'][0] ?? $pricing['unit_price'][0] ?? $parent->unit_price,
            'total' => isset($lastItem['variant_pricing-from']['from'][0]) && isset($lastItem['variant_pricing-from']['unit_price'][0]) ? $lastItem['variant_pricing-from']['from'][0] * $lastItem['variant_pricing-from']['unit_price'][0] : $total,
            'general_attributes' => $attributesGeneralArray,
            'attributes' => $attributes ?? [],
            'from' => $pricing['from'] ?? [],
            'to' => $pricing['to'] ?? [],
            'unit_price' => $pricing['unit_price'] ?? [],
            'variations' => $variations,
            'variationId' => $variationId ?? null,
            'lastItem' => $lastItem ?? [],
            'product_id' => $parent->id,
            'shop_name' => $parent->getShopName(),
            'max' => $max ?? 1,
            'min' => $min ?? 1,
            'video_provider' => $video_provider,
            'getYoutubeVideoId' => $getYoutubeVideoId ?? null,
            'getVimeoVideoId' => $getVimeoVideoId ?? null,
            'discountedPrice' => $discountedPrice ?? null,
            'totalDiscount' => $totalDiscount ?? null,
            'date_range_pricing' => $pricing['date_range_pricing'] ?? null,
            'discount_type' => $pricing['discount_type'] ?? null,
            'discount_percentage' => $pricing['discount_percentage'],
            'discount_amount' => $pricing['discount_amount'],
            'percent' => $percent ?? null,
            'product_id' => $parent->id ?? null,
            'sku' => $lastItem['sku'] ?? $parent->sku ?? null,
            'tags' => $parent->tags ?? null,
            'category' => optional(Category::find($parent->category_id))->name,
            'documents' => UploadProducts::where('id_product', $parent->id)
                ->where('type', 'documents')
                ->get(),
            'ratingPercentages' => $ratingPercentages,
            'unit_of_sale' => $parent->unit ?? null,
            'outStock' => $outStock,
            "sampleDetails" => $parent->getSampleDetails(),
        ];
    }

    public function getYoutubeVideoId($videoLink)
    {
        // Parse the YouTube video URL to extract the video ID
        $videoId = '';
        parse_str(parse_url($videoLink, PHP_URL_QUERY), $queryParams);
        if (isset($queryParams['v'])) {
            $videoId = $queryParams['v'];
        }

        return $videoId;
    }

    public function getVimeoVideoId($videoLink)
    {
        // Parse the Vimeo video URL to extract the video ID
        $videoId = '';
        $regex = '/(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/';
        if (preg_match($regex, $videoLink, $matches)) {
            $videoId = $matches[1];
        }

        return $videoId;
    }

    public function updatePrice($request)
    {
        try {
            $data = $request->session()->get('productPreviewData', null);
            $variations = $data['detailedProduct']['variations'];

            $qty = $request->quantity;
            $totalDiscount = 0;
            $discountPrice = 0;
            // Iterate through the ranges
            $unitPrice = null;

            if (count($variations) > 0) {
                foreach ($variations[$request->variationId]['variant_pricing-from']['from'] as $index => $from) {
                    $to = $variations[$request->variationId]['variant_pricing-from']['to'][$index];

                    if ($qty >= $from && $qty <= $to) {
                        $unitPrice = $variations[$request->variationId]['variant_pricing-from']['unit_price'][$index];
                        if (isset($variations[$request->variationId]['variant_pricing-from']['discount']['date'][$index]) && ($variations[$request->variationId]['variant_pricing-from']['discount']['date'][$index])) {
                            // Extract start and end dates from the first date interval

                            $dateRange = $variations[$request->variationId]['variant_pricing-from']['discount']['date'][$index];
                            [$startDate, $endDate] = explode(' to ', $dateRange);

                            // Convert date strings to DateTime objects for comparison
                            $currentDate = new DateTime; // Current date/time
                            $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                            $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                            // Check if the current date/time is within the specified date interval
                            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                                if ($variations[$request->variationId]['variant_pricing-from']['discount']['type'][$index] == 'percent') {
                                    $percent = $variations[$request->variationId]['variant_pricing-from']['discount']['percentage'][$index];
                                    if ($percent) {
                                        // Calculate the discount amount based on the given percentage
                                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                        $discountAmount = ($unitPrice * $discountPercent) / 100;

                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;
                                    }
                                } elseif ($variations[$request->variationId]['variant_pricing-from']['discount']['type'][$index] == 'amount') {
                                    // Calculate the discount amount based on the given amount
                                    $amount = $variations[$request->variationId]['variant_pricing-from']['discount']['amount'][$index];

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
            } else {
                foreach ($data['detailedProduct']['from'] as $index => $from) {
                    $to = $data['detailedProduct']['to'][$index];
                    if ($qty >= $from && $qty <= $to) {
                        $unitPrice = $data['detailedProduct']['unit_price'][$index];

                        if (isset($data['detailedProduct']['date_range_pricing'][$index]) && ($data['detailedProduct']['date_range_pricing'][$index])) {
                            // Extract start and end dates from the first date interval

                            $dateRange = $data['detailedProduct']['date_range_pricing'][$index];
                            [$startDate, $endDate] = explode(' to ', $dateRange);

                            // Convert date strings to DateTime objects for comparison
                            $currentDate = new DateTime; // Current date/time
                            $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                            $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                            // Check if the current date/time is within the specified date interval
                            if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                                if ($data['detailedProduct']['discount_type'][$index] == 'percent') {
                                    $percent = $data['detailedProduct']['discount_percentage'][$index];

                                    if ($percent) {
                                        // Calculate the discount amount based on the given percentage
                                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                        $discountAmount = ($unitPrice * $discountPercent) / 100;

                                        // Calculate the discounted price
                                        $discountPrice = $unitPrice - $discountAmount;
                                    }
                                } elseif ($data['detailedProduct']['discount_type'][$index] == 'amount') {
                                    // Calculate the discount amount based on the given
                                    $amount = $data['detailedProduct']['discount_amount'][$index];

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

            $maximum = 1;
            $minimum = 1;

            if (count($variations) > 0) {
                // Convert array values to integers
                $valuesFrom = array_map('intval', $variations[$request->variationId]['variant_pricing-from']['from']);
                $valuesMax = array_map('intval', $variations[$request->variationId]['variant_pricing-from']['to']);
            } else {
                $valuesFrom = array_map('intval', $data['detailedProduct']['from']);
                $valuesMax = array_map('intval', $data['detailedProduct']['to']);
            }

            // Get the maximum value
            if (! empty($valuesMax)) {
                $maximum = max($valuesMax);
            }
            // Get the minimum value
            if (! empty($valuesFrom)) {
                $minimum = min($valuesFrom);
            }

            $total = $qty * $unitPrice;
            if (isset($discountPrice) && $discountPrice > 0) {
                $totalDiscount = $qty * $discountPrice;
            }

            return response()->json([
                'unit_price' => $unitPrice,
                'qty' => $qty,
                'total' => single_price($total),
                'sampleTotal' => single_price($data['detailedProduct']['sampleDetails']["sample_price"] * $qty),
                'maximum' => $maximum,
                'minimum' => $minimum,
                'totalDiscount' => $totalDiscount,
                'discountPrice' => $discountPrice,
                'percent' => $percent ?? null,
            ]);
        } catch (Exception $e) {
            Log::error("Error while updating product price, with message: {$e->getMessage()}");

            return response()->json([
                'error' => true,
                'message' => __("There's an error"),
            ]);
        }
    }

    public function approveProduct($request)
    {
        try {
            $product = Product::find($request->id_variant);

            if ($product != null) {
                if (count($product->getChildrenProducts())) {
                    foreach ($product->getChildrenProducts() as $children) {
                        //Attribute section
                        $attributes_id = DB::table('product_attribute_values')
                            ->where('id_products', $children->id)
                            ->pluck('id')
                            ->toArray();

                            if (($request->status != 1) && ($request->status != 4)) {
                            $historique_attributes = Revision::where('revisionable_type', 'App\Models\ProductAttributeValues')
                                ->whereIn('revisionable_id', $attributes_id)
                                ->get();

                            if (count($historique_attributes) > 0) {
                                foreach ($historique_attributes as $attribute_history) {
                                    $update = [];
                                    switch ($attribute_history->key) {
                                        case 'value':
                                            $update['value'] = $attribute_history->old_value;
                                            break;
                                        case 'id_units':
                                            $update['id_units'] = $attribute_history->old_value;
                                            break;
                                        case 'id_values':
                                            $update['id_values'] = $attribute_history->old_value;
                                            break;
                                        case 'id_colors':
                                            $update['id_colors'] = $attribute_history->old_value;
                                            break;
                                    }

                                    DB::table('product_attribute_values')
                                        ->where('id', $attribute_history->revisionable_id)
                                        ->update($update);
                                }
                            }
                        }

                        $historique_attributes = Revision::where('revisionable_type', 'App\Models\ProductAttributeValues')
                            ->whereIn('revisionable_id', $attributes_id)
                            ->delete();

                        //Product section
                        if (($request->status != 1) && ($request->status != 4)) {
                            $historique_product_informations = Revision::where('revisionable_type', 'App\Models\Product')
                                ->where('revisionable_id', $children->id)
                                ->get();

                            if (count($historique_product_informations) > 0) {
                                $data = [];
                                foreach ($historique_product_informations as $product_history) {
                                    $data[$product_history->key] = $product_history->old_value;
                                }

                                DB::table('products')
                                    ->where('id', $children->id)
                                    ->update($data);
                            }
                        }

                        $historique_product_informations = Revision::where('revisionable_type', 'App\Models\Product')
                            ->where('revisionable_id', $children->id)
                            ->delete();

                        //Images section & thumbnails
                        $images_ids = DB::table('upload_products')
                            ->where('id_product', $children->id)
                            ->where('type', 'images')
                            ->orWhere('type', 'thumbnails')
                            ->pluck('id')
                            ->toArray();

                        if (($request->status != 1) && ($request->status != 4)) {
                            $historique_images = Revision::whereIn('revisionable_id', $images_ids)
                                ->where('revisionable_type', 'App\Models\UploadProducts')
                                ->get();

                            if (count($historique_images) > 0) {
                                foreach ($historique_images as $image) {
                                    $uploaded = DB::table('upload_products')
                                        ->where('id', $image->new_value)
                                        ->first();

                                    if (file_exists(public_path($uploaded->path))) {
                                        unlink(public_path($uploaded->path));
                                    }

                                    $uploaded = DB::table('upload_products')
                                        ->where('id', $image->new_value)
                                        ->delete();
                                    }
                            }
                        }

                        $historique_images = Revision::whereIn('revisionable_id', $images_ids)
                            ->where('revisionable_type', 'App\Models\UploadProducts')
                            ->delete();

                        $children->approved = $request->status;
                        if (($request->status == 1) || ($request->status == 3)) {
                            $children->last_version = 0;
                        }
                        $children->save();
                    }
                }

                $attributes_id = DB::table('product_attribute_values')
                    ->where('id_products', $product->id)
                    ->pluck('id')
                    ->toArray();

                if (($request->status != 1) && ($request->status != 4)) {
                    $historique_attributes = Revision::where('revisionable_type', 'App\Models\ProductAttributeValues')
                        ->whereIn('revisionable_id', $attributes_id)
                        ->get();

                    if (count($historique_attributes) > 0) {
                        foreach ($historique_attributes as $attribute_history) {
                            $update = [];
                            switch ($attribute_history->key) {
                                case 'value':
                                    $update['value'] = $attribute_history->old_value;
                                    break;
                                case 'id_units':
                                    $update['id_units'] = $attribute_history->old_value;
                                    break;
                                case 'id_values':
                                    $update['id_values'] = $attribute_history->old_value;
                                    break;
                                case 'id_colors':
                                    $update['id_colors'] = $attribute_history->old_value;
                                    break;
                            }

                            DB::table('product_attribute_values')
                                ->where('id', $attribute_history->revisionable_id)
                                ->update($update);
                        }
                    }
                }

                $historique_attributes = Revision::where('revisionable_type', 'App\Models\ProductAttributeValues')
                    ->whereIn('revisionable_id', $attributes_id)
                    ->delete();

                //Product section
                if (($request->status != 1) && ($request->status != 4)) {
                    $historique_product_informations = Revision::where('revisionable_type', 'App\Models\Product')
                        ->where('revisionable_id', $product->id)
                        ->get();

                    if (count($historique_product_informations) > 0) {
                        $data = [];
                        foreach ($historique_product_informations as $product_history) {
                            $data[$product_history->key] = $product_history->old_value;
                        }

                        DB::table('products')->where('id', $product->id)->update($data);
                    }
                }

                $historique_product_informations = Revision::where('revisionable_type', 'App\Models\Product')
                    ->where('revisionable_id', $product->id)
                    ->delete();

                //Images section & thumbnails
                $images_ids = DB::table('upload_products')
                    ->where('id_product', $product->id)
                    ->where('type', 'images')
                    ->orWhere('type', 'thumbnails')
                    ->pluck('id')
                    ->toArray();

                if (($request->status != 1) && ($request->status != 4)) {
                    $historique_images = Revision::whereIn('revisionable_id', $images_ids)
                        ->where('revisionable_type', 'App\Models\UploadProducts')
                        ->get();

                    if (count($historique_images) > 0) {
                        foreach ($historique_images as $image) {
                            $uploaded = DB::table('upload_products')
                                ->where('id', $image->new_value)
                                ->first();

                            if (file_exists(public_path($uploaded->path))) {
                                unlink(public_path($uploaded->path));
                            }

                            $uploaded = DB::table('upload_products')->where('id', $image->new_value)->delete();
                        }
                    }
                }

                $historique_images = Revision::whereIn('revisionable_id', $images_ids)
                    ->where('revisionable_type', 'App\Models\UploadProducts')
                    ->delete();

                //Documents section
                $documents_ids = DB::table('upload_products')
                    ->where('id_product', $product->id)
                    ->where('type', 'documents')
                    ->pluck('id')
                    ->toArray();

                if (($request->status != 1) && ($request->status != 4)) {
                    $historique_documents = Revision::whereIn('revisionable_id', $documents_ids)
                        ->where('revisionable_type', 'App\Models\UploadProducts')
                        ->get();

                    if (count($historique_documents) > 0) {
                        foreach ($historique_documents as $document) {
                            $uploaded = DB::table('upload_products')
                                ->where('id', $document->revisionable_id)
                                ->first();

                            if ($document->key == 'add_document') {
                                if (file_exists(public_path($uploaded->path))) {
                                    unlink(public_path($uploaded->path));
                                }

                                $uploaded = DB::table('upload_products')
                                    ->where('id', $document->revisionable_id)
                                    ->delete();
                            } else {
                                $new_value = json_decode($document->new_value, true);
                                $old_value = json_decode($document->old_value, true);

                                if (file_exists(public_path($new_value['new_path']))) {
                                    unlink(public_path($new_value['new_path']));
                                }

                                $data = [];
                                $data['path'] = $old_value['old_path'];
                                $data['document_name'] = $old_value['old_document_name'];
                                $uploaded = DB::table('upload_products')
                                    ->where('id', $document->revisionable_id)
                                    ->update($data);
                            }
                        }
                    }
                }

                $historique_documents = Revision::whereIn('revisionable_id', $documents_ids)
                    ->where('revisionable_type', 'App\Models\UploadProducts')
                    ->delete();

                //check if status is Revision Required or Rejected to set the rejection reason
                if (($request->status == 2) || ($request->status == 3)) {
                    if ($request->status == 2) {
                        $status = 'Revision Required for '.$product->name.' Listing';
                        $text = 'Dear Mr/Mrs,
                    <br>We hope this message finds you well. Our team has reviewed the listing for <b>'.$product->name.'</b> on our marketplace and identified areas that require revision.
                    <br>Please note the necessary correction(s):<br> '.$request->reason.'<br>Kindly make the appropriate changes to ensure that the listing meets our marketplace standards. <br>We appreciate your prompt attention to this matter.
                    Thank you for your cooperation.
                    <br>Best regards,
                    <br>MAWADONLINE team.';
                    } else {
                        $status = 'Rejection Notification for Product Listing';
                        $text = 'Dear Mr/Mrs,
                    <br>I hope this email finds you well. <br>After careful review, we regret to inform you that the listing for <b>'.$product->name.'</b> on our marketplace has been rejected.
                    <br>The reason for rejection is as follows:<br> '.$request->reason.'<br>We understand that this may be disappointing, and we encourage you to review our marketplace guidelines to ensure future submissions meet our requirements.
                    <br>Thank you for your understanding.
                    <br>Best regards,
                    <br>MAWADONLINE team.';
                    }

                    $user = User::find($product->user_id);
                    Mail::to($user->email)->send(new ApprovalProductMail($status, $text));

                    $product->rejection_reason = $request->reason;
                } else {
                    $product->rejection_reason = null;
                }

                $product->approved = $request->status;

                if (($request->status == 1) || ($request->status == 3)) {
                    $product->last_version = 0;
                }

                $product->save();

                if ($request->status == APPROVED_STATUS) {
                    $this->copyProductChangesInCatalog($product->id);

                    $status = 'Your product has been approved on our marketplace';
                    $text = 'We are pleased to inform you that your product '.$product->name.' has been approved on our marketplace after review by our administration team.<br>

                        You can now view it online and track it through your seller account.<br>

                        We thank you for your trust and remain at your disposal for any questions.<br>

                        Best regards, <br>MAWADONLINE team.';

                    $user = User::find($product->user_id);
                    Mail::to($user->email)->send(new ApprovalProductMail($status, $text));
                }

                if ($request->status == UNDER_REVIEW_STATUS) {
                    $status = 'Your product is under review';

                    $text = 'We would like to inform you that your product '.$product->name.' is currently under review by our team. Our administration is carefully checking the details to ensure that it meets the standards of our marketplace.<br>

                        We will notify you as soon as the review is complete.<br>

                        Thank you for your patience and trust.<br>

                        Best regards, <br>MAWADONLINE team.';

                    $user = User::find($product->user_id);

                    Mail::to($user->email)->send(new ApprovalProductMail($status, $text));
                }

                return response()->json([
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'failed',
                ]);
            }
        } catch (Exception $e) {
            Log::error("Error while approving product, with message: {$e->getMessage()}");

            return response()->json(['error' => true, 'message' => __("There's an error")]);
        }
    }

    function copyProductChangesInCatalog($id)
    {
        $existingProduct = Product::find($id);

        $product_catalog_exist = ProductCatalog::where('product_id', $id)->first();
        if ($product_catalog_exist != null) {
            $childrens_catalog = ProductCatalog::where('parent_id', $product_catalog_exist->id)->pluck('id')->toArray();
            if (count($childrens_catalog) > 0) {
                ProductAttributeValueCatalog::whereIn('catalog_id', $childrens_catalog)->delete();
                UploadProductCatalog::whereIn('catalog_id', $childrens_catalog)->delete();
                ProductCatalog::where('parent_id', $product_catalog_exist->id)->delete();

                foreach ($childrens_catalog as $children_catalog_id) {
                    $path_children = public_path('/upload_products/Product-'.$children_catalog_id);
                    File::deleteDirectory($path_children);
                }
            }

            $path_parent = public_path('/upload_products/Product-'.$product_catalog_exist->id);
            File::deleteDirectory($path_parent);

            ProductAttributeValueCatalog::where('catalog_id', $product_catalog_exist->id)->delete();
            UploadProductCatalog::where('catalog_id', $product_catalog_exist->id)->delete();
            ProductCatalog::where('id', $product_catalog_exist->id)->delete();
        }

        if (! $existingProduct) {
            // Handle the case where the product with the specific ID doesn't exist
            return redirect()->back()->with('error', 'Product not found');
        }

        $data = $existingProduct->attributesToArray();
        // Make necessary updates to the attributes (if any)
        unset($data['id']);
        $data['product_id'] = $id;
        $newProduct = ProductCatalog::insertGetId($data);

        $path = public_path('/upload_products/Product-'.$id);
        $destinationFolder = public_path('/upload_products_catalog/Product-'.$newProduct);

        if (! File::isDirectory($destinationFolder)) {
            File::makeDirectory($destinationFolder);
        }

        if (File::isDirectory($path)) {
            File::copyDirectory($path, $destinationFolder);
        }

        $uploads = UploadProducts::where('id_product', $id)->get();
        $new_records = [];

        if (count($uploads) > 0) {
            foreach ($uploads as $file) {
                $current_file = [];
                $newPath = str_replace("/upload_products/Product-{$id}", "/upload_products_catalog/Product-{$newProduct}", $file->path);

                $current_file['catalog_id'] = $newProduct;
                $current_file['path'] = $newPath;
                $current_file['extension'] = $file->extension;
                $current_file['document_name'] = $file->document_name;
                $current_file['type'] = $file->type;

                array_push($new_records, $current_file);
            }

            if (count($new_records) > 0) {
                UploadProductCatalog::insert($new_records);
            }
        }

        $attributes = ProductAttributeValues::where('id_products', $id)->get();

        $new_records_attributes = [];

        if (count($attributes) > 0) {
            foreach ($attributes as $attribute) {
                $current_attribute = [];
                $current_attribute['catalog_id'] = $newProduct;
                $current_attribute['id_attribute'] = $attribute->id_attribute;
                $current_attribute['id_units'] = $attribute->id_units;
                $current_attribute['id_values'] = $attribute->id_values;
                $current_attribute['id_colors'] = $attribute->id_colors;
                $current_attribute['value'] = $attribute->value;
                $current_attribute['is_variant'] = $attribute->is_variant;
                $current_attribute['is_general'] = $attribute->is_general;

                array_push($new_records_attributes, $current_attribute);
            }

            if (count($new_records_attributes) > 0) {
                ProductAttributeValueCatalog::insert($new_records_attributes);
            }
        }

        if (count($existingProduct->getChildrenProducts()) > 0) {
            foreach ($existingProduct->getChildrenProducts() as $children) {
                $data = $children->attributesToArray();
                // Make necessary updates to the attributes (if any)
                unset($data['id']);
                $data['parent_id'] = $newProduct;
                $data['product_id'] = $children->id;
                $newProductChildren = ProductCatalog::insertGetId($data);

                $path = public_path('/upload_products/Product-'.$children->id);
                $destinationFolder = public_path('/upload_products_catalog/Product-'.$newProductChildren);
                if (! File::isDirectory($destinationFolder)) {
                    File::makeDirectory($destinationFolder);
                }

                if (File::isDirectory($path)) {
                    File::copyDirectory($path, $destinationFolder);
                }

                $uploads = UploadProducts::where('id_product', $children->id)->get();
                $new_records = [];
                if (count($uploads) > 0) {
                    foreach ($uploads as $file) {
                        $current_file = [];
                        $newPath = str_replace("/upload_products/Product-{$children->id}", "/upload_products_catalog/Product-{$newProductChildren}", $file->path);

                        $current_file['catalog_id'] = $newProductChildren;
                        $current_file['path'] = $newPath;
                        $current_file['extension'] = $file->extension;
                        $current_file['document_name'] = $file->document_name;
                        $current_file['type'] = $file->type;

                        array_push($new_records, $current_file);
                    }

                    if (count($new_records) > 0) {
                        UploadProductCatalog::insert($new_records);
                    }
                }

                $attributes = ProductAttributeValues::where('id_products', $children->id)->get();
                $new_records_attributes = [];

                if (count($attributes) > 0) {
                    foreach ($attributes as $attribute) {
                        $current_attribute = [];
                        $current_attribute['catalog_id'] = $newProductChildren;
                        $current_attribute['id_attribute'] = $attribute->id_attribute;
                        $current_attribute['id_units'] = $attribute->id_units;
                        $current_attribute['id_values'] = $attribute->id_values;
                        $current_attribute['id_colors'] = $attribute->id_colors;
                        $current_attribute['value'] = $attribute->value;
                        $current_attribute['is_variant'] = $attribute->is_variant;
                        $current_attribute['is_general'] = $attribute->is_general;

                        array_push($new_records_attributes, $current_attribute);
                    }

                    if (count($new_records_attributes) > 0) {
                        ProductAttributeValueCatalog::insert($new_records_attributes);
                    }
                }
            }
        }
    }
}
