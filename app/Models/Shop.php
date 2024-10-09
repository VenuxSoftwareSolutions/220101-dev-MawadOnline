<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Shop extends Model
{
    use HasTranslations;

  protected $with = ['user'];
  protected $fillable = ['user_id','name','verification_status','slug'];
  public $translatable = ['name'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function seller_package(){
    return $this->belongsTo(SellerPackage::class);
  }
  public function followers(){
    return $this->hasMany(FollowSeller::class);
  }
}
