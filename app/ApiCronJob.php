<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiCronJob extends Model
{
    protected $table = 'apicronjob';
    protected $primaryKey = 'apicronjobid';

    public function user(){

    	return $this->hasOne('App\User', 'userid', 'apiuserid');
    }

    public function anytimeaccess(){

    	return $this->hasOne('App\AnyTimeAccessBelt', 'deviceid', 'apiuserid');
    }
}
