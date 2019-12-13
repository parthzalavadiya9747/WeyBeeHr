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
use App\EmployeeLog;
use Illuminate\Validation\Rule;

class EmployeePortal extends Controller
{
    public function empdashboard(){

    	$empid = session()->get('admin_id');

        $today = date('Y-m-d');

        $min = EmployeeLog::where('userid', $empid)->where('punchdate', $today)->min('checkin');
        $max = EmployeeLog::where('userid', $empid)->where('punchdate', $today)->max('checkout');

    	return view('empportal.dashboard')->with(compact('min', 'max'));

    }

    public function empprofile(){

    	$empid = session()->get('admin_id');

    	$employee = Employee::findOrFail($empid);
    	$department = Department::where('status', 1)->get()->all();
        $state = DB::table('states')->get()->all();
        $city_id = DB::table('cities')->where('id', $employee->city)->first();
        $cities = DB::table('cities')->get()->all();

    	return view('empportal.viewempprofile')->with(compact('employee', 'department', 'state', 'city_id', 'cities'));

    }

    public function emplogemp(){

        $employee = Employee::all();
        $empid = session()->get('admin_id');

        return view('empportal.viewemployeelog')->with(compact('employee', 'empid'));
    }

    public function searchemployeelogemp(Request $request){

        if ($request->ajax()) {

        $employeeid = $request->employeeid;
        $year = $request->year;
        $month = $request->month;

        if(!empty($employeeid) || !empty($year) || !empty($month)){

        if($request->month == 'Janaury'){
            $cal_month = 1;
        }else if($request->month == 'February'){
            $cal_month = 2;
        }else if($request->month == 'March'){
            $cal_month = 3;
        }else if($request->month == 'April'){
            $cal_month = 4;
        }else if($request->month == 'May'){
            $cal_month = 5;
        }else if($request->month == 'June'){
            $cal_month = 6;
        }else if($request->month == 'July'){
            $cal_month = 7;
        }else if($request->month == 'August'){
            $cal_month = 8;
        }else if($request->month == 'September'){
            $cal_month = 9;
        }else if($request->month == 'October'){
            $cal_month = 10;
        }else if($request->month == 'November'){
            $cal_month = 11;
        }else{
            $cal_month = 12;
        }

        $day_in_month = cal_days_in_month(CAL_GREGORIAN,$cal_month,$year);
        $fromdate = date('Y-m-d',strtotime("$year-$cal_month-01"));
        $todate = date('Y-m-d',strtotime("$year-$cal_month-$day_in_month"));
        
        $searchparameter = ['employeeid' => $employeeid, 'month' => $month, 'year' => $year];

        $employeelog = EmployeeLog::where('userid', $employeeid)->whereBetween('punchdate', [$fromdate, $todate])->orderBy('punchdate', 'desc')->get();

        return datatables()->of($employeelog)
        ->editColumn('punchdate', function($employeelog){
            return date('d-m-Y', strtotime($employeelog->punchdate));
        })
        ->editColumn('checkout', function($employeelog){
            if(!empty($employeelog->checkout)){
                return $employeelog->checkout;
            }else{
                if(session()->get('logged_role') == 'Admin'){

                    return "<a href=".route('addpunch', $employeelog->emplogid)." class='btn btn-danger'>Miss</a>";
                }else{
                    return "<a class='btn btn-danger' disabled title='Dare to edit this'>Miss</a>";
                }
            }

        })->escapeColumns([])
        ->make(true);

        //$employee = Employee::where('status', 1)->get()->all();


        //$employeelog->appends(array('employeeid' => $employeeid, 'year' => $year, 'month' => $month));

        
        //return view('hr.employeelog.viewemployeelog')->with(compact('employeeid', 'year', 'month', 'employeelog', 'employee', 'searchparameter'));

        }
    }







    }
}
