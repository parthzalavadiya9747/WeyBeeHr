<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExcelExport extends Model
{
    protected $table = 'excelexport';
    protected $primaryKey = 'excelexportid';

    protected $created_at = false;
    protected $updated_at = false;
}
