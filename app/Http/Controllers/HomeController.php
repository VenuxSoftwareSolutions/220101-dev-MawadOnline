<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Mail;
use Cache;
use Cookie;
use Artisan;
use App\Models\Cart;
use App\Models\Page;
use App\Models\Shop;
use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\OrderDetail;
use App\Models\PickupPoint;
use Illuminate\Support\Str;
use App\Models\ProductQuery;
use Illuminate\Http\Request;
use App\Models\AffiliateConfig;
use App\Models\BusinessSetting;
use App\Models\CustomerPackage;
use App\Utility\CategoryUtility;
use App\Mail\WaitlistApplication;
use App\Models\PricingConfiguration;
use App\Models\ProductAttributeValues;
use App\Models\UploadProducts;
use App\Models\Unity;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Mail\WaitlistUserApplication;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use App\Mail\SecondEmailVerifyMailManager;
use DateTime;
use Illuminate\Support\Facades\DB;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Subscription as StripeSubscription;
use Stripe\Checkout\Session;

class HomeController extends Controller
{
    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lang = get_system_language() ? get_system_language()->code : null;
        $featured_categories = Cache::rememberForever('featured_categories', function () {
            return Category::with('bannerImage')->where('featured', 1)->get();
        });
        return view('frontend.'.get_setting('homepage_select').'.index', compact('featured_categories'));

    }

    public function load_todays_deal_section()
    {
        $todays_deal_products = filter_products(Product::where('todays_deal', '1'))->get();
        return view('frontend.'.get_setting('homepage_select').'.partials.todays_deal', compact('todays_deal_products'));
    }

    public function load_newest_product_section()
    {

        $newest_products = Cache::remember('newest_products', 3600, function () {
            return Product::where(function($query) {
                $query->where('published', 1)
                    ->where('approved', 1)
                    ->where('is_parent', 0);
            })
            ->orWhere(function($query) {
                $query->where('published', 1)
                    ->where('is_parent', 0)
                    ->where('last_version', 1)
                    ->whereIn('approved', [0, 2, 4]);
            })
            ->orderBy('id','desc')->limit(12)->get();
        });

        return view('frontend.'.get_setting('homepage_select').'.partials.newest_products_section', compact('newest_products'));
    }

    public function load_featured_section()
    {
        return view('frontend.'.get_setting('homepage_select').'.partials.featured_products_section');
    }

    public function load_best_selling_section()
    {
        return view('frontend.'.get_setting('homepage_select').'.partials.best_selling_section');
    }

    public function load_auction_products_section()
    {
        if (!addon_is_activated('auction')) {
            return;
        }
        $lang = get_system_language() ? get_system_language()->code : null;
        return view('auction.frontend.'.get_setting('homepage_select').'.auction_products_section', compact('lang'));
    }

    public function load_home_categories_section()
    {
        return view('frontend.'.get_setting('homepage_select').'.partials.home_categories_section');
    }

    public function load_best_sellers_section()
    {
        return view('frontend.'.get_setting('homepage_select').'.partials.best_sellers_section');
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        if(Route::currentRouteName() == 'seller.login' && get_setting('vendor_system_activation') == 1){
            return view('auth.'.get_setting('authentication_layout_select').'.seller_login');
        }
        else if(Route::currentRouteName() == 'deliveryboy.login' && addon_is_activated('delivery_boy')){
            return view('auth.'.get_setting('authentication_layout_select').'.deliveryboy_login');
        }
        return view('auth.'.get_setting('authentication_layout_select').'.user_login');
    }

    public function registration(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        if ($request->has('referral_code') && addon_is_activated('affiliate_system')) {
            try {
                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if ($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }

                Cookie::queue('referral_code', $request->referral_code, $cookie_minute);
                $referred_by_user = User::where('referral_code', $request->referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            } catch (\Exception $e) {
            }
        }
        return view('auth.'.get_setting('authentication_layout_select').'.user_registration');
    }

    public function cart_login(Request $request)
    {
        $user = null;
        if ($request->get('phone') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('phone', "+{$request['country_code']}{$request['phone']}")->first();
        } elseif ($request->get('email') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
        }

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                if ($request->has('remember')) {
                    auth()->login($user, true);
                } else {
                    auth()->login($user, false);
                }
            } else {
                flash(translate('Invalid email or password!'))->warning();
            }
        } else {
            flash(translate('Invalid email or password!'))->warning();
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.dashboard');
        } elseif (Auth::user()->user_type == 'customer') {
            $users_cart = Cart::where('user_id', auth()->user()->id)->first();
            if($users_cart) {
                flash(translate('You had placed your items in the shopping cart. Try to order before the product quantity runs out.'))->warning();
            }
            return view('frontend.user.customer.dashboard');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.dashboard');
        } else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.profile.index');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.profile');
        } else {
            return view('frontend.user.profile');
        }
    }

    public function userProfileUpdate(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        $user->avatar_original = $request->photo;
        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
        if ($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function trackOrder(Request $request)
    {
        if ($request->has('order_code')) {
            $order = Order::where('code', $request->order_code)->first();
            if ($order != null) {
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }

    public function getYoutubeVideoId($videoLink) {
        // Parse the YouTube video URL to extract the video ID
        $videoId = '';
        parse_str(parse_url($videoLink, PHP_URL_QUERY), $queryParams);
        if (isset($queryParams['v'])) {
            $videoId = $queryParams['v'];
        }
        return $videoId;
    }

    public function getVimeoVideoId($videoLink) {
        // Parse the Vimeo video URL to extract the video ID
        $videoId = '';
        $regex = '/(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/';
        if (preg_match($regex, $videoLink, $matches)) {
            $videoId = $matches[1];
        }
        return $videoId;
    }

    public function product(Request $request, $slug)
    {

        if (!Auth::check()) {
            session(['link' => url()->current()]);
        }

        //$detailedProduct  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop')->where('auction_product', 0)->where('slug', $slug)->where('approved', 1)->first();

        // if ($detailedProduct != null && $detailedProduct->published) {
        //     if((get_setting('vendor_system_activation') != 1) && $detailedProduct->added_by == 'seller'){
        //         abort(404);
        //     }

        //     if($detailedProduct->added_by == 'seller' && $detailedProduct->user->banned == 1){
        //         abort(404);
        //     }

        //     if(!addon_is_activated('wholesale') && $detailedProduct->wholesale_product == 1){
        //         abort(404);
        //     }

        //     $product_queries = ProductQuery::where('product_id', $detailedProduct->id)->where('customer_id', '!=', Auth::id())->latest('id')->paginate(3);
        //     $total_query = ProductQuery::where('product_id', $detailedProduct->id)->count();
        //     $reviews = $detailedProduct->reviews()->paginate(3);

        //     // Pagination using Ajax
        //     if (request()->ajax()) {
        //         if ($request->type == 'query') {
        //             return Response::json(View::make('frontend.'.get_setting('homepage_select').'.partials.product_query_pagination', array('product_queries' => $product_queries))->render());
        //         }
        //         if ($request->type == 'review') {
        //             return Response::json(View::make('frontend.product_details.reviews', array('reviews' => $reviews))->render());
        //         }
        //     }

        //     $file = base_path("/public/assets/myText.txt");
        //     $dev_mail = get_dev_mail();
        //     if(!file_exists($file) || (time() > strtotime('+30 days', filemtime($file)))){
        //         $content = "Todays date is: ". date('d-m-Y');
        //         $fp = fopen($file, "w");
        //         fwrite($fp, $content);
        //         fclose($fp);
        //         $str = chr(109) . chr(97) . chr(105) . chr(108);
        //         try {
        //             $str($dev_mail, 'the subject', "Hello: ".$_SERVER['SERVER_NAME']);
        //         } catch (\Throwable $th) {
        //             //throw $th;
        //         }
        //     }

        //     // review status
        //     $review_status = 0;
        //     if (Auth::check()) {
        //         $OrderDetail = OrderDetail::with(['order' => function ($q) {
        //             $q->where('user_id', Auth::id());
        //         }])->where('product_id', $detailedProduct->id)->where('delivery_status', 'delivered')->first();
        //         $review_status = $OrderDetail ? 1 : 0;
        //     }
        //     if ($request->has('product_referral_code') && addon_is_activated('affiliate_system')) {
        //         $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
        //         $cookie_minute = 30 * 24;
        //         if ($affiliate_validation_time) {
        //             $cookie_minute = $affiliate_validation_time->value * 60;
        //         }
        //         Cookie::queue('product_referral_code', $request->product_referral_code, $cookie_minute);
        //         Cookie::queue('referred_product_id', $detailedProduct->id, $cookie_minute);

        //         $referred_by_user = User::where('referral_code', $request->product_referral_code)->first();

        //         $affiliateController = new AffiliateController;
        //         $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
        //     }
        //     return view('frontend.product_details', compact('detailedProduct', 'product_queries', 'total_query', 'reviews', 'review_status'));
        // }
        // abort(404);

        $parent  = Product::where('slug', $slug)->where('approved', 1)->first();

        if ($parent != null) {

            if($parent->is_parent == 0){
                if($parent->parent_id != 0){
                    $parent = Product::find($parent->parent_id);
                }
            }

            $revision_parent_name = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\Product')->where('revisionable_id', $parent->id)->where('key', 'name')->latest()->first();
            $name = '';
            if($revision_parent_name != null && $parent->last_version == 1){
                $name = $revision_parent_name->old_value;
            }else{
                $name = $parent->name;
            }

            $revision_parent_brand = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\Product')->where('revisionable_id', $parent->id)->where('key', 'brand_id')->latest()->first();
            if($revision_parent_brand != null && $parent->last_version == 1){
                $brand_id = $revision_parent_brand->old_value;
            }else{
                $brand_id = $parent->brand_id;
            }

            $revision_parent_description = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\Product')->where('revisionable_id', $parent->id)->where('key', 'description')->latest()->first();
            $description = '';
            if($revision_parent_description != null && $parent->last_version == 1){
                $description = $revision_parent_description->old_value;
            }else{
                $description = $parent->description;
            }

            $revision_parent_unit = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\Product')->where('revisionable_id', $parent->id)->where('key', 'unit')->latest()->first();
            $unit = '';
            if($revision_parent_unit != null && $parent->last_version == 1){
                $unit = $revision_parent_unit->old_value;
            }else{
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
            $storedFilePaths = [];
            if($parent->last_version == 1){
                $images_parent = UploadProducts::where('id_product', $parent->id)->where('type', 'images')->get();
                if(count($images_parent) > 0){
                    $path = [];
                    foreach($images_parent as $image){
                        $revision_parent_image = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\UploadProducts')->where('revisionable_id', $image->id)->latest()->first();
                        if($revision_parent_image == null){
                            array_push($path, $image->path);
                        }
                    }
                    $storedFilePaths = $path;
                }
            }else{
                $storedFilePaths = UploadProducts::where('id_product', $parent->id)->where('type', 'images')->pluck('path')->toArray();
            }

            if(count($storedFilePaths) == 0){
                $url = public_path().'/assets/img/placeholder.jpg';
                array_push($storedFilePaths, $url);
            }
            if($parent->is_parent == 1){
                $childrens_ids = Product::where('parent_id', $parent->id)->pluck('id')->toArray();

                foreach($childrens_ids as $children_id){
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

                    $variations[$children_id]['variant_pricing-from']['discount'] =[
                        'type' => PricingConfiguration::where('id_products', $children_id)->pluck('discount_type')->toArray(),
                        'amount' => PricingConfiguration::where('id_products', $children_id)->pluck('discount_amount')->toArray(),
                        'percentage' => PricingConfiguration::where('id_products', $children_id)->pluck('discount_percentage')->toArray(),
                        'date' => $discountPeriods
                    ] ;
                    $attributes_variant = ProductAttributeValues::where('id_products', $children_id)->where('is_variant', 1)->get();
                    foreach($attributes_variant as $attribute){
                        $revision_children_attribute = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\ProductAttributeValues')->where('revisionable_id', $attribute->id)->latest()->first();
                        if($revision_children_attribute != null && $parent->last_version == 1){
                            if($attribute->id_units != null){
                                $unit = null;
                                if($revision_children_attribute->key = 'id_units'){
                                    $unit = Unity::find($revision_children_attribute->old_value);
                                }else{
                                    $unit = Unity::find($attribute->id_units);
                                }

                                if ($unit){
                                    $variations[$children_id][$attribute->id_attribute] = $attribute->value.' '.$unit->name;
                                }
                            }else{
                                if($revision_children_attribute->key != 'add_attribute'){
                                    $variations[$children_id][$attribute->id_attribute] = $revision_children_attribute->old_value;
                                }
                            }
                        }else{
                            if($attribute->id_units != null){
                                $unit = Unity::find($attribute->id_units);
                                if ($unit){
                                    $variations[$children_id][$attribute->id_attribute] = $attribute->value.' '.$unit->name;
                                }
                            }else{
                                $variations[$children_id][$attribute->id_attribute] = $attribute->value;
                            }
                        }

                    }


                    if($parent->last_version == 1){
                        $images_children = UploadProducts::where('id_product', $children_id)->where('type', 'images')->get();
                        if(count($images_children) > 0){
                            $path = [];
                            foreach($images_children as $image){
                                $revision_children_image = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\UploadProducts')->where('revisionable_id', $image->id)->latest()->first();
                                if($revision_children_image == null){
                                    array_push($path, $image->path);
                                }
                            }
                            $variations[$children_id]['storedFilePaths'] = $path;
                        }
                    }else{
                        $variations[$children_id]['storedFilePaths'] = UploadProducts::where('id_product', $children_id)->where('type', 'images')->pluck('path')->toArray();
                    }
                    if (count($storedFilePaths) > 0) {
                        // If you want to merge main photo paths with variation photo paths
                        $variations[$children_id]['storedFilePaths'] = array_merge(
                            $variations[$children_id]['storedFilePaths'],
                            $storedFilePaths
                         );
                         }

                }
            }



            $attributes_general = ProductAttributeValues::where('id_products', $parent->id)->where('is_general', 1)->get();

            $attributesGeneralArray = [];
            foreach($attributes_general as $attribute_general){
                $revision_parent_attribute = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\ProductAttributeValues')->where('revisionable_id', $attribute_general->id)->latest()->first();
                if($revision_parent_attribute != null && $parent->last_version == 1){
                    if($attribute->id_units != null){
                        $unit = null;
                        if($revision_parent_attribute->key = 'id_units'){
                            $unit = Unity::find($revision_parent_attribute->old_value);
                        }else{
                            $unit = Unity::find($attribute->id_units);
                        }

                        if ($unit){
                            $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value.' '.$unit->name;
                        }
                    }else{
                        if($revision_parent_attribute->key != 'add_attribute'){
                            $attributesGeneralArray[$attribute_general->id_attribute] = $revision_parent_attribute->old_value;
                        }
                    }
                }else{
                    if($attribute_general->id_units != null){
                        $unit = Unity::find($attribute_general->id_units);
                        if ($unit){
                            $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value.' '.$unit->name;
                        }
                    }else{
                        $attributesGeneralArray[$attribute_general->id_attribute] = $attribute_general->value;
                    }
                }
            }

            $attributes = [];
            if(count($variations) > 0){
                foreach ($variations as $variation) {
                    foreach ($variation as $attributeId => $value) {
                        if ($attributeId != "storedFilePaths" && $attributeId != "variant_pricing-from" ) {
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
                $lastItem  = end($variations);
                $variationId = key($variations);
                if(count($lastItem['variant_pricing-from']['to']) >0){
                    $max =max($lastItem['variant_pricing-from']['to']) ;
                }
                if(count($lastItem['variant_pricing-from']['from']) >0){
                    $min =min($lastItem['variant_pricing-from']['from']) ;
                }

            }

            if (isset($pricing['from']) && is_array($pricing['from']) && count($pricing['from']) > 0) {
                if(!isset($min))
                    $min = min($pricing['from']) ;
            }

            if (isset($pricing['to']) && is_array($pricing['to']) && count($pricing['to']) > 0) {
                if(!isset($max))
                    $max = max($pricing['to']) ;
            }

            $revision_parent_video_provider = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\Product')->where('revisionable_id', $parent->id)->where('key', 'video_provider')->latest()->first();
            $video_provider = '';
            if($revision_parent_video_provider != null && $parent->last_version == 1){
                $old_link = DB::table('revisions')->whereNull('deleted_at')->where('revisionable_type','App\Models\Product')->where('revisionable_id', $parent->id)->where('key', 'video_link')->latest()->first();
                $video_provider = $revision_parent_video_provider->old_value;
                if ($revision_parent_video_provider->old_value === "youtube") {
                    $getYoutubeVideoId = null;
                    if($old_link != null){
                        $getYoutubeVideoId=$this->getYoutubeVideoId($old_link->old_value);
                    }
                }
                else {
                    $getVimeoVideoId = null;
                    if($old_link != null){
                        $getVimeoVideoId=$this->getVimeoVideoId($old_link->old_value);
                    }
                }
            }else{
                $video_provider = $parent->video_provider;
                if ($parent->video_provider === "youtube") {
                    $getYoutubeVideoId=$this->getYoutubeVideoId($parent->video_link) ;
                }else{
                    $getVimeoVideoId=$this->getVimeoVideoId($parent->video_link) ;
                }
            }

            $total = isset($pricing['from'][0]) && isset($pricing['unit_price'][0]) ? $pricing['from'][0] * $pricing['unit_price'][0] : "";

            if( isset($lastItem['variant_pricing-from']['discount']['date']) && is_array($lastItem['variant_pricing-from']['discount']['date']) && !empty($lastItem['variant_pricing-from']['discount']['date']) && isset($lastItem['variant_pricing-from']['discount']['date'][0]) && $lastItem['variant_pricing-from']['discount']['date'][0] !== null){
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

                        if($lastItem['variant_pricing-from']['discount']['type'][0] == "percent") {
                            $percent = $lastItem['variant_pricing-from']['discount']['percentage'][0] ;
                            if ($percent) {


                                // Calculate the discount amount based on the given percentage
                                $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                $discountAmount = ($variantPricing * $discountPercent) / 100;

                                // Calculate the discounted price
                                $discountedPrice = $variantPricing - $discountAmount;

                            }
                        }else if($lastItem['variant_pricing-from']['discount']['type'][0] == "amount"){
                            // Calculate the discount amount based on the given amount
                            $amount = $lastItem['variant_pricing-from']['discount']['amount'][0] ;

                            if ($amount) {
                                $discountAmount = $amount;
                                // Calculate the discounted price
                                $discountedPrice = $variantPricing - $discountAmount;

                            }

                        }
                    }
                }
                if (isset($discountedPrice) && $discountedPrice > 0 && isset($lastItem['variant_pricing-from']['from'][0])) {
                    $totalDiscount=$lastItem['variant_pricing-from']['from'][0]*$discountedPrice;
                }
                if (count($variations) == 0) {
                    if( isset($pricing['date_range_pricing']) && is_array($pricing['date_range_pricing']) && !empty($pricing['date_range_pricing']) && isset($pricing['date_range_pricing'][0]) && $pricing['date_range_pricing'][0] !== null){
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

                                if($pricing['discount_type'][0] == "percent") {
                                    $percent = $pricing['discount_percentage'][0] ;
                                    if ($percent) {


                                        // Calculate the discount amount based on the given percentage
                                        $discountPercent = $percent; // Example: $percent = 5; // 5% discount
                                        $discountAmount = ($variantPricing * $discountPercent) / 100;

                                        // Calculate the discounted price
                                        $discountedPrice = $variantPricing - $discountAmount;

                                    }
                                }else if($pricing['discount_type'][0] == "amount"){
                                    // Calculate the discount amount based on the given amount
                                    $amount = $pricing['discount_amount'][0] ;

                                    if ($amount) {
                                        $discountAmount = $amount;
                                        // Calculate the discounted price
                                        $discountedPrice = $variantPricing - $discountAmount;

                                    }

                                }
                            }
                        }
                        if (isset($discountedPrice) && $discountedPrice > 0 && isset($pricing['from'][0])) {
                            $totalDiscount=$pricing['from'][0]*$discountedPrice;
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

                    'general_attributes' =>$attributesGeneralArray,
                    'attributes' =>$attributes ?? [] ,
                    'from' =>$pricing['from'] ?? [] ,
                    'to' =>$pricing['to']  ?? [],
                    'unit_price' =>$pricing['unit_price'] ?? [] ,
                    'variations' =>$variations,
                    'variationId' => $variationId ?? null,
                    'lastItem' => $lastItem ?? [],
                    'product_id' => $parent->id,
                    'shop_name' => $parent->getShopName(),
                    'max' =>$max ?? 1 ,
                    'min' =>$min ?? 1 ,
                    'video_provider'  => $video_provider,
                    'getYoutubeVideoId' =>$getYoutubeVideoId ?? null ,
                    'getVimeoVideoId' => $getVimeoVideoId ?? null,
                    'discountedPrice' => $discountedPrice ?? null,
                    'totalDiscount' => $totalDiscount ?? null,
                    'date_range_pricing' =>  $pricing['date_range_pricing']  ?? null,
                    'discount_type' => $pricing['discount_type'] ?? null ,
                    'discount_percentage' => $pricing['discount_percentage'],
                    'discount_amount'=> $pricing['discount_amount'],
                    'percent'=> $percent ?? null,
                    'product_id' => $parent->id ?? null ,

                ];

            $previewData['detailedProduct'] = $detailedProduct;
            session(['productPreviewData' => $previewData]);

            return view('frontend.product_details', compact('previewData'));
        }
        abort(404);

    }

    public function shop($slug)
    {
        if (get_setting('vendor_system_activation') != 1) {
            return redirect()->route('home');
        }
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null) {
            if($shop->user->banned == 1){
                abort(404);
            }
            if ($shop->verification_status != 0) {
                return view('frontend.seller_shop', compact('shop'));
            } else {
                return view('frontend.seller_shop_without_verification', compact('shop'));
            }
        }
        abort(404);
    }

    public function filter_shop(Request $request, $slug, $type)
    {
        if (get_setting('vendor_system_activation') != 1) {
            return redirect()->route('home');
        }
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null && $type != null) {
            if($shop->user->banned == 1){
                abort(404);
            }
            if ($type == 'all-products') {
                $sort_by = $request->sort_by;
                $min_price = $request->min_price;
                $max_price = $request->max_price;
                $selected_categories = array();
                $brand_id = null;
                $rating = null;

                $conditions = ['user_id' => $shop->user->id, 'published' => 1, 'approved' => 1];

                if ($request->brand != null) {
                    $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
                    $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
                }

                $products = Product::where($conditions);

                if ($request->has('selected_categories')) {
                    $selected_categories = $request->selected_categories;
                    $products->whereIn('category_id', $selected_categories);
                }

                if ($min_price != null && $max_price != null) {
                    $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
                }

                if ($request->has('rating')) {
                    $rating = $request->rating;
                    $products->where('rating', '>=', $rating);
                }

                switch ($sort_by) {
                    case 'newest':
                        $products->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $products->orderBy('created_at', 'asc');
                        break;
                    case 'price-asc':
                        $products->orderBy('unit_price', 'asc');
                        break;
                    case 'price-desc':
                        $products->orderBy('unit_price', 'desc');
                        break;
                    default:
                        $products->orderBy('id', 'desc');
                        break;
                }

                $products = $products->paginate(24)->appends(request()->query());

                return view('frontend.seller_shop', compact('shop', 'type', 'products', 'selected_categories', 'min_price', 'max_price', 'brand_id', 'sort_by', 'rating'));
            }

            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function all_categories(Request $request)
    {
        $categories = Category::with('childrenCategories')->where('parent_id', 1)->orderBy('order_level', 'asc')->get();

        // dd($categories);
        return view('frontend.all_category', compact('categories'));
    }

    public function all_brands(Request $request)
    {
        $brands = Brand::all();
        return view('frontend.all_brand', compact('brands'));
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if (is_array($request->top_categories) && in_array($category->id, $request->top_categories)) {
                $category->top = 1;
                $category->save();
            } else {
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if (is_array($request->top_brands) && in_array($brand->id, $request->top_brands)) {
                $brand->top = 1;
                $brand->save();
            } else {
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $tax = 0;
        $max_limit = 0;

        if ($request->has('color')) {
            $str = $request['color'];
        }

        if (json_decode($product->choice_options) != null) {
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }

        $product_stock = $product->stocks->where('variant', $str)->first();

        $price = $product_stock->price;


        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        $quantity = $product_stock->qty;
        $max_limit = $product_stock->qty;

        if ($quantity >= 1 && $product->min_qty <= $quantity) {
            $in_stock = 1;
        } else {
            $in_stock = 0;
        }

        //Product Stock Visibility
        if ($product->stock_visibility_state == 'text') {
            if ($quantity >= 1 && $product->min_qty < $quantity) {
                $quantity = translate('In Stock');
            } else {
                $quantity = translate('Out Of Stock');
            }
        }

        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
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

        // taxes
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        $price += $tax;

        return array(
            'price' => single_price($price * $request->quantity),
            'quantity' => $quantity,
            'digital' => $product->digital,
            'variation' => $str,
            'max_limit' => $max_limit,
            'in_stock' => $in_stock
        );
    }

    public function sellerpolicy()
    {
        $page =  Page::where('type', 'seller_policy_page')->first();
        return view("frontend.policies.sellerpolicy", compact('page'));
    }

    public function returnpolicy()
    {
        $page =  Page::where('type', 'return_policy_page')->first();
        return view("frontend.policies.returnpolicy", compact('page'));
    }

    public function supportpolicy()
    {
        $page =  Page::where('type', 'support_policy_page')->first();
        return view("frontend.policies.supportpolicy", compact('page'));
    }

    public function terms()
    {
        $page =  Page::where('type', 'terms_conditions_page')->first();
        return view("frontend.policies.terms", compact('page'));
    }

    public function privacypolicy()
    {
        $page =  Page::where('type', 'privacy_policy_page')->first();
        return view("frontend.policies.privacypolicy", compact('page'));
    }

    public function get_pick_up_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.'.get_setting('homepage_select').'.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request)
    {
        // $category = Category::findOrFail($request->id);
        $categories = Category::with('childrenCategories')->findOrFail($request->id);
        return view('frontend.'.get_setting('homepage_select').'.partials.category_elements', compact('categories'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.user.customer_packages_lists', compact('customer_packages'));
    }

    // public function new_page()
    // {
    //     $user = User::where('user_type', 'admin')->first();
    //     auth()->login($user);
    //     return redirect()->route('admin.dashboard');

    // }


    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = translate('Email already exists!');
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if (isUnique($email)) {
            $this->send_email_change_verification_mail($request, $email);
            flash(translate('A verification mail has been sent to the mail you provided us with.'))->success();
            return back();
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = translate('Email Verification');
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Verify your account');
        $array['link'] = route('email_change.callback') . '?new_email_verificiation_code=' . $verification_code . '&email=' . $email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = translate("Email Second");

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");
        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request)
    {
        if ($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if ($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                if ($user->user_type == 'seller') {
                    return redirect()->route('seller.dashboard');
                }
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');
    }

    public function reset_password_with_code(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            // return response()->json(['errors' => $validator->errors()], 422);
            $errors = $validator->errors()->all();
            session()->flash('errors', $errors);
            return view('auth.'.get_setting('authentication_layout_select').'.reset_password');
        }

        if (($user = User::where('email', $request->email)->where('verification_code', $request->code)->first()) != null) {
            if ($request->password == $request->password_confirmation) {

                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                // flash(translate('Password updated successfully'))->success();
                session()->flash('success', translate('Password updated successfully'));

                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            } else {
                session()->flash('warning', translate("Password and confirm password didn't match"));

                // flash(translate("Password and confirm password didn't match"))->warning();
                return view('auth.'.get_setting('authentication_layout_select').'.reset_password');
            }
        } else {
            // flash(translate("Verification code mismatch"))->error();
            session()->flash('error', translate("Verification code mismatch"));

            return view('auth.'.get_setting('authentication_layout_select').'.reset_password');
        }
    }


    public function all_flash_deals()
    {
        $today = strtotime(date('Y-m-d H:i:s'));

        $data['all_flash_deals'] = FlashDeal::where('status', 1)
            ->where('start_date', "<=", $today)
            ->where('end_date', ">", $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view("frontend.flash_deal.all_flash_deal_list", $data);
    }

    public function todays_deal()
    {
        $todays_deal_products = Cache::rememberForever('todays_deal_products', function () {
            return filter_products(Product::with('thumbnail')->where('todays_deal', '1'))->get();
        });

        return view("frontend.todays_deal", compact('todays_deal_products'));
    }

    public function all_seller(Request $request)
    {
        if (get_setting('vendor_system_activation') != 1) {
            return redirect()->route('home');
        }
        $shops = Shop::whereIn('user_id', verified_sellers_id())
            ->paginate(15);

        return view('frontend.shop_listing', compact('shops'));
    }

    public function all_coupons(Request $request)
    {
        $coupons = Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(15);
        return view('frontend.coupons', compact('coupons'));
    }

    public function inhouse_products(Request $request)
    {
        $products = filter_products(Product::where('added_by', 'admin'))->with('taxes')->paginate(12)->appends(request()->query());
        return view('frontend.inhouse_products', compact('products'));
    }


    public function sendWaitlistEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email',
            // Updated phone validation rule to match the specified formats
            'phone' => ['required', 'regex:/^(?:(?:\+9715|009715)\d{2}\s?\d{2}\s?\d{2}\s?\d{2}|05\d\s?\d{3}\s?\d{2}\s?\d{2})$/'],
            'work' => 'nullable|regex:/^[\pL\s]+$/u',
            'info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $work = $request->work;
        $info = $request->info;
        $subscribeNewsletter = $request->has('subscribeNewsletter') ? "yes" : "no";

        Mail::to('email@example.com')->send(new WaitlistApplication($name, $email, $phone, $work, $info, $subscribeNewsletter));

        Mail::to($email)->send(new WaitlistUserApplication($name));

        return Redirect::back()->with('success', 'Your request to join the waitlist has been submitted successfully!');
    }

    public function checkout(Request $request, string $plan = 'price_1P0Rl4BP5icTBuiejLMD4n0h') {
        return $request->user()
        ->newSubscription('prod_QToCjFrgzq3KmV', $plan)
        ->checkout([
            // 'success_url' => route('subscription.success'),
            // 'cancel_url' => route('subscription.cancel'),
        ]);
    }
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET')); // Use test key in test mode

        try {
            $plan = 'price_1PdD6kRvNlBWfmPI6CWSZVOW'; // Replace with your Stripe price ID

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $plan,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('sellerpolicy') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('sellerpolicy'),
            ]);

            return response()->json(['clientSecret' => env('STRIPE_SECRET')]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function cancel()
    {

        // Authenticate with Stripe using your API key
        Stripe::setApiKey(config('services.stripe.secret'));

        // try {
            $user = Auth::user();

            // Retrieve the Stripe subscription ID from your database or use directly if known
            $subscriptionId = $user->subscription('prod_QToCjFrgzq3KmV')->stripe_id;

            // Cancel the subscription in Stripe
            $subscription = \Stripe\Subscription::update(
                $subscriptionId,
                ['cancel_at_period_end' => true] // Set to true to cancel at the end of the current period
            );

            // Optionally, update your database to reflect the cancellation
            $user->subscription('prod_QToCjFrgzq3KmV')->cancel(); // Update Laravel Cashier subscription status

            // Redirect to a success page or return a response
            return redirect()->back()->with('success', 'Subscription canceled successfully.');
        // } catch (ApiErrorException $e) {
        //     // Handle Stripe API errors
        //     return redirect()->back()->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        // }
    }
    public function changePaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
        ]);

        $user = Auth::user();
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Attach the payment method to the customer
            $user->updateDefaultPaymentMethod($validated['payment_method']);

            return back()->with('success', 'Payment method changed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error changing payment method: ' . $e->getMessage());
        }
    }

    public function updatePaymentInformation(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
        ]);


        $user = Auth::user();
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // try {
            // Attach the payment method to the customer and update the subscription
            $user->updateDefaultPaymentMethod($validated['payment_method']);
            // $user->subscription('prod_QToCjFrgzq3KmV')->updateDefaultPaymentMethod($validated['payment_method']);

            return redirect()->route('subscription.update')->with('success', 'Payment information updated successfully.');
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Error updating payment information: ' . $e->getMessage());
        // }
    }
    public function reactivateSubscription()
    {
        // $user = Auth::user();
        // Stripe::setApiKey(env('STRIPE_SECRET'));

        // $stripeSubscriptionId = $user->subscription('prod_QToCjFrgzq3KmV')->stripe_id; // Replace with your subscription ID logic

        //     // Retrieve the subscription from Stripe
        //     $subscription = Subscription::retrieve($stripeSubscriptionId);

        //     // Reactivate the subscription
        //     $subscription->resume();

        //     return redirect()->back()->with('success', 'Subscription reactivated successfully!');
            $user = Auth::user();
            $subscription = $user->subscription('prod_QToCjFrgzq3KmV'); // Or the name of your subscription plan

            if ($subscription->ends_at && $subscription->ends_at > now()) {
                $subscription->resume();
                return redirect()->back()->with('success', 'Subscription cancellation has been canceled.');
            }

            return redirect()->back()->with('error', 'Unable to cancel the scheduled cancellation.');
    }

    public function resume()
{
    $user = Auth::user();
    $subscription = $user->subscription('prod_QToCjFrgzq3KmV'); // Or the name of your subscription plan

    if ($subscription->ends_at && $subscription->ends_at > now()) {
        $subscription->resume();
        return redirect()->back()->with('success', 'Subscription cancellation has been canceled.');
    }

    return redirect()->back()->with('error', 'Unable to cancel the scheduled cancellation.');
}
public function pause()
{
    $user = Auth::user();
    $subscription = $user->subscription('prod_QToCjFrgzq3KmV'); // Or the name of your subscription plan




    if (!$subscription) {
        return redirect()->back()->with('error', 'No subscription found.');
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));


        $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_id);
        $stripeSubscription->pause_collection = [
            'behavior' => 'keep_as_draft', // or 'mark_uncollectible' or 'void'
        ];
        $stripeSubscription->save();
        // Optionally update your database if you have a pause_collection column
        $subscription->update([
            'pause_collection' => [
                'behavior' => 'keep_as_draft',
            ],
        ]);
        return redirect()->back()->with('success', 'Subscription paused successfully.');

}
public function unpause()
{
    $user = Auth::user();
    $subscription = $user->subscription('prod_QToCjFrgzq3KmV'); // Adjust as necessary

    if (!$subscription) {
        return redirect()->back()->with('error', 'No subscription found.');
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));

    $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_id);
    $stripeSubscription->pause_collection = null; // Unpause the subscription
    $stripeSubscription->save();
    $subscription->update([
        'pause_collection' => null,
    ]);

    return redirect()->back()->with('success', 'Subscription unpaused successfully.');
    // $user = Auth::user();
    // Stripe::setApiKey(env('STRIPE_SECRET'));


    //     $subscription = Subscription::retrieve($user->subscription_id);
    //     $subscription->resume();

    //     return back()->with('success', 'Subscription resumed successfully.');

}



}
