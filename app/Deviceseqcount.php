<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deviceseqcount extends Model
{
    //
    protected $table = 'deviceseqcount';
     protected $primaryKey = 'deviceseqcountid';
   protected $fillable = [
   		'deviceid',
        'rollovercount','seqno',];
}
