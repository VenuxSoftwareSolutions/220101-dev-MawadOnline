<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceEmailManager;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CombinedOrder;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Models\Refund;
use App\Models\RefundHistories;
use App\Utility\NotificationUtility;
use App\Utility\SmsUtility;
use Auth;
use CoreComponentRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mail;
use Log;
use App\Jobs\firstCountDownNotificationJob;
use App\Models\CommissionVat;
use App\Models\Discount;
use App\Utility\CartUtility;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders'])->only('all_orders');
        $this->middleware(['permission:view_order_details'])->only('show');
        $this->middleware(['permission:delete_order'])->only('destroy', 'bulk_order_delete');
    }

    // All Orders
    public function all_orders(Request $request)
    {
        //CoreComponentRepository::instantiateShopRepository();

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';

        $orders = Order::orderBy('id', 'desc');
        $admin_user_id = User::where('user_type', 'admin')->first()->id;

        if (
            Route::currentRouteName() == 'inhouse_orders.index' &&
            Auth::user()->can('view_inhouse_orders')
        ) {
            $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
        } elseif (
            Route::currentRouteName() == 'seller_orders.index' &&
            Auth::user()->can('view_seller_orders')
        ) {
            $orders = $orders->where('orders.seller_id', '!=', $admin_user_id);
        } elseif (
            Route::currentRouteName() == 'pick_up_point.index' &&
            Auth::user()->can('view_pickup_point_orders')
        ) {
            if (get_setting('vendor_system_activation') != 1) {
                $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
            }
            $orders->where('shipping_type', 'pickup_point')->orderBy('code', 'desc');
            if (
                Auth::user()->user_type == 'staff' &&
                Auth::user()->staff->pick_up_point != null
            ) {
                $orders->where('shipping_type', 'pickup_point')
                    ->where('pickup_point_id', Auth::user()->staff->pick_up_point->id);
            }
        } elseif (
            Route::currentRouteName() == 'all_orders.index' &&
            Auth::user()->can('view_all_orders')
        ) {
            if (get_setting('vendor_system_activation') != 1) {
                $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
            }
        } else {
            abort(403);
        }

        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(' to ', $date)[0])).'  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(' to ', $date)[1])).'  23:59:59');
        }
        $orders = $orders->paginate(15);

        return view('backend.sales.index', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date'));
    }

    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
            ->where('user_type', 'delivery_boy')
            ->get();

        $order->viewed = 1;
        $order->save();

        return view('backend.sales.show', compact('order', 'delivery_boys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $carts = Cart::where('user_id', Auth::user()->id)->get();

            if ($carts->isEmpty()) {
                flash(translate('Your cart is empty'))->warning();

                return redirect()->route('home');
            }

            $address = Address::where('id', $carts->first()->address_id)->first();
            $shippingAddress = [];

            if ($address != null) {
                $shippingAddress['name'] = Auth::user()->name;
                $shippingAddress['email'] = Auth::user()->email;
                $shippingAddress['address'] = $address->address;
                $shippingAddress['country'] = $address->country->name;
                $shippingAddress['state'] = $address->emirate->name;
                $shippingAddress['city'] = $address->state->name;
                $shippingAddress['postal_code'] = $address->postal_code;
                $shippingAddress['phone'] = $address->phone;

                if ($address->latitude || $address->longitude) {
                    $shippingAddress['lat_lang'] = $address->latitude.','.$address->longitude;
                }
            }

            $combined_order = new CombinedOrder();
            $combined_order->user_id = Auth::user()->id;
            $combined_order->shipping_address = json_encode($shippingAddress);
            $combined_order->save();

            $seller_products = [];

            foreach ($carts as $cartItem) {
                $product_ids = [];
                $product = Product::find($cartItem['product_id']);
                if (isset($seller_products[$product->user_id])) {
                    $product_ids = $seller_products[$product->user_id];
                }
                array_push($product_ids, $cartItem);
                $seller_products[$product->user_id] = $product_ids;
            }

            foreach ($seller_products as $seller_product) {
                $order = new Order();
                $order->combined_order_id = $combined_order->id;
                $order->user_id = Auth::user()->id;
                $order->shipping_address = $combined_order->shipping_address;

                $order->additional_info = $request->additional_info;

                $order->payment_type = $request->payment_option;
                $order->delivery_viewed = '0';
                $order->payment_status_viewed = '0';
                $order->code = date('Ymd-His').rand(10, 99);
                $order->date = strtotime('now');
                $order->save();

                $subtotal = 0;
                $tax = 0;
                $shipping = 0;
                $coupon_discount = 0;

                foreach ($seller_product as $cartItem) {
                    $product = Product::find($cartItem['product_id']);

                    $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                    $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                    $coupon_discount += $cartItem['discount'];

                    $product_variation = $cartItem['variation'];

                    // @todo we should remove the quantity from stock
                    // deduct the qty from the warehouse that has enough stock to fulfill order:
                    // 1. get stock summaries sorted by quantities
                    // 2. deduct qty from the first stock summaries that has enough stock
                    // 3. add a stock details entry which record the stock deduction (same warehouse of prev stock summaries)

                    $order_detail = new OrderDetail();
                    $order_detail->order_id = $order->id;
                    $order_detail->seller_id = $product->user_id;
                    $order_detail->product_id = $product->id;
                    $order_detail->variation = $product_variation;
                    $order_detail->price = cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                    $order_detail->tax = cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                    $order_detail->shipping_type = $cartItem['shipping_type'];
                    $order_detail->product_referral_code = $cartItem['product_referral_code'];
                    $order_detail->shipping_cost = $cartItem['shipping_cost'];

                    $shipping += $order_detail->shipping_cost;

                    $order_detail->quantity = $cartItem['quantity'];

                    if (addon_is_activated('club_point')) {
                        $order_detail->earn_point = $product->earn_point;
                    }

                    $order_detail->save();

                    $this->storeMwdCommission($product, $cartItem["quantity"], $order_detail->id);

                    firstCountDownNotificationJob::dispatch($order_detail)
                                                ->delay(now()->addHours(24));

                    $product->num_of_sale += $cartItem['quantity'];
                    $product->save();

                    $order->seller_id = $product->user_id;
                    $order->shipping_type = $cartItem['shipping_type'];

                    if ($cartItem['shipping_type'] == 'pickup_point') {
                        $order->pickup_point_id = $cartItem['pickup_point'];
                    }

                    if ($cartItem['shipping_type'] == 'carrier') {
                        $order->carrier_id = $cartItem['carrier_id'];
                    }

                    if ($product->added_by == 'seller' && $product->user->seller != null) {
                        $seller = $product->user->seller;
                        $seller->num_of_sale += $cartItem['quantity'];
                        $seller->save();
                    }

                    if (addon_is_activated('affiliate_system')) {
                        if ($order_detail->product_referral_code) {
                            $referred_by_user = User::where(
                                'referral_code',
                                $order_detail->product_referral_code
                            )->first();

                            (new AffiliateController())
                                ->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                        }
                    }
                }

                $order->grand_total = ($coupon_discount > 0 ? $coupon_discount : $subtotal) + $tax + $shipping;

                if ($seller_product[0]->coupon_code != null) {
                    $order->coupon_discount = $coupon_discount;

                    $coupon_usage = new CouponUsage();
                    $coupon_usage->user_id = Auth::user()->id;
                    $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
                    $coupon_usage->save();
                }

                $combined_order->grand_total += $order->grand_total;

                $order->save();
            }

            $combined_order->save();

            $request->session()->put('combined_order_id', $combined_order->id);
        } catch(Exception $e) {
            Log::error("Error while storing order, with message {$e->getMessage()}");
            flash(translate("Something went wrong!"))->error();
            return redirect()->route("home");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        if ($order != null) {
            foreach ($order->orderDetails as $orderDetail) {
                try {
                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $orderDetail->variation)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                } catch (Exception $error) {
                    Log::error("Error while editing product {$orderDetail->product_id} stock, with message: {$error->getMessage()}");
                }

                $orderDetail->delete();
            }

            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }

        return back();
    }

    public function deleteOrderIfPaymentFail($combined_order_id)
    {
        try {
            $combined_order = CombinedOrder::findOrFail($combined_order_id);

            if ($combined_order != null) {
                foreach ($combined_order->orders as $order) {
                    $orderDetails = OrderDetail::where("order_id", $order->id)->get();

                    foreach ($orderDetails as $orderDetail) {
                        try {
                            $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                                ->where('variant', $orderDetail->variation)
                                ->first();

                            if ($product_stock != null) {
                                $product_stock->qty += $orderDetail->quantity;
                                $product_stock->save();
                            }
                        } catch (Exception $error) {
                            Log::error("Error while editing product {$orderDetail->product_id} stock in `deleting order if payment fail`, with message: {$error->getMessage()}");
                        }

                        $orderDetail->delete();
                    }

                    $order->delete();
                }

                return response()->json([
                    "error" => false,
                    "message" => translate('Order has been deleted successfully')
                ], 200);
            } else {
                return response()->json([
                    "error" => true,
                    "message" => translate('Something went wrong')
                ], 500);
            }

            return response()->json([
                "error" => true,
                "message" => translate('Something went wrong')
            ], 500);
        } catch (Exception $e) {
            Log::error("Error while `deleting order if payment fail`, with message: {$e->getMessage()}");

            return response()->json([
                "error" => true,
                "message" => translate('Something went wrong')
            ], 500);
        }
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }

        return 1;
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();

        return view('seller.order_details_seller', compact('order'));
    }

     public function update_delivery_status(Request $request)
    {
        try {
            $order = OrderDetail::findOrFail($request->order_id);
            $order->order->delivery_viewed = '0';
            $order->delivery_status = $request->status;
            $order->save();
            $shippers = explode(",", $order->product->shippingOptions($order->quantity)->shipper);

            if ($request->status === 'ready_for_shipment' && in_array("third_party", $shippers)) {
                $controller = new AramexController;

                $warehouses = session()->get('warehouses');
                $selectedWarehouse = Warehouse::findOrFail($warehouses[0]['warehouse_id']);

                $vendorBusinessInfo = BusinessInformation::where('user_id', auth()->user()->id)->first();
                $contactPerson = ContactPerson::where('user_id', auth()->user()->id)->first();

                $request->merge([
                    'pickup_address_line1' => $selectedWarehouse->address_street,
                    'pickup_address_line2' => $selectedWarehouse->address_building,
                    'pickup_address_line3' => $selectedWarehouse->address_unit,
                    'pickup_city' => $selectedWarehouse->area_id,
                    'pickup_state' => $selectedWarehouse->emirate_id,
                    'full_name' => "$contactPerson->first_name $contactPerson->last_name",
                    'phone' => $contactPerson->mobile_phone,
                    'shipper_address_line1' => $vendorBusinessInfo->street,
                    'shipper_address_line2' => null,
                    'shipper_address_line3' => null,
                    'shipper_building_name' => $vendorBusinessInfo->building,
                    'shipper_building_number' => $vendorBusinessInfo->unit,
                    'shipper_post_code' => $vendorBusinessInfo->po_box,
                    'state' => $vendorBusinessInfo->state,
                    'city' => $vendorBusinessInfo->area_id,
                    'email' => $contactPerson->email,
                ]);

                $pickup_input = $controller->transformNewPickupData($request->all());

                $pickup = $controller->createPickup($pickup_input);

                if ($pickup !== null && $pickup['HasErrors'] === true) {
                    Log::error(sprintf(
                        'Error while creating pickup for order %d, with message: %s',
                        $order->id, json_encode($pickup)
                    ));

                    $order->delivery_status = "in_preparation";
                    $order->save();

                    return response()->json([
                        'error' => true,
                        'message' => __("There's an error while processing pickup creation! Please try again later!"),
                    ], 500);
                }

                $product = get_single_product($request->product_id);

                $package_weight = $product->weight !== null ? (
                    str()->lower($product->unit_weight) === 'kilograms' ?
                    $product->weight
                    : $product->weight * POUNDS_TO_KG_RATIO
                ) : null;

                $actualWeightValue = $package_weight !== null ? (
                    (float) ($package_weight * $order->quantity * WEIGHT_MARGIN)
                ) : ((float) $controller->calculateShipmentActualWeight(
                    $product->only(['length', 'width', 'height']),
                    $order->quantity
                ));

                $dimensions = [
                    'Length' => $product->length,
                    'Width' => $product->width,
                    'Height' => $product->height,
                    'Unit' => 'cm',
                ];

                $weight = [
                    'Value' => $actualWeightValue,
                    'Unit' => 'KG',
                ];

                $request->merge([
                    'pickup_guid' => $pickup['ProcessedPickup']['GUID'],
                    'dimensions' => $dimensions,
                    'weight' => $weight,
                ]);

                $shipment_input = $controller->transformNewShipmentsData($request->all());
                $shipment = $controller->createShipments($shipment_input);

                if ($shipment !== null && $shipment['HasErrors'] === true) {
                    Log::error(sprintf(
                        'Error while creating shipment for order %d, with message: %s',
                        $order->id, json_encode($shipment)
                    ));

                    $order->delivery_status = "in_preparation";
                    $order->save();

                    return response()->json([
                        'error' => true,
                        'message' => __("There's an error while processing shipment creation! Please try again later!"),
                    ], 500);
                }

                $link = $shipment['Shipments'][0]['ShipmentLabel']['LabelURL'];

                TrackingShipment::firstOrCreate([
                    'user_id' => auth()->user()->id,
                    'order_detail_id' => $order->id,
                    'shipment_id' => $shipment['Shipments'][0]['ID'],
                    'label_url' => $link,
                ]);
            }

            if ($request->status == 'cancelled' && $order->order->payment_type == 'wallet') {
                $user = User::where('id', $order->order->user_id)->first();
                $user->balance += $order->grand_total;
                $user->save();
            }

            if ($request->status == 'cancelled') {

                $variant = $order->variation;
                if ($order->variation == null) {
                    $variant = '';
                }

                $product_stock = ProductStock::where('product_id', $order->product_id)
                    ->where('variant', $variant)
                    ->first();
                if ($product_stock != null) {
                    $product_stock->qty += $order->quantity;
                    $product_stock->save();
                }
            }


            if (addon_is_activated('affiliate_system')) {
                if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                    $orderDetail->product_referral_code
                ) {

                    $no_of_delivered = 0;
                    $no_of_canceled = 0;

                    if ($request->status == 'delivered') {
                        $no_of_delivered = $orderDetail->quantity;
                    }
                    if ($request->status == 'cancelled') {
                        $no_of_canceled = $orderDetail->quantity;
                    }
                }
            }

            if ($request->status == 'Replaced') {
                $this->replaceOrder($order);
            }
            if(in_array($request->status,['cancelled','Returned'])){
                $refund = Refund::where(['order_detail_id'=>$order->id])->get();
                if($refund->isEmpty()){
                   return $this->executeRefund($order);
                }
                return response()->json(['error' => true, 'message' => __('Refund already done')], 200);
            }
            if (
                addon_is_activated('otp_system') &&
                SmsTemplate::where('identifier', 'delivery_status_change')
                    ->first()
                    ->status == 1
            ) {
                try {
                    SmsUtility::delivery_status_change(json_decode($order->order->shipping_address)->phone, $order->order);
                } catch (Exception) {
                }
            }

            NotificationUtility::sendNotification($order->order, $request->status);

            if (get_setting('google_firebase') == 1 && $order->order->user->device_token != null) {
                $status = str_replace('_', '', $order->delivery_status);
                $request->merge([
                    'device_token' => $order->order->user->device_token,
                    'title' => 'Order updated !',
                    'status' => $status,
                    'text' => " Your order {$order->order->code} has been {$status}",
                    'type' => 'order',
                    'id' => $order->id,
                    'user_id' => $order->order->user->id,
                ]);

                NotificationUtility::sendFirebaseNotification($request);
            }

            if (addon_is_activated('delivery_boy')) {
                if (Auth::user()->user_type == 'delivery_boy') {
                    $deliveryBoyController = new DeliveryBoyController;
                    $deliveryBoyController->store_delivery_history($order);
                }
            }

            if ($request->status === 'ready_for_shipment') {
                return response()->json([
                    'error' => false,
                    'data' => [
                        'link' => $link,
                    ],
                ], 200);
            }

            return 1;
        } catch (Exception $e) {
            Log::info("Error while changing delivery status, with message: {$e->getMessage()}");
            return response()->json(['error' => true, 'message' => __('Something went wrong!')], 500);
        }
    }

    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->owner_id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();

        if (
            $order->payment_status == 'paid' &&
            $order->commission_calculated == 0
        ) {
            calculateCommissionAffilationClubPoint($order);
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = 'Order updated !';
            $status = str_replace('_', '', $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = 'order';
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }

        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
            try {
                SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (Exception) {
                // @todo log error
            }
        }

        return 1;
    }

    public function assign_delivery_boy(Request $request)
    {
        if (addon_is_activated('delivery_boy')) {

            $order = Order::findOrFail($request->order_id);
            $order->assign_delivery_boy = $request->delivery_boy;
            $order->delivery_history_date = date('Y-m-d H:i:s');
            $order->save();

            $delivery_history = \App\Models\DeliveryHistory::where('order_id', $order->id)
                ->where('delivery_status', $order->delivery_status)
                ->first();

            if (empty($delivery_history)) {
                $delivery_history = new \App\Models\DeliveryHistory();

                $delivery_history->order_id = $order->id;
                $delivery_history->delivery_status = $order->delivery_status;
                $delivery_history->payment_type = $order->payment_type;
            }
            $delivery_history->delivery_boy_id = $request->delivery_boy;

            $delivery_history->save();

            if (env('MAIL_USERNAME') != null && get_setting('delivery_boy_mail_notification') == '1') {
                $array['view'] = 'emails.invoice';
                $array['subject'] = translate('You are assigned to delivery an order. Order code').' - '.$order->code;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['order'] = $order;

                try {
                    Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                }
            }

            if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
                try {
                    SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
                } catch (\Exception $e) {
                }
            }
        }

        return 1;
    }

    public function storeMwdCommission($product, $qty, $subOrderId)
    {
        $mwdCommissionPercentage = get_setting("mwd_commission_percentage") ?? 1;
        $mwdCommissionPercentageVat = get_setting("mwd_commission_percentage_vat") ?? 1;

        $priceVatIncl = $product->unit_price;

        $discount = 0;

        try {
            $discount = Discount::getDiscountPercentage($product->id, $qty);
        } catch (Exception $e) {
            Log::info("Error while getting product #{$product->id} discount, with message: {$e->getMessage()}");
        }

        $priceAfterDiscountVatIncl = CartUtility::priceProduct($product->id, $qty);

        $mwdCommissionPercentageAmount = $priceAfterDiscountVatIncl * $mwdCommissionPercentage;

        $mwdCommissionPercentageVatAmount = $mwdCommissionPercentageAmount * $mwdCommissionPercentageVat;

        $mwdCommissionTotalPercentage = $mwdCommissionPercentageAmount + $mwdCommissionPercentageVatAmount;

        $priceAfterMwdCommission = roundUpToTwoDigits(
            $priceAfterDiscountVatIncl + $mwdCommissionPercentageAmount + $mwdCommissionPercentageVatAmount
        );

        $data = [
            "sub_order_id" => $subOrderId,
            "price_vat_incl" => $priceVatIncl,
            "discount_percentage" => is_array($discount) === true ? $discount["discount_percentage"] : 0,
            "price_after_discount_vat_incl" => $priceAfterDiscountVatIncl,
            "mwd_commission_percentage" => $mwdCommissionPercentage,
            "mwd_commission_percentage_vat" => $mwdCommissionPercentageVat,
            "mwd_commission_percentage_amount" => $mwdCommissionPercentageAmount,
            "mwd_commission_percentage_vat_amount" => $mwdCommissionPercentageVatAmount,
            "mwd_total_percentage" => $mwdCommissionTotalPercentage,
            "price_after_mwd_percentage" => $priceAfterMwdCommission,
        ];

        $isDataInserted = CommissionVat::insert($data);

        if ($isDataInserted === true) {
            Log::info("Commission vat inserted data:", $data);
        }
    }

    public function executeRefund($order){
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $result = $stripe->refunds->create(['payment_intent' => "pi_3QfkfoFlc6vGgLAs1XEpbB8E","amount" => $order->price * 100]);
            $refund = new Refund();
            $refundHistories = new RefundHistories();
            $refund->buyer_id = $order->order->user_id;
            $refund->seller_id = $order->order->seller_id;
            $refund->order_detail_id = $order->id;
            $refundHistories->refund_status = $refund->refund_status = $result->status;
            $refundHistories->description_error = $refund->description_error = $result->failure_reason;
            $refundHistories->payment_refund_id = $refund->payment_refund_id = $result->id;
            $refundHistories->payment_charge_id = $refund->payment_charge_id = $result->charge;
            $refundHistories->amount = $refund->amount = $result->amount;
            $refund->save();
            $refundHistories->refund_id = $refund->id;
            $refundHistories->save();
            return response()->json(['false' => true, 'message' => __('Refund executed')], 500);
        } catch (Exception $e) {
            Log::info("Error while execute refund, with message: {$e->getMessage()}");
            return response()->json(['error' => true, 'message' => __('Something went wrong: more details in refund management!')], 500);
        }
    }

}
