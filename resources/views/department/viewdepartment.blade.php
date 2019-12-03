@extends('layout.mainlayout')

@section('title', 'View Employee')

@push('css')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">


<link rel="stylesheet" type="text/css" href="{{ asset('css/validate.css') }}">
@endpush

@section('content')
	
	<section class="content-header">
       <div class="row">
	      	<div class="col-md-12">
		      <ol class="breadcrumb">
		        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		        <li><a href="{{ route('viewdepartment') }}">Department</a></li>
		        <li class="active">View Department</li>
		      </ol>
	  		</div>
  		</div>
    </section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">View Department</h3>
						<a href="{{ route('department') }}" class="btn btn-primary backbuttonheader"><i class="fa fa-plus"></i> Add Department</a>
					</div>
					<div class="box-body">
						<table class="table table-responsive table-hover">
							<thead>
								<tr>
									<th>Department Name</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@if(!empty($department))
									@foreach($department as $departmentdata)
									<tr>
										<td>{{ $departmentdata->departmentname }}</td>
										<td>
											@if($departmentdata->status == 1)
												Active
											@else
												Deactive
											@endif
										</td>
										<td>
											<a href="{{ route('updatedept', $departmentdata->departmentid) }}"><i class="fa fa-edit"></i></a>
											@if($departmentdata->status == 1)
												<a href="{{ route('deactivedept', $departmentdata->departmentid) }}"><i class="fa fa-trash trash" ></i></a>
											@else
												<a href="{{ route('activedept', $departmentdata->departmentid) }}"><i class="fa fa-check check"></i></a>

											@endif
										</td>
									</tr>
									@endforeach
								@else
									<tr>
										<td colspan="8"><center>No Data Found</center></td>
									</tr>
								@endif
							</tbody>
						</table>
						@if(!empty($employee))
							<center>{{ $employee->render() }}</center>
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>





@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
	
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

		});
	</script>


@endpush