<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\OrderDetail;

class ChangeOrderStatusFromDeliveredToCompletedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->fetchDeliveredOrdres();
        $this->fetchCompletedOrdres();
    }

    protected function fetchDeliveredOrdres(){
        try{
            OrderDetail::where(['delivery_status'=>'delivered'])
                                ->get()
                                ->each(function($order){
                                   $days = now()->diffInDays($order->updated_at);
                                   if($days >= 7){
                                       $order->update(['delivery_status'=>"completed"]);
                                   }
                                });
        }catch(Exception $e){
            Log::error('an error when fetch delivered ordres',$e->getMessage());
        }
    }

    protected function fetchCompletedOrdres(){
        try{
            Order::where(['delivery_status','!=','completed'])
                                ->get()
                                ->each(function($order){
                                   foreach ($order->orderDetails as $key => $value) {
                                     if($value->delivery_status == 'completed'){
                                        $status = 'completed';
                                     }else{
                                        $status = 'in_progress';
                                     }
                                   }
                                   $order->delivery_status = $status;
                                   $order->save();
                                });
        }catch(Exception $e){
            Log::error('an error when fetch delivered ordres',$e->getMessage());
        }
    }


}
