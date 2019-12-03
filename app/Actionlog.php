<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actionlog extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'log_id';
}
