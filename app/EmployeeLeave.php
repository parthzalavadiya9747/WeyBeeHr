<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    protected $table = 'employeeleave';
    protected $primaryKey = 'employeeleaveid';

    public function empname(){

    	return $this->hasOne('App\employee', 'employeeid', 'employeeid');
    }
}
