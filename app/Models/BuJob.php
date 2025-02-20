<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuJob extends Model
{
    use HasFactory;

    protected $table = 'bu_jobs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'vendor_user_id',
        'vendor_org_file',
        'preprocessed_file',
        'total_rows',
        'images_base_folder',
        'images_base_folder_final_url',
        'images_base_folder_host',
        'docs_base_folder',
        'docs_base_folder_final_url',
        'docs_base_folder_host',
        'vendor_product_shipping',
        'mwd3p_product_shipping',
        'stage',
        'progress',
        'has_errors',
        'error_msg',
    ];

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brands_bujobs', 'bu_job_id', 'brand_id');
    }
}
