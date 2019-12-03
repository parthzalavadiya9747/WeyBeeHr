<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class Helper
{
   public static function errormail($module, $errordesc, $level){


   	$module = $module;
   	$errordesc = $errordesc;
   	$level = $level;

   	Mail::to('parth@weybee.com')->send(new SendMailable($module ,$errordesc, $level));


   }
}
