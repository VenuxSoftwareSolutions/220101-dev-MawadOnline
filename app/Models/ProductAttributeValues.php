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
}
