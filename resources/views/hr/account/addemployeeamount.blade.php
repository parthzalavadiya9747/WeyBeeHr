@extends('layout.mainlayout') 

@section('title', 'Add Amount')

@section('content')



        @php
            $amount = !empty($amount) ? $amount : old('amount');
            $type = !empty($type) ? $type : old('type');
            $employeeid = !empty($employeeid) ? $employeeid : old('employeeid');
        @endphp

           <section class="content-header">
           <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('viewemployeeaccount') }}">View Account</a></li>
                <li class="active">Add Amount</li>
              </ol>
            </div>
            </div>
        </section>
       
        <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Add Amount</h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <form action="{{ route('employeeaccount') }}" method="post" id="workingdays">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <label>Empolyee<span style="color: red;">*</span></label>
                                                        <select  class="form-control span11 select2"title="Select Year" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Employee" required="" name="employeeid" id="employeeid" data-sear>
                                                        @if(!empty($employee))
                                                        <option value="">--Select Employee--</option>
                                                        @foreach($employee as $emp)
                                                            <option value="{{ $emp->employeeid }}" @if(old('employeeid') ==  $emp->employeeid) selected="" @endif>{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
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
                                                        <select  class="form-control" name="mobileno" id="mobileno" disabled="" >
                                                        @if(!empty($employee))
                                                            <option value="">--Select Mobileno--</option>
                                                        @foreach($employee as $emp)
                                                            <option value="{{ $emp->employeeid }}"  @if(old('employeeid') ==  $emp->employeeid) selected="" @endif>{{ $emp->mobileno }}</option>
                                                        @endforeach
                                                        @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Type<span style="color: red;">*</span></label>
                                                        <select  class="form-control" name="type" id="type" placeholder="Select type">
                                                            <option value="">--Select Type--</option>
                                                            <option value="Loan" @if($type="Loan") selected="" @endif>Loan</option>
                                                            <option value="EMI" @if($type="EMI") selected="" @endif>EMI</option>
                                                        </select>
                                                        @if($errors->has('type'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('type') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Amount<span style="color: red;">*</span></label>
                                                        <input type="text" name="amount" value="{{ $amount }}" class="form-control number" maxlength="8" required="">
                                                        @if($errors->has('amount'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('amount') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>
                                                    <button type="submit" class="btn btn-primary bg-orange">Submit</button>
                                                    <a href="{{ route('viewemployeeaccount') }}" class="btn btn-danger">Cancel</a>
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
