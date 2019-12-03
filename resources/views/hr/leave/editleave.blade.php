@extends('layout.mainlayout') 

@section('title', 'Edit Leave')

@section('content')

         <section class="content-header">
           <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('viewleave') }}">Leave</a></li>
                <li class="active">Edit Leave</li>
              </ol>
            </div>
            </div>
        </section>

        @php
            $employeeid = !empty($Leave_obj->employeeid) ? $Leave_obj->employeeid : old('employeeid');
            $noofleave = !empty($Leave_obj->noofleave) ? $Leave_obj->noofleave : old('noofleave');
            $expirydate = !empty($Leave_obj->expirydate) ? $Leave_obj->expirydate : old('expirydate');
        @endphp
       
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Edit Leave</h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                               <form action="{{ route('editleave', $Leave_obj->leaveid) }}" method="post" id="workingdays">
                                                    {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <label>Empolyee<span style="color: red;">*</span></label>
                                                        <select  class="form-control span11 select2"title="Select Year" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Year" required="" name="employeeid" id="employee" data-sear>
                                                        @if(!empty($employee))
                                                        @foreach($employee as $emp)
                                                            <option value="{{ $emp->employeeid }}" @if($employeeid ==  $emp->employeeid) selected="" @endif>{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
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
                                                            <option value="{{ $emp->employeeid }}"  @if($employeeid ==  $emp->employeeid) selected="" @endif>{{ $emp->mobileno }}</option>
                                                        @endforeach
                                                        @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>No Of Leave<span style="color: red;">*</span></label>
                                                        <input type="text" name="noofleave" value="{{ $noofleave }}" class="form-control number" maxlength="2" required="">
                                                        @if($errors->has('noofleave'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('noofleave') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>
                                                    <div class="form-group ">
                                                        <label>Expiry Date<span style="color: red;">*</span></label>
                                                        <input type="date" name="expirydate" value="{{ $expirydate }}" class="form-control" onkeypress="return false" required="" min="{{ date('Y-m-d') }}">
                                                        @if($errors->has('expirydate'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('expirydate') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>
                                                    <button type="submit" class="btn btn-primary bg-orange">Submit</button>
                                                    <a href="{{ route('viewleave') }}" class="btn btn-danger">Cancel</a>
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
   
</script>
@endpush