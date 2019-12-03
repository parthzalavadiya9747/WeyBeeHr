<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApiCronJob;
use App\Employee;
use App\Deviceuser;
use App\EmployeeFinger;
use App\DeviceInfo;
use App\DeviceFetchlogs;
use DB;
use Session;

class EnrollController extends Controller
{
    public function enrolldevice(){

    	$employee  = Employee::with('deviceuser')->where('status', 1)->get()->all();
    	$device  = DeviceInfo::where('status', 1)->get()->all();

    	if(empty($device)){

    		Session::flash('message', 'Please add device or active device');
    		Session::flash('alert-type', 'error');

    		return redirect()->route('adddevice', 'device');

    	}

    	return view('enrolldevice.enrolldevice')->with(compact('employee', 'device'));
    }

    public function employeedeviceinfo(Request $request){

    	$employeeid = $request->empid;

    	$empinfo = Deviceuser::where('employeeid', $employeeid)->first();

    	if(!empty($empinfo)){

    		$contract_term = date('d-m-Y', strtotime($empinfo->contractterm));
    		$device1 = $empinfo->device1;
    		$device2 = $empinfo->device2;
    		$device3 = $empinfo->device3;
    		$device4 = $empinfo->device4;
    		$enroll = $empinfo->enroll;
            $fingertemplate = 0;
            $fingertemplate_check = $empinfo->fingertemplate;
            if(!empty($fingertemplate_check)){
                $fingertemplate = 1;
            }

    		$data = ['contract_term' => $contract_term, 'device1' => $device1, 'device2' => $device2, 'device3' => $device3, 'device4' => $device4, 'enroll' => $enroll, 'fingertemplate' => $fingertemplate];

    		return $data;
    	}else{

    		$data = [];

    		return $data;
    	}


    }

    public function devicelist(){

    	$device  = DeviceInfo::all();
    	$devicename = [];
    	if(!empty($device)){
    		foreach($device as $key => $device_data){
    			$devicename['device'.++$key] = $device_data->devicename;
    		}

    		return $devicename;
    	}else{
    		return $devicename;
    	}


    }

    public function empindevice(Request $request){


    	$empid = $request->empid;
        $deviceid = $request->deviceid;
    	$contract_term = date('Y-m-d', strtotime($request->contract_term));
    	$enrollflag = !empty($request->enrollflag) ? $request->enrollflag : 0;

    	$empdata = Employee::where('employeeid', $empid)->first();
    	$firstname = $empdata->first_name;
    	$lastname = $empdata->last_name;

    	$fname = substr($firstname, 0,3);
    	$lname = substr($lastname, 0,3);
    	$username_emp = $fname.$lname.$empid;
       

    	$date_explode = explode('-' , $contract_term);
    	$day = $date_explode[2];
    	$month = $date_explode[1];
    	$year = $date_explode[0];

    	$device = DeviceInfo::where('deviceinfoid', $deviceid)->first();
    	if(!empty($device)){
    			
    			$deviceip = $device->ipaddress;
    			$username = $device->username;
    			$password = $device->password;
    			$portno = $device->portno;
    			$devicename = $device->devicename;
    			$status = '';
    			$errordevice = [];


    			$upload_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/users?action=set&user-id='.$empid.'&name='.$username_emp.'&ref-user-id='.$empid.'&user-active=1&validity-enable=1&validity-date-dd='.$day.'&validity-date-mm='.$month.'&validity-date-yyyy='.$year.'';

    			$apicronjob = new ApiCronJob();
    			$apicronjob->apiuserid = $empid;
    			$apicronjob->apitype = 'Employee Upload';
    			$apicronjob->api = $upload_api;
    			$apicronjob->response_code = null;
    			$apicronjob->status = 0;
    			$apicronjob->save();

                $apicronjoblastid = $apicronjob->apicronjobid;

                try{

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                    curl_setopt($ch, CURLOPT_URL,$upload_api);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    $response = explode('=', $response);

                    $apicronjob_update = ApiCronJob::findOrfail($apicronjoblastid);
                    $apicronjob_update->response_code = $response[1];
                    $apicronjob_update->status = 1;
                    $apicronjob_update->save();

                    if($response[1] == 0){

                        $deviceset = 'device'.$deviceid.'setuser';

                        $deviceuser = Deviceuser::where('employeeid', $empid)->first();
                        if(empty($deviceuser)){
                            $deviceuser = new Deviceuser();
                    		$deviceuser->employeeid = $empid;
                    		$deviceuser->contractterm = date('Y-m-d', strtotime($contract_term));
                    		$deviceuser->enroll = 1;
                            $deviceuser->$deviceset = 1;
                    		$deviceuser->devicestatus = 1;
                    		$deviceuser->save();

                        }else{

                            $deviceuser->$deviceset = 1;
                            $deviceuser->save();
                        }


    		            return 201;

                    }else{

                        return 202;

                    }

                } catch(\Exception $e){

                    return 203;

                }


    	}else{
    		return 204;
    	}
    
    }

    public function checksetuser(Request $request){

        $empid = $request->empid;

        $deviceinfo = DeviceInfo::where('main', 1)->first();

        if(!empty($deviceinfo)){

            $deviceid = $deviceinfo->deviceinfoid;

            $deviceuser = Deviceuser::where('employeeid', $empid)->first();
            if(!empty($deviceuser)){
                $setuser = 'device'.$deviceid.'setuser';
                $setfinger = 'device'.$deviceid.'finger';

                $isset = $deviceuser->$setuser;
                $issetfinger = $deviceuser->$setfinger;

                if($isset == 1 && $issetfinger == 0){
                    return 201;
                }else if($isset == 1 && $issetfinger == 1){
                    return 203;
                }else{
                    return 202;
                }
            }

        }


    }

    public function getuserdevicelist(Request $request){

        $empid = $request->empid;

        $device = DeviceInfo::where('status', 1)->get()->all();

        $deviceuser = Deviceuser::where('employeeid', $empid)->first();

        $device1setuser = 0;
        $device2setuser = 0;
        $device3setuser = 0;
        $device4setuser = 0;

        if(!empty($deviceuser)){
            $device1setuser = $deviceuser->device1setuser;
            $device2setuser = $deviceuser->device2setuser;
            $device3setuser = $deviceuser->device3setuser;
            $device4setuser = $deviceuser->device4setuser;
        }
      

        $html = '';

        if(!empty($device)){
            foreach ($device as $key => $device_data) {
                $deviceid = $device_data->deviceinfoid;
                $id = ++$key;
                switch ($deviceid) {
                    case '1':
                    if($device_data->main == 1){
                        $html .='<tr style="background:#ec9f9f;">';
                    }else{
                        $html .='<tr>';
                    }
                    $html .= '<td>'.$device_data->devicename.'</td>';
                    $html .= '<td>'.$device_data->location.'</td>';
                    if($device1setuser == 1){

                        $html .='<td ><a id="enroll'.$deviceid.'" class="btn btn-success" disabled>Enrolled</a></td>'; 
                    }else{
                        $html .='<td ><a id="enroll'.$deviceid.'" class="btn btn-success" onclick="setuserintodevice('.$deviceid.')">Enroll</a></td>'; 
                    }
                    $html .='</tr>';
                    break;

                    case '2':
                    $html .='<tr>';
                    $html .= '<td>'.$device_data->devicename.'</td>';
                    $html .= '<td>'.$device_data->location.'</td>';
                    if($device2setuser == 1){

                        $html .='<td ><a id="enroll'.$deviceid.'" class="btn btn-success" disabled>Enrolled</a></td>'; 
                    }else{
                        $html .='<td ><a id="enroll'.$deviceid.'" class="btn btn-success" onclick="setuserintodevice('.$deviceid.')">Enroll</a></td>'; 
                    }
                    $html .='</tr>';
                    break;

                    case '3':
                    $html .='<tr>';
                    $html .= '<td>'.$device_data->devicename.'</td>';
                    $html .= '<td>'.$device_data->location.'</td>';
                    if($device3setuser == 1){

                        $html .='<td ><a id="enroll'.$deviceid.'" class="btn btn-success" disabled>Enrolled</a></td>'; 
                    }else{
                        $html .='<td ><a id="enroll'.$deviceid.'" class="btn btn-success" onclick="setuserintodevice('.$deviceid.')">Enroll</a></td>'; 
                    }
                    $html .='</tr>';
                    break;

                    case '4':
                    $html .='<tr>';
                    $html .= '<td>'.$device_data->devicename.'</td>';
                    $html .= '<td>'.$device_data->location.'</td>';
                    if($device4setuser == 1){

                        $html .='<td><a id="enroll'.$deviceid.'" class="btn btn-success" disabled>Enrolled</a></td>'; 
                    }else{
                        $html .='<td><a id="enroll'.$deviceid.'" class="btn btn-success" onclick="setuserintodevice('.$deviceid.')">Enroll</a></td>'; 
                    }
                    $html .='</tr>';
                    break;
                }
            }
        }else{
            $html .= 'Device not Active or not found';
        }

        return $html;

    }

    public function enrollfingertemplate(Request $request){

    	$empid = $request->empid;

    	$deviceinfo = DeviceInfo::where('status', 1)->where('main', 1)->first();
    
    	if(!empty($deviceinfo)){

            $deviceid = $deviceinfo->deviceinfoid;
    		$deviceip = $deviceinfo->ipaddress;
    		$username = $deviceinfo->username;
    		$password = $deviceinfo->password;
    		$port = $deviceinfo->portno;

    		$fingerupload_api = 'http://'.$deviceip.':'.$port.'/device.cgi/enrolluser?action=enroll&user-id='.$empid.'&type=2&finger-count=1';
    		
           $apicronjob = new ApiCronJob();
           $apicronjob->apiuserid = $empid;
           $apicronjob->apitype = 'Upload Fingertemplate';
           $apicronjob->api = $fingerupload_api;
           $apicronjob->response_code = 0;
           $apicronjob->status = 1;
           $apicronjob->save();

           $ch = curl_init();
           curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
           curl_setopt($ch, CURLOPT_URL,$fingerupload_api);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           $response = curl_exec($ch);
           $response = explode('=', $response);


           if($response[1] == 0){
	    	  return 201;
           }else{
              return 202;
           }
    		

    	}else{

            return 203;

    	}

    }



    public function getfingertemplate(Request $request){

    	$empid = $request->empid;
    
    	$deviceinfo = DeviceInfo::where('status', 1)->where('main', 1)->first();
    	if(!empty($deviceinfo)){

    		$deviceip = $deviceinfo->ipaddress;
            $deviceid = $deviceinfo->deviceinfoid;
    		$username = $deviceinfo->username;
    		$password = $deviceinfo->password;
    		$portno = $deviceinfo->portno;
            $url = $deviceip.':'.$portno;

    		$getfinger_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/credential?action=get&type=1&user-id='.$empid.'&finger-index=1';

            try{
          
	    		$ch = curl_init($url);
	    		curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
	    		curl_setopt($ch, CURLOPT_URL,$getfinger_api);
	    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    		$response = curl_exec($ch);
	    

                if(strpos($response, 'Response-Code') === false){

                    $deviceuser = Deviceuser::where('employeeid', $empid)->first();
                    $deviceid_tbl = 'device'.$deviceid.'finger';
                    $devicestatus = 'device'.$deviceid.'status';
                    $deviceuser->fingertemplate = $response;
                    $deviceuser->istemplateupload = 1;
                    $deviceuser->$deviceid_tbl = 1;
                    $deviceuser->$devicestatus = 1;
                    $deviceuser->save();

	    		    return 201;
                }else{
                    return 202;
                }


            }catch(\Exception $e){

                return 203;
            }


    	}else{

            return 204;

    	}



    }

    public function checkfingerprint(Request $request){
        $empid = $request->empid;


        $deviceinfo = DeviceInfo::where('status', 1)->where('main', 1)->first();
        if(!empty($deviceinfo)){

            $deviceip = $deviceinfo->ipaddress;
            $username = $deviceinfo->username;
            $password = $deviceinfo->password;
            $portno = $deviceinfo->portno;
            $url = $deviceip.':'.$portno;

            $getfinger_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/credential?action=get&type=1&user-id='.$empid.'&finger-index=1';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($ch, CURLOPT_URL,$getfinger_api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $response = explode('=', $response);
            
            $deviceuser = Deviceuser::where('employeeid', $empid)->first();
            $deviceuser->fingertemplate = $response[0];
            $deviceuser->istemplateupload = 1;
            $deviceuser->save();

        }

    }


    public function fetchdeviceenroll(Request $request){

        $empid = $request->empid;

        $device = DeviceInfo::where('status', 1)->get()->all();
        $maindevice = DeviceInfo::where('main', 1)->first();

        $main = 'device'.$maindevice->deviceinfoid.'finger';

        $deviceuser = Deviceuser::where('employeeid', $empid)->first();
        $mainfinger = 0;
        if(!empty($deviceuser)){

            $device1setuser = $deviceuser->device1setuser;
            $device2setuser = $deviceuser->device2setuser;
            $device3setuser = $deviceuser->device3setuser;
            $device4setuser = $deviceuser->device4setuser;

            $device1finger = $deviceuser->device1finger;
            $device2finger = $deviceuser->device2finger;
            $device3finger = $deviceuser->device3finger;
            $device4finger = $deviceuser->device4finger;

            $device1status = $deviceuser->device1status;
            $device2status = $deviceuser->device2status;
            $device3status = $deviceuser->device3status;
            $device4status = $deviceuser->device4status;

            $mainfinger = $deviceuser->$main;

            if($mainfinger != 1){
                return 211;
            }else{

                $html = '';

                foreach ($device as $key => $device_data) {
                    $deviceid = $device_data->deviceinfoid;
                    $id = ++$key;
                    switch ($deviceid) {
                        case '1':
                            $html .='<tr>';
                            $html .= '<td>'.$device_data->devicename.'</td>';
                            if($device1finger == 1){

                                $html .='<td ><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" disabled>Uploaded</a></td>'; 
                            }else{
                                if($device1setuser == 1){

                                    $html .='<td ><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" onclick="uploadfingertemplate('.$deviceid.')">Upload</a></td>'; 
                                }else{
                                    $html .='<td ><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" onclick="uploadfingertemplate('.$deviceid.')" disabled title="Please upload user">Upload</a></td>';
                                }
                            }

                            if($device1setuser == 1){
                                if($device1status == 1){
                                    $html .='<td ><a id="deactive'.$deviceid.'" class="btn btn-danger" onclick="deactive('.$deviceid.')">Deactive</a></td><td><a id="active'.$deviceid.'" class="btn btn-success" style="display:none;" onclick="active('.$deviceid.')">Active</a></td>';
                                }else{
                                    $html .='<td><a id="active'.$deviceid.'" class="btn btn-success" onclick="active('.$deviceid.')">Active</a></td><td><a id="deactive'.$deviceid.'" class="btn btn-danger" style="display:none;" onclick="deactive('.$deviceid.')">Deactive</a></td>';
                                }
                            }

                            $html .= '</tr>';

                            break;

                        case '2':
                            $html .='<tr>';
                            $html .= '<td>'.$device_data->devicename.'</td>';
                            if($device2finger == 1){

                                $html .='<td><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" disabled>Uploaded</a></td>'; 
                            }else{
                                if($device2setuser == 1){
                                    $html .='<td><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" onclick="uploadfingertemplate('.$deviceid.')">Upload</a></td>';
                                }else{
                                    $html .='<td><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" onclick="uploadfingertemplate('.$deviceid.')" disabled title="Please upload user">Upload</a></td>';

                                } 
                            }

                            if($device2setuser == 1){
                                if($device2status == 1){
                                    $html .='<td><a id="deactive'.$deviceid.'" class="btn btn-danger" onclick="deactive('.$deviceid.')">Deactive</a></td><td><a id="active'.$deviceid.'" class="btn btn-success" style="display:none;" onclick="active('.$deviceid.')">Active</a></td>';
                                }else{
                                    $html .='<td><a id="active'.$deviceid.'" class="btn btn-success" onclick="active('.$deviceid.')">Active</a></td><td><a id="deactive'.$deviceid.'" class="btn btn-danger" style="display:none;" onclick="deactive('.$deviceid.')">Deactive</a></td>';
                                }
                            }

                            $html .= '</tr>';

                            break;

                        case '3':
                            $html .='<tr>';
                            $html .= '<td>'.$device_data->devicename.'</td>';
                            if($device3finger == 1){

                                $html .='<td><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" disabled>Uploaded</a></td>'; 
                            }else{
                                if($device3setuser == 1){

                                    $html .='<td><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" onclick="uploadfingertemplate('.$deviceid.')">Upload</a></td>';
                                }else{
                                    $html .='<td><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" onclick="uploadfingertemplate('.$deviceid.')" disabled title="Please upload user">Upload</a></td>';

                                } 
                            }
                            if($device3setuser == 1){
                                if($device3status == 1){
                                    $html .='<td><a id="deactive'.$deviceid.'" class="btn btn-danger" onclick="deactive('.$deviceid.')">Deactive</a></td><td><a id="active'.$deviceid.'" class="btn btn-success" style="display:none;" onclick="active('.$deviceid.')">Active</a></td>';
                                }else{
                                    $html .='<td><a id="active'.$deviceid.'" class="btn btn-success" onclick="active('.$deviceid.')">Active</a></td><td><a id="deactive'.$deviceid.'" class="btn btn-danger" style="display:none;" onclick="deactive('.$deviceid.')">Deactive</a></td>';
                                }
                            }
                            $html .= '</tr>';

                            break;

                        case '4':
                            $html .='<tr>';
                            $html .= '<td>'.$device_data->devicename.'</td>';
                            if($device4finger == 1){

                                $html .='<td><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" disabled>Uploaded</a></td>'; 
                            }else{
                                if($device2setuser == 1){
                                    $html .='<td><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" onclick="uploadfingertemplate('.$deviceid.')">Upload</a></td>'; 
                                }else{
                                    $html .='<td><a id="enrollintodevice'.$deviceid.'" class="btn btn-success" onclick="uploadfingertemplate('.$deviceid.')" disabled title="Please upload user">Upload</a></td>'; 

                                }
                            }
                            if($device4setuser == 1){
                                if($device4status == 1){
                                    $html .='<td><a id="deactive'.$deviceid.'" class="btn btn-danger" onclick="deactive('.$deviceid.')">Deactive</a></td><td><a id="active'.$deviceid.'" class="btn btn-success" style="display:none;" onclick="active('.$deviceid.')">Active</a></td>';
                                }else{
                                    $html .='<td><a id="active'.$deviceid.'" class="btn btn-success" onclick="active('.$deviceid.')">Active</a></td><td><a id="deactive'.$deviceid.'" class="btn btn-danger" style="display:none;" onclick="deactive('.$deviceid.')">Deactive</a></td>';
                                }
                            }
                            $html .= '</tr>';

                            break;
                    }
                }

                return $html;

            }
        }else{
            return 211;
        }

    }

    public function uploadfingerprint(Request $request){
        
        $empid = $request->empid;
        $deviceid = $request->deviceid;

        $device_data = DeviceInfo::where('deviceinfoid', $deviceid)->first();

        if(!empty($device_data)){

            $deviceip = $device_data->ipaddress;
            $username = $device_data->username;
            $password = $device_data->password;
            $portno = $device_data->portno;
            $url = $deviceip.':'.$portno;
            $deviceid = $device_data->deviceinfoid;

            $deviceuser = Deviceuser::where('employeeid', $empid)->first();
            $fingertemplate = $deviceuser->fingertemplate;

            $upload_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/credential?action=set&type=1&user-id='.$empid.''; 

            $header = array('Content-Type: binary/octet-stream');

            $apicronjob = new ApiCronJob();
            $apicronjob->apiuserid = $empid;
            $apicronjob->apitype = 'Upload Fingertemplate';
            $apicronjob->api = $upload_api;
            $apicronjob->response_code = null;
            $apicronjob->status = 0;
            $apicronjob->save();

            $lastapicronjob = $apicronjob->apicronjobid;

            try{

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                curl_setopt($ch, CURLOPT_URL,$upload_api);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fingertemplate);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $response = curl_exec($ch);
                $response = explode('=', $response);


                $apicronjob = ApiCronJob::findOrfail($lastapicronjob);  
                $apicronjob->response_code = $response[1];
                $apicronjob->status = 1;
                $apicronjob->save();

                $finger = 'device'.$deviceid.'finger';
                $activestatus = 'device'.$deviceid.'status';

                if($response[1] == 0){

                    $deviceuser = Deviceuser::where('employeeid', $empid)->first();
                    $deviceuser->$finger = 1;
                    $deviceuser->$activestatus = 1;
                    $deviceuser->save();

                    return 201;

                }else{

                    return 202;
                }


            } catch(\Exception $e){

                $apicronjob = ApiCronJob::findOrfail($lastapicronjob);  
                $apicronjob->response_code = 101;
                $apicronjob->status = 1;
                $apicronjob->save();

                return 203;
            }

        }else{
            return 204;
        }


    }

   /* public function setfingerprinteachdevice(Request $request){

        $empid = $request->empid;
        $deviceid = $request->deviceid;
        $deviceid_update = 'device'.$deviceid;
        $devicestatus = 'device'.$deviceid.'status';

        $device_data = DeviceInfo::where('deviceinfoid', $deviceid)->first();
        if(!empty($device_data)){


            $deviceip = $device_data->ipaddress;
            $username = $device_data->username;
            $password = $device_data->password;
            $portno = $device_data->portno;
            $url = $deviceip.':'.$portno;

            $deviceuser = Deviceuser::where('employeeid', $empid)->first();
            $fingertemplate = $deviceuser->fingertemplate; 

            $upload_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/credential?action=set&type=1&user-id='.$empid.'';
            $header = array('Content-Type: binary/octet-stream');

            $apicronjob = new ApiCronJob();
            $apicronjob->apiuserid = $empid;
            $apicronjob->apitype = 'Upload Fingertemplate';
            $apicronjob->api = $upload_api;
            $apicronjob->response_code = null;
            $apicronjob->status = 1;
            $apicronjob->save();

            $lastapicronjob = $apicronjob->apicronjobid;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($ch, CURLOPT_URL,$upload_api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fingertemplate);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $response = curl_exec($ch);
            $response = explode('=', $response);

            $apicronjob = ApiCronJob::findOrfail($lastapicronjob);  
            $apicronjob->response_code = $response[0];
            $apicronjob->save();

            if($response[1] == 0){
                $deviceuser = Deviceuser::where('employeeid', $empid)->first();
                $deviceuser->$deviceid_update = 1;
                $deviceuser->$devicestatus = 1;
                $deviceuser->save();

                return 201;

            }else{

                return 202;
            }
        }else{

            return 203;
        }

    }*/


    public function deactiveuser(Request $request){

        $empid = $request->empid;
        $deviceid = $request->deviceid;

        $deviceid_update = 'device'.$deviceid;
        $devicestatus = 'device'.$deviceid.'status';

        $device_data = DeviceInfo::where('deviceinfoid', $deviceid)->first();
        if(!empty($device_data)){

            $deviceip = $device_data->ipaddress;
            $username = $device_data->username;
            $password = $device_data->password;
            $portno = $device_data->portno;
            $url = $deviceip.':'.$portno;

            $deviceuser = Deviceuser::where('employeeid', $empid)->first();

            $deactive_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/users?action=set&user-id='.$empid.'&user-active=0';

            $apicronjob = new ApiCronJob();
            $apicronjob->apiuserid = $empid;
            $apicronjob->apitype = 'Deactive user';
            $apicronjob->api = $deactive_api;
            $apicronjob->response_code = null;
            $apicronjob->status = 0;
            $apicronjob->save();

            $lastapicronjob = $apicronjob->apicronjobid;

            try{

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                curl_setopt($ch, CURLOPT_URL,$deactive_api);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                $response = explode('=', $response);

                $apicronjob = ApiCronJob::findOrfail($lastapicronjob);  
                $apicronjob->response_code = $response[1];
                $apicronjob->status = 1;
                $apicronjob->save();

                if($response[1] == 0){
                    $deviceuser = Deviceuser::where('employeeid', $empid)->first();
                    $deviceuser->$devicestatus = 0;
                    $deviceuser->save();

                    return 201;

                }else{

                    return 202;
                }

            } catch(\Exception $e){

                return 203;
            }



        }else{
            return 204;
        }


    }

    public function activeuser(Request $request){

        $empid = $request->empid;
        $deviceid = $request->deviceid;

        $deviceid_update = 'device'.$deviceid;
        $devicestatus = 'device'.$deviceid.'status';

        $device_data = DeviceInfo::where('deviceinfoid', $deviceid)->first();
        if(!empty($device_data)){

            $deviceip = $device_data->ipaddress;
            $username = $device_data->username;
            $password = $device_data->password;
            $portno = $device_data->portno;
            $url = $deviceip.':'.$portno;

            $deviceuser = Deviceuser::where('employeeid', $empid)->first();

            $active_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/users?action=set&user-id='.$empid.'&user-active=1';

            $apicronjob = new ApiCronJob();
            $apicronjob->apiuserid = $empid;
            $apicronjob->apitype = 'Active user';
            $apicronjob->api = $active_api;
            $apicronjob->response_code = null;
            $apicronjob->status = 0;
            $apicronjob->save();

            $lastapicronjob = $apicronjob->apicronjobid;

            try{

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                curl_setopt($ch, CURLOPT_URL,$active_api);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                $response = explode('=', $response);

                $apicronjob = ApiCronJob::findOrfail($lastapicronjob);  
                $apicronjob->response_code = $response[1];
                $apicronjob->status = 1;
                $apicronjob->save();

                if($response[1] == 0){
                    $deviceuser = Deviceuser::where('employeeid', $empid)->first();
                    $deviceuser->$devicestatus = 1;
                    $deviceuser->save();

                    return 201;

                }else{

                    return 202;
                }

            } catch(\Exception $e) {

                return 203;
            }

        }else{
            return 204;
        }


    }

    public function getcontractdate(Request $request){

        $empid = $request->empid;

        $deviceuser = Deviceuser::where('employeeid', $empid)->first();
        if(!empty($deviceuser)){
            $contractterm = $deviceuser->contractterm;
            $isenroll= $deviceuser->enroll;
            if($isenroll == 1){
                return $contractterm;
            }else{
                return 203;
            }
        }else{
            return 203;

        }

    }

    public function setcontractdate(Request $request){

        $empid = $request->empid;
        $contractdate = date('Y-m-d', strtotime($request->contractdate));

        $date_explode = explode('-' , $contractdate);
        $day = $date_explode[2];
        $month = $date_explode[1];
        $year = $date_explode[0];

        $device_data = DeviceInfo::where('status', 1)->get()->all();
        if(!empty($device_data)){

            foreach($device_data as $device){

                $deviceip = $device->ipaddress;
                $username = $device->username;
                $password = $device->password;
                $portno = $device->portno;
                $url = $deviceip.':'.$portno;

                $deviceuser = Deviceuser::where('employeeid', $empid)->first();

                $upload_api = 'http://'.$deviceip.':'.$portno.'/device.cgi/users?action=set&user-id='.$empid.'&validity-date-dd='.$day.'&validity-date-mm='.$month.'&validity-date-yyyy='.$year.'';

                $apicronjob = new ApiCronJob();
                $apicronjob->apiuserid = $empid;
                $apicronjob->apitype = 'Employee extend date';
                $apicronjob->api = $upload_api;
                $apicronjob->response_code = null;
                $apicronjob->status = 0;
                $apicronjob->save();

            }

            return 201;


        }else{
            return 202;
        }
    }

    public function checkdevicecount(Request $request){

        $empid = $request->empid;
        $deviceuser = Deviceuser::where('employeeid', $empid)->first();
        $count = 0;
        if(!empty($deviceuser)){

            $device1finger = $deviceuser->device1finger;
            /*$device2setuser = $deviceuser->device2setuser;
            $device3setuser = $deviceuser->device3setuser;
            $device4setuser = $deviceuser->device4setuser;

            $count = $device1setuser + $device2setuser + $device3setuser + $device4setuser;*/

            return $device1finger;

        }

    }

    public function emplog(Request $request){

        $employee  = Employee::all();

        if($request->isMethod('post')){
            

            $employeeid = $request->employeeid;
            $fromdate = !empty($request->fromdate) ? date('Y-m-d', strtotime($request->fromdate)) : '';
            $todate = !empty($request->todate) ? date('Y-m-d', strtotime($request->todate)) : date('Y-m-d');
            $query=[];
            $query['employeeid']=$employeeid ;
            $query['fromdate']=$fromdate ;
            $query['todate']=$todate;

            $fetchlog =  DeviceFetchlogs::with('emp');
            
            if($employeeid){

                $fetchlog->where('detail1','=',$employeeid);

            }

            if(!$fromdate){

                $fetchlog->whereBetween('date', [$fromdate, $todate]);

            }

            if(!$todate){

                $fetchlog->whereBetween('date', [$fromdate, $todate]);

            }

            
            $fetchlog = $fetchlog->where('eventid', 101)->orderBy('deviceeventid', 'desc')->paginate(10)->appends('query');
          
            return view('device.emplogs')->with(compact('fetchlog', 'employee', 'query'));

        }




        //$fetchlog = DeviceFetchlogs::with('emp')->where('eventid', 101)->orderBy('deviceeventid', 'desc')->paginate(10);

        return view('device.emplogs')->with(compact('employee'));


    }

}
