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
	$empid = !empty($query['employeeid']) ? $query['employeeid'] : 0;
	$fromdate = !empty($query['fromdate']) ? $query['fromdate'] : 0;
	$todate = !empty($query['todate']) ? $query['todate'] : 0;
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
						<form method="post" class="form-inline" action="{{ route('emplog') }}">
							@csrf
							<div class="form-group">
								<label>Employee:</label>
								<select  class="form-control span11 select2"title="Select Employee" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  placeholder="Select Employee" required="" name="employeeid" id="employeeid" data-search="true">
									@if(!empty($employee))
									<option value="">--Please Select Employee--</option>
									@foreach($employee as $emp)
									<option value="{{ $emp->employeeid }}" @if($empid == $emp->employeeid) selected="" @endif>{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
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
									<option value="{{ $emp->employeeid }}" @if($empid == $emp->employeeid) selected="" @endif>{{ $emp->mobileno }}</option>
									@endforeach
									@endif
								</select>

							</div>
							<div class="form-group">
								<label>From Date:</label>
								<input type="date" name="fromdate" class="form-control" value="{{ $fromdate }}">

							</div>
							<div class="form-group">
								<label>To Date:</label>
								<input type="date" name="todate" class="form-control" value="{{ $todate }}">

							</div>
							<div class="form-group" >
								<button type="submit" class="btn btn-primary bg-orange" style="margin-top: 25px;">Submit</button>
							</div>
						</form>
					</div>
					<br/>
					<table class="table table-responsive table-hover">
						<thead>
							<tr>
								<th>Name</th>
								<th>Mobile No</th>
								<th>Punchdate</th>
								<th>Punchtime</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($fetchlog))
								@foreach($fetchlog as $log)
								<tr>
									<td>
										<?php 
										$fname = !empty($log->emp->first_name) ? ucfirst($log->emp->first_name) : '';
										$lname = !empty($log->emp->last_name) ? ucfirst($log->emp->last_name) : '';
										?>
										{{ $fname }} {{ $lname }}
									</td>
									<td>{{ $log->emp->mobileno }}</td>
									<td>{{ date('d-m-Y', strtotime($log->date)) }}</td>
									<td>{{ $log->time }}</td>
								</tr>
								@endforeach
							@else
							<tr>
								<td><center>No Data Found</center></td>
							</tr>
							@endif
						</tbody>
					</table>
					@if(!empty($fetchlog))
					@if(isset($query))
					<center>{{ $fetchlog->appends($query)->links() }}</center>
					@else
					<center>{{ $fetchlog->links() }}</center>
					@endif
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

		$('#employeeid').change(function(){

            let empid = $(this).val();
            if(empid){
               $('#mobileno option[value='+empid+']').prop('selected', true);
           }
       });

	});
</script>


@endpush