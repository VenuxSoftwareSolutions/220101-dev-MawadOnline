<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Refund;
use App\Models\RefundHistories;

class RefundController extends Controller
{

    public function index(){
        $refunds = Refund::paginate(10);
        return view('backend.refunds.index', compact('refunds'));
    }


    public function execute(Refund $refund){
        try{
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $result = $stripe->refunds->create(['payment_intent' => "pi_3QfkfoFlc6vGgLAs1XEpbB8E","amount" => $refund->orderDetail->price * 100]);
            $newRefund = new RefundHistories();
            $newRefund->refund_status = $result->status;
            $newRefund->description_error = $result->failure_reason;
            $newRefund->payment_refund_id = $result->id;
            $newRefund->payment_charge_id = $result->charge;
            $newRefund->amount = number_format($result->amount/100,2);
            $newRefund->refund_id = $refund->id;
            $refund->refund_status = $result->status;
            $refund->save();
            $newRefund->save();
            flash(translate('Refund has been executed successfully'))->success();
            return back();
        } catch (Exception $e) {
            Log::info("Refund Controller - Error while execute refund, with message: {$e->getMessage()}");
            return response()->json(['error' => true, 'message' => __('Something went wrong: more details in refund management!')], 500);
        }
    }

    public function details(Refund $refund){
        try {
            $details = $refund->refundHistories()->paginate(10);
            return view('backend.refunds.details', compact('details'));
        } catch (Exception $e) {
            Log::info("Refund Controller - Error while execute refund, with message: {$e->getMessage()}");
        }

    }
}
