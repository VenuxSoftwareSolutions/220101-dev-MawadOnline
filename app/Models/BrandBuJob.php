<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandBuJob extends Model
{
    use HasFactory;

    protected $table = 'brands_bujobs';

    protected $fillable = [
        'brand_id',
        'bu_job_id',
        'created_at',
    ];

    public $timestamps = false;

   
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

  
    public function job()
    {
        return $this->belongsTo(BuJob::class, 'bu_job_id');
    }

  
    public function scopeByBrandId($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }
}
