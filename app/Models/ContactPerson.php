<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'mobile_phone',
        'additional_mobile_phone',
        'nationality',
        'date_of_birth',
        'emirates_id_number',
        'emirates_id_expiry_date',
        'emirates_id_file_path',
        'business_owner',
        'designation',
        // Add other fields as needed
    ];
}
