@extends('layout.mainlayout')

@section('title', 'Edit Employee')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/validate.css') }}">
@endpush

@section('content')
	
	<section class="content-header">
       <div class="row">
	      	<div class="col-md-12">
		      <ol class="breadcrumb">
		        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		        <li><a href="{{ route('employee') }}">Employee</a></li>
		        <li class="active">Edit Employee</li>
		      </ol>
	  		</div>
  		</div>
    </section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit Employee</h3>
						<a href="{{ route('viewemployee') }}" class="btn btn-primary backbuttonheader"><i class="fa fa-arrow-left"></i> Back</a>
					</div>
					<div class="box-body">
						<form  id="user_validation_form" id="emp_form" action="{{ route('updateemp', $employee->employeeid) }}"  method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							
								<h4>Employee Details</h4>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>First Name<span style="color: red;">*</span></label>
											<input type="text"  id="first_name" name="first_name" value="{{ $employee->first_name }}" class="form-control"placeholder="Enter First name" required=""  maxlength="191" class="span11" autocomplete="off" />
											@if($errors->has('first_name'))
											<span class="help-block">
												<strong>{{ $errors->first('first_name') }}</strong>
											</span>
											@endif
										</div>
									</div>


									<div class="col-md-4">
										<div class="form-group">
											<label>Last Name<span style="color: red;">*</span></label>
											<input type="text"  id="last_name" name="last_name" value="{{ $employee->last_name }}" class="form-control"placeholder="Enter Last name" required=""  maxlength="191" class="span11" autocomplete="off" />
											@if($errors->has('last_name'))
											<span class="help-block">
												<strong>{{ $errors->first('last_name') }}</strong>
											</span>
											@endif
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>User Name<span style="color: red;">*</span></label>
											<input type="text"  id="username" name="username" value="{{ $employee->username }}" class="form-control"placeholder="Enter User name" autocomplete="off" required=""  maxlength="255" readonly="" />
											<span id="error_username" class="ajaxerror">Username already exist</span>
											@if($errors->has('username'))
											<span class="help-block">
												<strong>{{ $errors->first('username') }}</strong>
											</span>
											@endif
										</div>
									</div>

								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>Select Role<span style="color: red;">*</span></label>
											<select name="role" id="Role_id"  class="form-control"class="span11">
												<option selected disabled="" required="" value="">--Please choose an option--</option>
												<option @if($employee->role == 'Admin' ) selected="" @endif>Admin</option>
												<option @if($employee->role == 'Employee' ) selected="" @endif>Employee</option>
											</select>
											@if($errors->has('Role_id'))
											<span class="help-block">
												<strong>{{ $errors->first('Role_id') }}</strong>
											</span>
											@endif
										</div>
									</div>


									<div class="col-md-4">

										<div class="form-group">
											<label>Email Id<span style="color: red;">*</span></label>
											<input type="email" maxlength="255" name="email" id="email" value="{{ $employee->email }}" class="form-control span11" placeholder="Enter Email"  required=""  autocomplete="off" />
											@if($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
											@endif
										</div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>Addressline1<span style="color: red;">*</span></label>
											<textarea rows="1" cols="20" name="addressline1" id="add" maxlength="255"  wrap="soft" class="form-control" required=""  placeholder="Enter Address" autocomplete="off">{{ $employee->addressline1 }}</textarea>
											@if($errors->has('addressline1'))
											<span class="help-block">
												<strong>{{ $errors->first('addressline1') }}</strong>
											</span>
											@endif
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label>Addressline 2<span style="color: red;"></span></label>
											<textarea rows="1" cols="20" name="addressline2" id="add" maxlength="255"  wrap="soft" class="form-control" placeholder="Enter Address" autocomplete="off">{{ $employee->addressline2 }}</textarea>
											@if($errors->has('addressline2'))
											<span class="help-block">
												<strong>{{ $errors->first('addressline2') }}</strong>
											</span>
											@endif
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>State<span style="color: red;">*</span></label>
											<select name="state" id="state" class="form-control select2" class="span11">
												@if(!empty($state))
												<option selected disabled="" value="">--Please choose an option--</option>
												@foreach($state as $s)
													<option value="{{ $s->stateid }}" @if($s->stateid == $employee->state) selected="" @endif>{{ $s->name }}</option>
												@endforeach
												@else
													<option value="">---No State Available-</option>
												@endif
												
											</select>
											@if($errors->has('state'))
											<span class="help-block">
												<strong>{{ $errors->first('state') }}</strong>
											</span>
											@endif
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>City<span style="color: red;">*</span></label>
											<select name="city" id="city" required="" class="form-control select2" class="span11">
												@if(!empty($cities))
												<option selected disabled="" value="">--Please choose an option--</option>
												@foreach($cities as $cityname)
													<option value="{{ $cityname->id }}" @if($cityname->id == $employee->city) selected="" @endif>{{ $cityname->city }}</option>
												@endforeach

												@else
													<option>--No City Available--</option>
												@endif
											</select>
											@if($errors->has('city'))
											<span class="help-block">
												<strong>{{ $errors->first('city') }}</strong>
											</span>
											@endif
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>Department<span style="color: red;">*</span></label>
											<select class="form-control select2" name="department" required="">
												@if(!empty($department))
													<option value="">--Select Department--</option>
													@foreach($department as $dept)									
													<option value="{{ $dept->departmentid }}" @if($dept->departmentid == $employee->department) selected="" @endif>{{ $dept->departmentname }}</option>
													@endforeach
												@else
												<option>--No Department Available--</option>
												@endif
											</select>
											@if($errors->has('department'))
											<span class="help-block">
												<strong>{{ $errors->first('department') }}</strong>
											</span>
											@endif
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>Salary<span style="color: red;">*</span></label>
											<input type="text" value="{{ $employee->salary }}" class="form-control number" name="salary" placeholder="Enter Salary" class="span8" maxlength="8" required="" autocomplete="off" />
											@if($errors->has('salary'))
											<span class="help-block">
												<strong>{{ $errors->first('salary') }}</strong>
											</span>
											@endif
										</div>
									</div>

									<div class="col-md-4">
												<div class="form-group">
													<label>Birthdate</label>
													<input placeholder="Birthdate" value="{{  $employee->dob }}" type="date" onkeypress="return false" class="form-control" name="dob" requiredclass="span11" max="{{ date('Y-m-d') }}">
												</div>
											</div>

								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<span><label> Shift : From</label></span>
											<input type="time" class="form-control"  name="working_hour_from_1"
											min="9:00 am" max="12:00 pm" value="{{ $employee->workinghourfrom1 }}" required />
											<label>To</label> 
											<input type="time" class="form-control"  name="working_hour_to_1"
											min="9:00 pm" default="09:pm" max="12:00 pm"  value="{{ $employee->workinghourto1 }}"  required />
										</div>
									</div>

									<div class="col-md-8">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Working Hour(Per Day)<span style="color: red;">*</span></label>
													<input type="text" name="workinghour" placeholder="Enter Working Hour" class="form-control number" maxlength="2" max="24" required="" value="{{  $employee->workinghour }}" autocomplete="off">
													@if($errors->has('workinghour'))
													<span class="help-block">
														<strong>{{ $errors->first('workinghour') }}</strong>
													</span>
													@endif
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>Photo</label>
													<input type="file" id="profile-img" name="image" class="form-control"  class="span11" accept="image/jpg, image/jpeg, image/png" />
													@if(!empty($employee->photo)) {{$employee->photo}} <a href="" data-toggle="modal" data-target="#photomodal"><i class="fa fa-eye"></i></a>@endif
												</div>
											</div>

											
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">  
													<label>Gender</label>
													<select name="gender" class="form-control" class="span11" required="">
														<option selected disabled="" value="">--Please choose gender--</option>
														<option value="Male" @if($employee->gender == 'Male') selected="" @endif>Male</option> 
														<option value="Female" @if($employee->gender == 'Female') selected="" @endif>Female</option> 
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>Mobile No<span style="color: red;">*</span></label>
													<input type="text" id="mobileno" autocomplete="off" name="mobileno" value="{{ $employee->mobileno }}" class="form-control number" required placeholder="Enter Mobile no" class="span11" minlength="10" maxlength="10" readonly="" />
													<span id="error_mobileno" class="ajaxerror">Mobileno already exist</span>
													@if($errors->has('mobileno'))
													<span class="help-block">
														<strong>{{ $errors->first('mobileno') }}</strong>
													</span>
													@endif

												</div>
											</div>


											
										</div>

										<div class="row">
											
											<div class="col-md-6">
												<div class="form-group">
													<label>New Password<span style="color: red;"></span></label>
													<span>Note: Minimum 6 characters are required</span>


													<input type="password" autocomplete="off" name="password" class="form-control"   placeholder="Enter Password"class="span11" minlength="6" min="6" />
													@if($errors->has('password'))

													<span class="help-block">
														<strong>{{ $errors->first('password') }}</strong>
													</span>
													@endif
												</div>
											</div>
										</div>
									</div>

								</div>

								<hr style="background: #f39c12">
								<h4>Account Details</h4>

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>Account No<span style="color: red;">*</span></label>
											<input type="text"  name="accountNo" value="{{ $employee->accountno }}" class="form-control number "autocomplete="off" placeholder="Enter Account No"  class="span11" maxlength="16" required="" />
											@if($errors->has('accountNo'))
											<span class="help-block">
												<strong>{{ $errors->first('accountNo') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Account Name<span style="color: red;">*</span></label>
											<input type="text"  name="accountName" id="accountName" value="{{ $employee->accountname }}" class="form-control  " autocomplete="off"placeholder="Enter Account Name"  class="span11" maxlength="255" required="" />
											@if($errors->has('accountName'))
											<span class="help-block">
												<strong>{{ $errors->first('accountName') }}</strong>
											</span>
											@endif
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>IFSC Code<span style="color: red;">*</span></label>
											<input type="text"  name="IFSCcode" value="{{ $employee->ifsccode }}" class="form-control" id="ifcs" autocomplete="off" placeholder="Enter IFSC Code" maxlength="11" minlength="11" required="" />
											@if($errors->has('IFSCcode'))
											<span class="help-block">
												<strong>{{ $errors->first('IFSCcode') }}</strong>
											</span>
											@endif
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>Bank Name<span style="color: red;">*</span></label>
											<input type="text"  name="BankName" value="{{ $employee->bankname }}" class="form-control " autocomplete="off"placeholder="Enter Bank Name"  class="span11" maxlength="255" required="" />
											@if($errors->has('BankName'))
											<span class="help-block">
												<strong>{{ $errors->first('BankName') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Branch Name<span style="color: red;">*</span></label>
											<input type="text"  name="BranchName" value="{{ $employee->branchname }}" class="form-control" autocomplete="off"placeholder="Enter Branch Name"  maxlength="255" class="span11" required="" />
											@if($errors->has('BranchName'))
											<span class="help-block">
												<strong>{{ $errors->first('BranchName') }}</strong>
											</span>
											@endif
										</div>
									</div>
								</div>

								<div class="row">
									<center>
										<button type="submit" id="submit" class="btn btn-success">Submit</button>
										<a href="{{ route('employee') }}" class="btn btn-danger">Cancel</a>
									</center>
								</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@if(!empty($employee->photo))
<div class="modal fade" id="photomodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Employee Photo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
          	
            <center><img src="{{ asset('public/userupload/').'/'.$employee->photo }}" height="200px" width="100px"></center>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>
@endif


@endsection

@push('script')
	
	<script type="text/javascript">
		$(document).ready(function(){

			$('#first_name').change(function(){
				$('#last_name').trigger('change');
			});

			$('#last_name').change(function(){

				let fname = $('#first_name').val();
				let lname = $('#last_name').val();
				let username = fname + lname;
				$('#accountName').val(fname+' '+lname);

			});

			$('#username').on('keypress', function(e) {
				if (e.which == 32)
					return false;
			});

			$('#username').on('keyup', function(){

				let error_username = '';
				let username = $('#username').val();
				let mobileno = $('#mobileno').val();

				$.ajax({
					type : 'POST',
					url : '{{ route('checkuserexist') }}',
					data : {username:username,mobileno:mobileno, '_token' : '{{ csrf_token() }}'},
					success : function(data){
						if(data == 'exist'){
							$('#error_username').show();
							$('#submit').attr('disabled', true);
						}else{
							$('#submit').removeAttr('disabled');
							$('#error_username').hide();

						}
					}
				});

			});

			$('#mobileno').on('input', function(){

				let mobileno = $(this).val();

				$.ajax({
					type : 'POST',
					url : '{{ route('checkmobilenoexist') }}',
					data : {mobileno:mobileno, '_token' : '{{ csrf_token() }}'},
					success : function(data){
						if(data == 'exist'){
							$('#error_mobileno').show();
							$('#submit').attr('disabled', true);
						}else{
							$('#submit').removeAttr('disabled');
							$('#error_mobileno').hide();

						}
					}
				});
			})

			$('#ifcs').keypress(function (e) {
				var regex = new RegExp("^[a-zA-Z0-9]+$");
				var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
				if (regex.test(str)) {
					return true;
				}
				e.preventDefault();
				return false;
			});

			$('#state').change(function(){
				let stateid = $(this).val();
				$('#city').html('');
				if(stateid){
					$.ajax({

						type : 'POST',
						url : '{{ route('getcity') }}',
						data : {stateid:stateid, _token : '{{ csrf_token() }}'},
						success : function(city){
							$('#city').append(city);
						}
					});
				}
			});


		});
	</script>


@endpush