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
            $refund_id = $request->data['object']['id'];
            $charge_id = $request->data['object']['charge'];
            $refund = Refund::where(['payment_refund_id' => $refund_id,'payment_charge_id'=>$charge_id])->first();
            if($refund){
                Log::channel('webhooks')->info('webhook event received from refund changed',$request->all());
                Log::channel('webhooks')->info('webhook event received details',array("type"=>$request->type,'refund_id'=>$request->data['object']['id'],'charge'=>$request->data['object']['charge'],'Model'=>$refund,'canceled'=>"yes"));
                $refund->refund_status = $request->data['object']['status'];
                $refund->save();
            }
        }
        Log::channel('webhooks')->info('webhook event received object  WEBHOOK',$request->all());
        Log::channel('webhooks')->info('webhook event received type  WEBHOOK',array("type"=>$request->type,'refund_id'=>$request->data['object']['id'],'charge'=>$request->data['object']['charge']));
    }
}
