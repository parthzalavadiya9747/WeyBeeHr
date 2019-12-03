@extends('layout.mainlayout') 

@section('title', 'View Salary')
@section('content')



        @php

            $year = !empty($year) ? $year : '';
            $month = !empty($month) ? $month : '';
            $employeeid = !empty($employeeid) ? $employeeid : '';
            $i = 0;
            $confirmdate = '';


        @endphp

        <section class="content-header">
         <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('viewsalary') }}">Salary</a></li>
                <li class="active">View Salary</li>
            </ol>
        </div>
    </div>
</section>
    
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Salary Detail</h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <form method="post" class="form-inline" action="{{ route('searchsalary') }}">
                                                @csrf
                                                <div class="form-group">
                                                 
                                                        <select  class="form-control span11 select2"title="Select Employee" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Employee" required="" name="employeeid" id="employeeid" data-sear>
                                                           @if(!empty($employee))
                                                           <option value="">--Please Select Employee--</option>
                                                           @foreach($employee as $emp)
                                                                <option value="{{ $emp->employeeid }}" @if($emp->employeeid == $employeeid) selected="" @endif>{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
                                                           @endforeach
                                                           @endif
                                                        </select>
                                                   
                                                </div>
                                                <div class="form-group">
                                                   
                                                        <select  class="form-control" name="mobile" id="mobileno" placeholder="Mobileno" disabled="" style="width: 240px !important;">
                                                            <option value="">--Select Mobileno--</option>
                                                           @if(!empty($employee))
                                                           @foreach($employee as $emp)
                                                                <option value="{{ $emp->employeeid }}" @if($emp->employeeid == $employeeid) selected="" @endif>{{ $emp->mobileno }}</option>
                                                           @endforeach
                                                           @endif
                                                        </select>
                                                
                                                </div>
                                                <div class="form-group">
                                                    
                                                        <select  class="form-control span11 select2"title="Select Month" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Month" required="" name="month" id="month" placeholder="Month">
                                                            <option value="">--Select Month--</option>
                                                            <option value='Janaury' @if($month == 'Janaury') selected="" @endif>Janaury</option>
                                                            <option value='February' @if($month == 'February') selected="" @endif>February</option>
                                                            <option value='March' @if($month == 'March') selected="" @endif>March</option>
                                                            <option value='April' @if($month == 'April') selected="" @endif>April</option>
                                                            <option value='May' @if($month == 'May') selected="" @endif>May</option>
                                                            <option value='June' @if($month == 'June') selected="" @endif>June</option>
                                                            <option value='July' @if($month == 'July') selected="" @endif>July</option>
                                                            <option value='August' @if($month == 'August') selected="" @endif>August</option>
                                                            <option value='September' @if($month == 'September') selected="" @endif>September</option>
                                                            <option value='October' @if($month == 'October') selected="" @endif>October</option>
                                                            <option value='November' @if($month == 'November') selected="" @endif>November</option>
                                                            <option value='December' @if($month == 'December') selected="" @endif>December</option>
                                                        </select>
                                                 
                                                </div>
                                                <div class="form-group">
                                                    
                                                        <select  class="form-control span11 select2"title="Select Year" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Year" required="" name="year" data-sear value="{{ $year }}">
                                                            <option value="">--Select year--</option>
                                                            @for($i = 2019; $i<=2030; $i++)
                                                                <option value="{{ $i }}" @if($i == $year) selected="" @endif>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                        @if($errors->has('year'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('year') }}</strong>
                                                      </span>
                                                      @endif
                                                  
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary bg-orange">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                        <br/>
                                        <br/>
                                        <br/>

                                         <div class="col-md-12">
                                            <div class="box-body table-responsive no-padding">
                                            <table class="table table-responsive table-stripped">
                                                <thead>
                                                    <th>Employee Name</th>
                                                    <th>Month</th>
                                                    <th>Year</th>
                                                    <th>Actual Salary</th>
                                                    <th>Salary</th>
                                                    <th>Paid Leave</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($salary))
                                                        @foreach($salary as $salary_data)
                                                            <tr>
                                                                @php
                                                                    $fname = !empty($salary_data->employee->first_name) ? $salary_data->employee->first_name  : '';
                                                                    $lname = !empty($salary_data->employee->last_name) ? $salary_data->employee->last_name  : '';
                                                                    $emp_name = ucfirst($fname).' '.ucfirst($lname);
                                                                @endphp
                                                                <td>{{ $emp_name }}</td>
                                                                <td>{{ $salary_data->month }}</td>
                                                                <td>{{ $salary_data->year }}</td>
                                                                <td>{{ $salary_data->empsalary }}</td>
                                                                <td>{{ $salary_data->currentsalary }}</td>
                                                                <td>{{ $salary_data->paidleave }}</td>
                                                                <td>{{ $salary_data->status }}</td>
                                                                <td>
                                                                    <a href="{{ route('editsalary', $salary_data->salaryid) }}" title="Edit Salary"><i class="fa fa-edit"></i></a>
                                                                    <a href="{{ route('locksalary', $salary_data->salaryid) }}" title="Lock Salary"><i class="fa fa-lock"></i></a>
                                                                </td> 
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5">No data found</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
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