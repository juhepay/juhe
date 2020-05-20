<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guarded = ['id','created_at','updated_at'];
    protected $hidden  = ['password', 'google_key','remember_token'];
}
