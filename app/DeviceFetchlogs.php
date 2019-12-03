<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceFetchlogs extends Model
{
    //
        protected $table = 'devicefetchlogs';
     protected $primaryKey = 'deviceeventid';
   protected $fillable = [
   	
        'rollovercount','seqno','date','time','eventid','detail1','detail2','detail3','detail4','detail5','status'];

    public function user(){
    	return $this->hasOne('App\User', 'userid', 'detail1');
    }

    public function emp(){
    	return $this->hasOne('App\Employee', 'employeeid', 'detail1');

    }
}
