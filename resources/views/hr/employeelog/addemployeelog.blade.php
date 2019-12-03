@extends('layout.mainlayout') 

@section('title', 'Add Employee Log')

@section('content')


<section class="content-header">
 <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('viewemployeeaccount') }}">Employee Log</a></li>
        <li class="active">Add Employee Log</li>
    </ol>
</div>
</div>
</section>
     
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Add Punch Log</h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <form action="{{ route('addpunch', $log->emplogid) }}" method="post" id="workingdays">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <label>Employee<span style="color: red;">*</span></label>
                                                        <select  class="form-control" name="employeeid" disabled="">
                                                           @if(!empty($employee))
                                                           @foreach($employee as $emp)
                                                           <option value="{{ $emp->employeeid }}" @if($log->userid == $emp->employeeid) selected="" @endif>{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
                                                           @endforeach
                                                           @endif
                                                        </select>
                                                        @if($errors->has('employeeid'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('employeeid') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Mobile No<span style="color: red;">*</span></label>
                                                        <select  class="form-control" disabled="">
                                                           @if(!empty($employee))
                                                           @foreach($employee as $emp)
                                                           <option value="{{ $emp->employeeid }}" @if($log->userid == $emp->employeeid) selected="" @endif>{{ $emp->mobileno }}</option>
                                                           @endforeach
                                                           @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Punch Time<span style="color: red;">*</span></label>
                                                        <input type="time" name="punchtime" class="form-control" min="{{ date('H:i', strtotime($log->checkin)) }}" max="24:00:00" />
                                                        @if($errors->has('punchtime'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('punchtime') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <p style="color: red;">Punchin time is {{ $log->checkin }}.</p>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary bg-orange">Submit</button>
                                                    <a href="{{ route('viewworkingdays') }}" class="btn btn-danger">Cancel</a>
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
