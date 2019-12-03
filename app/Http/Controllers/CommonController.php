<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Curl;
use DB;
use App\DeviceInfo;
use App\Deviceuser;
use App\Department;

class CommonController extends Controller
{
    public function notification(){


    	session()->set('success','Item created successfully.');


        return view('common.notification');

    }

    public function method(){

       /* for($i = 1; $i < 600; $i++){
            $api = 'http://192.168.1.80/device.cgi/credential?action=delete&user-id='.$i.'&type=1';
            $url = '192.168.1.80:80';
            $username = 'admin';
            $password = '1234';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($ch, CURLOPT_URL,$api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            echo "<pre>";print_r($response);
        }*/

    	/*//$data =  Curl::to('http://192.168.1.79/device.cgi/credential?action=get&type=1&user-id=500&finger-index=1')->get();
    	/*$api = 'http://192.168.1.80/device.cgi/credential?action=get&type=1&user-id=4&finger-index=1';
     	//$api = 'http://192.168.1.79/device.cgi/users?action=get&user-id=500';
    	$url = '192.168.1.80:80';
    	$username = 'admin';
    	$password = '1234';
    	$header = array('Content-Type: binary/octet-stream');
    	$ch = curl_init($url);
    	curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    	curl_setopt($ch, CURLOPT_URL,$api);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$response = curl_exec($ch);*/
    	//dd($response);
    	
    	//DB::table('finger')->insert(['template' => $response]);

    	//$fingertemplate = DB::table('finger')->get()->first();
    	//$tmp = str_replace('"', '', $fingertemplate->template);
    	//dd($fingertemplate->template);
    	/*
    	$fields = $response;
    	$tmp = str_replace('"', '', $fields);*/
    	/*$api = 'http://192.168.1.79:80/device.cgi/credential?action=set&type=1&user-id=9';
    	$username = 'admin';
    	$password = '1234';
    	$header = array('Content-Type: binary/octet-stream');
    	$url = '192.168.1.79:80';
    	$resource = curl_init();
    	curl_setopt($resource, CURLOPT_USERPWD, $username . ":" . $password);
    	curl_setopt($resource, CURLOPT_URL,$api);
    	curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($resource, CURLOPT_POST, 1);
    	curl_setopt($resource, CURLOPT_POSTFIELDS, $fingertemplate->template);
    	curl_setopt($resource, CURLOPT_HTTPHEADER, $header);
    	$response_second = curl_exec($resource);
    	dd($response_second);*/
    	/*$username = 'admin';
    	$password = '1234';
    	$header = array('Content-Type: binary/octet-stream');

    	$device1_api = 'http://192.168.1.79/device.cgi/credential?action=get&type=1&user-id=513&finger-index=1';
    	$url = '192.168.1.79:80';

    	$ch = curl_init($url);
    	curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    	curl_setopt($ch, CURLOPT_URL,$device1_api);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$response = curl_exec($ch);
    	
    	$device2_api = 'http://192.168.1.80:80/device.cgi/credential?action=set&type=1&user-id=2';
    	$url = '192.168.1.80:80';
    	$resource = curl_init($url);
    	curl_setopt($resource, CURLOPT_USERPWD, $username . ":" . $password);
    	curl_setopt($resource, CURLOPT_URL,$device2_api);
    	curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($resource, CURLOPT_POST, 1);
    	curl_setopt($resource, CURLOPT_POSTFIELDS, $fingertemplate->template);
    	curl_setopt($resource, CURLOPT_HTTPHEADER, $header);
    	$response_second = curl_exec($resource);
    	dd($response_second);

        
    
        /*$deviceinfo = DeviceInfo::where('status', 1)->where('main', 1)->first();
        if(!empty($deviceinfo)){*/

            for($i=1; $i<=50; $i++){


           

           

           
            $deviceip = '192.168.1.79';
            //$deviceip = '192.168.1.81';
            //$deviceip = '192.168.1.82';
            $portno = '80';
            $username = 'admin';
            $password = '1234';

            //$getfinger_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/credential?action=delete&user-id='.$i.'&type=1';
            $getfinger_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/users?action=delete&user-id='.$i.'';
            //$getfinger_api = 'http://192.168.1.80:80/device.cgi/credential?action=get&type=1&user-id=2&finger-index=1';
           
            $ch = curl_init($deviceip);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($ch, CURLOPT_URL,$getfinger_api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            //$response = explode('=', $response);
   
            echo "<pre>";print_r($response);
                
            }
            /*$deviceip = '192.168.1.80';
            $portno = '80';
            $username = 'admin';
            $password = '1234';
            $employeeid= 6;
            $empinfo = Deviceuser::where('employeeid', $employeeid)->first();
            $fingertemplate = $empinfo->fingertemplate;

            $header = array('Content-Type: binary/octet-stream');

            $device2_api = 'http://192.168.1.80:80/device.cgi/credential?action=set&type=1&user-id=6';
            $url = '192.168.1.80:80';
            $resource = curl_init($url);
            curl_setopt($resource, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($resource, CURLOPT_URL,$device2_api);
            curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($resource, CURLOPT_POST, 1);
            curl_setopt($resource, CURLOPT_POSTFIELDS, $fingertemplate);
            curl_setopt($resource, CURLOPT_HTTPHEADER, $header);
            $response_second = curl_exec($resource);
            dd($response_second);*/
                

                   
        }

        public function getcity(Request $request){

            $state = $request->stateid;

            $cities = DB::table('cities')->where('state_id', $state)->get()->all();


            $html = '';
            if(!empty($cities)){
                $html .= '<option value="">--Select City--</option>';
                foreach($cities as $city){
                    $html .= '<option value="'.$city->id.'">'.$city->city.'</option>';
                }
            }else{
                $html .= '<option value="">--No City Available--</option>';
            }

            return $html;
        }

        

       
    

    
}
