<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductStock;
use App\Models\SmsTemplate;
use App\Models\StockSummary;
use App\Models\User;
use App\Utility\NotificationUtility;
use App\Utility\SmsUtility;
use Auth;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\AramexController;
use Log;
use App\Models\TrackingShipment;

class OrderController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:seller_view_all_orders'])->only('index');
        $this->middleware(['permission:seller_view_order_details'])->only('show');
        /* $this->middleware(['permission:seller_update_delivery_status'])->only('update_delivery_status'); */
        $this->middleware(['permission:seller_update_payment_status'])->only('update_payment_status');
    }

    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /* seller_lease_creation($user=Auth::user()); */

        /*  $step=7; */
        /*  $tour_steps=Tour::orderBy('step_number')->get(); */
        /*  return view('seller.coming_soon',compact('step','tour_steps')); */

        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = Order::orderBy('id', 'desc')
            ->where('seller_id', Auth::user()->owner_id)
            ->distinct();

        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }

        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }

        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }

        $orders = $orders->paginate(15);

        foreach ($orders as $key => $value) {
            $order = Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        return view('seller.orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
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

        return view('seller.orders.show', compact('order', 'delivery_boys'));
    }

    public function update_delivery_status(Request $request)
    {
        try {
            $order = OrderDetail::findOrFail($request->order_id);
            $order->order->delivery_viewed = '0';
            $order->delivery_status = $request->status;
            $order->save();

            if ($request->status === "ready_for_shipment") {
                $controller = new AramexController;
                $pickup_input = $controller->transformNewPickupData($request->all());

                $pickup = $controller->createPickup($pickup_input);

                if ($pickup !== null && $pickup["HasErrors"] === true) {
                    return response()->json([
                        "error" => true,
                        "message" => __("There's an error while processing pickup creation! Please try again later!")
                    ], 500);
                }

                $product = get_single_product($request->product_id);

                $dimensions = [
                    'Length' => $product->length,
                    'Width' => $product->width,
                    'Height' => $product->height,
                    'Unit' => 'cm',
                ];

                $request->merge([
                    "pickup_guid" => $pickup["ProcessedPickup"]["GUID"],
                    "dimensions" => $dimensions
                ]);

                $shipment_input = $controller->transformNewShipmentsData($request->all());
                $shipment = $controller->createShipments($shipment_input);

                if ($shipment !== null && $shipment["HasErrors"] === true) {
                    return response()->json([
                        "error" => true,
                        "message" => __("There's an error while processing shipment creation! Please try again later!")
                    ], 500);
                }

                $link = $shipment["Shipments"][0]["ShipmentLabel"]["LabelURL"];

                TrackingShipment::firstOrCreate([
                    "user_id" => auth()->user()->id,
                    "order_detail_id" => $order->id,
                    "shipment_id" => $shipment["Shipments"][0]["ID"],
                    "label_url" => $link
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

            if (
                addon_is_activated('otp_system') &&
                SmsTemplate::where('identifier', 'delivery_status_change')
                    ->first()
                    ->status == 1
            ) {
                try {
                    SmsUtility::delivery_status_change(json_decode($order->order->shipping_address)->phone, $order->order);
                } catch (Exception) {}
            }

            NotificationUtility::sendNotification($order->order, $request->status);

            if (get_setting('google_firebase') == 1 && $order->order->user->device_token != null) {
                $status = str_replace('_', '', $order->delivery_status);
                $request->merge([
                    "device_token" => $order->order->user->device_token,
                    "title" => 'Order updated !',
                    "status" => $status,
                    "text" => " Your order {$order->order->code} has been {$status}",
                    "type" => 'order',
                    "id" => $order->id,
                    "user_id" => $order->order->user->id
                ]);

                NotificationUtility::sendFirebaseNotification($request);
            }

            if (addon_is_activated('delivery_boy')) {
                if (Auth::user()->user_type == 'delivery_boy') {
                    $deliveryBoyController = new DeliveryBoyController;
                    $deliveryBoyController->store_delivery_history($order);
                }
            }

           if ($request->status === "ready_for_shipment") {
                return response()->json([
                    "error" => false,
                    "data" => [
                        "link" => $link
                    ]
                ], 200);
            }

            return 1;
        } catch(Exception $e) {
            Log::info("Error while changing delivery status, with message: {$e->getMessage()}");
            return response()->json(["error" => true, "message" => __("Something went wrong!")]);
        }
     }

    // Update Payment Status
    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        foreach ($order->orderDetails->where('seller_id', Auth::user()->owner_id) as $key => $orderDetail) {
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();

        if ($order->payment_status == 'paid' && $order->commission_calculated == 0) {
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
            } catch (\Exception $e) {

            }
        }

        return 1;
    }

    public function getWarhouses(Request $request)
    {
        try {
            $quantity = OrderDetail::find($request->order_id)->quantity;
            $data = StockSummary::where(['seller_id' => $request->seller, 'variant_id' => $request->product])
                ->with(['productVariant', 'warehouse'])->get();

            return response()->json(['error' => false, 'data' => $data, 'quantity' => $quantity]);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }

    }

    public function stockMovement(Request $request)
    {
        try {
            $warehouses = $request->warehouses;
            $order = OrderDetail::find($request->order);
            $order->delivery_status = 'in_preparation';
            $order->save();
            foreach ($warehouses as $key => $value) {
                $stock = StockSummary::where(['warehouse_id' => $value['warehouse_id'], 'variant_id' => $request->product])
                    ->first();
                $stock->current_total_quantity = $stock->current_total_quantity - $value['quantity'];
                $stock->save();
            }

            return response()->json(['error' => false, 'message' => translate('Order status has been updated')]);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }
}
