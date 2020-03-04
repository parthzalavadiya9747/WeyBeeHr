@extends('layout.mainlayout') 

@section('title', 'View Employee Leave')

@section('content')


        @php

            $employeeid = !empty($employeeid) ? $employeeid : '';

        @endphp

        <section class="content-header">
           <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('viewemployeeleave') }}">Employee Leave</a></li>
                <li class="active">Edit Employee Leave</li>
            </ol>
        </div>
    </div>
</section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Employee Leave Detail</h3>
                                    <div class="" style="float: right;"><a href="{{ route('employeeleave') }}" class="btn btn-primary bg-orange" title="Add Working days">Add Employee Leave</a></div>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row" style="margin-left: 0px !important; ">
                                      
                                          <form method="post" class="form-inline" action="{{ route('searchemployeeleave') }}">
                                            @csrf
                                            <div class="form-group">
                                              {{-- <label>Employee<span style="color: red;">*</span></label> --}}
                                              <select  class="form-control span11 select2"title="Select Employee" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Employee" required="" placeholder="Please Select Employee" name="employeeid" id="employeeid" data-sear>
                                               @if(!empty($employee))
                                               <option value="">--Please Select Employee--</option>
                                               @foreach($employee as $emp)
                                               <option value="{{ $emp->employeeid }}" @if($employeeid == $emp->employeeid) selected="" @endif>{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
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
                                           {{--  <label>Mobile No<span style="color: red;">*</span></label> --}}
                                           <select  class="form-control" disabled="" id="mobileno">
                                             @if(!empty($employee))
                                             <option value="">--Select Employee--</option>
                                             @foreach($employee as $emp)
                                             <option value="{{ $emp->employeeid }}"  @if($employeeid == $emp->employeeid) selected="" @endif>{{ $emp->mobileno }}</option>
                                             @endforeach
                                             @endif
                                           </select>
                                           <span id="leave_error" style="color: red;display: none;">Please add employee leave</span>
                                         </div>
                                         <div class="form-group">
                                          <button type="submit" class="btn btn-primary bg-orange">Submit</button>
                                        </div>
                                      </form>
                                       
                                    </div>
                                    <br/>
                                     <div class="box-body table-responsive no-padding">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Leave Type</th>
                                                <th>Reason</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($employeeleave))
                                                @foreach($employeeleave as $key => $employee)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        @php

                                                          $fname = !empty($employee->empname->first_name) ? $employee->empname->first_name : '';
                                                          $lname = !empty($employee->empname->last_name) ? $employee->empname->last_name : '';

                                                        @endphp
                                                        <td>{{ $fname }} {{ $lname }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($employee->date)) }}</td>
                                                        <td>
                                                          @if($employee->leavetype == 'CHl')
                                                            Half Casual Leave
                                                          @elseif($employee->leavetype == 'Cl')
                                                            Casual Leave
                                                          @elseif($employee->leavetype == 'Ml')
                                                            Medical Leave
                                                          @elseif($employee->leavetype == 'Pl')
                                                            Paid Leave
                                                          @elseif($employee->leavetype == 'Other')
                                                            Other
                                                          @endif
                                                        </td>
                                                        <td>{{ $employee->reason }}</td>
                                                        <td>
                                                            <a href="{{ route('editemployeeleave', $employee->employeeleaveid) }}" title="edit"><i class="fa fa-edit"></i></a>
                                                            <a href="{{ route('deleteemployeeleave', $employee->employeeleaveid) }}" title="edit"><i class="fa fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5">No Data Found</td>
                                                </tr>
                                            @endif
                                        </tbody>

                                    </table>
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