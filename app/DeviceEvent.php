<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceEvent extends Model
{
    //
        protected $table = 'deviceevent';
     protected $primaryKey = 'deviceeventid';
   protected $fillable = [
   	
        'rollovercount','seqno','date','time','eventid','detail1','detail2','detail3','detail4','detail5','status','created_at', 'deviceid'];
}
