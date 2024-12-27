<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Carrier;
use App\Models\Cart;
use App\Models\Category;
use App\Models\CombinedOrder;
use App\Models\Country;
use App\Models\Coupon;
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
use App\Models\StockSummary;
use Exception;

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
            $carts->each(function($cart) {
                $cart->reserved = "YES";

                $reservedQuantity = - $cart->quantity;

                $isStockSummaryExists = StockSummary::where("variant_id", $cart->product->id)
                    ->where('warehouse_id', MAWADONLINE_WAREHOUSE_ID)
                    ->where('current_total_quantity', $reservedQuantity)
                    ->where('seller_id', auth()->user()->owner_id)
                    ->exists();

                if ($isStockSummaryExists === false) {
                    StockSummary::create([
                        'variant_id' => $cart->product->id,
                        'warehouse_id' => MAWADONLINE_WAREHOUSE_ID,
                        'current_total_quantity' => $reservedQuantity,
                        'seller_id' => $cart->product->user_id,
                    ]);
                }

                $cart->save();
            });

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
            ->where('emirate_id', $address->emirate_id)
            ->where('state_id', $address->state_id)
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
        try {
            $carts = Cart::where('user_id', auth()->user()->id)
                ->get();

            $discounts = [];
            $total = 0;
            $tax = 0;
            $shipping = 0;

            $carts->each(function ($cart) use ($request, &$discounts, &$total, &$tax, &$shipping) {
                try {
                    [
                        "discount_percentage" => $discountPercentage,
                        "max_discount_amount" => $maxDiscountAmount,
                    ] = Coupon::getDiscountDetailsByCode(
                        $request->code,
                        $cart->product->id
                    );
                } catch(Exception $e) {
                    throw $e;
                }

                $subTotal = cart_product_price($cart, $cart->product, false, false) * $cart['quantity'];
                $tax += cart_product_tax($cart, $cart->product, false) * $cart['quantity'];
                $product_shipping_cost = $cart['shipping_cost'];

                $shipping += $product_shipping_cost;

                $percentage =  ($subTotal * $discountPercentage) / 100;

                if ($percentage > $maxDiscountAmount) {
                    $subTotal -= $maxDiscountAmount;
                } else {
                    $subTotal -= $percentage;
                }

                $cart->discount = $subTotal;
                $cart->coupon_code = $request->code;
                $cart->save();
                $total += $subTotal;

                $discounts[$cart->product->id] = single_price($subTotal);
            });

            return response()->json([
                "discounts" => $discounts,
                "tax" => single_price($tax),
                "shipping" => single_price($shipping),
                "total" => single_price($total + $tax + $shipping),
                "subTotal" => $total + $tax + $shipping
            ], 200);
        } catch(Exception $e) {
            Log::error("Error while applying coupon code, with message: {$e->getMessage()}");
            return response()->json([
                "error" => true,
                "message" => __(str()->contains($e->getMessage(), "Coupon") === true ? $e->getMessage() : "Something went wrong!")
            ], 500);
        }
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
