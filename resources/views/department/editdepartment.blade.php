@extends('layout.mainlayout')

@section('title', 'Edit Department')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/validate.css') }}">
@endpush

@section('content')
	
	<section class="content-header">
       <div class="row">
	      	<div class="col-md-12">
		      <ol class="breadcrumb">
		        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		        <li><a href="{{ route('viewdepartment') }}">Department</a></li>
		        <li class="active">Edit Department</li>
		      </ol>
	  		</div>
  		</div>
    </section>

	<section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Edit Department Name</h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <form action="{{ route('updatedept', $department->departmentid) }}" method="post" >
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                    	<div class="form-group">
                                                    		<label>Department Name<span style="color: red;">*</span></label>
                                                    		<input type="text"  id="departmentname" name="departmentname" value="{{ $department->departmentname }}" class="form-control"placeholder="Enter Department Name" required=""  maxlength="255" class="span11" autocomplete="off" />
                                                    		@if($errors->has('departmentname'))
                                                    		<span class="help-block">
                                                    			<strong>{{ $errors->first('departmentname') }}</strong>
                                                    		</span>
                                                    		@endif
                                                    	</div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary bg-orange">Update</button>
                                                    <a href="{{ route('viewdepartment') }}" class="btn btn-danger">Cancel</a>
                                                </form> 
                                            </div>
                                            <div class="col-md-3"></div>
                                        </div>                                   
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
        </section>
@endsection


									