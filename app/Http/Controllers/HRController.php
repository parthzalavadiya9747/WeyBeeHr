<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WorkingDays;
use App\Leave;
use App\EmployeeAccount;
use App\Employee;
use App\User_log;
use App\EmployeeLog;
use App\Salary;
use App\EmployeeLeave;
use App\MonthLeave;
use App\ExcelExport;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Helper;
use DB;
use Session;
use Datatables;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;

class HRController extends Controller
{
    
	/////////////////////////////////////////// Working Days Start ////////////////////////////////////////////////////////

	public function workingdays(Request $request){


		if($request->isMethod('post')){

			$request->validate([

				'year' => 'required',
				'month' => 'required',
				'workingdays' => 'required|integer',

			]);

		DB::beginTransaction();
		try {

			$year = $request->year;
			$month = $request->month;
			$workingdays = $request->workingdays;
			$nonworkingdats = !empty($request->nonworkingdate) ? $request->nonworkingdate : [];

			$nonworgdayscount = count($nonworkingdats);

			$month_exist = WorkingDays::where('year', $year)->where('month', $month)->get()->all();

			if(!empty($month_exist)){
				return redirect()->back()->with('error', 'Month already Exist')->withInput(Input::all());
			}

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

			$holiday_cal = $day_in_month - $nonworgdayscount; 

			$workingdays_obj = new WorkingDays();
			$workingdays_obj->year = $year;
			$workingdays_obj->month = $request->month;
			$workingdays_obj->holidays = $nonworgdayscount;
			$workingdays_obj->workingdays = $holiday_cal;
			$workingdays_obj->save();

			$working_days_id = $workingdays_obj->workingcalid;

			if($nonworgdayscount > 0){
				foreach($nonworkingdats as $nondate){
					if(!empty($nondate)){
						$nondateins = new MonthLeave();
						$nondateins->workingcalanderid = $working_days_id;
						$nondateins->nonworkingdate = date('Y-m-d', strtotime($nondate));
						$nondateins->action_by = session()->get('admin_id');
						$nondateins->save();
					}
				}
			}
			
			DB::commit();
			$success = true;

			Session::flash('message', 'Working days is added successfully');
    		Session::flash('alert-type', 'success');

			return redirect()->route('viewworkingdays');

		 } catch (\Exception $e) {
          Helper::errormail('HR', 'Add Workingdays', 'High');
          $success = false;
          DB::rollback();

        }
        
        if ($success == false) { 
          return redirect('dashboard');
        }


		}


		return view('hr.workingdays.addworkingdays');

	}

	public function viewworkingdays(){

		$working_days = WorkingDays::paginate(10);

		return view('hr.workingdays.viewworkingdays', compact('working_days'));



	}

	public function editworkingdays($id, Request $request){

		$working_days = WorkingDays::with('nonworkingdays')->where('workingcalid',$id)->first();

		if($request->isMethod('post')){


			$request->validate([

				'year' => 'required',
				'month' => 'required',
				'workingdays' => 'required|integer',

			]);

		DB::beginTransaction();
		try {
			$year = $request->year;
			$month = $request->month;
			$workingdays = $request->workingdays;
			$nonworkingdats = !empty($request->nonworkingdate) ? $request->nonworkingdate : [];
			
			$nonworgdayscount = count($nonworkingdats);

			$month_exist = WorkingDays::where('year', $year)->where('month', $month)->where('workingcalid' ,'!=', $id)->get()->all();

			if(!empty($month_exist)){
				return redirect()->back()->with('error', 'Month already Exist')->withInput(Input::all());
			}

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

			$workingdays = $day_in_month - $nonworgdayscount;

			$holiday_cal = $day_in_month - $workingdays; 

			$workingdays_obj = WorkingDays::findOrfail($id);
			$workingdays_obj->year = $year;
			$workingdays_obj->month = $request->month;
			$workingdays_obj->holidays = $nonworgdayscount;
			$workingdays_obj->workingdays = $workingdays;
			$workingdays_obj->save();

			$working_days_id = $workingdays_obj->workingcalid;

			$working_days = MonthLeave::where('workingcalanderid', $working_days_id)->get()->all();
			if(!empty($working_days)){
				foreach($working_days as $days){
					$nonworkdate = MonthLeave::findOrfail($days->monthleaveid);
					if(!empty($nonworkdate)){
						$nonworkdate->delete();
					}
				}
			} 

			if($nonworgdayscount > 0){
				foreach($nonworkingdats as $nondate){
					if(!empty($nondate)){
						$nondateins = new MonthLeave();
						$nondateins->workingcalanderid = $working_days_id;
						$nondateins->nonworkingdate = date('Y-m-d', strtotime($nondate));
						$nondateins->action_by = session()->get('admin_id');
						$nondateins->save();
					}
				}
			}

			DB::commit();
			$success = true;

			Session::flash('message', 'Working days is added successfully');
    		Session::flash('alert-type', 'success');

			return redirect()->route('viewworkingdays');

		} catch(\Exception $e) {

			Helper::errormail('HR', 'Edit Workingdays', 'High');
			DB::rollback();
			$success = false;

		}

		if ($success == false) { 
          return redirect('dashboard');
        }


		}

		return view('hr.workingdays.editworkingdays', compact('working_days'));



	}

	public function searchyear(Request $request){

		$year = $request->year;

		$working_days = WorkingDays::where('year', $year)->orderBy('workingcalid', 'asc')->get()->all();

		return view('hr.workingdays.viewworkingdays', compact('working_days', 'year'));
	}

	/////////////////////////////////////////// Working Days End   ////////////////////////////////////////////////////////



	/////////////////////////////////////////// Leave Start   ////////////////////////////////////////////////////////


	public function leave(Request $request){


		if($request->isMethod('post')){


			$request->validate([

				'employeeid' => 'required|unique:leave,employeeid',
				'noofleave' => 'required|integer',
				'expirydate' => 'required|date',

			]);

		DB::beginTransaction();
		try {
			$Leave_obj = new Leave();
			$Leave_obj->employeeid = $request->employeeid;
			$Leave_obj->noofleave = $request->noofleave;
			$Leave_obj->expirydate = date('Y-m-d', strtotime($request->expirydate));
			$Leave_obj->actionby = session()->get('admin_id');
			$Leave_obj->save();

			DB::commit();
			$success = true;

			Session::flash('message', 'Leave is added successfully');
    		Session::flash('alert-type', 'success');
			return redirect()->route('viewleave');

		} catch(\Exception $e) {

			Helper::errormail('HR', 'Add Leave', 'High');
			DB::rollback();
			$success = false;

		}

		if($success == false){
			return redirect('dashboard');
		}


		}

		$employee = Employee::where('status', 1)->get()->all();
		return view('hr.leave.addleave', compact('employee'));

	}

	public function viewleave(){

		$Leave = Leave::with('employeename')->paginate(10);
		$employee = Employee::where('status', 1)->get()->all();
		//dd($Leave);
		return view('hr.leave.viewleave', compact('Leave', 'employee'));

	}

	public function searcheleave(Request $request){

		$empid = $request->employeeid;

		$Leave = Leave::with('employeename')->where('employeeid', $empid)->paginate(10);
		$employee = Employee::where('status', 1)->get()->all();
		
		return view('hr.leave.viewleave', compact('Leave', 'employee'));
	}

	public function editleave($id, Request $request){

		$Leave_obj = Leave::findOrfail($id);


		if($request->isMethod('post')){


			$request->validate([

				'employeeid' => ['required', Rule::unique('leave')->ignore($id, 'leaveid')],
				'noofleave' => 'required|integer',
				'expirydate' => 'required|date',

			]);

		DB::beginTransaction();
		try {
			$Leave_obj->employeeid = $request->employeeid;
			$Leave_obj->noofleave = $request->noofleave;
			$Leave_obj->expirydate = date('Y-m-d', strtotime($request->expirydate));
			$Leave_obj->actionby = session()->get('admin_id');
			$Leave_obj->save();

			DB::commit();
			$success = true;

			Session::flash('message', 'Leave is edited successfully');
    		Session::flash('alert-type', 'success');
			return redirect()->route('viewleave');


		} catch(\Exception $e){
			Helper::errormail('HR', 'Edit Leave', 'High');
			DB::rollback();
			$success = false;

		}

		if($success == false){
			return redirect('dashboard');
		}

		}

		$employee = Employee::where('status', 1)->get()->all();
		return view('hr.leave.editleave', compact('Leave_obj', 'employee'));

	}

	public function searchleaveyear(Request $request){

		$year = $request->year;

		$Leave = Leave::where('leaveyear', $year)->orderBy('leaveid', 'asc')->get()->all();

		return view('hr.leave.viewleave', compact('Leave', 'year'));
	}

	/////////////////////////////////////////// Leave End   ////////////////////////////////////////////////////////

	//////////////////////////////////////////// Employee Acoount Start /////////////////////////////////////////////

	public function employeeaccount(Request $request){

		if($request->isMethod('post')){


			$request->validate([

				'employeeid' => 'required',
				'amount' => 'required|integer',
				'type' => 'required',

			]);

		DB::beginTransaction();
		try {

			$pastrecord = EmployeeAccount::where('employeeid', $request->employeeid)->orderBy('empaccountid', 'desc')->first();
			if(!empty($pastrecord)){
				$amountcal = $pastrecord->amount;
			} else {
				$amountcal = 0;
			}

			if($request->type == 'Loan'){

				$amount = $amountcal + $request->amount;

			}else{

				if($amountcal == 0){

					Session::flash('message', 'No loan found');
    				Session::flash('alert-type', 'error');

					return redirect()->back();
				}

				$amount = $amountcal - $request->amount;

				if($amount < 0){

					Session::flash('message', 'Please add valid amount');
    				Session::flash('alert-type', 'error');

					return redirect()->back();
				}
			}

			$employeeaccount = new EmployeeAccount();
			$employeeaccount->employeeid = $request->employeeid;
			$employeeaccount->amount = $amount;
			$employeeaccount->type = $request->type;
			$employeeaccount->empaccountdate = date('Y-m-d h:i:s');
			$employeeaccount->actionby = session()->get('admin_id');
			$employeeaccount->save();

			$salary = Salary::where('employeeid', $request->employeeid)->where('ispaid', 0)->orderBy('salaryid', 'desc')->first();
			if(!empty($salary)){

				$emi = $salary->salaryemi;
				$currentsalary = $salary->currentsalary;

				$updateemi = $emi - $request->amount;
				$updatesalary = $currentsalary + $request->amount;

				$salary->salaryemi = $updateemi;
				$salary->currentsalary = $updatesalary;

				$salary->save();

			}

			DB::commit();
			$success = true;

			Session::flash('message', 'Amount is added successfully');
    		Session::flash('alert-type', 'success');

			return redirect()->route('viewemployeeaccount');

		} catch(\Exception $e) {
		  Helper::errormail('HR', 'Add Employeeaccount', 'High');
		  $success = false;
          DB::rollback();

		}

		if ($success == false) { 
          return redirect('dashboard');
        }

		}

		$employee = Employee::where('status', 1)->get()->all();
		return view('hr.account.addemployeeamount', compact('employee'));


	}

	public function viewemployeeaccount(){

		$account = EmployeeAccount::with('employeename')->paginate(10);
		$employee = Employee::where('status', 1)->get()->all();


		return view('hr.account.viewemployeeamount')->with(compact('account', 'employee'));


	}

	public function searchemployeeaccount(Request $request){

		$empid = $request->employeeid;

		$account = EmployeeAccount::with('employeename')->where('employeeid', $empid)->get()->all();
		$employee = Employee::where('status', 1)->get()->all();


		return view('hr.account.viewemployeeamount')->with(compact('account', 'employee', 'empid'));


	}





	//////////////////////////////////////////// Employee Acoount End   /////////////////////////////////////////////


	//////////////////////////////////////////// Employee Log Start //////////////////////////////////////////////

	public function employeelog(){

		$employee = Employee::where('status', 1)->get()->all();

		return view('hr.employeelog.viewemployeelog')->with(compact('employee'));

	}

	public function searchemployeelog(Request $request){
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

		$employeelog = EmployeeLog::where('userid', $employeeid)->whereBetween('punchdate', [$fromdate, $todate])->orderBy('checkout', 'asc')->get();

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


	public function addpunch($id, Request $request){

		$log = EmployeeLog::findOrfail($id);

		if($request->isMethod('post')){

			$request->validate([

				'punchtime' => 'required',


			]);

			$checkout = $request->punchtime;
			$checkin = $log->checkin;

			DB::beginTransaction();
			try {

				$log->checkout = date('H:i:s', strtotime($request->punchtime));
				$log->save();

				DB::commit();
				$success = true;

				Session::flash('message', 'Punch is added successfully');
	    		Session::flash('alert-type', 'success');


				return redirect()->route('employeelog');

			} catch(\Exception $e){

                Helper::errormail('HR', 'Add Punch', 'High');
                DB::rollback();
                $success = false;
            }

            if($success == false){
                return redirect('dashboard');
            }


		}

		$employee = Employee::where('status', 1)->get()->all();

		return view('hr.employeelog.addemployeelog')->with(compact('employee', 'log'));


	}


	public function addemppunch(Request $request){

		$employee = Employee::where('status', 1)->get()->all();


		if($request->isMethod('POST')){

			$request->validate([

				'employeeid' => 'required',
				'punchdate' => 'required|date',
				'checkin' => 'required',
				'checkin' => 'required',

			]);

			$emppunch = new EmployeeLog();
			$emppunch->userid = $request->employeeid;
			$emppunch->punchdate = $request->punchdate;
			$emppunch->checkin = $request->checkin;
			$emppunch->checkout = $request->checkin;
			$emppunch->actionby = session()->get('admin_id');

			$emppunch->save();

			Session::flash('message', 'Punch is added successfully');
			Session::flash('alert-type', 'success');


			return redirect()->route('employeelog');


		}




		return view('hr.employeelog.addemployeepunch')->with(compact('employee'));

	}



	//////////////////////////////////////////// Employee Log End   /////////////////////////////////////////////

	//////////////////////////////////////////// salary start //////////////////////////////////////////////////


	public function salary(Request $request){


		$employee = Employee::where('status', 1)->get()->all();

		return view('hr.salary.salary')->with(compact('employee'));

	}

	public function empsalary(Request $request){
		
		$employeeid = Input::get('employeeid');
		$year = Input::get('year');
		$month = Input::get('month');

		$if_exist = Salary::where('year', $year)->where('month', $month)->where('employeeid', $employeeid)->first();

		if(!empty($if_exist)){

			$status = $if_exist->status;

			if($status == 'Locked'){

				Session::flash('message', 'Salary is already loacked');
	    		Session::flash('alert-type', 'error');

				return redirect()->route('viewlockedsalary');

			}else{

				Session::flash('message', 'Salary is calculated');
	    		Session::flash('alert-type', 'error');

				return redirect()->route('viewsalary');
			}

		}

		$workingdays_data = WorkingDays::where('year', $year)->where('month', $month)->first();
		if(empty($workingdays_data)){

			Session::flash('message', 'Please add workingdays of '.$month);
    		Session::flash('alert-type', 'error');


			return redirect()->route('workingdays')->with(compact('year', 'month'));
		}

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

		$employee = Employee::where('status', 1)->get()->all();
		$emptime = Employee::where('employeeid', $employeeid)->first();
		$checkintime = $emptime->workinghourfrom1;
		$checkouttime = $emptime->workinghourto1;

		$employeelog = EmployeeLog::where('userid', $employeeid)->whereBetween('punchdate', [$fromdate, $todate])->where('checkout', null)->get()->all();

		$lateemployeelog = EmployeeLog::where('userid', $employeeid)->whereBetween('punchdate', [$fromdate, $todate])->where(function($query) use ($checkintime, $checkouttime){
			$query->where('checkin', '>', $checkintime)->orWhere('checkout', '<', $checkouttime);
		})->get()->all();

		$error = 1;
		if(!empty($employeelog)){

			Session::flash('message', 'Please complete employee log');
    		Session::flash('alert-type', 'error');

			return view('hr.employeelog.viewemployeelog')->with(compact('employeeid', 'year', 'month', 'employee', 'error'));

		}

	
		/*try {*/

		$employeelog = EmployeeLog::where('userid', $employeeid)->whereBetween('punchdate', [$fromdate, $todate])->get()->all();

		$employeelog_days = EmployeeLog::where('userid', $employeeid)->whereBetween('punchdate', [$fromdate, $todate])->groupBy('punchdate')->select('punchdate')->get()->all();
		

		$attenddays = count($employeelog_days);
		
		$totalminute = 0;
		$totalhour = 0;
		$totaldays = 0;
		$givenleave = 0;

		foreach($employeelog as $emplog){

			$difference = ROUND(ABS(strtotime($emplog->checkout) - strtotime($emplog->checkin))/60);



			$totalminute += abs($difference);

		}
		
		$totalhour_dispaly_model = round($totalminute/60);
		
		$totalminute_dispaly = $totalminute;
		/*$hours123 = floor($totalminute / 60);
		$minutes123 = ($totalminute % 60);*/
		//echo $hours123.":".$minutes123;exit;
		

		$noofleave = Leave::where('employeeid', $employeeid)->first();
		if(!empty($noofleave)){
			$givenleave = $noofleave->noofleave;
		}else{
			$givenleave = 0;
		}

		$paidleave = 0;

		$empleave = EmployeeLeave::where('employeeid', $employeeid)->whereBetween('date', [$fromdate, $todate])->get()->all();
		if(!empty($empleave)){
			foreach($empleave as $leaveinfo){
				if($leaveinfo->leavetype == 'Pl'){
					$paidleave += 1;
				}
			}
		}

		$takenleave = count($empleave);
		$takenleave_display = count($empleave);

		$empdata = Employee::where('employeeid', $employeeid)->first();

		/*$employeeaccount = Employeeaccount::where('employeeid', $employeeid)->*/

		$empsalary = $empdata->salary;
		$empworkinghour = $empdata->workinghour;

		$Workindays = 0;
		$holidays = 0;
		$workingdays_data = WorkingDays::where('year', $year)->where('month', $month)->first();
		if(!empty($workingdays_data)){
			$Workindays = $workingdays_data->workingdays;
			$holidays = $workingdays_data->holidays;
		}else{
			$Workindays = 0;
			$holidays = 0;
		}

		$actualdays = $Workindays;
		//dd($actualdays);

		$leavedays_cal = $Workindays - $attenddays;

		$totalworkinghour = $Workindays * $empworkinghour;
		$empworkingminute = $Workindays * $empworkinghour * 60;
		$totalminute = $attenddays * $empworkinghour * 60; 
		$totalminutedisplay = $totalminute/60;

		$total_hour = ceil($totalminute / 60);


		$takenleave = $Workindays - $attenddays;
		
		$totalattenddays = $Workindays - $takenleave;
	
		
		$perdaysalary = ceil($empsalary/$Workindays);

		$current_salary = round($perdaysalary * $totalattenddays);

		$store = !empty($request->store) ? $request->store : 0;

		$success = true;

		$emploanamount = EmployeeAccount::where('employeeid', $employeeid)->orderBy('empaccountid', 'desc')->first();

		return view('hr.salary.calculatesalary')->with(compact('attenddays', 'totalminute', 'totalhour', 'totaldays', 'givenleave', 'takenleave', 'empdata', 'empsalary', 'empworkinghour', 'total_hour', 'year', 'month', 'Workindays', 'holidays', 'empworkingminute', 'current_salary', 'employeeid', 'takenleave_display', 'Workindays', 'leavedays_cal', 'totalworkinghour', 'employeelog', 'totalminute_dispaly', 'totalhour_dispaly_model', 'emploanamount', 'lateemployeelog', 'actualdays'));

	/*}  catch(\Exception $e) {

		Helper::errormail('Hr', 'Calculate Salary', 'High');

		$success = false;
	}

	if($success == false){
		return redirect('dashboard');
	}*/




	}
}

	public function storeempsalary(Request $request){
	
		/*$request->validate([

			'attenddays_display' => 'required|numeric|digits_between:1,2',
			'takenleave_display' => 'required|numeric|digits_between:1,2',
			'casualleave' => 'nullable|numeric|digits_between:1,2',
			'medicalleave' => 'nullable|numeric|digits_between:1,2',
			'paidleave' => 'nullable|numeric|digits_between:1,2',
			'current_salary' => 'required|numeric|digits_between:1,10',

		]);*/

		$if_exist = Salary::where('year', $request->year)->where('month', $request->month)->where('employeeid', $request->employeeid)->first();

		if(!empty($if_exist)){

			Session::flash('message', 'Employee Salary is already locked');
			Session::flash('alert-type', 'error');

			return redirect()->route('viewlockedsalary');
		}

		DB::beginTransaction();
		try {
			
			/*if($request->emi > 0){

				$empaccount = EmployeeAccount::where('employeeid', $request->employeeid)->orderBy('empaccountid', 'desc')->first();
				
				if(!empty($empaccount)){

					$empamount = $empaccount->amount;
					if($empamount > 0 || $empamount >= $request->emi){
						$finalamount = $empamount - $request->emi;

						$newempaccount = new EmployeeAccount();
						$newempaccount->employeeid = $request->employeeid;
						$newempaccount->amount = $finalamount;
						$newempaccount->type = 'EMI';
						$newempaccount->empaccountdate = date('Y-m-d');
						$newempaccount->actionby = session()->get('admin_id');
						$newempaccount->save();

					}

				}

			}*/


			$salary = new Salary();
			$salary->employeeid = $request->employeeid;
			$salary->workingdays = $request->Workindays;
			$salary->attenddays = $request->attenddays_display;
			$salary->actualdays = $request->actualdays_display;
			$salary->totalminute = $request->workingminute;
			$salary->empworkingminute = $request->empworkingminute;
			$salary->empworkinghour = $request->monthlyworking_hour_display;
			$salary->totalhour = $request->totalworkinghour_display;
			$salary->givenleave = $request->givenleave;
			$salary->takenleave = $request->takenleave_display;
			$salary->empsalary = $request->empsalary;
			$salary->currentsalary = $request->current_salary;
			$salary->holidays = $request->holidays;
			$salary->casualleave = !empty($request->casualleave) ? $request->casualleave : 0;
			$salary->medicalleave = !empty($request->medicalleave) ? $request->medicalleave : 0;
			$salary->paidleave = !empty($request->paidleave) ? $request->paidleave : 0;
			$salary->year = $request->year;
			$salary->month = $request->month_display;
			$salary->salaryemi = !empty($request->emi) ? $request->emi : 0;
			$salary->salaryothercharges = !empty($request->otheramount) ? $request->otheramount : 0;
			$salary->loanamount = !empty($request->loan) ? $request->loan : 0;
			$salary->status = 'Unlocked';
			$salary->actionby = session()->get('admin_id');

			$salary->save();

			DB::commit();
			$success = true;
			
			Session::flash('message', 'Employee Salary is locked');
			Session::flash('alert-type', 'success');

			return redirect()->route('viewsalary');

		} catch(\Exception $e) {

			Helper::errormail('HR', 'Store Salary', 'High');

			DB::rollback();
			$success = false;
		}

		if($success == false){
			return redirect('dashboard');
		}

	}

	public function viewlockedsalary(Request $request){

		$salary = Salary::with('employee')->where('status', 'Locked')->paginate(10);
		$employee  = Employee::where('status', 1)->get()->all();

		return view('hr.salary.viewlockedsalary')->with(compact('salary', 'employee'));

	}


	public function viewsalary(){

		$salary = Salary::with('employee')->where('status', 'Unlocked')->paginate(10);

		$employee = Employee::where('status', 1)->get()->all();

		return view('hr.salary.viewsalary')->with(compact('salary', 'employee'));

	}

	public function editsalary($id, Request $request){
		
		$salary = Salary::with('employee')->where('salaryid', $id)->first();
		$employeeid = $salary->employeeid;
		$month = $salary->month;
		$year = $salary->year;

		if($month == 'Janaury'){
			$cal_month = 1;
		}else if($month == 'February'){
			$cal_month = 2;
		}else if($month == 'March'){
			$cal_month = 3;
		}else if($month == 'April'){
			$cal_month = 4;
		}else if($month == 'May'){
			$cal_month = 5;
		}else if($month == 'June'){
			$cal_month = 6;
		}else if($month == 'July'){
			$cal_month = 7;
		}else if($month == 'August'){
			$cal_month = 8;
		}else if($month == 'September'){
			$cal_month = 9;
		}else if($month == 'October'){
			$cal_month = 10;
		}else if($month == 'November'){
			$cal_month = 11;
		}else{
			$cal_month = 12;
		}

		$day_in_month = cal_days_in_month(CAL_GREGORIAN,$cal_month,$year);
		$fromdate = date('Y-m-d',strtotime("$year-$cal_month-01"));
		$todate = date('Y-m-d',strtotime("$year-$cal_month-$day_in_month"));
		
		$employeelog = EmployeeLog::where('userid', $employeeid)->whereBetween('punchdate', [$fromdate, $todate])->get()->all();


		if($request->isMethod('POST')){

			$request->validate([

				'attenddays_display' => 'required|numeric|digits_between:1,2',
				'takenleave_display' => 'required|numeric|digits_between:1,2',
				'casualleave' => 'nullable|numeric|digits_between:1,2',
				'medicalleave' => 'nullable|numeric|digits_between:1,2',
				'paidleave' => 'nullable|numeric|digits_between:1,2',
				'current_salary' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',

			]);

			DB::beginTransaction();
			try {

			$salary->employeeid = $request->employeeid;
			$salary->workingdays = $request->workingdays_display;
			$salary->attenddays = $request->attenddays_display;
			$salary->actualdays = $request->actualdays_display;
			$salary->totalminute = $request->workingminute;
			$salary->empworkingminute = $request->empworkingminute;
			$salary->empworkinghour = $request->monthlyworking_hour_display;
			$salary->totalhour = $request->totalworkinghour_display;
			$salary->givenleave = $request->givenleave;
			$salary->takenleave = $request->takenleave_display;
			$salary->empsalary = $request->empsalary;
			$salary->currentsalary = $request->current_salary;
			$salary->holidays = $request->holidays;
			$salary->casualleave = !empty($request->casualleave) ? $request->casualleave : 0;
			$salary->medicalleave = !empty($request->medicalleave) ? $request->medicalleave : 0;
			$salary->paidleave = !empty($request->paidleave) ? $request->paidleave : 0;
			$salary->year = $request->year;
			$salary->month = $request->month_display;
			$salary->salaryemi = $request->emi;
			$salary->salaryothercharges = $request->otheramount;
			$salary->loanamount = $request->loan;
			$salary->status = 'Unlocked';
			$salary->actionby = session()->get('admin_id');

			$salary->save();

			DB::commit();
			$success = true;

			Session::flash('message', 'Employee Salary is updated');
			Session::flash('alert-type', 'success');

			return redirect()->route('viewsalary');

		} catch(\Exception $e) {

			Helper::errormail('HR', 'Edit Salary', 'High');

			DB::rollback();
			$success = false;
		}

		if($success == false){
			return redirect('dashboard');
		}


		}


		return view('hr.salary.editsalary')->with(compact('salary', 'employeelog'));

	}

	public function locksalary($id){

		$salary = Salary::findOrfail($id);

		DB::beginTransaction();
		try {

			$salary->status = 'Locked';
			$salary->save();

			DB::commit();
			$success = true;

			Session::flash('message', 'Employee Salary is locked');
			Session::flash('alert-type', 'success');

			return redirect()->route('viewlockedsalary');

		} catch(\Exception $e) {

			Helper::errormail('HR', 'Lock Salary', 'High');

			DB::rollback();
			$success = false;
		}

		if($success == false){

			return redirect('dashboard');
		}

	}

	public function unlocksalary($id){

		$salary = Salary::findOrfail($id);

		DB::beginTransaction();
		try {
			$salary->status = 'Unlocked';
			$salary->save();

			Session::flash('message', 'Employee Salary is Unlocked');
			Session::flash('alert-type', 'success');

			return redirect()->route('viewsalary');

		} catch(\Exception $e) {

			Helper::errormail('HR', 'Unlock Salary', 'High');

			DB::rollback();
			$success = false;
		}

		if($success == false){

			return redirect('dashboard');
		}

	}

	public function confirmsalary(Request $request){

		$accountno = $request->accountno;
		$empname = $request->empname;
		$empid = $request->empid;
		$salaryid = $request->salaryid;

		$salary = Salary::findOrfail($salaryid);
		$salary->accountno = $accountno;
		$salary->ispaid = 1;
		$emi = $salary->salaryemi;
		$employeeid = $salary->employeeid;
		$salary->paidby = session()->get('admin_id');
		$salary->paiddate = date('Y-m-d');
		$salary->save();

		if($emi > 0){

				$empaccount = EmployeeAccount::where('employeeid', $employeeid)->orderBy('empaccountid', 'desc')->first();
				
				if(!empty($empaccount)){

					$empamount = $empaccount->amount;
					if($empamount > 0 || $empamount >= $emi){
						$finalamount = $empamount - $emi;

						$newempaccount = new EmployeeAccount();
						$newempaccount->employeeid = $employeeid;
						$newempaccount->amount = $finalamount;
						$newempaccount->type = 'EMI';
						$newempaccount->empaccountdate = date('Y-m-d');
						$newempaccount->actionby = session()->get('admin_id');
						$newempaccount->save();

					}

				}

			}

		return 201;
	}

	public function viewlockedsalarysearch(Request $request){

		$employeeid = $request->employeeid;
		$month = $request->month;
		$year = $request->year;
		
		$salary = Salary::where('employeeid', $employeeid)->where('month', $month)->where('year', $year)->paginate(10);
		$employee  = Employee::where('status', 1)->get()->all();

		return view('hr.salary.viewlockedsalary')->with(compact('salary', 'employee', 'employeeid', 'month', 'year'));
		

	}

	public function searchsalary(Request $request){

		$employeeid = Input::get('employeeid');
		$year = Input::get('year');
		$month = Input::get('month');

		$salary = Salary::where('employeeid', $employeeid)->where('month', $month)->where('year', $year)->paginate(10);
		$employee  = Employee::where('status', 1)->get()->all();
		/*dd($salary->month);*/

		return view('hr.salary.viewsalary')->with(compact('salary', 'employee', 'employeeid', 'year', 'month'));


	}

	//////////////////////////////////////////// salary end   //////////////////////////////////////////////////




///////////////////////////////////// Employee Leave start //////////////////////////////////////////////////////////////

	public function employeeleave(Request $request){



		if($request->isMethod('post')){

			$request->validate([

				'employeeid' =>  'required',
				'leavedate' =>  'required',
				'reason' =>  'nullable|max:255',

			]);


			$employeeid = $request->employeeid;
			$leavecount = 0;
			$totalleave = 0;

			$empleave = Leave::where('employeeid', $employeeid)->first();
			if(empty($empleave)){
				$employee = $request->employeeid;
				$employee  = Employee::where('status', 1)->get()->all();

				Session::flash('message', 'Please add employee leave');
				Session::flash('alert-type', 'error');

				return redirect()->route('leave')->with(compact('employee'));
			}else{

				$totalleave = $empleave->noofleave;
			}

			
			$leavecount  = EmployeeLeave::where('employeeid', $employeeid)->get()->all();
			$leavecount = count($leavecount);
			

			if($leavecount > $totalleave){
				return back()->with('error', 'You can not add leave as Employee leaves are already used!');
			}

			$existleave = EmployeeLeave::where('employeeid', $employeeid)->where('date', date('Y-m-d', strtotime($request->leavedate)))->first();
			if(!empty($existleave)){
				return back()->withInput()->with('error', 'You can not add same leave!');

			}

			DB::beginTransaction();
			try {

				$employeeleave = new EmployeeLeave();
				$employeeleave->employeeid =  $employeeid;
				$employeeleave->date =  date('Y-m-d', strtotime($request->leavedate));
				$employeeleave->leavetype = $request->leavetype;
				$employeeleave->reason =  !empty($request->reason) ? $request->reason : null;
				$employeeleave->actionby = session()->get('admin_id');
				$employeeleave->Save();
				
				DB::commit();
				$success = true;

				Session::flash('message', 'Employee leave is added successfully');
				Session::flash('alert-type', 'success');

				return redirect()->route('viewemployeeleave')->with('success', 'Employee leave is added successfully.');

			} catch(\Exception $e) {

				Helper::errormail('HR', 'Add Employee Leave', 'High');

				DB::rollback();
				$success = false;
			}

			if($success == false){
				return redirect('dashboard');
			}



		}


		$employee = Employee::where('status', 1)->get()->all();

		return view('hr.employeeleave.addemployeeleave')->with(compact('employee'));



	}

	public function editemployeeleave($id, Request $request){

		$empleave = EmployeeLeave::where('employeeleaveid', $id)->first();

		$empexpirydate = Leave::where('employeeid', $empleave->employeeid)->first();
		
		if(!empty($empexpirydate)){

			$expirydate = $empexpirydate->expirydate;

		}else{

			$expirydate='';
		}

		if($request->isMethod('post')){

			$request->validate([

				'leavedate' =>  'required',
				'reason' =>  'nullable|max:255',

			]);

			$employeeid = $empleave->employeeid;
			
			$existleave = EmployeeLeave::where('employeeid', $employeeid)->where('date', date('Y-m-d', strtotime($request->leavedate)))->where('employeeleaveid', '!=', $id)->first();
			if(!empty($existleave)){
				return back()->with('error', 'You can not add same leave!');

			}

			DB::beginTransaction();
			try {

				$empleave->date =  date('Y-m-d', strtotime($request->leavedate));
				$empleave->reason =  !empty($request->reason) ? $request->reason : null;
				$empleave->leavetype = $request->leavetype;
				$empleave->actionby = session()->get('admin_id');
				$empleave->Save();

				DB::commit();
				$success = true;

				Session::flash('message', 'Employee leave is updated successfully');
				Session::flash('alert-type', 'success');

				return redirect()->route('viewemployeeleave')->with('success', 'Employee leave is updated successfully');

			} catch(\Exception $e) {

				Helper::errormail('HR', 'Edit Employee Leave', 'High');

				DB::rollback();
				$success = false;
			}

			if($success == false){
				return redirect('dashboard');
			}


			
		}


		$employee = Employee::where('status', 1)->get()->all();

		return view('hr.employeeleave.editemployeeleave')->with(compact('empleave', 'employee', 'expirydate'));






	}

	public function viewemployeeleave(){

		$employeeleave = EmployeeLeave::with('empname')->paginate(10);
		$employee = Employee::where('status', 1)->get()->all();

		return view('hr.employeeleave.viewemployeeleave')->with(compact('employeeleave', 'employee'));


	}

	public function searchemployeeleave(Request $request){

		$employeeid = $request->employeeid;

		$employeeleave = EmployeeLeave::where('employeeid', $employeeid)->get()->all();
		$employee = Employee::where('status', 1)->get()->all();

		return view('hr.employeeleave.viewemployeeleave')->with(compact('employeeleave', 'employee', 'employeeid'));

	}

	public function empexpirydate(){

		$empid = $_REQUEST['empid'];

		$empexpirydate = Leave::where('employeeid', $empid)->first();
		//dd($empexpirydate);

		if(!empty($empexpirydate)){

			$expirydate = $empexpirydate->expirydate;

			return $expirydate;

		}else{

			return 'leavenotfound';

		}



	}



	public function deleteemployeeleave($id){

		DB::beginTransaction();
		try {

		$empexpirydate = EmployeeLeave::where('employeeid', $id)->first();
		$empexpirydate->delete();

		DB::commit();
		$success = true;

		return redirect()->route('viewemployeeleave')->with('error', 'Employee leave is deleted');

	} catch(\Exception $e) {

		Helper::errormail('HR', 'Delete Employee Leave', 'High');

		DB::rollback();
		$success = false;
	}

	if($success == false){
		return redirect('dashboard');
	}


	}







///////////////////////////////////// Employee Leave End ////////////////////////////////////////////////////////////////


////////////////////////////////////////// import punch /////////////////////////////////////////////////////////////
	public function importpunch(Request $request){


		$employee = Employee::where('status', 1)->get()->all();
		
		return view('hr.employeelog.importpunch')->with(compact('employee'));

	}


	public function downloaddemosheet(Request $request){
		

		if($request->isMethod('POST')){


			$employeeid = $request->employeeid;
			$month = $request->month;
			$year = $request->year;
			
			ExcelExport::truncate();
			/*$excel = ExcelExport::all();
			if(!empty($excel)){
				foreach($excel as $e){
					ExcelExport::where('excelexportid', $e->excelexportid)->delete();
				}
			}*/
			
			$fullname = '';

			if($employeeid && $month && $year){

				$empdetail = Employee::where('employeeid', $employeeid)->first();
				if(!empty($empdetail)){

					$fullname = ucfirst($empdetail->first_name).' '.ucfirst($empdetail->last_name);
				}

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

				$export_array = [];

				for($i = 1; $i<= $day_in_month; $i++){

					$current_date = date('Y-m-d',strtotime("$year-$cal_month-$i"));
					//dd($current_date);
					$excel = new ExcelExport();
					$excel->employeeid = $employeeid;
					$excel->employeename = ucfirst($empdetail->first_name).' '.ucfirst($empdetail->last_name);
					$excel->date = $current_date;
					$excel->checkin = '';
					$excel->checkout = '';
					$excel->save();

					$excel = new ExcelExport();
					$excel->employeeid = $employeeid;
					$excel->employeename = ucfirst($empdetail->first_name).' '.ucfirst($empdetail->last_name);
					$excel->date = $current_date;
					$excel->checkin = '';
					$excel->checkout = '';
					$excel->save();
					


				}
				
				
			$isexport = 1;
			$employee = Employee::where('status', 1)->get()->all();
			$employee_name = $fullname.'-'.$request->month.'-'.$request->year.'.csv';

			Session::flash('downloadexcel', 'downloadexcel');
			Session::put('empname', $employee_name);

			Session::flash('message', 'Employee sheet will download shortly');
			Session::flash('alert-type', 'success');

			//return Excel::download(new EmployeeExport(),'user.csv');

			//return view('hr.employeelog.importpunch')->with(compact('employee', 'employeeid', 'month', 'year', 'isexport'));

			return redirect()->route('importpunch');

			}
	    }

	}

	public function downloadexcel(){

		$empname = session()->get('empname');

		return Excel::download(new EmployeeExport(), $empname);

	}



	public function importemppunchcsv(Request $request){

		$request->validate([

			'file' => 'required|mimes:csv'

		]);

		$file = $request->file('file');

		 // File Details 
		$filename = $file->getClientOriginalName();
		$extension = $file->getClientOriginalExtension();
		$path = $file->getRealPath();
		$fileSize = $file->getSize();
		$mimeType = $file->getMimeType();

		$valid_extension = array("csv");

		$maxFileSize = 2097152; 

		// Check file extension
      	if(in_array(strtolower($extension),$valid_extension)){

      		// Check file size
      		if($fileSize <= $maxFileSize){

      			$data = array_map('str_getcsv', file($path));
      			//dd($data);
      			foreach($data as $key => $csv_data){
      				if($key != 0){

      					$empid = $csv_data[0];
      					$empname = $csv_data[1];
      					$empdate = $csv_data[2];
      					$empcheckin = $csv_data[3];
      					$empcheckout = $csv_data[4];
      					echo $empid.'<br/>';
      					echo $empname.'<br/>';
      					echo $empdate.'<br/>';
      					echo $empcheckin.'<br/>';
      					echo $empcheckout.'<br/>';
      					//dd('stop');

      					if(!empty($empid) && is_numeric($empid) && !empty($empdate) && strtotime($empdate) && !empty($empcheckin) &&  !empty($empcheckout)){

      						$employeelog_exist = EmployeeLog::where('userid', $empid)->where('punchdate', date('Y-m-d', strtotime($empdate)))->where('checkin', 'like' , $empcheckin.'%')->where('checkout', 'like' , $empcheckout.'%')->first();

      						if(empty($employeelog_exist)){

	      						$employeelog = new EmployeeLog();
	      						$employeelog->userid = $empid;
	      						$employeelog->punchdate = date('Y-m-d', strtotime($empdate));
	      						$employeelog->checkin = $empcheckin;
	      						$employeelog->checkout = $empcheckout;
	      						$employeelog->actionby = session()->get('admin_id');
	      						$employeelog->save();

	      					}
      					}


      				}
      			}

      			Session::flash('message', 'Employee punch is added successfully');
      			Session::flash('alert-type', 'success');

      			return redirect()->back();

      		}

      	}










	}



////////////////////////////////////////// import punch end/////////////////////////////////////////////////////////////

}
