<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $table = 'leave';
    protected $primaryKey = 'leaveid';

    public function employeename(){

    	return $this->hasOne('App\Employee', 'employeeid', 'employeeid');

    }
}
