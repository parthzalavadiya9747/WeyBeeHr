@extends('layout.mainlayout')

@section('title', 'View Device')

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
		        <li><a href="{{ route('viewdevice') }}">Device</a></li>
		        <li class="active">View Device</li>
		      </ol>
	  		</div>
  		</div>
    </section>

	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">View Device</h3>
						<a href="{{ route('adddevice') }}" class="btn btn-primary backbuttonheader"><i class="fa fa-plus"></i> Add Device</a>
					</div>
					<div class="box-body">
						<div style="overflow-x:auto;">
							<table class="table table-responsive table-hover">
								<thead>
									<tr>
										<th>Device Name</th>
										<th>Ipaddress</th>
										<th>Portno</th>
										<th>Location</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($devices))
										@foreach($devices as $device)
										<tr>
											<td>{{ $device->devicename}}</td>
											<td>{{ $device->ipaddress }}</td>
											<td>{{ $device->portno }}</td>
											<td>{{ $device->location }}</td>
											<td>
												@if($device->status == 1)
													Active
												@else
													Deactive
												@endif
											</td>
											<td>
												<a href="{{ route('updatedevice', $device->deviceinfoid) }}"><i class="fa fa-edit"></i></a>
												@if($device->status == 1)
													<a href="{{ route('deactivedevice', $device->deviceinfoid) }}"><i class="fa fa-trash trash" ></i></a>
												@else
													<a href="{{ route('activedevice', $device->deviceinfoid) }}"><i class="fa fa-check check"></i></a>

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
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>





@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
@endpush