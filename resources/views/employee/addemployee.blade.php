@extends('layout.mainlayout')

@section('title', 'Add Employee')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/validate.css') }}">
@endpush

@section('content')
@if ($errors->any())
        {{ implode('', $errors->all('<div>:message</div>')) }}
@endif
	<section class="content-header">
       <div class="row">
	      	<div class="col-md-12">
		      <ol class="breadcrumb">
		        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		        <li><a href="{{ route('employee') }}">Employee</a></li>
		        <li class="active">Add Employee</li>
		      </ol>
	  		</div>
  		</div>
    </section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Add Employee</h3>
						<a href="{{ route('viewemployee') }}" class="btn btn-primary backbuttonheader"><i class="fa fa-arrow-left"></i> Back</a>
					</div>
					<div class="box-body">
						<form  id="user_validation_form" id="emp_form" action="{{ url('employee') }}"  method="post" enctype="multipart/form-data">
							{{ csrf_field() }}
							
								<h4>Employee Details</h4>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>First Name<span style="color: red;">*</span></label>
											<input type="text"  id="first_name" name="first_name" value="{{ old('first_name') }}" class="form-control"placeholder="Enter First name" required=""  maxlength="191" class="span11" autocomplete="off" />
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
											<input type="text"  id="last_name" name="last_name" value="{{ old('last_name') }}" class="form-control"placeholder="Enter Last name" required=""  maxlength="191" class="span11" autocomplete="off" />
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
											<input type="text"  id="username" name="username" value="{{ old('username') }}" class="form-control"placeholder="Enter User name" autocomplete="off" required=""  maxlength="255" />
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
											<select name="role" id="Role_id"  class="form-control"class="span11" required="">
												<option selected disabled=""  value="">--Please choose an option--</option>
												<option @if(old('role') == 'Admin' ) selected="" @endif>Admin</option>
												<option @if(old('role') == 'Employee' ) selected="" @endif>Employee</option>
											</select>
											@if($errors->has('role'))
											<span class="help-block">
												<strong>{{ $errors->first('role') }}</strong>
											</span>
											@endif
										</div>
									</div>


									<div class="col-md-4">

										<div class="form-group">
											<label>Email Id<span style="color: red;">*</span></label>
											<input type="email" maxlength="255" name="email" id="email" value="{{ old('email') }}" class="form-control span11" placeholder="Enter Email"  required=""  autocomplete="off" />
											@if($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
											@endif
										</div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>Addressline 1<span style="color: red;">*</span></label>
											<textarea rows="1" cols="20" name="addressline1" id="add" maxlength="255"  wrap="soft" class="form-control" required=""  placeholder="Enter Address" autocomplete="off">{{ old('addressline1') }}</textarea>
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
											<textarea rows="1" cols="20" name="addressline2" id="add" maxlength="255"  wrap="soft" class="form-control" placeholder="Enter Address" autocomplete="off">{{ old('addressline2') }}</textarea>
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
													<option value="{{ $s->stateid }}">{{ $s->name }}</option>
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
												<option selected disabled="" value="">--Please choose an option--</option>
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
													<option value="{{ $dept->departmentid }}" @if($dept->departmentid == old('department')) selected="" @endif>{{ $dept->departmentname }}</option>
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
											<input type="text" value="{{ old('salary') }}" class="form-control number" name="salary" placeholder="Enter Salary" class="span8" maxlength="8" required="" autocomplete="off" />
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
													<input placeholder="Birthdate" value="{{ old('dob') }}" type="date" on class="form-control" name="dob" requiredclass="span11" max="{{ date('Y-m-d') }}">
												</div>
											</div>

								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<span><label> Shift : From</label></span>
											<input type="time" class="form-control"  name="working_hour_from_1"
											min="9:00 am" max="12:00 pm" @if(!empty(old('working_hour_from_1'))) value="{{ old('working_hour_from_1') }}" @else value="08:00" @endif required />
											<label>To</label> 
											<input type="time" class="form-control"  name="working_hour_to_1"
											min="9:00 pm" default="09:pm" max="12:00 pm" @if(!empty(old('working_hour_to_1'))) value="{{ old('working_hour_to_1') }}" @else value="20:00" @endif required />
										</div>
									</div>

									<div class="col-md-8">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Working Hour(Per Day)<span style="color: red;">*</span></label>
													<input type="text" name="workinghour" placeholder="Enter Working Hour" class="form-control number" maxlength="2" max="24" required="" value="{{ old('workinghour') }}" autocomplete="off">
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
												</div>
											</div>

											
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">  
													<label>Gender<span style="color: red;">*</span></label>
													<select name="gender" class="form-control" class="span11" required="">
														<option selected disabled="" value="">--Please choose gender--</option>
														<option value="Male" @if(old('gender') == 'Male') selected="" @endif>Male</option> 
														<option value="Female"  @if(old('gender') == 'Female') selected="" @endif>Female</option> 
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>Mobile No<span style="color: red;">*</span></label>
													<input type="text" id="mobileno" autocomplete="off" name="mobileno" value="{{ old('mobileno') }}" class="form-control number" required placeholder="Enter Mobile no" class="span11" minlength="10" maxlength="10"  />
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
													<label>Password<span style="color: red;">*</span></label>
													<span>Note: Minimum 6 characters are required</span>


													<input type="text" autocomplete="off" name="password" class="form-control" required  placeholder="Enter Password"class="span11" minlength="6" min="6" />
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
											<input type="text"  name="accountNo" value="{{ old('accountNo') }}" class="form-control number "autocomplete="off" placeholder="Enter Account No"  class="span11" maxlength="20" required="" />
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
											<input type="text"  name="accountName"  id="accountName" value="{{ old('accountName') }}" class="form-control  " autocomplete="off"placeholder="Enter Account Name"  class="span11" maxlength="255"/ required="">
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
											<input type="text"  name="IFSCcode" value="{{ old('IFSCcode') }}" class="form-control" autocomplete="off" placeholder="Enter IFSC Code"  class="span11" maxlength="11" minlength="11" required="" />
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
											<input type="text"  name="BankName" value="{{ old('BankName') }}" class="form-control " autocomplete="off"placeholder="Enter Bank Name"  class="span11" maxlength="255" required="" />
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
											<input type="text"  name="BranchName" value="{{ old('BranchName') }}" class="form-control" autocomplete="off"placeholder="Enter Branch Name"  maxlength="255" class="span11" required="" />
											@if($errors->has('BranchName'))
											<span class="help-block">
												<strong>{{ $errors->first('BranchName') }}</strong>
											</span>
											@endif
										</div>
									</div>
									{{-- <div class="col-md-4">
										<div class="form-group">
											<label>Branch Code<span style="color: red;">*</span></label>
											<input type="text"  name="BranchCode" value="{{ old('BranchCode') }}" class="form-control number" autocomplete="off" maxlength="20" placeholder="Enter Branch Code"  class="span11" required="" />
											@if($errors->has('BranchCode'))
											<span class="help-block">
												<strong>{{ $errors->first('BranchCode') }}</strong>
											</span>
											@endif
										</div>
									</div> --}}
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
				$('#username').val(username);
				$('#accountName').val(fname+' '+lname);
				$('#username').trigger('keyup');

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
						if(data == 201){
							$('#error_username').show();
							$('#submit').attr('disabled', 'true');
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
						if(data == 201){
							$('#error_mobileno').show();
							$('#submit').attr('disabled', true);
						}else{
							$('#submit').removeAttr('disabled');
							$('#error_mobileno').hide();

						}
					}
				});
			})

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