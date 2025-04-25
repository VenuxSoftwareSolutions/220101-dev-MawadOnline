<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundHistories extends Model
{
    use HasFactory;
    protected $casts = [
        'fee_details' => 'array'
    ];
    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }
}
