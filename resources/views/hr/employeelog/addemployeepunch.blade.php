@extends('layout.mainlayout') 

@section('title', 'Add Employee Log')

@section('content')


<section class="content-header">
 <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('employeelog') }}">Employee Log</a></li>
        <li class="active">Add Employee punch</li>
    </ol>
</div>
</div>
</section>
     
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Add Punch</h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <form action="{{ route('addemppunch') }}" method="post" id="workingdays">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <label>Employee<span style="color: red;">*</span></label>
                                                        <select  class="form-control select2" name="employeeid" id="employeeid">
                                                           @if(!empty($employee))
                                                           <option value="">--Select Employee--</option>
                                                           @foreach($employee as $emp)
                                                           <option value="{{ $emp->employeeid }}">{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
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
                                                        <select  class="form-control" id="mobileno" disabled="">
                                                           @if(!empty($employee))
                                                           <option value="">--Select Employee--</option>
                                                           @foreach($employee as $emp)
                                                           <option value="{{ $emp->employeeid }}">{{ $emp->mobileno }}</option>
                                                           @endforeach
                                                           @endif
                                                        </select>
                                                    </div>
                                                     <div class="form-group">
                                                        <label>Punch Date<span style="color: red;">*</span></label>
                                                        <input type="date" name="punchdate" class="form-control" max={{date('Y-m-d')}} />
                                                        @if($errors->has('punchdate'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('punchdate') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Check In<span style="color: red;">*</span></label>
                                                        <input type="time" name="checkin" class="form-control" max="24:00:00" />
                                                        @if($errors->has('checkin'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('checkin') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>

                                                     <div class="form-group">
                                                        <label>Check Out<span style="color: red;">*</span></label>
                                                        <input type="time" name="checkout" class="form-control" max="24:00:00" />
                                                        @if($errors->has('checkout'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('checkout') }}</strong>
                                                      </span>
                                                      @endif
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
@push('script')
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
