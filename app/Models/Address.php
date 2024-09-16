<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id','full_name','address','country_id','state_id','city_id','phone','building_name','landmark','address_type','delivery_instructions','set_default'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // public function state()
    // {
    //     return $this->belongsTo(State::class);
    // }

    // public function city()
    // {
    //     return $this->belongsTo(City::class);
    // }

    public function state()
    {
        return $this->belongsTo(Emirate::class);
    }

    public function city()
    {
        return $this->belongsTo(Area::class);
    }
}
