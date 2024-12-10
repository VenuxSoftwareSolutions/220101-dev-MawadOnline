<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountAuthenticator extends Model
{
    use HasFactory;

    protected $table = 'account_authenticators';

   
    protected $fillable = [
        'name',
    ];

    
    public function users()
    {
        return $this->hasMany(User::class, 'authenticator_id');
    }
}
