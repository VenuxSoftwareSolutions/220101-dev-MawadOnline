<?php

namespace App\Models;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Model;

class TourTranslation extends Model
{
    protected $fillable = ['title', 'description','lang'];

    public function tour(){
      return $this->belongsTo(Tour::class);
    }
}
