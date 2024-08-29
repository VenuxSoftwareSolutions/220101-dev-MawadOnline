<?php

namespace App\Jobs;

use App\Models\SubscriptionPause;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stripe\Stripe;

class PauseSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscriptionId;

    public function __construct($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    public function handle()
    {
        $subscription = \App\Models\Subscription::find($this->subscriptionId);
        $user = $subscription->user;
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripeSubscription = \Stripe\Subscription::retrieve($subscription->stripe_id);

            // Update the subscription with pause_collection settings
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
            SubscriptionPause::where('user_id', $user->id)
                ->whereNull('paused_at')
                ->update(['paused_at' => now()]);

        } catch (\Exception $e) {
            // Handle exception
        }
    }
}
