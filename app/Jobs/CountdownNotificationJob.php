<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CountdownEmail;
use App\Models\OrderDetail;
use Log;
use Illuminate\Support\Facades\Mail;

class CountdownNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orders ;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $this->fetchPendingOrdres();
        }catch(Exception $e){
            Log::error('an error when runing job countdown notification',$e->getMessage());
        }
    }

    protected function fetchPendingOrdres(){
        try{
            OrderDetail::where(['delivery_status'=>'pending',
                                'first_count_down_notification'=>'yes'])
                                ->get()
                                ->each(function($order){
                                    $this->sendMail($order);
                                });
        }catch(Exception $e){
            Log::error('an error when fetch pending ordres',$e->getMessage());
        }
    }

    protected function sendMail($order){
        try{
            Mail::to($order->seller->email)->send(new CountdownEmail($order));
        }catch(Exception $e){
            Log::error('an error when runing job countdown notification',$e->getMessage());
        }
    }


}
