<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = ['id','created_at','updated_at'];
    protected $hidden  = ['password', 'api_key', 'google_key','save_code','remember_token'];
}
