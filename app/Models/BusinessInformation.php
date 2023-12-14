<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BusinessInformation extends Model
{
    use HasFactory;
    public $translatable = ['trade_name','eshop_name','eshop_desc'];
    protected $fillable = [
        'user_id',
        'trade_name_english',
        'trade_name_arabic',
        'trade_license_doc',
        'eshop_name_english',
        'eshop_name_arabic',
        'eshop_desc_en',
        'eshop_desc_ar',
        'license_issue_date',
        'license_expiry_date',
        'state',
        'area_id',
        'street',
        'building',
        'unit',
        'po_box',
        'landline',
        'vat_registered',
        'vat_certificate',
        'trn',
        'tax_waiver',
        'civil_defense_approval',
    ];
}
