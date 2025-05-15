<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BuJob extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'bu_jobs';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $primaryKey = 'id';
    public $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'vendor_user_id',
        'vendor_products_file',
        'preprocessed_file',
        'ai_processed_file',
        'total_rows',
        'images_base_folder',
        'images_base_folder_final_url',
        'images_base_folder_host',
        'docs_base_folder',
        'docs_base_folder_final_url',
        'docs_base_folder_host',
        'vendor_product_shipping',
        'mwd3p_product_shipping',
        'product_discount',
        'stage',
        'progress',
        'has_errors',
        'error_msg',
        'error_file'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'has_errors' => 'boolean',
    ];



    public function uniqueIds()
    {
        return ['id'];
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brands_bujobs', 'bu_job_id', 'brand_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, "bu_job_id");
    }
}
