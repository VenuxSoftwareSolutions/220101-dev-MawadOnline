<?php

namespace App\Models;

use App\Models\User;
use App\Models\SellerPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerLease extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','package_id','total','discount','start_date','end_date','roles'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function package(){
        return $this->belongsTo(SellerPackage::class ,'package_id');
    }

}
