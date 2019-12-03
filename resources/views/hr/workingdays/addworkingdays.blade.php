@extends('layout.mainlayout') 

@section('title', 'Add Working Days')

@section('content')



        @php
            $year = !empty($year) ? $year : old('year');
            $month = !empty($month) ? $month : old('month');
        @endphp

        <section class="content-header">
           <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('viewworkingdays') }}">Working Days</a></li>
                <li class="active">Add Working Days</li>
              </ol>
            </div>
            </div>
        </section>
    
        <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Add Working Days</h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <form action="{{ route('workingdays') }}" method="post" >
                                                    {{ csrf_field() }}
                                                    <div class="form-group ">
                                                        <label>Year<span style="color: red;">*</span></label>
                                                        <select  class="form-control span11 select2"title="Select Year" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Year" required="" name="year" data-sear value="{{ $year }}" placeholder="Please Select Year" title="Select Year" id="year">
                                                            <option value="">--Select Year--</option>
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
                                                    <div class="form-group ">
                                                        <label>Month<span style="color: red;">*</span></label>
                                                        <select id="month" class="form-control span11 select2"title="Select Month" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Month" required="" name="month" data-sear value="{{ $month }}" id="month">
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
                                                        @if($errors->has('month'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('month') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>
                                                    <div class="form-group ">
                                                        <input type="hidden" name="workingdays" id="workingdays">
                                                        <label>Working Days<span style="color: red;">*</span></label>
                                                        <input type="number" id="workingdays_dis" name="workingdays_dis" value="{{ old('workingdays') }}" class="form-control number" maxlength="2" required="" max="31" min="0" disabled="">
                                                        @if($errors->has('workingdays'))
                                                        <span class="help-block">
                                                          <strong>{{ $errors->first('workingdays') }}</strong>
                                                      </span>
                                                      @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Add Non Working Days</label><br/>
                                                        <br/><br/>
                                                        <span id="nonworkingdate"></span>
                                                        <a class="btn btn-success" title="Add Non workingdays" id="addnonworkingdays">+</a>
                                                        <br/>
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
    var count = 1;
   $(document).ready(function(){

    

    $('#addnonworkingdays').click(function(){
        let month = $('#month').val();
        let year = $('#year').val();
        let cal_month = 0;
        if(!year){
            alert('Please Select Year');
            return;
        }else if(!month){
            alert('Please Select Month');
        }

        if(month == 'Janaury'){
            cal_month = 1;
        }else if(month == 'February'){
            cal_month = 2;
        }else if(month == 'March'){
            cal_month = 3;
        }else if(month == 'April'){
            cal_month = 4;
        }else if(month == 'May'){
            cal_month = 5;
        }else if(month == 'June'){
            cal_month = 6;
        }else if(month == 'July'){
            cal_month = 7;
        }else if(month == 'August'){
            cal_month = 8;
        }else if(month == 'September'){
            cal_month = 9;
        }else if(month == 'October'){
            cal_month = 10;
        }else if(month == 'November'){
            cal_month = 11;
        }else{
            cal_month = 12;
        }

      
        let days = new Date(year, cal_month, 0).getDate();

        if(cal_month < 10){
            cal_month = '0'+cal_month;
        }

        let startdate = year+'-'+cal_month+'-'+'01';
        let enddate = year+'-'+cal_month+'-'+days;


        if(count < 15){
            $('#nonworkingdate').append('<span><table><tr><td><input type="date" id="nonworkingdate'+count+'" name="nonworkingdate[]" class="form-control" style="width : 255px;margin-right:20px;"" min="'+startdate+'" max="'+enddate+'" onchange="checkdate('+count+')"></td><td><a class="" onclick="removedate('+count+')" onkeypress="return false"><i class="fa fa-trash nonworkingdateremove" id="remove'+count+'"></i></a></td></tr></table><br/></span>');
            count++;
        }else{
            alert('Cannot add more than 15 non working days');
        }
    });

    $('#month').change(function(){
        workingdays();
        $('#nonworkingdate').html('');
    });

     $('#year').change(function(){
        workingdays();
        $('#nonworkingdate').html('');
    });

   });

   function workingdays(){

    let month = $('#month').val();
    let year = $('#year').val();
    let cal_month = 0;

    if(month == 'Janaury'){
        cal_month = 1;
    }else if(month == 'February'){
        cal_month = 2;
    }else if(month == 'March'){
        cal_month = 3;
    }else if(month == 'April'){
        cal_month = 4;
    }else if(month == 'May'){
        cal_month = 5;
    }else if(month == 'June'){
        cal_month = 6;
    }else if(month == 'July'){
        cal_month = 7;
    }else if(month == 'August'){
        cal_month = 8;
    }else if(month == 'September'){
        cal_month = 9;
    }else if(month == 'October'){
        cal_month = 10;
    }else if(month == 'November'){
        cal_month = 11;
    }else{
        cal_month = 12;
    }

 
    
    let noofdays = getday(cal_month, year);
  
    $('#workingdays_dis').attr('value', noofdays);
    $('#workingdays').val(noofdays);

   

   }

   function getday(month,year) {
       return new Date(year, month, 0).getDate();
   };

   function removedate(id){
    let removeid = id;
    count--;
    $('#remove'+id).closest('span').remove();
   }

   function checkdate(id){
    let currentdate = $('#nonworkingdate'+id).val();
    for(let i =1; i < count; i++ ){
        let nonworkingdate = $('#nonworkingdate'+i).val();
        if(i != id ){

            if(currentdate == nonworkingdate){
                alert('Please do not enter same date again');
                $('#nonworkingdate'+id).val(' ');
            }
        }
    }
   }
</script>
@endpush