<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeAccount extends Model
{
    protected $table = 'employeeaccount';
    protected $primaryKey = 'empaccountid';

    public function employeename(){

    	return $this->hasOne('App\Employee', 'employeeid', 'employeeid');

    }
}
