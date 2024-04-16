<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposedPayoutChange extends Model
{
    use HasFactory;
    protected $table = 'proposed_changes'; // Specify the table name

    protected $fillable = [
        'user_id',
        'modified_fields',
        'status',
        'admin_viewed', // Add the new column to the fillable array
    ];


    public function getNewValue($field)
    {
        $modifiedFields = json_decode($this->modified_fields, true);

        foreach ($modifiedFields as $modifiedField) {
            if ($modifiedField['field'] === $field) {
                return $modifiedField['new_value'];
            }
        }

        return null;
    }

    public function vendorAdmin()
    {
        return $this->belongsTo(User::class,"user_id");
    }
}
