<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketReplies()
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'desc');
    }

    public function orderDetails()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function getVendor()
    {
        $sub_order_id = $this->orderDetails->order_id;

        return Order::find($sub_order_id)->vendor;
    }

}
