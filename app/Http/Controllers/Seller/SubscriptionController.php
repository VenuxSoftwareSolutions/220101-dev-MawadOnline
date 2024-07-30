<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $planName = 'prod_QToCjFrgzq3KmV';

        $isSubscribedToDailyPlan = $user->subscriptions()->where('name', $planName)->exists();

        $subscription = $user->subscriptions()->where('name', $planName)->first();
        // Initialize Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Retrieve the customer's Stripe customer ID
            $stripeCustomerId = $user->stripe_id;

            // if (!$stripeCustomerId) {
            //     throw new \Exception('Stripe customer ID not found.');
            // }

            // Retrieve the customer's payment methods
            $paymentMethods = PaymentMethod::all([
                'customer' => $stripeCustomerId,
                'type' => 'card',
            ]);

            $paymentMethod = $paymentMethods->data[0] ?? null;

            return view('seller.subscription.index', [
                'paymentMethod' => $paymentMethod,
                'isSubscribedToDailyPlan' =>$isSubscribedToDailyPlan,
                'user'  =>$user,
                'subscription' =>$subscription
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching payment method: ' . $e->getMessage());
        }
    }
}
