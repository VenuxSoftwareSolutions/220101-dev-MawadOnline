<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Carrier;
use App\Models\Cart;
use App\Models\Category;
use App\Models\CombinedOrder;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Emirate;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippersArea;
use Auth;
use Log;
use Illuminate\Http\Request;
use App\Mail\OrderConfirmation;
use Session;
use Mail;

class CheckoutController extends Controller
{
    /**
     * check the selected payment gateway,
     * then redirect to that controller accordingly
     */
    public function checkout(Request $request)
    {
        if ($request->payment_option == null) {
            flash(translate('There is no payment option is selected.'))->warning();

            return redirect()->route('checkout.shipping_info');
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        // Minimum order amount check
        if (get_setting('minimum_order_amount_check') == 1) {
            $subtotal = 0;
            foreach ($carts as $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            }

            if ($subtotal < get_setting('minimum_order_amount')) {
                flash(translate('You order amount is less than the minimum order amount'))->warning();

                return redirect()->route('home');
            }
        }

        (new OrderController)->store($request);

        if (count($carts) > 0) {
            Cart::where('user_id', Auth::user()->id)->delete();
        }

        $request->session()->put('payment_type', 'cart_payment');

        $data['combined_order_id'] = $request->session()->get('combined_order_id');
        $request->session()->put('payment_data', $data);

        if ($request->session()->get('combined_order_id') != null) {
            // If block for Online payment, wallet and cash on delivery. Else block for Offline payment
            $decorator = __NAMESPACE__.'\\Payment\\'.str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))).'Controller';

            if (class_exists($decorator)) {
                return (new $decorator)->pay($request);
            } else {
                $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                $manual_payment_data = [
                    'name' => $request->payment_option,
                    'amount' => $combined_order->grand_total,
                    'trx_id' => $request->trx_id,
                    'photo' => $request->photo,
                ];
                foreach ($combined_order->orders as $order) {
                    $order->manual_payment = 1;
                    $order->manual_payment_data = json_encode($manual_payment_data);
                    $order->save();
                }
                flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();

                return redirect()->route('order_confirmed');
            }
        }
    }

    /**
     * redirects to this method after a successful checkout
     */
    public function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);

        foreach ($combined_order->orders as $order) {
            $order = Order::findOrFail($order->id);
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            calculateCommissionAffilationClubPoint($order);
        }
        Session::put('combined_order_id', $combined_order_id);

        return redirect()->route('order_confirmed');
    }

    public function get_shipping_info(Request $request)
    {
        $emirates = Emirate::all();

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        if ($carts && $carts->count() > 0) {
            $categories = Category::all();

            $isCheckoutSessionTimeoutExpires = $carts->filter(fn($cart) => str()->upper($cart->reserved) === "NO")
                ->count() > 0;

            return view(
                'frontend.shipping_info',
                compact(
                    'categories', 'carts',
                    'emirates', 'isCheckoutSessionTimeoutExpires'
                )
            );
        }

        flash(translate('Your cart is empty'))->success();

        return back();
    }

    public function store_shipping_info(Request $request)
    {
        if ($request->address_id == null) {
            flash(translate('Please add shipping address'))->warning();

            return back();
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        $isCheckoutSessionTimeoutExpires = $carts->filter(fn($cart) => str()->upper($cart->reserved) === "NO")
            ->count() > 0;

        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();

            return redirect()->route('home');
        }

        $carts->each(function ($cart) {
            $cart->address_id = request()->address_id;
            $cart->save();
        });

        $carrier_list = [];

        if (get_setting('shipping_type') == 'carrier_wise_shipping') {
            $zone = Country::where('id', $carts[0]['address']['country_id'])->first()->zone_id;

            $carrier_list = Carrier::where('status', 1)
                ->whereIn('id', function ($query) use ($zone) {
                    $query->select('carrier_id')
                        ->from('carrier_range_prices')
                        ->where('zone_id', $zone);
                })->orWhere('free_shipping', 1)
                ->get();
        }

        $address = Address::find($request->address_id);

        $shippers_areas = ShippersArea::with(['shipper'])
            ->where('emirate_id', $address->state_id)
            ->where('area_id', $address->city_id)
            ->get();

        $admin_products = [];
        $seller_products = [];
        $productQtyPanier = [];
        $admin_product_variation = [];
        $seller_product_variation = [];

        $carts->each(function ($cart) use (
            &$admin_products, &$seller_products,
            &$productQtyPanier, &$admin_product_variation,
            &$seller_product_variation
        ) {
            $product = get_single_product($cart['product_id']);
            $productId = $cart['product_id'];
            $quantity = $cart['quantity'];

            if (isset($productQtyPanier[$productId])) {
                $productQtyPanier[$productId] += $quantity;
            } else {
                $productQtyPanier[$productId] = $quantity;
            }

            if ($product->added_by == 'admin') {
                array_push($admin_products, $cart['product_id']);
                $admin_product_variation[] = $cart['variation'];
            } else {
                $product_ids = [];
                if (isset($seller_products[$product->user_id])) {
                    $product_ids = $seller_products[$product->user_id];
                }
                array_push($product_ids, $cart['product_id']);
                $seller_products[$product->user_id] = $product_ids;
                $seller_product_variation[] = $cart['variation'];
            }
        });

        $pickup_point_list = [];

        if (get_setting('pickup_point') == 1) {
            $pickup_point_list = get_all_pickup_points();
        }

        return view('frontend.delivery_info', compact(
            'carts', 'carrier_list', 'shippers_areas',
            'pickup_point_list', 'admin_products', 'seller_products',
            'productQtyPanier', 'admin_product_variation', 'seller_product_variation',
            'isCheckoutSessionTimeoutExpires'
        ));
    }

    public function store_delivery_info(Request $request)
    {
        $carts = Cart::where('user_id', auth()->user()->id)
            ->get();

        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();

            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;

        if ($carts && count($carts) > 0) {
            foreach ($carts as $key => $cart) {
                $product = Product::find($cart['product_id']);
                $tax += cart_product_tax($cart, $product, false) * $cart['quantity'];
                $subtotal += cart_product_price($cart, $product, false, false) * $cart['quantity'];

                if (
                    get_setting('shipping_type') != 'carrier_wise_shipping' ||
                    $request['shipping_type_'.$product->user_id] == 'pickup_point'
                ) {
                    if ($request['shipping_type_'.$product->user_id] == 'pickup_point') {
                        $cart['shipping_type'] = 'pickup_point';
                        $cart['pickup_point'] = $request['pickup_point_id_'.$product->user_id];
                    } else {
                        $cart['shipping_type'] = 'home_delivery';
                    }

                    $cart['shipping_cost'] = 0;

                    if ($cart['shipping_type'] == 'home_delivery') {
                        $cart['shipping_cost'] = getShippingCost($carts, $key);
                    }
                } else {
                    $cart['shipping_type'] = 'carrier';
                    $cart['carrier_id'] = $request['carrier_id_'.$product->user_id];
                    $cart['shipping_cost'] = getShippingCost($carts, $key, $cart['carrier_id']);
                }

                $shipping += $cart['shipping_cost'];
                $cart->save();
            }

            $total = $subtotal + $tax + $shipping;

            $isCheckoutSessionTimeoutExpires = $carts->filter(fn($cart) => str()->upper($cart->reserved) === "NO")
                ->count() > 0;

            return view(
                'frontend.payment_select',
                compact(
                    'carts', 'shipping_info',
                    'total', 'isCheckoutSessionTimeoutExpires'
                )
            );
        } else {
            flash(translate('Your Cart was empty'))->warning();

            return redirect()->route('home');
        }
    }

    public function apply_coupon_code(Request $request)
    {
        $user = auth()->user();
        $coupon = Coupon::where('code', $request->code)->first();
        $response_message = [];

        // if the Coupon type is Welcome base, check the user has this coupon or not
        $couponUser = true;
        if ($coupon && $coupon->type == 'welcome_base') {
            $userCoupon = $user->userCoupon;
            if (! $userCoupon) {
                $couponUser = false;
            }
        }

        if ($coupon != null && $couponUser) {

            //  Coupon expiry Check
            if ($coupon->type != 'welcome_base') {
                $validationDateCheckCondition = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;
            } else {
                $validationDateCheckCondition = false;
                if ($userCoupon) {
                    $validationDateCheckCondition = $userCoupon->expiry_date >= strtotime(date('d-m-Y H:i:s'));
                }
            }
            if ($validationDateCheckCondition) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);

                    $carts = Cart::where('user_id', Auth::user()->id)
                        ->where('owner_id', $coupon->user_id)
                        ->get();

                    $coupon_discount = 0;

                    if ($coupon->type == 'cart_base' || $coupon->type == 'welcome_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;
                        if ($coupon->type == 'cart_base' && $sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                        } elseif ($coupon->type == 'welcome_base' && $sum >= $userCoupon->min_buy) {
                            $coupon_discount = $userCoupon->discount_type == 'percent' ? (($sum * $userCoupon->discount) / 100) : $userCoupon->discount;
                        }
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }

                    if ($coupon_discount > 0) {
                        Cart::where('user_id', Auth::user()->id)
                            ->where('owner_id', $coupon->user_id)
                            ->update(
                                [
                                    'discount' => $coupon_discount / count($carts),
                                    'coupon_code' => $request->code,
                                    'coupon_applied' => 1,
                                ]
                            );

                        $response_message['response'] = 'success';
                        $response_message['message'] = translate('Coupon has been applied');
                    } else {
                        $response_message['response'] = 'warning';
                        $response_message['message'] = translate('This coupon is not applicable to your cart products!');
                    }
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'warning';
                $response_message['message'] = translate('Coupon expired!');
            }
        } else {
            $response_message['response'] = 'danger';
            $response_message['message'] = translate('Invalid coupon!');
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        $returnHTML = view('frontend.'.get_setting('homepage_select').'.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();

        return response()->json(['response_message' => $response_message, 'html' => $returnHTML]);
    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', Auth::user()->id)
            ->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0,
                ]
            );

        $coupon = Coupon::where('code', $request->code)->first();
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        return view('frontend.'.get_setting('homepage_select').'.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'));
    }

    public function apply_club_point(Request $request)
    {
        if (addon_is_activated('club_point')) {

            $point = $request->point;

            if (Auth::user()->point_balance >= $point) {
                $request->session()->put('club_point', $point);
                flash(translate('Point has been redeemed'))->success();
            } else {
                flash(translate('Invalid point!'))->warning();
            }
        }

        return back();
    }

    public function remove_club_point(Request $request)
    {
        $request->session()->forget('club_point');

        return back();
    }

    public function order_confirmed()
    {
        $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));

        if (auth()->user()->email !== null) {
            Mail::to(auth()->user()->email)->send(new OrderConfirmation($combined_order));
        } else {
            Log::info(
                sprintf(
                    "User % hasn't an email ! We can't send you an order confirmation email !",
                    auth()->user()->name
                )
            );

            flash(
                __(
                    "Hey :name ! You don't seem to have an email ! Sorry, We can't send you an order confirmation email !", [
                        "name" => auth()->user()->name
                    ]
                )
            )->warning();
        }

        Cart::where('user_id', $combined_order->user_id)
            ->delete();

        $first_order = $combined_order->orders->first()->toArray();
        $first_order["shipping_address"] = json_decode($first_order["shipping_address"], true);

        return view('frontend.order_confirmed', compact('combined_order', 'first_order'));
    }
}
