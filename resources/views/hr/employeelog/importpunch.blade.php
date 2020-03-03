@extends('layout.mainlayout') 

@section('title', 'Import Employee Punch')

@section('content')


        @php

            $year = !empty($year) ? $year : '';
            $month = !empty($month) ? $month : '';
            $employeeid = !empty($employeeid) ? $employeeid : '';
            $i = 0;
            $confirmdate = '';


        @endphp
        @if(Session::has('downloadexcel'))
         <meta http-equiv="refresh" content="5;url={{ Session::get('downloadexcel') }}">
        @endif
         <section class="content-header">
           <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('employeelog') }}">Employee Log</a></li>
                <li class="active">import Employee Log</li>
              </ol>
            </div>
            </div>
        </section>
      
        <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Download </h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row mb-5">
                                        <div class="col-md-12">
                                            <div class="form-inline">
                                            <form action="{{ route('downloaddemosheet') }}" onsubmit="return downloadcsv()" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    
                                                        <select  class="form-control span11 select2"title="Select Employee" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Employee" name="employeeid" id="employeeid" data-sear>
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
                                                    
                                                        <select  class="form-control span11 select2"title="Select Month" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Month"  name="month" id="month" placeholder="Month">
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
                                                    
                                                        <select  class="form-control span11 select2"title="Select Year" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Year" name="year" data-sear value="{{ $year }}" id="year">
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
                                                    <button type="submit" id="download" class="btn btn-primary bg-orange">Download</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            
                                   <form method="post" onsubmit="return chekfile()" id="uploadcsvform" enctype="multipart/form-data" action="{{ route('importemppunchcsv') }}" style="margin-top: 20px;">
                                    @csrf
                                    <div class="row mb-5">
                                    <div class="col-md-2"></div>
                                        <div class="col-md-8">
                                            
                                                <div class="form-group">
                                                    <label>Upload File<sapn style="color: red;">*</sapn></label>
                                                    <input type="file" name="file" id="file" class="form-control" accept=".csv">
                                                </div>
                                                @if($errors->has('file'))
                                                <span class="help-block">
                                                  <strong>{{ $errors->first('file') }}</strong>
                                              </span>
                                              @endif
                                        </div>
                                    </div>
                                    

                                    <div class="row mb-5">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-8">
                                            <button id="uploadcsv" class="btn btn-success" style="margin-bottom: 10px;">Submit</button>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </form>
                            </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

    function chekfile(){


        var filename = $('#file').val();
        if(filename){
            $('#uploadcsv').attr('disabled', 'true');
            return true;
        }else{
            $('#uploadcsv').removeAttr('disabled');
            alert('Please upload csv file');
            return false;
        }
    }

    function downloadcsv(){

        var emp = $('#employeeid').val();
        var month = $('#month').val();
        var year = $('#year').val();

        if(!emp){
            alert('Please select Employee');
            return false;
        }else if(!month){
            alert('Please select Month');
            return false;
        }else if(!year){
            alert('Please select Year');
            return false;
        }else{
            $('#download').attr('disabled', 'true');
            return true;
        }

    }


</script>
@endpush