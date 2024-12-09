<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\StockSummary;
use App\Models\Cart;
use Carbon\Carbon;
use Log;
use Exception;

class ProcessStockQuantityReservationCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            $stockSummaries = StockSummary::where('current_total_quantity', '<', 0)
                ->where('created_at', '<', Carbon::now()->subMinutes(RESERVATION_WAIT_TIME))
                ->get();

            // unlock the cart reservation
            $stockSummaries->each(function($summary) {
                $updatedCartNumber = Cart::where("user_id", $summary->seller_id)
                    ->where("product_id", $summary->variant_id)
                    ->update(["reserved" => "NO"]);

                Log::info(
                    sprintf(
                        "Unlock carts reservation ends with success, it updates: %d record(s)",
                        $updatedCartNumber
                    )
                );
              $summary->delete();
            });

            Log::info(
                sprintf(
                    "Processing stock quantity reservation check job ends with success, it deletes: %d record(s)",
                    $stockSummaries->count()
                )
            );
        } catch (Exception $e) {
            Log::error("Error while Processing stock quantity reservation check job, with message: {$e->getMessage()}");
        }
    }
}
