<?php

namespace App\Observers;

use App\Models\OrderDetail;
use Log;
use App\Events\OrderDetailsShipmentStatusChange;

class OrderDetailObserver
{

    /**
     * Handle the OrderDetail "created" event.
     *
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return void
     */
    public function created(OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Handle the OrderDetail "updated" event.
     *
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return void
     */
    public function updated(OrderDetail $orderDetail)
    {

         Log::info('observer fired');
         if ($orderDetail->wasChanged('delivery_status') && in_array($orderDetail->delivery_status, DELIVERY_STATUS)) {
            event(new OrderDetailsShipmentStatusChange($orderDetail,$orderDetail->delivery_status));
        }
    }

    /**
     * Handle the OrderDetail "deleted" event.
     *
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return void
     */
    public function deleted(OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Handle the OrderDetail "restored" event.
     *
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return void
     */
    public function restored(OrderDetail $orderDetail)
    {
        //
    }

    /**
     * Handle the OrderDetail "force deleted" event.
     *
     * @param  \App\Models\OrderDetail  $orderDetail
     * @return void
     */
    public function forceDeleted(OrderDetail $orderDetail)
    {
        //
    }
}
