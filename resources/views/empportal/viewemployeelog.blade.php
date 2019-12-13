@extends('layout.emp_mainlayout') 

@section('title', 'View Employee Log')

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
                <li><a href="{{ route('empdashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="#">Employee Log</a></li>
                <li class="active">View Employee Log</li>
              </ol>
            </div>
            </div>
        </section>
      
        <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Employee Log Detail</h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <div class="form-inline">
                                                @csrf
                                                <div class="form-group">
                                                    
                                                        <select  class="form-control span11 select2"title="Select Employee" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Employee" required="" name="employeeid" id="employeeid" data-sear disabled="">
                                                           @if(!empty($employee))
                                                           <option value="">--Please Select Employee--</option>
                                                           @foreach($employee as $emp)
                                                                <option value="{{ $emp->employeeid }}" @if($emp->employeeid == $empid) selected="" @endif>{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
                                                           @endforeach
                                                           @endif
                                                        </select>
                                               
                                                </div>
                                                <div class="form-group">
                                                   
                                                        <select  class="form-control" name="mobile" id="mobileno" placeholder="Mobileno" disabled="" style="width: 240px !important;">
                                                            <option value="">--Select Mobileno--</option>
                                                           @if(!empty($employee))
                                                           @foreach($employee as $emp)
                                                                <option value="{{ $emp->employeeid }}" @if($emp->employeeid == $empid) selected="" @endif>{{ $emp->mobileno }}</option>
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
                                                    
                                                        <select  class="form-control span11 select2"title="Select Year" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Year" required="" name="year" data-sear value="{{ $year }}" id="year">
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
                                                    <a id="submit" class="btn btn-primary bg-orange">Submit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5"><br/>
                                     @if (!isset($error))
                                    <div class="col-md-12 ">
                                         <div class="box-body table-responsive no-padding">
                                            <table id="emplog" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Check In</th>
                                                        <th>Check Out</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                    {{-- @if(!empty($employeelog))
                                                        @foreach($employeelog as $log)
                                                            <tr>
                                                                <td>{{ date('d-m-Y', strtotime($log->punchdate)) }}</td>
                                                                <td>{{ $log->checkin }}</td>
                                                                @if(!empty($log->checkout))
                                                                    <td>{{ $log->checkout }}</td>
                                                                @else
                                                                    <td><a href="{{ route('addpunch', $log->emplogid) }}" class="btn btn-danger">Miss</a></td>
                                                                @endif
                                                            </tr>
                                                        @endforeach   
                                                    @else
                                                        <tr>
                                                            <td colspan="5">No Data Found</td>
                                                        </tr>
                                                    @endif --}}
                                                </tbody>

                                            </table>
                                        </div>
                                             @if(!empty($employeelog))
                                            <center>{!!  $employeelog->render() !!} </center>
                                            @endif
                                        </div> 
                                        @endif
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


        $('#submit').click(function(){
            $('#emplog').DataTable({
                processing : true,
                serverSide : true,
                destroy : true,
                ajax : {
                    url : '{{ route('searchemployeelogemp') }}', 
                    data : function(d){
                        d.employeeid = $('#employeeid').val(),
                        d.year = $('#year').val(),
                        d.month = $('#month').val()
                    }
                },
                columns: [
                {data: 'punchdate', name: 'punchdate'},
                {data: 'checkin', name: 'checkin'},
                {data: 'checkout', name: 'checkout'},
                ]
            });
        });



    });
</script>
@endpush