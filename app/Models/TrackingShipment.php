<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingShipment extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "order_detail_id", "shipment_id", "label_url"];
}
