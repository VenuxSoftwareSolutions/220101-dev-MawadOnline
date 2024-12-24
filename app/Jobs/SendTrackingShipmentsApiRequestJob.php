<?php

namespace App\Jobs;

use App\Http\Controllers\AramexController;
use App\Models\OrderDetail;
use App\Models\TrackingShipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTrackingShipmentsApiRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $controller = new AramexController;

        TrackingShipment::all()->each(function ($tracking) use ($controller) {
            $result = $controller->trackShipments($tracking->shipment_id);

            if ($result['HasErrors'] === false && count($result['TrackingResults']) > 0) {
                $orderDetail = OrderDetail::find($tracking->order_detail_id);
                $orderDetail->status = $result['TrackingResults'][0];
                $orderDetail->save();
            }
        });
    }
}
