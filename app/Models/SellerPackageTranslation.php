<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerPackageTranslation extends Model
{
    protected $fillable = ['details','name', 'lang', 'seller_package_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array',
    ];

    public function seller_package(){
      return $this->belongsTo(SellerPackage::class);
    }
}
