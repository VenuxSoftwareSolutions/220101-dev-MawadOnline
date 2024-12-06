<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Revision;
use App\Models\Cart;
use App\Models\PricingConfiguration;
use App\Models\ProductAttributeValues;
use App\Models\StockSummary;
use App\Models\Unity;
use App\Models\UploadProducts;
use App\Models\Wishlist;
use Auth;
use App\Utility\CartUtility;
use Session;
use DateTime;
use DB;
use Str;
use App\Models\User;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;

            if ($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );

                Session::forget('temp_user_id');
            }

            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->get() : [];
        }

        $total = 0;

        $data = [];

        foreach ($carts as $key => $item) {
            $product_stock = StockSummary::where('variant_id', $item['product_id'])->sum('current_total_quantity');

            $product = get_single_product($item['product_id']);

            $total = $total + cart_product_price($item, $product, false) * $item['quantity'];
            $product_name_with_choice = str()->ucfirst($product->getTranslation('name'));
            $stockStatus = 'In Stock';
            $stockAlert = '';
            $outOfStockItems = [];

            $vendor_name = User::find($product->user_id)->shop->name;

            if ($item['variation'] != null) {
                $product_name_with_choice = sprintf(
                    "%s - %s: %s, %s",
                    str()->ucfirst(
                        $product->getTranslation('name')
                    ),
                    __("Vendor"),
                    $vendor_name,
                    $item['variation']
                );
            }

            if ($product_stock <= 0) {
                $stockStatus = translate('Out of Stock');
                $outOfStockItems[] = $item['id'];
            } elseif ($product_stock <= LOW_STOCK_THRESHOLD) {
                $stockAlert = translate('Running Low');
            }

            $data[$key] = [
                "product" => $product,
                "product_stock" => $product_stock,
                "total" => $total,
                "product_name_with_choice" => $product_name_with_choice,
                "stockStatus" =>$stockStatus,
                "outOfStockItems" => $outOfStockItems,
                "stockAlert" => $stockAlert
            ];

            if ($product_stock < $item->quantity) {
                if (auth()->user() != null) {
                    $existingWishlistItem = Wishlist::where('user_id', $user_id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    // Move to Wishlist if not already in the wishlist
                    if ($existingWishlistItem === null) {
                        Wishlist::create([
                            'user_id' => $user_id,
                            'product_id' => $item->product_id,
                        ]);
                    }
                }
                // Remove from Cart
                $item->delete();
            }
        }

        return view('frontend.view_cart', compact('carts', 'data'));
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

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        $parent  = Product::where('id', $request->id)->first();

        if ($parent != null) {
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
            $pricing['from'] = PricingConfiguration::where('id_products', $parent->id)->pluck('from')->toArray();
            $pricing['to'] = PricingConfiguration::where('id_products', $parent->id)->pluck('to')->toArray();
            $pricing['unit_price'] = PricingConfiguration::where('id_products', $parent->id)->pluck('unit_price')->toArray();
            $pricing['discount_type'] = PricingConfiguration::where('id_products', $parent->id)->pluck('discount_type')->toArray();
            $pricing['discount_amount'] = PricingConfiguration::where('id_products', $parent->id)->pluck('discount_amount')->toArray();
            $pricing['discount_percentage'] = PricingConfiguration::where('id_products', $parent->id)->pluck('discount_percentage')->toArray();

            $startDates = PricingConfiguration::where('id_products', $parent->id)->pluck('discount_start_datetime')->toArray();
            $endDates = PricingConfiguration::where('id_products', $parent->id)->pluck('discount_end_datetime')->toArray();
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
            $pricing_children = [];

            if ($parent->is_parent == 1) {
                $childrens_ids = Product::where('parent_id', $parent->id)->pluck('id')->toArray();

                foreach ($childrens_ids as $children_id) {
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
                        'date' => $discountPeriods
                    ];
                    $attributes_variant = ProductAttributeValues::where('id_products', $children_id)->where('is_variant', 1)->get();
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
                                    $variations[$children_id][$attribute->id_attribute] = $attribute->value . ' ' . $unit->name;
                                }
                            } else {
                                if ($revision_children_attribute->key != 'add_attribute') {
                                    $variations[$children_id][$attribute->id_attribute] = $revision_children_attribute->old_value;
                                }
                            }
                        } else {
                            if ($attribute->id_units != null) {
                                $unit = Unity::find($attribute->id_units);
                                if ($unit) {
                                    $variations[$children_id][$attribute->id_attribute] = $attribute->value . ' ' . $unit->name;
                                }
                            } else {
                                $variations[$children_id][$attribute->id_attribute] = $attribute->value;
                            }
                        }
                    }

                    if ($parent->last_version == 1) {
                        $images_children = UploadProducts::where('id_product', $children_id)->where('type', 'images')->get();
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
                        $variations[$children_id]['storedFilePaths'] = UploadProducts::where('id_product', $children_id)->where('type', 'images')->pluck('path')->toArray();
                    }
                }
            }
            $storedFilePaths = [];
            if ($parent->last_version == 1) {
                $images_parent = UploadProducts::where('id_product', $parent->id)->where('type', 'images')->get();
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
                $storedFilePaths = UploadProducts::where('id_product', $parent->id)->where('type', 'images')->pluck('path')->toArray();
            }

            if (count($storedFilePaths) == 0) {
                $url = public_path() . '/assets/img/placeholder.jpg';
                array_push($storedFilePaths, $url);
            }


            $attributes_general = ProductAttributeValues::where('id_products', $parent->id)->where('is_general', 1)->get();

            $attributesGeneralArray = [];
            foreach ($attributes_general as $attribute_general) {
                $revision_parent_attribute = Revision::whereNull('deleted_at')
                    ->where('revisionable_type', 'App\Models\ProductAttributeValues')
                    ->where('revisionable_id', $attribute_general->id)
                    ->latest()
                    ->first();

                if ($revision_parent_attribute != null && $parent->last_version == 1) {
                    if ($attribute->id_units != null) {
                        $unit = null;
                        if ($revision_parent_attribute->key = 'id_units') {
                            $unit = Unity::find($revision_parent_attribute->old_value);
                        } else {
                            $unit = Unity::find($attribute->id_units);
                        }

                        if ($unit) {
                            $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value . ' ' . $unit->name;
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
                            $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value . ' ' . $unit->name;
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
                        if ($attributeId != "storedFilePaths" && $attributeId != "variant_pricing-from") {
                            if (!isset($attributes[$attributeId])) {
                                $attributes[$attributeId] = [];
                            }
                            // Add value to the unique attributes array if it doesn't already exist
                            if (!in_array($value, $attributes[$attributeId])) {
                                $attributes[$attributeId][] = $value;
                            }
                        }
                    }
                }
            }

            if (is_array($variations) && !empty($variations)) {
                $lastItem  = $variations[$request->id];
                $variationId = $request->id;

                if (count($lastItem['variant_pricing-from']['to']) > 0) {
                    $max = max($lastItem['variant_pricing-from']['to']);
                }
                if (count($lastItem['variant_pricing-from']['from']) > 0) {
                    $min = min($lastItem['variant_pricing-from']['from']);
                }
            }

            if (isset($pricing['from']) && is_array($pricing['from']) && count($pricing['from']) > 0) {
                if (!isset($min))
                    $min = min($pricing['from']);
            }

            if (isset($pricing['to']) && is_array($pricing['to']) && count($pricing['to']) > 0) {
                if (!isset($max))
                    $max = max($pricing['to']);
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
                if ($revision_parent_video_provider->old_value === "youtube") {
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
                if ($parent->video_provider === "youtube") {
                    $getYoutubeVideoId = $this->getYoutubeVideoId($parent->video_link);
                } else {
                    $getVimeoVideoId = $this->getVimeoVideoId($parent->video_link);
                }
            }

            $total = isset($pricing['from'][0]) && isset($pricing['unit_price'][0]) ? $pricing['from'][0] * $pricing['unit_price'][0] : "";
            if (isset($lastItem['variant_pricing-from']['discount']['date']) && is_array($lastItem['variant_pricing-from']['discount']['date']) && !empty($lastItem['variant_pricing-from']['discount']['date']) &&  isset($lastItem['variant_pricing-from']['discount']['date'][0]) && $lastItem['variant_pricing-from']['discount']['date'][0] !== null) {
                // Extract start and end dates from the first date interval

                $dateRange = $lastItem['variant_pricing-from']['discount']['date'][0];
                list($startDate, $endDate) = explode(' to ', $dateRange);

                // Convert date strings to DateTime objects for comparison
                $currentDate = new DateTime(); // Current date/time
                $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                // Check if the current date/time is within the specified date interval
                if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                    // Assuming $lastItem is your array containing the pricing information
                    $unitPrice = $lastItem['variant_pricing-from']['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                    // Calculate the total price based on quantity and unit price
                    $variantPricing = $unitPrice;

                    if ($lastItem['variant_pricing-from']['discount']['type'][0] == "percent") {
                        $percent = $lastItem['variant_pricing-from']['discount']['percentage'][0];
                        if ($percent) {
                            // Calculate the discount amount based on the given percentage
                            $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                            $discountAmount = ($variantPricing * $discountPercent) / 100;

                            // Calculate the discounted price
                            $discountedPrice = $variantPricing - $discountAmount;
                        }
                    } else if ($lastItem['variant_pricing-from']['discount']['type'][0] == "amount") {
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
                if (isset($pricing['date_range_pricing']) && is_array($pricing['date_range_pricing']) && !empty($pricing['date_range_pricing']) && isset($pricing['date_range_pricing'][0]) && $pricing['date_range_pricing'][0] !== null) {
                    // Extract start and end dates from the first date interval

                    $dateRange = $pricing['date_range_pricing'][0];
                    list($startDate, $endDate) = explode(' to ', $dateRange);

                    // Convert date strings to DateTime objects for comparison
                    $currentDate = new DateTime(); // Current date/time
                    $startDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $startDate);
                    $endDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $endDate);

                    // Check if the current date/time is within the specified date interval
                    if ($currentDate >= $startDateTime && $currentDate <= $endDateTime) {
                        // Assuming $lastItem is your array containing the pricing information
                        $unitPrice = $pricing['unit_price'][0]; // Assuming 'unit_price' is the price per unit

                        // Calculate the total price based on quantity and unit price
                        $variantPricing = $unitPrice;

                        if ($pricing['discount_type'][0] == "percent") {
                            $percent = $pricing['discount_percentage'][0];
                            if ($percent) {
                                // Calculate the discount amount based on the given percentage
                                $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                $discountAmount = ($variantPricing * $discountPercent) / 100;

                                // Calculate the discounted price
                                $discountedPrice = $variantPricing - $discountAmount;
                            }
                        } else if ($pricing['discount_type'][0] == "amount") {
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

            $detailedProduct = [
                'name' => $name,
                'brand' => $brand ? $brand->name : "",
                'unit' => $unit,
                'description' => $description,
                'main_photos' => $lastItem['storedFilePaths'] ?? $storedFilePaths, // Add stored file paths to the detailed product data
                'quantity' => $lastItem['variant_pricing-from']['from'][0] ?? $pricing['from'][0] ?? '',
                'price' => $lastItem['variant_pricing-from']['unit_price'][0] ?? $pricing['unit_price'][0] ?? '',
                'total' => isset($lastItem['variant_pricing-from']['from'][0]) && isset($lastItem['variant_pricing-from']['unit_price'][0]) ? $lastItem['variant_pricing-from']['from'][0] * $lastItem['variant_pricing-from']['unit_price'][0] : $total,
                'general_attributes' => $attributesGeneralArray,
                'attributes' => $attributes ?? [],
                'from' => $pricing['from'] ?? [],
                'to' => $pricing['to']  ?? [],
                'unit_price' => $pricing['unit_price'] ?? [],
                'variations' => $variations,
                'variationId' => $variationId ?? null,
                'lastItem' => $lastItem ?? [],
                'product_id' => $parent->id,
                'shop_name' => $parent->getShopName(),
                'max' => $max ?? 1,
                'min' => $min ?? 1,
                'video_provider'  => $video_provider,
                'getYoutubeVideoId' => $getYoutubeVideoId ?? null,
                'getVimeoVideoId' => $getVimeoVideoId ?? null,
                'discountedPrice' => $discountedPrice ?? null,
                'totalDiscount' => $totalDiscount ?? null,
                'date_range_pricing' =>  $pricing['date_range_pricing']  ?? null,
                'discount_type' => $pricing['discount_type'] ?? null,
                'discount_percentage' => $pricing['discount_percentage'],
                'discount_amount' => $pricing['discount_amount'],
                'percent' => $percent ?? null,
                'product_id' => $parent->id ?? null,
            ];

            $previewData['detailedProduct'] = $detailedProduct;
            session(['productPreviewData' => $previewData]);

            $viewPath = 'frontend.' . get_setting('homepage_select') . '.partials.addToCart';

            return view($viewPath, compact('product', 'previewData'));
        }
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
    {
        // Get product preview data from session
        $dataProduct = $request->session()->get('productPreviewData', null);

        // Determine the user ID; use temp_user_id if not logged in
        $userId = auth()->check() ? auth()->user()->id : $request->session()->get('temp_user_id');

        // If the user is not authenticated and temp_user_id doesn't exist, generate a new temp_user_id
        if (!auth()->check() && !$userId) {
            $userId = (string) Str::uuid();
            $request->session()->put('temp_user_id', $userId);
        }

        // Fetch existing cart items using user_id or temp_user_id
        $carts = Cart::where(
            auth()->check() ? 'user_id' : 'temp_user_id',
            $userId
        )->get();

        $product = Product::find($request->variationId);

        // Minimum quantity validation
        $min_from_value = PricingConfiguration::where('id_products', $request['variationId'])->min('from');

        $quantity = $request['quantity'];

        if ($quantity < $min_from_value) {
            return [
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.' . get_setting('homepage_select') . '.partials.minQtyNotSatisfied', ['min_qty' => $min_from_value])->render(),
                'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
            ];
        }

        // Check the product variant details
        $str = $product->productVariantDetails();
        $product_stock = StockSummary::where('variant_id', $request['variationId'])->sum('current_total_quantity');

        if ($product_stock < $request['quantity']) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.' . get_setting('homepage_select') . '.partials.outOfStockCart')->render(),
                'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
            );
        }

        // Create or update the cart item based on user_id or temp_user_id
        $data = [
            'variation' => $str,
            'product_id' => $request['variationId']
        ];

        if (auth()->check()) {
            $data["user_id"] = $userId;
        } else {
            $data["temp_user_id"] = $userId;
        }

        $cart = Cart::firstOrNew($data);

        // Handle product-specific validations
        if ($cart->exists && $product->digital == 0) {
            if ($product->auction_product == 1 && ($cart->product_id == $product->id)) {
                return [
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.' . get_setting('homepage_select') . '.partials.auctionProductAlredayAddedCart')->render(),
                    'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
                ];
            }

            if ($product_stock < $cart->quantity + $request['quantity']) {
                return [
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.' . get_setting('homepage_select') . '.partials.outOfStockCart')->render(),
                    'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
                ];
            }
            $quantity = $cart->quantity + $request['quantity'];
        }

        // Calculate the price and tax
        $price = CartUtility::priceProduct($request->variationId, $request->quantity);

        $tax = 0;

        // Save the cart data
        CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);

        // Fetch updated cart items
        $carts = Cart::where(
            auth()->check() ? 'user_id' : 'temp_user_id',
            $userId
        )->get();

        $carts->each(function($cart) {
            $cart->user->wishlists->each(fn($wishlist) => $wishlist->delete());
        });

        return [
            'status' => 1,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.' . get_setting('homepage_select') . '.partials.addedToCart', compact('product', 'cart'))->render(),
            'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
        ];
    }

    public function removeFromCart(Request $request)
    {
        Cart::destroy($request->id);

        if (auth()->user() != null) {
            $carts = Cart::where('user_id', auth()->user()->id)->get();
        } else {
            $carts = Cart::where('temp_user_id', $request->session()->get('temp_user_id'))->get();
        }

        $total = 0;

        $data = [];

        foreach ($carts as $key => $item) {
            $product_stock = StockSummary::where('variant_id', $item['product_id'])->sum('current_total_quantity');

            $product = get_single_product($item['product_id']);

            $total = $total + cart_product_price($item, $product, false) * $item['quantity'];
            $product_name_with_choice = str()->ucfirst($product->getTranslation('name'));
            $stockStatus = 'In Stock';
            $stockAlert = '';
            $outOfStockItems = [];

            if ($item['variation'] != null) {
                $product_name_with_choice = str()->ucfirst(
                    $product->getTranslation('name')
                ) . ' - ' . $item['variation'];
            }

            if ($product_stock <= 0) {
                $stockStatus = translate('Out of Stock');
                $outOfStockItems[] = $item['id'];
            } elseif ($product_stock <= LOW_STOCK_THRESHOLD) {
                $stockAlert = translate('Running Low');
            }

            $data[$key] = [
                "product" => $product,
                "product_stock" => $product_stock,
                "total" => $total,
                "product_name_with_choice" => $product_name_with_choice,
                "stockStatus" =>$stockStatus,
                "outOfStockItems" => $outOfStockItems,
                "stockAlert" => $stockAlert
            ];

            if ($product_stock < $item->quantity) {
                if (auth()->user() != null) {
                    $existingWishlistItem = Wishlist::where('user_id', auth()->user()->id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    // Move to Wishlist if not already in the wishlist
                    if ($existingWishlistItem === null) {
                        Wishlist::create([
                            'user_id' => auth()->user()->id,
                            'product_id' => $item->product_id,
                        ]);
                    }
                }
                // Remove from Cart
                $item->delete();
            }
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart_details', compact('carts', 'data'))->render(),
            'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    // public function updateQuantity(Request $request)
    // {
    //     $cartItem = Cart::findOrFail($request->id);

    //     if ($cartItem['id'] == $request->id) {
    //         $product = Product::find($cartItem['product_id']);
    //         $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
    //         $quantity = $product_stock->qty;
    //         $price = $product_stock->price;

    //         //discount calculation
    //         $discount_applicable = false;

    //         if ($product->discount_start_date == null) {
    //             $discount_applicable = true;
    //         } elseif (
    //             strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
    //             strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
    //         ) {
    //             $discount_applicable = true;
    //         }

    //         if ($discount_applicable) {
    //             if ($product->discount_type == 'percent') {
    //                 $price -= ($price * $product->discount) / 100;
    //             } elseif ($product->discount_type == 'amount') {
    //                 $price -= $product->discount;
    //             }
    //         }

    //         if ($quantity >= $request->quantity) {
    //             if ($request->quantity >= $product->min_qty) {
    //                 $cartItem['quantity'] = $request->quantity;
    //             }
    //         }

    //         if ($product->wholesale_product) {
    //             $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
    //             if ($wholesalePrice) {
    //                 $price = $wholesalePrice->price;
    //             }
    //         }

    //         $cartItem['price'] = $price;
    //         $cartItem->save();
    //     }

    //     if (auth()->user() != null) {
    //         $user_id = Auth::user()->id;
    //         $carts = Cart::where('user_id', $user_id)->get();
    //     } else {
    //         $temp_user_id = $request->session()->get('temp_user_id');
    //         $carts = Cart::where('temp_user_id', $temp_user_id)->get();
    //     }

    //     return array(
    //         'cart_count' => count($carts),
    //         'cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart_details', compact('carts'))->render(),
    //         'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    //     );
    // }
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);

        if ($cartItem['id'] == $request->id) {
            $product = Product::find($cartItem['product_id']);
            $price = CartUtility::priceProduct($product->id, $request->quantity);

            $cartItem['price'] = $price;
            $cartItem['quantity'] = $request->quantity;
            $cartItem->save();
        }

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        $total = 0;

        $data = [];

        foreach ($carts as $key => $item) {
            $product_stock = StockSummary::where('variant_id', $item['product_id'])->sum('current_total_quantity');

            $product = get_single_product($item['product_id']);

            $total = $total + cart_product_price($item, $product, false) * $item['quantity'];
            $product_name_with_choice = str()->ucfirst($product->getTranslation('name'));
            $stockStatus = 'In Stock';
            $stockAlert = '';
            $outOfStockItems = [];

            if ($item['variation'] != null) {
                $product_name_with_choice = str()->ucfirst(
                    $product->getTranslation('name')
                ) . ' - ' . $item['variation'];
            }

            if ($product_stock <= 0) {
                $stockStatus = translate('Out of Stock');
                $outOfStockItems[] = $item['id'];
            } elseif ($product_stock <= LOW_STOCK_THRESHOLD) {
                $stockAlert = translate('Running Low');
            }

            $data[$key] = [
                "product" => $product,
                "product_stock" => $product_stock,
                "total" => $total,
                "product_name_with_choice" => $product_name_with_choice,
                "stockStatus" =>$stockStatus,
                "outOfStockItems" => $outOfStockItems,
                "stockAlert" => $stockAlert
            ];

            if ($product_stock < $item->quantity) {
                if (auth()->user() != null) {
                    $existingWishlistItem = Wishlist::where('user_id', auth()->user()->id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    // Move to Wishlist if not already in the wishlist
                    if ($existingWishlistItem === null) {
                        Wishlist::create([
                            'user_id' => auth()->user()->id,
                            'product_id' => $item->product_id,
                        ]);
                    }
                }
                // Remove from Cart
                $item->delete();
            }
        }
        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart_details', compact('carts', 'data'))->render(),
            'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
        );
    }
}
