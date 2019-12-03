<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';
    protected $primaryKey = 'employeeid';

    public function deviceuser(){

		return $this->hasOne('App\Deviceuser', 'employeeid', 'employeeid');

	}
}


