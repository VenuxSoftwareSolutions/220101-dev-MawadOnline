<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Refund;
use App\Models\RefundHistories;


class StripeWebhooksController extends Controller
{
    public function handle(Request $request){
        if($request->has("type") && $request->type == "charge.refund.updated"){
            $result = $request->data['object'];
            $refund_id = $result['id'];
            $charge_id = $result['charge'];
            $refund = Refund::where(['payment_refund_id' => $result['id'],'payment_charge_id' => $result['charge']])->first();
            if($refund){
                Log::channel('webhooks')->info('webhook event received from refund changed',$request->all());
                Log::channel('webhooks')->info('webhook event received details',array("type"=>$request->type,'refund_id'=>$result['id'],'charge'=>$result['charge'],'Model'=>$refund,'canceled'=>"yes"));
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
        }
        Log::channel('webhooks')->info('webhook event received object  WEBHOOK',$request->all());
    }
}
