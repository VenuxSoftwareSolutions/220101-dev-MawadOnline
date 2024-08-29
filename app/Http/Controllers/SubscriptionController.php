<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\SubscriptionPause;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;
use Stripe\Refund;
use Stripe\Exception\ApiErrorException;

class SubscriptionController extends Controller
{
    public function index()
    {
        // Fetch all subscriptions, you can use pagination if needed
        $subscriptions = Subscription::with('user')->get();

        // Return the view with subscriptions data
        return view('backend.subscriptions.index', compact('subscriptions'));
    }

    public function pause(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        if (!$subscription) {
            return redirect()->back()->with('error', 'Subscription not found.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_id);

            // Update the subscription with pause_collection settings immediately
            $stripeSubscription->pause_collection = [
                'behavior' => 'keep_as_draft', // Options: 'keep_as_draft', 'mark_uncollectible', or 'void'
            ];
            $stripeSubscription->save();

            // Update your database
            $subscription->update([

                'pause_collection' => json_encode([
                    'behavior' => 'keep_as_draft',
                ]),
            ]);




            // Log pause event
            \App\Models\SubscriptionPause::updateOrCreate(
                ['user_id' => $subscription->user_id, 'paused_at' => null],
                ['paused_at' => now(), 'resume_at' => null]
            );

            return redirect()->back()->with('success', 'Subscription paused immediately.');
        } catch (\Exception $e) {
            // Handle exception
            return redirect()->back()->with('error', 'Failed to pause subscription: ' . $e->getMessage());
        }
    }

    public function unpause(Request $request,$subscriptionId)
    {
        $subscription = Subscription::find($subscriptionId);


        if (!$subscription) {
            return redirect()->back()->with('error', 'No subscription found.');
        }
        $user = $subscription->user;
        $subscription = $user->subscription('default'); // Or the name of your subscription plan

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

// Assuming you have a user associated with the subscription
public function cancel($subscriptionId)
{
    $subscription = Subscription::find($subscriptionId);

    if ($subscription) {
        try {
            // Find the associated user
            $user = $subscription->user;

            // Cancel the subscription using Cashier
            $user->subscription('default')->cancelNow();

            return redirect()->back()->with('success', 'Subscription canceled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel the subscription.');
        }
    } else {
        return redirect()->back()->with('error', 'Subscription not found.');
    }
}
public function retryPayment($subscriptionId)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    try {
        // Retrieve the subscription
        $subscription = \Stripe\Subscription::retrieve($subscriptionId);

        if (!$subscription) {
            return redirect()->back()->with('error', 'Subscription not found.');
        }

        // Retrieve the latest invoice
        $invoiceId = $subscription->latest_invoice;
        if (!$invoiceId) {
            return redirect()->back()->with('error', 'No invoice found for this subscription.');
        }

        $invoice = Invoice::retrieve($invoiceId);

        // Ensure the invoice has a payment method
        $paymentMethodId = $invoice->payment_method;

        if (!$paymentMethodId) {
            return redirect()->back()->with('error', 'No payment method associated with this invoice.');
        }

        // Retry the payment
        $invoice->pay();

        return redirect()->back()->with('success', 'Payment retried successfully.');

    } catch (\Stripe\Exception\ApiErrorException $e) {
        return redirect()->back()->with('error', 'Failed to retry payment: ' . $e->getMessage());
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to retry payment: ' . $e->getMessage());
    }
}

public function refundLastPayment($subscriptionId)
{
    // Set your Stripe secret key
    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        // Retrieve the subscription from Stripe
        $subscription = \Stripe\Subscription::retrieve($subscriptionId);

        // Retrieve the latest invoice for the subscription
        $invoice = Invoice::retrieve($subscription->latest_invoice);

        // Get the charge ID from the invoice
        $chargeId = $invoice->charge;

        if (!$chargeId) {
            return [
                'success' => false,
                'message' => 'No charge found for this subscription'
            ];
        }

        // Create a refund
        $refund = Refund::create([
            'charge' => $chargeId,
        ]);

        // Return success response
        return [
            'success' => true,
            'message' => 'Refund successful',
            'refund' => $refund
        ];

    } catch (ApiErrorException $e) {
        // Handle errors
        return [
            'success' => false,
            'message' => 'Refund failed: ' . $e->getMessage()
        ];
    }
}
public function refund(Request $request, $subscriptionId)
{
    $result = $this->refundLastPayment($subscriptionId);

    // Redirect back with a success or error message
    return redirect()->back()->with($result['success'] ? 'success' : 'error', $result['message']);
}


}
