<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Deviceuser;
use App\Salary;
use App\Department;
use Hash;
use Session;
use Helper;
use DB;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function loginprocess(Request $request){

      $username = $request->username;
      $password = $request->password;
      
      $encpassword = Hash::make($password);

      $user = Employee::where('username', $username)->first();

      if(!empty($user)){

       if($user->status != 1){

        return back()->with('message', 'Username is not active');

        }else{


        $user_email = $user->email;
        $user_username = $user->username;
        $role = $user->role;
        $firstname = $user->first_name;
        $lastname = $user->last_name;
        $mobile = $user->mobile;
        $user_password = $user->encpassword;
        $employeeid = $user->employeeid;
        $photo = $user->photo;

        
       
        if($username == $user_username && Hash::check($password, $user_password)){

         session()->put('logged_email', $user_email);
         session()->put('user_username', $user_username);
         session()->put('logged_role', $role);
         session()->put('logged_firstname', $firstname);
         session()->put('logged_lastname', $lastname);
         session()->put('logged_mobile', $mobile);
         session()->put('admin_id', $employeeid);
         session()->put('photo', $photo);

         if($role == 'Admin'){
            return redirect()->to('dashboard');
         }else{
            return redirect()->to('empdashboard');
         }

        }else{

             return back()->with('message', 'Email or password is invalid');
        }


        }
        }else{
           return back()->with('message', 'Username not found');
        }
    }

    public function logout(){

      Session::flush();

      return redirect()->to('/');

   }

    public function dashboard(){

        $employee = Employee::all();
        $deviceuser = Deviceuser::all();
        $salary = Salary::where('status', 'Locked')->where('ispaid', 0)->get()->all();
        $paid_salary = Salary::where('status', 'Locked')->where('ispaid', 1)->get()->all();
        $emp_count = count($employee);
        $deviceuser_count = count($deviceuser);
        $salary_count = count($salary);
        $paidsalary_count = count($paid_salary);



        return view('dashboard')->with(compact('emp_count', 'deviceuser_count', 'salary_count', 'paidsalary_count'));
    }

	public function checkuserexist(Request $request){

		$username = $request->username;

		$employeedata = Employee::where('username', $username)->first(); 
		if(!empty($employeedata)){
			return 201;
		}else{
			return 202;
		}
	}

	public function checkmobilenoexist(Request $request){

		$mobileno = $request->mobileno;

		$employeedata = Employee::where('mobileno', $mobileno)->first(); 
		if(!empty($employeedata)){
			return 201;
		}else{
			return 202;
		}
	}


    public function employee(Request $request){

    	if($request->isMethod('post')){

    		$request->validate([

    			'first_name' => 'required|max:255',
    			'last_name' => 'required|max:255',
    			'username' => 'required|max:255|unique:employee,username',
    			'role' => 'required',
    			'email' => 'required|max:255|email',
    			'addressline1' => 'required|max:255',
                'addressline2' => 'nullable|max:255',
    			'department' => 'required|max:255',
    			'salary' => 'required|numeric|digits_between:1,8',
    			'workinghour' => 'required|numeric|digits_between:1,2',
    			'department' => 'nullable|max:255',
    			'dob' => 'nullable|date',
    			'mobileno' => 'required|digits_between:1,10|unique:employee,mobileno',
    			'password' => 'required|min:6',
    			'accountNo' => 'required|numeric|digits_between:1,16',
    			'accountName' => 'required|max:255',
    			'IFSCcode' => 'required|max:11',
    			'BankName' => 'required|max:255',
    			'BranchName' => 'required|max:255',
                'city' => 'required',
                'state' => 'required',
                

    		]);


            DB::beginTransaction();
            try {
        		$encpassword = Hash::make($request->password);

        		if($request->has('image')){

        			$img_name = $request->image->getClientOriginalName();
        			$imageName = $img_name;

            		request()->image->move(public_path('userupload'), $imageName);


        		}else{
                    $imageName = '';
                }
        		
        		if(!empty($request->dob)){
        			$dob = date('Y-m-d', strtotime($request->dob));
        		}else{
        			$dob = null;
        		}

        		$employee = new Employee();
        		$employee->first_name = $request->first_name;
        		$employee->last_name = $request->last_name;
        		$employee->username = $request->username;
        		$employee->role = $request->role;
        		$employee->email = $request->email;
        		$employee->addressline1 = $request->addressline1;
                $employee->addressline2 = $request->addressline2;
        		$employee->department = $request->department;
        		$employee->salary = $request->salary;
        		$employee->workinghour = $request->workinghour;
        		$employee->department = $request->department;
        		$employee->dob = $dob;
        		$employee->city = $request->city;
                $employee->state = $request->state;
        		$employee->workinghourfrom1 = $request->working_hour_from_1;
        		$employee->workinghourto1 = $request->working_hour_to_1;
        		$employee->mobileno = $request->mobileno;
        		$employee->password = $request->password;
        		$employee->encpassword = $encpassword;
        		$employee->gender = $request->gender;
        		$employee->accountname = $request->accountName;
        		$employee->accountNo = $request->accountNo;
        		$employee->ifsccode = $request->IFSCcode;
        		$employee->bankname = $request->BankName;
        		$employee->branchname = $request->BranchName;
        		$employee->photo = $imageName;
        		$employee->save();

        		$notification = array(
        			'message' => 'Employee is added successfully!',
        			'alert-type' => 'success'
        		);

                DB::commit();
                $success = true;

        		Session::flash('message', 'Employee is added successfully!');
        		Session::flash('alert-type', 'success');
        		

        		return redirect()->route('viewemployee');

            } catch(\Exception $e){

                Helper::errormail('Employee', 'Add Employee', 'High');
                DB::rollback();
                $success = false;
            }

            if($success == false){
                return redirect('dashboard');
            }

    	}

        $department = Department::where('status', 1)->get()->all();
        $state = DB::table('states')->get()->all();

    	return view('employee.addemployee')->with(compact('department', 'state'));

    }

    public function viewemployee(){

    	$employee = Employee::leftjoin('department', 'employee.department', 'department.departmentid')->leftjoin('states', 'employee.state', 'states.stateid')->leftjoin('cities', 'employee.city', 'cities.id')->select('department.*', 'employee.*', 'states.*', 'cities.*', 'employee.status AS empstatus')->paginate(10);

    	return view('employee.viewemployee')->with(compact('employee'));

    }

    public function updateemployee($id, Request $request){

    	$employee = Employee::findOrfail($id);
        $state = DB::table('states')->get()->all();

        $stateid = $employee->state;

        $cities = DB::table('cities')->where('state_id', $stateid)->get()->all();

    	if($request->isMethod('post')){

    		$request->validate([

    			'first_name' => 'required|max:255',
    			'last_name' => 'required|max:255',
    			'username' => ['required', 'max:255', Rule::unique('employee')->ignore($id, 'employeeid') ],
    			'role' => 'required',
    			'email' => 'required|max:255|email',
    			'addressline1' => 'required|max:255',
                'addressline2' => 'nullable|max:255',
    			'department' => 'nullable|max:255',
    			'salary' => 'required|numeric|digits_between:1,8',
    			'workinghour' => 'required|numeric|digits_between:1,2',
    			'department' => 'nullable|max:255',
    			'dob' => 'nullable|date',
    			'mobileno' => ['required','digits_between:1,10', Rule::unique('employee')->ignore($id, 'employeeid')],
    			'password' => 'nullable|min:6',
    			'accountNo' => 'required|numeric|digits_between:1,16',
    			'accountName' => 'required|max:255',
    			'IFSCcode' => 'required|max:11',
    			'BankName' => 'required|max:255',
    			'BranchName' => 'required|max:255',
    			'city' => 'required',
                'state' => 'required',

    		]);

            DB::beginTransaction();
            try {

        		if(!empty($request->password)){

        			$encpassword = Hash::make($request->password);
        		}

        		if($request->has('image')){

        			$img_name = $request->image->getClientOriginalName();
        			$imageName = $img_name;

            		request()->image->move(public_path('userupload'), $imageName);


        		}else{

        			$imageName = $employee->photo;

        		}

        		$employee->first_name = $request->first_name;
        		$employee->last_name = $request->last_name;
        		$employee->role = $request->role;
        		$employee->email = $request->email;
        		$employee->addressline1 = $request->addressline1;
                $employee->addressline2 = $request->addressline2;
        		$employee->department = $request->department;
        		$employee->salary = $request->salary;
        		$employee->workinghour = $request->workinghour;
        		$employee->department = $request->department;
        		$employee->dob = !empty($request->dob) ? date('Y-m-d', strtotime($request->dob)) : null;
        		$employee->city = $request->city;
                $employee->state = $request->state;
        		$employee->workinghourfrom1 = $request->working_hour_from_1;
        		$employee->workinghourto1 = $request->working_hour_to_1;
        		if(!empty($request->password)){
    	    		$employee->password = $request->password;
    	    		$employee->encpassword = $encpassword;
    	    	}
        		$employee->gender = $request->gender;
        		$employee->accountname = $request->accountName;
        		$employee->accountNo = $request->accountNo;
        		$employee->ifsccode = $request->IFSCcode;
        		$employee->bankname = $request->BankName;
        		$employee->branchname = $request->BranchName;
        		$employee->photo = $imageName;
        		$employee->save();

                DB::commit();
                $success = true;

        		Session::flash('message', 'Employee is updated successfully!');
        		Session::flash('alert-type', 'success');

        		return redirect()->route('viewemployee');

            } catch(\Exception $e) {

                Helper::errormail('Employee', 'Edit Employee', 'High');

                DB::rollback();
                $success = false;
            }

            if($success == false){
                return redirect('dashboard');
            }

    	}

        $department = Department::where('status', 1)->get()->all();
        $state = DB::table('states')->get()->all();
    	return view('employee.editemployee')->with(compact('employee', 'state', 'department', 'cities'));

    }

    public function activeemployee($id){

    	$employee = Employee::findOrfail($id);

        DB::beginTransaction();
        try {

        	$employee->status = 1;
        	$employee->save();

            DB::commit();
            $success = true;

        	Session::flash('message', 'Employee is activeted successfully!');
        	Session::flash('alert-type', 'success');

        	return redirect()->route('viewemployee');

        } catch(\Exception $e) {

            Helper::errormail('Employee', 'Active Employee', 'High');

            DB::rollback();
            $success = false;
        }

        if($success == false){
            return redirect('dashboard');
        }

    }

     public function deactiveemployee($id){

    	$employee = Employee::findOrfail($id);

        DB::beginTransaction();
        try {

        	$employee->status = 0;
        	$employee->save();

            DB::commit();
            $success = true;

        	Session::flash('message', 'Employee is deactivated successfully!');
        	Session::flash('alert-type', 'warning');

        	return redirect()->route('viewemployee');

        } catch(\Exception $e) {

            Helper::errormail('Employee', 'Deactive Employee', 'High');

            DB::rollback();
            $success = false;
        }

        if($success == false){
            return redirect('dashboard');
        }
    }


}
