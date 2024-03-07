<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\EnhancedRevisionableTrait;

class ProductAttributeValues extends Model
{
    use HasFactory, EnhancedRevisionableTrait;

    protected function getLastActionNumber()
    {
        $lastRevision = $this->revisionHistory()->latest('id')->first();
        return $lastRevision ? $lastRevision->action_number + 1 : 0;
    }
    public function attribute() {
        return $this->belongsTo(Attribute::class,'id_attribute') ;
    }

    public function unity() {
        return $this->belongsTo(Unity::class,'id_units') ;
    }

    public function color() {
        return $this->belongsTo(Color::class,'id_units') ;
    }
}
