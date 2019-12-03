@extends('layout.mainlayout')

@section('title', 'Dashboard')

@section('content')
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
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3>{{ $emp_count }}</h3>

						<p>Employee</p>
					</div>
					<div class="icon">
						<i class="ion ion-person-add"></i>
					</div>
					<a href="{{ route('viewemployee') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>

			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-green">
					<div class="inner">
						<h3>{{ $deviceuser_count }}</h3>

						<p>Device User</p>
					</div>
					<div class="icon">
						<i class="fa fa-signal"></i>
					</div>
					<a href="#" class="small-box-footer">
						More info <i class="fa fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>

			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3>{{ $salary_count }}</h3>

						<p>Salary</p>
					</div>
					<div class="icon">
						<i class="fa fa-money"></i>
					</div>
					<a href="{{ route('viewsalary') }}" class="small-box-footer">
						More info <i class="fa fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>

			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ $paidsalary_count }}</h3>

						<p>Paid Salary</p>
					</div>
					<div class="icon">
						<i class="fa fa-lock"></i>
					</div>
					<a href="{{ route('viewlockedsalary') }}" class="small-box-footer">
						More info <i class="fa fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>

			

		</div>
	</section>

@endsection