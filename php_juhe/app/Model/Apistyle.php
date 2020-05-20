<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Apistyle extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function upaccount()
    {
        return $this->belongsToMany('App\Model\Upaccount', 'apizjs','apistyle_id','upaccount_id')
            ->withPivot('costfl', 'runfl','minje','maxje','todayje','status','ifchoose','changetime','id');
    }
    public function getPollingIdsAttribute($value)
    {
        return unserialize($value);
    }
}
