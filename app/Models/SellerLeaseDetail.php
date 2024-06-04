<?php

namespace App\Models;

use App\Models\Role;
use App\Models\SellerLease;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerLeaseDetail extends Model
{
    use HasFactory;

    protected $fillable = ['lease_id','role_id','start_date','end_date','roles'];

    public function lease(){
        return $this->belongsTo(SellerLease::class,'lease_id');
    }
    public function role(){
        return $this->belongsTo(Role::class);
    }
}
