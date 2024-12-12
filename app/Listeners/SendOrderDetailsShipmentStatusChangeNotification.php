<?php

namespace App\Listeners;

use App\Events\OrderDetailsShipmentStatusChange;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ShipmentStatusEmail;
use App\Models\OrderDetail;
use Log;
use Illuminate\Support\Facades\Mail;

class SendOrderDetailsShipmentStatusChangeNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderDetailsShipmentStatusChange  $event
     * @return void
     */
    public function handle(OrderDetailsShipmentStatusChange $event)
    {
        Mail::to($event->orderDetails->seller->email)->send(new ShipmentStatusEmail($event->orderDetails));
    }
}
