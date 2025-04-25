<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Refund;
use App\Models\RefundHistories;
use Stripe\Stripe;
use Stripe\StripeClient;
use App\Models\Order;
use App\Models\OrderFees;
use App\Models\OrderDetailsFees;

class StripeWebhooksController extends Controller
{
    public function handle(Request $request){
        if($request->has("type") ){
            $eventType = $request->type;
            $result = $request->data['object'];
            switch ($eventType) {
                case 'charge.refund.updated':
                    return $this->chargeRefundUpdated($result);
                case 'charge.updated':
                    return $this->chargeUpdated($result);
            }
        }
    }

    /**
     * handle charge refund updated from stripe
     */
    public function chargeRefundUpdated($result){
            $refund_id = $result['id'];
            $charge_id = $result['charge'];
            $refund = Refund::where(['payment_refund_id' => $result['id'],'payment_charge_id' => $result['charge']])->first();
            if($refund){
                $refund->refund_status = $result['status'];
                $newRefund = new RefundHistories();
                $newRefund->refund_status = $result['status'];
                $newRefund->description_error = $result['failure_reason'];
                $newRefund->payment_refund_id = $result['id'];
                $newRefund->payment_charge_id = $result['charge'];
                $newRefund->amount = number_format($result['amount']/100,2);
                $newRefund->refund_id = $refund->id;
                $refund->save();
                $newRefund->save();
            }
            Log::channel('webhooks')
                ->info('webhook event received object  charge.refund.updated',$result);
    }

    /**
     * handle charge updated to get balance key
    */
    public function chargeUpdated($result){

        $this->calculateOrderFee($result);

    }

    public function calculateOrderFee($result){
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        if($result['object'] == "charge"){
            $balance = $stripe->balanceTransactions->retrieve($result['balance_transaction'], []);
            $order = Order::where(['payment_intent_id' => $result['payment_intent']])->first();
            if($order){
                $fee = new OrderFees(['fee_details'=>$balance->fee_details,'total_fee'=>$balance->fee,'payment_charge_id' => $result['id'],'payment_balance_id' => $balance->id]);
                $order->orderFees()->save($fee);
                $this->calculateAndSaveOrderDetailsFees($order,$balance->fee);
            }
        }
        Log::channel('webhooks')
            ->info('webhook event received object  charge.updated',["charge_id"=>$result['id'],"balance_id"=>$balance->id,"balance"=>$balance->fee,"details"=>$balance->fee_details]);
    }

    public function calculateAndSaveOrderDetailsFees(Order $order,$feeAmount){
        $products = $order->orderDetails;
        $orderAmount = $order->grand_total;
        foreach ($products as $product){
            $productAmount = $product->price + $product->shipping_cost;
            $percentage = ($productAmount/$orderAmount) * 100 ;
            $feeByProduct = ($percentage * $feeAmount) / 100;
            OrderDetailsFees::create([
                'order_detail_id' => $product->id,
                'order_fee_id'    => $order->orderFees->id,
                'fee_amount'      => $feeByProduct,
            ]);
        }
    }
}
