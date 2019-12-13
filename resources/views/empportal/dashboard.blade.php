@extends('layout.emp_mainlayout')

@section('title', 'Dashboard')

@section('content')
<style type="text/css">
	.checkin{
		padding-left: 10px;
	}	
</style>

	<section class="content-header">
		<h1>
			Dashboard
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-yellow" style="height: 105px !important;">
					<div class="inner">
						<p>Today Log</p>
					</div>
					<div class="icon">
						<i class="fa fa-clock-o" aria-hidden="true"></i>
					</div>
					<div class="checkin">
						<span style="font-size: 18px;">Check In : </span>
						<span style="color: black;">
							
						@if(!empty($min))
							{{ $min }}
						@endif
						</span>
					</div>

					<div class="checkin">
						<span style="font-size: 18px;">Check Out : </span>
						<span style="color: black;">
						@if(!empty($max))
							{{ $max }}
						@endif
						</span>
					</div>
					</i></a>
				</div>
			</div>


		</div>
	</section>

@endsection