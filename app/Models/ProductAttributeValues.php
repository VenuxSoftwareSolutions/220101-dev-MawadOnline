<?php

namespace App\Models;

use App\Traits\EnhancedRevisionableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValues extends Model
{
    use EnhancedRevisionableTrait, HasFactory;

    protected $with = ["attribute", "unity"];

    protected function getLastActionNumber()
    {
        $lastRevision = $this->revisionHistory()->latest('id')->first();

        return $lastRevision ? $lastRevision->action_number + 1 : 0;
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'id_attribute');
    }

    public function unity()
    {
        return $this->belongsTo(Unity::class, 'id_units');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'id_colors');
    }

    public function attributeValues()
    {
        return $this->belongsTo(AttributeValue::class, 'id_values');
    }
}
