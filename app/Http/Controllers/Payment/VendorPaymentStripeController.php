<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SellerLease;
use App\Models\SellerPackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class VendorPaymentStripeController extends Controller
{
    public function payPlan(Request $request, string $plan= 'price_1QHkCxFlc6vGgLAs8Iwta3Pd'){
        return $request->user()
        ->newSubscription('prod_RA4L1fkvO8w7Y9', 'price_1QHkCxFlc6vGgLAs8Iwta3Pd')
        ->checkout();
    }


    public function confirmPaymentAccount(SellerPackage $package){
        // dd($package);
        $user = Auth::user();
        return view('seller.payment_methods.stripe.update-payment-method', [
        'intent' => $user->createSetupIntent(),'package'=>$package
        ]);
    }


    public function storePaymentAccount(Request $request){
        try{
                $user = Auth::user();
                $stripe = new \Stripe\StripeClient(config('app.STRIPE_KEY'));
                $customer = $user->createOrGetStripeCustomer();
                $striepeMethod = $stripe->paymentMethods->attach(
                $request->paymentMethodId,
                ['customer' => $customer->id]
                );
                $striepeDefaultMethod = $stripe->customers->update(
                $customer->id,
                ['invoice_settings' =>['default_payment_method' => $request->paymentMethodId]]
                );
                $payment = $stripe->setupIntents->create(
                [
                    'payment_method_types' => ['card'],
                    'customer'=>$customer->id,
                    'payment_method'=>$request->paymentMethodId,
                ]);
                $currentDate = Carbon::now();
                // Calculate the start date of the lease cycle
                $startDate = Carbon::create($currentDate);
                // Calculate the end date of the lease cycle
                $endDate = $startDate->copy()->addMonth()->subDay();
                $package=SellerPackage::find($request->package);
                $seller_lease=new SellerLease;
                $seller_lease->vendor_id =$user->id;
                $seller_lease->package_id = $package->id;
                $seller_lease->start_date = $startDate->format('Y-m-d') ;
                $seller_lease->end_date = $endDate->format('Y-m-d') ;
                $seller_lease->total = $package->amount;
                $seller_lease->discount = $package->amount;
                $seller_lease->save();


                return response()->json(['error'=>false,
                                         'message'=> trans('lease.payment_completed_successfully'),
                                         'customer'=>$customer]
                                         ,200);
        }catch(exception $e){
            return response()->json(['error'=>true,'message'=>$e->getMessage()],500);
        }


    }
}
