<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPause;
use Auth;
use Illuminate\Http\Request;
use Stripe\Subscription;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Laravel\Cashier\Exceptions\IncompletePayment;


class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = Auth::user();

        // $planName = 'prod_QToCjFrgzq3KmV';

        // $isSubscribedToDailyPlan = $user->subscriptions()->where('name', $planName)->exists();

        // $subscription = $user->subscriptions()->where('name', $planName)->first();
        $subscription = $user->subscription('default');
        // $subscription = Subscription::retrieve($subscription->stripe_id);
        //  // Access subscription details
        //  $startDate = $subscription->current_period_start; // Unix timestamp
        //  $endDate = $subscription->current_period_end; // Unix timestamp

        //  // Convert timestamps to readable dates
        //  $startDateFormatted = \Carbon\Carbon::createFromTimestamp($startDate)->toDateString();
        //  $endDateFormatted = \Carbon\Carbon::createFromTimestamp($endDate)->toDateString();


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

                'user'  =>$user,
                'subscription' =>$subscription
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching payment method: ' . $e->getMessage());
        }
    }
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $user = $request->user();

        $checkout_session = StripeSession::create([
            'ui_mode' => 'embedded',

            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $request->input('price_id'), // This should be a subscription price ID
                'quantity' => 1,
            ]],
            'mode' => 'subscription', // Ensure this is 'subscription'
            'customer' => $user->stripe_id, // Use Stripe customer ID

            'return_url' => route('seller.lease.index') . '?session_id={CHECKOUT_SESSION_ID}',

        ]);

        return response()->json(['clientSecret' => $checkout_session->client_secret]);

    }
    public function pause(Request $request)
    {
        $user = Auth::user();
        $subscription = $user->subscription('default'); // Or the name of your subscription plan

        if (!$subscription) {
            return redirect()->back()->with('error', 'No subscription found.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_id);

            // Calculate the end of the current billing cycle
            $currentPeriodEnd = $stripeSubscription->current_period_end;
            $pauseAt = \Carbon\Carbon::createFromTimestamp($currentPeriodEnd);

            // Store the pause request with the pause date
            $subscription->update([
                'pause_at' => $pauseAt, // You need to add this column in your subscription table
                // 'pause_collection' => json_encode([
                //     'behavior' => 'keep_as_draft',
                // ]),
            ]);

            // Log pause request
            SubscriptionPause::create([
                'user_id' => $user->id,
                'paused_at' => null, // Will be updated by the scheduled job
                'resume_at' => null,
            ]);

            // Dispatch a job to handle pausing at the end of the billing cycle
            \App\Jobs\PauseSubscriptionJob::dispatch($subscription->id)
                ->delay($pauseAt);

            return redirect()->back()->with('success', 'Pause request scheduled successfully.');
        } catch (\Exception $e) {
            // Handle exception
            return redirect()->back()->with('error', 'Failed to schedule pause: ' . $e->getMessage());
        }
    }


    public function unpause(Request $request)
{
    $user = Auth::user();
    $subscription = $user->subscription('default'); // Or the name of your subscription plan

    if (!$subscription) {
        return redirect()->back()->with('error', 'No subscription found.');
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));

 try {
        $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_id);

        // Remove pause_collection settings to unpause the subscription
        $stripeSubscription->pause_collection = null;
        $stripeSubscription->save();

        // Optionally update your database if you have a pause_collection column
        $subscription->update([
            'pause_collection' => null,
        ]);

          // Log resume event
          SubscriptionPause::where('user_id', $user->id)
          ->whereNull('resumed_at')
          ->update(['resumed_at' => now()]);
            // Calculate total pause duration in the current month
            $currentMonthStart = now()->startOfMonth();

            $totalPausedDays = SubscriptionPause::where('user_id', $user->id)
                ->whereBetween('paused_at', [$currentMonthStart, now()])
                ->whereNotNull('resumed_at')
                ->get()
                ->map(function ($pause) {
                    return $pause->paused_at->diffInDays($pause->resumed_at);
                })
                ->sum();
         // Handle cases where the pause has no resume
            $unresolvedPauses = SubscriptionPause::where('user_id', $user->id)
            ->whereNull('resumed_at')
            ->get();

        foreach ($unresolvedPauses as $pause) {
            $totalPausedDays += $pause->paused_at->diffInDays(now());
        }


       // Monthly lease cost in dollars
       $featureMonthlyLease = 5;
       $daysInMonth = now()->daysInMonth;

       // Calculate prorated reduction amount
       $proratedReduction = ($featureMonthlyLease / $daysInMonth) * $totalPausedDays;

       // Convert the prorated amount to cents and round to the nearest whole number
       $proratedReductionCents = round($proratedReduction * 100);

        // Create an invoice item for the prorated reduction
        \Stripe\InvoiceItem::create([
            'customer' => $stripeSubscription->customer,
            'amount' => -$proratedReductionCents, // Use negative amount to create a reduction
            'currency' => 'usd', // Replace with your currency
            'description' => 'Reduction for paused subscription period',
        ]);

        // Create and finalize an invoice
        $invoice = \Stripe\Invoice::create([
            'customer' => $stripeSubscription->customer,
            'auto_advance' => true, // Automatically finalize the invoice
        ]);

        $invoice->finalizeInvoice();

        return redirect()->back()->with('success', 'Subscription resumed successfully and reduction for paused period applied.');
    } catch (\Exception $e) {
        // Handle exception
        return redirect()->back()->with('error', 'Failed to resume subscription: ' . $e->getMessage());
    }
}



    public function cancel()
    {

            $user = Auth::user();

            // Check if the user has an active subscription
            if ($user->subscribed('default')) {
                // Cancel the subscription at the end of the current period
                $user->subscription('default')->cancel();

                // Redirect to a success page or return a response
                return redirect()->back()->with('success', 'Subscription canceled successfully.');
            } else {
                return redirect()->back()->with('error', 'No active subscription found.');
            }

    }

    public function resume()
{
    try {
        $user = Auth::user();

        if ($user->subscribed('default')) {
            // Resume the subscription if it's been canceled at period end
            $user->subscription('default')->resume();

            return redirect()->back()->with('success', 'Subscription resumed successfully.');
        } else {
            return redirect()->back()->with('error', 'No active subscription found or it is not canceled.');
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to resume subscription: ' . $e->getMessage());
    }
}
public function updatePaymentInformation(Request $request)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    $user = Auth::user();
    $paymentMethodId = $request->input('payment_method');

    try {
        // Update the user's default payment method
        $user->updateDefaultPaymentMethod($paymentMethodId);

        // Set session variable to indicate payment information update attempt
        session(['payment_update_attempt' => true]);

        return redirect()->back()->with('success', 'Payment information updated. Please wait while we process your payment.');
    } catch (IncompletePayment $exception) {
        return redirect()->back()->with('error', 'Payment information update failed: ' . $exception->getMessage());
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to update payment information: ' . $e->getMessage());
    }
}

}
