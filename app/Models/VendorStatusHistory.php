<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorStatusHistory extends Model
{
    protected $fillable = ['vendor_id', 'status', 'suspension_reason', 'details','reason'];
    protected $table = 'vendor_status_history';
    public function vendor()
    {
        return $this->belongsTo(User::class);
    }
}
