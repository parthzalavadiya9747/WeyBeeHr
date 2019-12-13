@extends('layout.mainlayout')

@section('title', 'View Employee')

@push('css')

 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">


<style type="text/css">
	.form-group{
		padding: 15px;
	}
	label {
		display:block;
	}
</style>
@endpush

@section('content')

<section class="content-header">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="{{ route('emplog') }}">Log</a></li>
				<li class="active">View Employee Log</li>
			</ol>
		</div>
	</div>
</section>
<?php 
	
?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">View Employee Log</h3>
				</div>
				<div class="box-body">
					<div class="col-md-12">
						<div  class="form-inline">
							@csrf
							<div class="form-group">
								<label>Employee:</label>
								<select  class="form-control span11 select2"title="Select Employee" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  placeholder="Select Employee" required="" name="employeeid" id="employeeid" data-search="true">
									@if(!empty($employee))
									<option value="">--Please Select Employee--</option>
									@foreach($employee as $emp)
									<option value="{{ $emp->employeeid }}">{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
									@endforeach
									@else
									<option value="">--No Employee available--</option>
									@endif
								</select>

							</div>
							<div class="form-group">
								<label>Mobile No:</label>
								<select  class="form-control" name="mobile" id="mobileno" placeholder="Mobileno" disabled="" style="width: 240px !important;">
									<option value="">--Select Mobileno--</option>
									@if(!empty($employee))
									@foreach($employee as $emp)
									<option value="{{ $emp->employeeid }}" >{{ $emp->mobileno }}</option>
									@endforeach
									@endif
								</select>

							</div>
							<div class="form-group">
								<label>From Date:</label>
								<input type="date" name="fromdate" id="fromdate" class="form-control" >

							</div>
							<div class="form-group">
								<label>To Date:</label>
								<input type="date" name="todate" id="todate" class="form-control">

							</div>
							<div class="form-group" >
								<button id="submit" class="btn btn-primary bg-orange" style="margin-top: 25px;">Submit</button>
							</div>
						</div>
					</div>
					<br/>
					<div class="row">
						
						<div class="col-md-12">
							<table class="table table-responsive table-hover" id="emp_table">
								<thead>
									<tr>
										<th>Name</th>
										<th>Mobile No</th>
										<th>Punchdate</th>
										<th>Punchtime</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
						
					</div>
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

		$('#employeeid').change(function(){

            let empid = $(this).val();
            if(empid){
               $('#mobileno option[value='+empid+']').prop('selected', true);
           }
       });

		$('#submit').click(function(){
			$('#emp_table').DataTable({
				processing: true,
				serverSide: true,
				destroy: true,
				ajax: {
					url: "{{ route('emplogajax') }}",
					data : function(d){
						d.employeeid = $('#employeeid').val(),
						d.fromdate = $('#fromdate').val(),
						d.todate = $('#todate').val()
					}
				},
				columns: [
				{data: 'fullname', name: 'fullname'},
				{data: 'mobileno', name: 'mobileno'},
				{data: 'date', name: 'date'},
				{data: 'time', name: 'time'},
				]
			});
		});





	});
</script>


@endpush