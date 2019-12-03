<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use Session;
use Helper;
use DB;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function department(Request $request){

    	if($request->isMethod('POST')){

    		$request->validate([

    			'departmentname' => 'required|max:255|unique:department,departmentname'

    		]);

    		$department = new Department();
    		$department->departmentname = $request->departmentname;
    		$department->save();

    		Session::flash('message', 'Department is added successfully');
    		Session::flash('alert-type', 'success');

    		return redirect()->route('viewdepartment');



    	}

    	
    	return view('department.adddepartment');

    }

    public function viewdepartment(){

    	$department = Department::paginate(10);
    	return view('department.viewdepartment')->with(compact('department'));
    }

    public function updatedepartment($id, Request $request){

    	$department = Department::findOrfail($id);

    	if($request->isMethod('POST')){

    		$request->validate([

    			'departmentname' => ['required','max:255', Rule::unique('department')->ignore($id, 'departmentid') ]

    		]);

    		$department->departmentname = $request->departmentname;
    		$department->save();

    		Session::flash('message', 'Department is updated successfully');
    		Session::flash('alert-type', 'success');

    		return redirect()->route('viewdepartment');



    	}

    	
    	return view('department.editdepartment')->with(compact('department'));

    }

    public function activeedepartment($id){

    	$Department = Department::findOrfail($id);

        DB::beginTransaction();
        try {

        	$Department->status = 1;
        	$Department->save();

            DB::commit();
            $success = true;

        	Session::flash('message', 'Department is activeted successfully!');
        	Session::flash('alert-type', 'success');

        	return redirect()->route('viewdepartment');

        } catch(\Exception $e) {

            Helper::errormail('Department', 'Active Department', 'High');

            DB::rollback();
            $success = false;
        }

        if($success == false){
            return redirect('dashboard');
        }

    }

     public function deactivedepartment($id){

    	$Department = Department::findOrfail($id);

        DB::beginTransaction();
        try {

        	$Department->status = 0;
        	$Department->save();

            DB::commit();
            $success = true;

        	Session::flash('message', 'Department is deactivated successfully!');
        	Session::flash('alert-type', 'warning');

        	return redirect()->route('viewdepartment');

        } catch(\Exception $e) {

            Helper::errormail('Department', 'Deactive Department', 'High');

            DB::rollback();
            $success = false;
        }

        if($success == false){
            return redirect('dashboard');
        }
    }
}
