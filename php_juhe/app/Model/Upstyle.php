<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Upstyle extends Model
{
    protected $guarded = ['id','created_at','updated_at'];
    public function getParamsAttribute($value)
    {
        return json_decode($value,true);
    }
}
