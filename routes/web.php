 <?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('admin_login');
})->name('admin_login');


////////////////////////////////////////////////////////// Login Route ///////////////////////////////////////////////////////////////////

Route::any('loginprocess', 'EmployeeController@loginprocess')->name('loginprocess');
Route::any('logout', 'EmployeeController@logout')->name('logout');


/////////////////////////////////////////////////////// Login Route End///////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////// common route start /////////////////////////////////////////////////////////////
Route::get('notification', 'CommonController@notification');
Route::get('method', 'CommonController@method');
Route::any('getcity', 'CommonController@getcity')->name('getcity');
////////////////////////////////////////////////////////// common route end  /////////////////////////////////////////////////////////////

Route::group(['middleware'=>['Islogin', 'isadmin']], function(){


Route::any('dashboard', 'EmployeeController@dashboard')->name('dashboard');
////////////////////////////////////////////////////////// Department route start /////////////////////////////////////////////////////////////
Route::any('department', 'DepartmentController@department')->name('department');
Route::any('viewdepartment', 'DepartmentController@viewdepartment')->name('viewdepartment');
Route::any('updatedept/{id}', 'DepartmentController@updatedepartment')->name('updatedept');
Route::any('activedept/{id}', 'DepartmentController@activeedepartment')->name('activedept');
Route::any('deactivedept/{id}', 'DepartmentController@deactivedepartment')->name('deactivedept');
////////////////////////////////////////////////////////// common route end  /////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////// Employee start //////////////////////////////////////////////////////////////

Route::any('employee', 'EmployeeController@employee')->name('employee');
Route::any('checkuserexist', 'EmployeeController@checkuserexist')->name('checkuserexist');
Route::any('checkmobilenoexist', 'EmployeeController@checkmobilenoexist')->name('checkmobilenoexist');
Route::any('viewemployee', 'EmployeeController@viewemployee')->name('viewemployee');
Route::any('updateemp/{id}', 'EmployeeController@updateemployee')->name('updateemp');
Route::any('activeemp/{id}', 'EmployeeController@activeemployee')->name('activeemp');
Route::any('deactiveemp/{id}', 'EmployeeController@deactiveemployee')->name('deactiveemp');


//////////////////////////////////////////////////////////// Employee end   //////////////////////////////////////////////////////////////

//////////////////////////////////////////// HR Module Start /////////////////////////////////////////////////////////

////////////////////////// working Days ////////////////////////////////////////
Route::any('workingdays', 'HRController@workingdays')->name('workingdays');
Route::any('viewworkingdays', 'HRController@viewworkingdays')->name('viewworkingdays');
Route::any('searchyear', 'HRController@searchyear')->name('searchyear');
Route::any('editworkingdays/{id}', 'HRController@editworkingdays')->name('editworkingdays');
////////////////////////// working Days end ////////////////////////////////////////

////////////////////////// leave Days ////////////////////////////////////////
Route::any('leave', 'HRController@leave')->name('leave');
Route::any('viewleave', 'HRController@viewleave')->name('viewleave');
Route::any('searchleaveyear', 'HRController@searchleaveyear')->name('searchleaveyear');
Route::any('editleave/{id}', 'HRController@editleave')->name('editleave');
Route::any('searcheleave', 'HRController@searcheleave')->name('searcheleave');
////////////////////////// leave Days end ////////////////////////////////////////

///////////////////////////// Employee account start /////////////////////////////////////
Route::any('employeeaccount', 'HRController@employeeaccount')->name('employeeaccount');
Route::any('viewemployeeaccount', 'HRController@viewemployeeaccount')->name('viewemployeeaccount');
Route::any('searchleaveyear', 'HRController@searchleaveyear')->name('searchleaveyear');
Route::any('searchemployeeaccount', 'HRController@searchemployeeaccount')->name('searchemployeeaccount');
///////////////////////////// Employee account end   /////////////////////////////////////

///////////////////////////// Employee log start /////////////////////////////////////
Route::any('employeelog', 'HRController@employeelog')->name('employeelog');

Route::any('storelog', 'HRController@storelog')->name('storelog');
Route::any('addpunch/{id}', 'HRController@addpunch')->name('addpunch');
Route::any('addemppunch', 'HRController@addemppunch')->name('addemppunch');
Route::any('importpunch', 'HRController@importpunch')->name('importpunch');
Route::any('downloaddemosheet', 'HRController@downloaddemosheet')->name('downloaddemosheet');
Route::any('downloadexcel', 'HRController@downloadexcel')->name('downloadexcel');
Route::any('importemppunchcsv', 'HRController@importemppunchcsv')->name('importemppunchcsv');
///////////////////////////// Employee log end ///////////////////////////////////////

///////////////////////////////////// Employee Leave start //////////////////////////////////////////////////////////////
Route::any('employeeleave', 'HRController@employeeleave')->name('employeeleave');
Route::any('viewemployeeleave', 'HRController@viewemployeeleave')->name('viewemployeeleave');
Route::any('empexpirydate', 'HRController@empexpirydate')->name('empexpirydate');
Route::any('searchemployeeleave', 'HRController@searchemployeeleave')->name('searchemployeeleave');
Route::any('editemployeeleave/{id}', 'HRController@editemployeeleave')->name('editemployeeleave');
Route::any('deleteemployeeleave/{id}', 'HRController@deleteemployeeleave')->name('deleteemployeeleave');
///////////////////////////////////// Employee Leave end //////////////////////////////////////////////////////////////

/////////////////////////////////////////////// salary start ///////////////////////////////////////////////////////////
Route::any('salary', 'HRController@salary')->name('salary');
Route::any('empsalary', 'HRController@empsalary')->name('empsalary');
Route::any('storeempsalary', 'HRController@storeempsalary')->name('storeempsalary');
Route::any('viewsalary', 'HRController@viewsalary')->name('viewsalary');
Route::any('locksalary/{id}', 'HRController@locksalary')->name('locksalary');
Route::any('unlocksalary/{id}', 'HRController@unlocksalary')->name('unlocksalary');
Route::any('viewlockedsalary', 'HRController@viewlockedsalary')->name('viewlockedsalary');
Route::any('viewlockedsalarysearch', 'HRController@viewlockedsalarysearch')->name('viewlockedsalarysearch');
Route::any('editsalary/{id}', 'HRController@editsalary')->name('editsalary');
Route::any('confirmsalary', 'HRController@confirmsalary')->name('confirmsalary');
Route::any('searchsalary', 'HRController@searchsalary')->name('searchsalary');
/////////////////////////////////////////////// salary end   ///////////////////////////////////////////////////////////

///////////////////////////////////////////////////// Device Start ////////////////////////////////////////////////////
Route::any('adddevice', 'DevicecController@adddevice')->name('adddevice');
Route::any('viewdevice', 'DevicecController@viewdevice')->name('viewdevice');
Route::any('updatedevice/{id}', 'DevicecController@updatedevice')->name('updatedevice');
Route::any('deactivedevice/{id}', 'DevicecController@deactivedevice')->name('deactivedevice');
Route::any('activedevice/{id}', 'DevicecController@activedevice')->name('activedevice');
///////////////////////////////////////////////////// Device End ////////////////////////////////////////////////////

//////////////////////////////////////////////////// Cron job start ///////////////////////////////////////////////////

Route::get('fetchlogtable', function(){
	return view('script_fetchlog_userlogtable');
});

Route::get("cronjob", function() {
   return view('script_apicron');
});



//////////////////////////////////////////////////// Cron job end  ///////////////////////////////////////////////////

///////////////////////////////////////////////////// enroll employee /////////////////////////////////////////////////
Route::any('enrolldevice', 'EnrollController@enrolldevice')->name('enrolldevice');
Route::any('employeedeviceinfo', 'EnrollController@employeedeviceinfo')->name('employeedeviceinfo');
Route::any('devicelist', 'EnrollController@devicelist')->name('devicelist');
Route::any('empindevice', 'EnrollController@empindevice')->name('empindevice');
Route::any('enrollfingertemplate', 'EnrollController@enrollfingertemplate')->name('enrollfingertemplate');
Route::any('getfingertemplate', 'EnrollController@getfingertemplate')->name('getfingertemplate');
Route::any('checkfingerprint', 'EnrollController@checkfingerprint')->name('checkfingerprint');
Route::any('setfingerprinteachdevice', 'EnrollController@setfingerprinteachdevice')->name('setfingerprinteachdevice');
Route::any('fetchdeviceenroll', 'EnrollController@fetchdeviceenroll')->name('fetchdeviceenroll');
Route::any('getuserdevicelist', 'EnrollController@getuserdevicelist')->name('getuserdevicelist');
Route::any('checksetuser', 'EnrollController@checksetuser')->name('checksetuser');
Route::any('uploadfingerprint', 'EnrollController@uploadfingerprint')->name('uploadfingerprint');
Route::any('deactiveuser', 'EnrollController@deactiveuser')->name('deactiveuser');
Route::any('activeuser', 'EnrollController@activeuser')->name('activeuser');
Route::any('getcontractdate', 'EnrollController@getcontractdate')->name('getcontractdate');
Route::any('setcontractdate', 'EnrollController@setcontractdate')->name('setcontractdate');
Route::any('checkdevicecount', 'EnrollController@checkdevicecount')->name('checkdevicecount');
Route::any('emplog', 'EnrollController@emplog')->name('emplog');
Route::any('emplogajax', 'EnrollController@emplogajax')->name('emplogajax');
Route::any('searchemployeelog', 'HRController@searchemployeelog')->name('searchemployeelog');
///////////////////////////////////////////////////// enroll employee /////////////////////////////////////////////////

});

//////////////////////////////////////////////////////// Employee Route start /////////////////////////////////////////////////
Route::group(['middleware'=>['Islogin', 'isemployee']], function(){


Route::any('empdashboard', 'EmployeePortal@empdashboard')->name('empdashboard');
Route::any('empprofile', 'EmployeePortal@empprofile')->name('empprofile');
Route::any('emplogemp', 'EmployeePortal@emplogemp')->name('emplogemp');
Route::any('searchemployeelogemp', 'EmployeePortal@searchemployeelogemp')->name('searchemployeelogemp');

});









//////////////////////////////////////////////////////// Employee Route end //////////////////////////////////////////////////






