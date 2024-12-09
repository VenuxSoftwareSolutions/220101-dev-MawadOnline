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

class firstCountDownNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OrderDetail $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       try{
            Mail::to($this->order->seller->email)->send(new CountdownEmail($this->order));
            $this->order->first_count_down_notification = 'yes';
            $this->order->save();
        }catch(Exception $e){
            Log::error('an error when runing job countdown notification',$e->getMessage());
        }
    }
}
