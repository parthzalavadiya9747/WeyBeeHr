<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingDays extends Model
{
    protected $table = 'workingcalander'; 
    protected $primaryKey = 'workingcalid';

    public function nonworkingdays(){

    	return $this->hasMany('App\MonthLeave', 'workingcalanderid', 'workingcalid');

    }
}
