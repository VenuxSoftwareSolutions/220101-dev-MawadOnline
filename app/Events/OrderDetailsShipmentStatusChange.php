<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\OrderDetail;

class OrderDetailsShipmentStatusChange
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderDetails;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(OrderDetail $orderDetails)
    {
        $this->orderDetails = $orderDetails;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
