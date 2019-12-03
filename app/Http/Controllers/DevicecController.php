<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeviceInfo;
use Illuminate\Validation\Rule;
use Session;
use Helper;
use DB;

class DevicecController extends Controller
{
    public function adddevice(Request $request){

    	if($request->ismethod('post')){

    		$request->validate([

    			'deviceip_1' => 'required|digits_between:1,3|numeric',
    			'deviceip_2' => 'required|digits_between:1,3|numeric',
    			'deviceip_3' => 'required|digits_between:1,3|numeric',
    			'deviceip_4' => 'required|digits_between:1,3|numeric',
    			'device_port' => 'required|digits_between:1,4|numeric',
    			'devicename' => 'required|max:255|unique:deviceinfo,devicename',
    			'username' => 'required|max:255',
    			'password' => 'required|max:255',

    		]);

    		$ipaddress = $request->deviceip_1.'.'.$request->deviceip_2.'.'.$request->deviceip_3.'.'.$request->deviceip_4;

    		$ifexist = DeviceInfo::where('ipaddress', $ipaddress)->first();

    		if(!empty($ifexist)){

    			Session::flash('message', 'Device is already exist!');
    			Session::flash('alert-type', 'error');

    			return redirect()->back()->withInput();
    		}

            DB::beginTransaction();
            try {


        		$device = new DeviceInfo();
        		$device->devicename = $request->devicename;
        		$device->portno = $request->device_port;
        		$device->devicetype = $request->dtype;
        		$device->location = $request->location;
        		$device->username = $request->username;
        		$device->password = $request->password;
        		$device->ipaddress = $ipaddress;
        		$device->save();

                DB::commit();
                $success = true;


        		Session::flash('message', 'Device is added successfully!');
        		Session::flash('alert-type', 'success');

        		return redirect()->route('viewdevice');

            } catch(\Exception $e) {

                Helper::errormail('Device', 'Add Device', 'High');

                DB::rollback();
                $success = false;
            }

            if($success == false){
                return redirect('dashboard');
            }




    	}

    	return view('device.adddevice');

    }

    public function viewdevice(){

    	$devices = DeviceInfo::all();

    	return view('device.viewdevice')->with(compact('devices'));

    }

    public function updatedevice($id, Request $request){

    	$device = DeviceInfo::findOrfail($id);

    	if($request->isMethod('post')){


    		$request->validate([

    			'deviceip_1' => 'required|digits_between:1,3|numeric',
    			'deviceip_2' => 'required|digits_between:1,3|numeric',
    			'deviceip_3' => 'required|digits_between:1,3|numeric',
    			'deviceip_4' => 'required|digits_between:1,3|numeric',
    			'device_port' => 'required|digits_between:1,4|numeric',
    			'devicename' => ['required','max:255', Rule::unique('deviceinfo')->ignore($id, 'deviceinfoid')],
    			'username' => 'required|max:255',
    			'password' => 'required|max:255',

    		]);

    		$ipaddress = $request->deviceip_1.'.'.$request->deviceip_2.'.'.$request->deviceip_3.'.'.$request->deviceip_4;
    		
    		$ifexist = DeviceInfo::where('ipaddress', $ipaddress)->where('deviceinfoid', '!=', $id)->first();

    		if(!empty($ifexist)){

    			Session::flash('message', 'Device is already exist!');
    			Session::flash('alert-type', 'error');

    			return redirect()->back()->withInput();
    		}

            DB::beginTransaction();
            try {


        		$device->devicename = $request->devicename;
        		$device->portno = $request->device_port;
        		$device->devicetype = $request->dtype;
        		$device->location = $request->location;
        		$device->username = $request->username;
        		$device->password = $request->password;
        		$device->ipaddress = $ipaddress;
        		$device->save();

                DB::commit();
                $success = true;

        		Session::flash('message', 'Device is updated successfully!');
        		Session::flash('alert-type', 'success');

        		return redirect()->route('viewdevice');

            } catch(\Exception $e) {

                Helper::errormail('Employee', 'Edit Device', 'High');

                DB::rollback();
                $success = false;
            }

            if($success == false){
                return redirect('dashboard');
            }





    	}

    	return view('device.editdevice')->with(compact('device'));

    }

    public function activedevice($id){

    	$device = DeviceInfo::findOrfail($id);

       DB::beginTransaction();
       try {

        	$device->status = 1;
        	$device->save();

            DB::commit();
            $success = true;

        	Session::flash('message', 'Device is activated');
        	Session::flash('alert-type', 'success');

    	    return redirect()->route('viewdevice');

        } catch(\Exception $e) {

            Helper::errormail('Device', 'Active Device', 'High');

            DB::rollback();
            $success = false;
        }

        if($success == false){
            return redirect('dashboard');
        }
    } 

    public function deactivedevice($id){

    	$device = DeviceInfo::findOrfail($id);

        DB::beginTransaction();
        try {

        	$device->status = 0;
        	$device->save();

            DB::commit();
            $success = true;

        	Session::flash('message', 'Device is deactivated');
        	Session::flash('alert-type', 'error');

        	return redirect()->route('viewdevice');

        } catch(\Exception $e) {

            Helper::errormail('Device', 'Deactive Device', 'High');

            DB::rollback();
            $success = false;
        }

        if($success == false){
            return redirect('dashboard');
        }

    } 
}
