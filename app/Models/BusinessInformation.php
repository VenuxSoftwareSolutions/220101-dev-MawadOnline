<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BusinessInformation extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['trade_name','eshop_name','eshop_desc'];
    protected $fillable = [
        'user_id',
        'trade_name',
        'trade_name',
        'trade_license_doc',
        'eshop_name',
        'eshop_name',
        'eshop_desc',
        'eshop_desc',
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
