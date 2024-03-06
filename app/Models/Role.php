<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
//use Illuminate\Database\Eloquent\Model;
use App;

class Role extends SpatieRole
{
    protected $with = ['role_translations'];

    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $role_translation = $this->role_translations->where('lang', $lang)->first();
        return $role_translation != null ? $role_translation->$field : $this->$field;
    }

    public function role_translations(){
      return $this->hasMany(RoleTranslation::class);
    }
}
