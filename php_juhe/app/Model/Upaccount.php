<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Upaccount extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public function apistyle()
    {
        return $this->belongsToMany('App\Model\Apistyle', 'apizjs','upaccount_id','apistyle_id')
            ->withPivot('costfl', 'runfl','minje','maxje','todayje','status','ifchoose','changetime','id');
    }

    public function getUpaccountParamsAttribute($value)
    {
        return json_decode($value,true);
    }
}
