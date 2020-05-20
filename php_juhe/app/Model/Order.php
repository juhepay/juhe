<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id','updated_at'];

    public function getPaytimeAttribute($value)
    {
        return $value ? date('Y-m-d H:i:s',$value) : '-';
    }
    public function getFjAttribute($value)
    {
        return json_decode($value,true);
    }
}
