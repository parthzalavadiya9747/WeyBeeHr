@extends('layout.mainlayout') 

@section('title', 'Edit Salary')

@section('content')


        @php

            $year = !empty($year) ? $year : '';
            $month = !empty($month) ? $month : '';
            $employeeid = !empty($employeeid) ? $employeeid : '';
            $i = 0;
            $confirmdate = '';


        @endphp
            @if ($errors->any())
        {{ implode('', $errors->all('<div>:message</div>')) }}
@endif
        <section class="content-header">
         <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('viewemployeeaccount') }}">Salary</a></li>
                <li class="active">Calculate Salary</li>
            </ol>
        </div>
    </div>
</section>


                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Salary Detail #<b>{{ ucfirst($salary->first_name) }} {{ ucfirst($salary->last_name) }}</b></h3>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                            <div class="col-md-8">
                                            <form method="post" class="" action="{{ route('editsalary', $salary->salaryid) }}">
                                                <input type="hidden" name="employeeid" value="{{ $salary->employeeid }}">
                                                <input type="hidden" name="year" value="{{ $salary->year }}">
                                                <input type="hidden" name="Workindays" value="{{ $salary->Workindays }}">
                                                <input type="hidden" name="holidays" value="{{ $salary->holidays }}">
                                                <input type="hidden" name="totalworkinghour" value="{{ $salary->totalworkinghour }}">
                                                <input type="hidden" name="empworkingminute" value="{{ $salary->empworkingminute }}">
                                                <input type="hidden" name="empsalary" value="{{ $salary->empsalary }}">
                                                <input type="hidden" name="givenleave" value="{{ $salary->givenleave }}">
                                                <input type="hidden" name="store" value="1">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Employee Name</label>
                                                            <input type="text" name="empname_display" class="form-control" value="{{ucfirst($salary->employee->first_name) }} {{ ucfirst($salary->employee->last_name)}}" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Mobile No</label>
                                                            <input type="text" name="mobileno_display" class="form-control" value="{{ $salary->employee->mobileno }}" readonly="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Month</label>
                                                            <input type="text" name="month_display" class="form-control" value="{{ $salary->month }}" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label>Year</label>
                                                            <input type="text" name="year_display" class="form-control" value="{{ $salary->year }}" readonly="">
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Total Days</label>
                                                            <input type="text" name="workingdays_display" id="workingdays" class="form-control" value="{{ $salary->workingdays }}" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Holidays</label>
                                                            <input type="text" name="holidays_display" id="holiday" class="form-control number" value="{{ $salary->holidays }}" readonly="">
                                                        </div>
                                                    </div>
                                                    
                                                </div>  

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Actual Working Days</label>
                                                            <input type="text" name="actualdays_display" id="actualdays" class="form-control number" value="{{ $salary->actualdays }}" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Present Days</label>
                                                            <input type="text" name="attenddays_display" oninput="caldays('pday', this.value)" id="attenddays" class="form-control number" value="{{ $salary->attenddays }}" required="" min="1" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Absent Days</label>
                                                            <input type="text" name="takenleave_display" id="takenleave" class="form-control number" oninput="caldays('takenleave', this.value)" value="{{ $salary->takenleave }}" required="" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" style="margin-top: 25px;">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#punch">Punch</button>
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#worktime">Worktime</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Loan Amount</label>
                                                        <input type="text" name="loan" id="loan" class="form-control" value="{{ $salary->loanamount }}" readonly="">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>EMI</label>
                                                        <input type="number" name="emi" class="form-control" max="{{ $salary->loanamount }}" value="{{ $salary->salaryemi }}" min="0" id="emi">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Other Deduction</label>
                                                        <input type="number" name="otheramount" class="form-control" min="0" id="otheramount" max="{{ $salary->currentsalary }}" value="{{ $salary->salaryothercharges }}">
                                                    </div>


                                                </div>
                                                @if($salary->casualleave > 0 || $salary->medicalleave > 0 || $salary->paidleave > 0)
                                                    <div class="row" id="leavereport" style="display: block;">
                                                @else
                                                    <div class="row" id="leavereport" style="display: none;">

                                                @endif
                                                        <div class="col-md-3"></div>
                                                        <div class="col-md-6">
                                                            <table class="table">
                                                                <tr>
                                                                    <th>Leave Type</th>
                                                                    <th>No Of Leave</th>
                                                                </tr>
                                                                <tr>
                                                                    <td>Casual Leave</td>
                                                                    @php $casualleave = !empty($salary->casualleave) ? $salary->casualleave : 0 @endphp
                                                                    <td><input type="no" name="casualleave" id="casualleave" class="form-controller number" min="0" max="31" maxlength="2" autocomplete="off" value="{{ $casualleave }}"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Medical Leave</td>
                                                                    @php $medicalleave = !empty($salary->medicalleave) ? $salary->medicalleave : 0 @endphp
                                                                    <td><input type="no" name="medicalleave" id="medicalleave" class="form-controller number" min="0" max="31" maxlength="2" autocomplete="off" value="{{ $medicalleave }}"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Paid Leave</td>
                                                                    @php $paidleave = !empty($salary->paidleave) ? $salary->paidleave : 0 @endphp
                                                                    <td><input type="no" name="paidleave" id="paidleave" class="form-controller number" min="0" max="31" maxlength="2" autocomplete="off" value="{{ $paidleave }}"></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-3"></div>
                                                    </div>
                                            

                                               

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Monthly Salary</label>
                                                            <input type="text" id="monthlysalary" name="monthlysalary" class="form-control number" value="{{ $salary->empsalary }}" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6"> 
                                                        <div class="form-group">
                                                            <label>Current Monthly Salary</label>
                                                            <input type="text" id="current_salary" name="current_salary" class="form-control number" max="{{ $salary->currentsalary }}" value="{{ $salary->currentsalary }}" autocomplete="off" required="" max="10">
                                                        </div>
                                                    </div>
                                                </div>
                                                <center>
                                                    <div class="form-row" style="margin-top: 35px; margin-left: 15px;">
                                                        <button type="submit" class="btn btn-primary bg-orange" id="submit">Update Salary</button>
                                                        <a href="{{ route('viewsalary') }}" class="btn btn-danger">cancel</a>
                                                    </div>
                                                </center>
                                                 <div class="modal fade" id="worktime" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Worktime</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Monthly Working Hour</label>
                                    <input type="text" name="monthlyworking_hour_display" class="form-control number" value="{{ $salary->empworkinghour }}" readonly="">
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="form-group">
                                    <label>Total Working Hour</label>
                                    <input type="text" name="totalworkinghour_display" id="totalworkinghour" readonly="" class="form-control number" value="{{ $salary->totalhour }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Monthly Working Minute</label>
                                    <input type="text" name="" class="form-control number" value="{{ $salary->empworkingminute }}" readonly="">
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="form-group">
                                    <label>Total Working Minute</label>
                                    <input type="text" id="workingminute" name="workingminute" readonly="" class="form-control number" value="{{ $salary->totalminute }}">
                                </div>
                            </div>
                        </div>
                        @php $timediff = $salary->empworkinghour - $salary->totalhour;  @endphp
                        <div class="row">
                            <center><h3>Time Difference : {{ $timediff }} Hour</h3></center>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
                                            </form>
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
                    <div class="modal fade" id="punch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Punch Record</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;overflow-y:auto;">
                                    <table class="table table-responsive">
                                        <thead>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Checkin</th>
                                            <th>Checkout</th>
                                        </thead>
                                        <tbody>
                                            @if(!empty($employeelog))
                                                @foreach($employeelog as $key => $emplog)
                                                    <tr>
                                                        <?php $count = ++$key; ?>
                                                        <td>{{ $count }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($emplog->punchdate)) }}</td>
                                                        <td>{{ $emplog->checkin }}</td>
                                                        <td>{{ $emplog->checkout }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>No Punch Found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
                </section>
           
@endsection
@push('script')
<script type="text/javascript">

  
    $(document).ready(function(){

        var leavetakencount = {{ $salary->takenleave }};

        if(leavetakencount > 0){
            $('#submit').attr('disabled', 'true');
        }

        $('#employeeid').change(function(){

            let empid = $(this).val();
            if(empid){
               $('#mobileno option[value='+empid+']').prop('selected', true);
           }
       });



    });


    $('#casualleave').on('input', function(){
        //calculatesalary();
        calsal();
    });

    $('#medicalleave').on('input', function(){
        //calculatesalary();
        calsal();
    });

    $('#paidleave').on('input', function(){
        //calculatesalary();
        calsal();
    });

    $('#emi').on('input', function(){
        //calculatesalary();
        calsal();
    });

    $('#otheramount').on('input', function(){
        //calculatesalary();
        calsal();
    });

    $('#takenleave').change(function(){
        $('#casualleave').val(0);
        $('#medicalleave').val(0);
        $('#paidleave').val(0);
    });
   

function caldays(type,val)
{
    let actualdays = $('#actualdays').val();
    let current_salary_disp = {{ $salary->empsalary }};
    let attenddays_disp = {{ $salary->attenddays }};
    let takenleave_disp = {{ $salary->takenleave }};
    let salary = $('#salary').val();
    let workingdays = $('#workingdays').val();
    let current_salary = $('#current_salary').val();
    let perdaysalary = monthlysalary/actualdays;
    let casualleave = $('#casualleave').val();
    let medicalleave = $('#medicalleave').val();
    let paidleave = $('#paidleave').val();
    let emi = $('#emi').val();
    let otheramount = $('#otheramount').val();
    let loanamount = $('#loan').val();
    let totalleave= 0;

    let leftdays = Number(actualdays) - Number(val);
    let totaldays = Number(leftdays) + Number(val);
    $('#casualleave').val('');
    $('#medicalleave').val('');
    $('#paidleave').val('');
    $('#otheramount').val('');

    
    if(leftdays < 0){
        alert('Pease Enter valid days');
        $('#attenddays').val(attenddays_disp);
        $('#takenleave').val(takenleave_disp);
        $('#current_salary').val(current_salary_disp);
        $('#casualleave').val('');
        $('#medicalleave').val('');
        $('#paidleave').val('');

    }else{

        if(type=='takenleave')
        {

            $('#attenddays').val(leftdays);
            calsal();
        }
        else
        {
            $('#takenleave').val(leftdays);
            calsal();
        }

        


    }
   

}

function calsal(){

    let salary = $('#salary').val();
    let workingdays = $('#workingdays').val();
    let current_salary = $('#current_salary').val();
    let current_salary_disp = {{ $salary->empsalary }};
    let attenddays_disp = {{ $salary->attenddays }};
    let takenleave_disp = {{ $salary->takenleave }};
    let empworkinghour = {{ $salary->employee->workinghour }};
    let monthlysalary = $('#monthlysalary').val();
    let attenddays = $('#attenddays').val();
    let totalworkinghour = $('#totalworkinghour').val();
    let takenleave = $('#takenleave').val();
    let casualleave = $('#casualleave').val();
    let medicalleave = $('#medicalleave').val();
    let paidleave = $('#paidleave').val();
    let actualdays = $('#actualdays').val();
    let leavedays_cal  = Number(actualdays) - Number(attenddays); 
    let perdaysalary = monthlysalary/actualdays;
    var holidays = Number($('#holiday').val());
    let emi = $('#emi').val();
    let otheramount = $('#otheramount').val();
    let loanamount = $('#loan').val();
        
    if(!otheramount){
        otheramount = 0;
    }

    if(!emi){
        emi = 0;
    }

    // console.log(perdaysalary);

    let totalleave = Number(casualleave) + Number(medicalleave) + Number(paidleave);
    let commsalary = (Number(attenddays)) * Number(perdaysalary);
    calleave();

    //let totalsalary = Number(attenddays) * Number(perdaysalary);
    //$('#current_salary').val(Number(totalsalary));

    if(Number(attenddays) == 0){
        
        $('#current_salary').val(0);
        $('#emi').val(0);
        $('#otheramount').val(0);

    }else{

    let attendhour = Number(attenddays) * Number(empworkinghour);
    $('#totalworkinghour').val(attendhour);

    let attendminute = Number(attenddays) * Number(empworkinghour) * 60;
    $('#workingminute').val(attendminute);

    if(Number(casualleave) > 0 ){
        
        let totalsalary = Number(casualleave) * Number(perdaysalary);
        commsalary = Number(commsalary) + Number(totalsalary);

    }
    if(Number(medicalleave) > 0){
   
        let totalsalary = Number(medicalleave) * Number(perdaysalary);
        commsalary = Number(commsalary) + Number(totalsalary);

    }


    commsalary=commsalary.toFixed(2);


    if(Number(emi) > Number(loanamount) || Number(emi) > Number(commsalary))
    {
        alert('Please enter valid EMI');
        $('#emi').val('');
        commsalary = commsalary - Number(otheramount);
        $('#current_salary').val(Number(commsalary));

    }else{

        commsalary = commsalary - Number(emi);

        if(Number(otheramount) > Number(commsalary)){
            alert('Please enter valid deduction amount');
            $('#otheramount').val('');
        }else{
            commsalary = commsalary - Number(otheramount);
        }
    }   

    commsalary=commsalary.toFixed(2);
   $('#current_salary').val(Number(commsalary)); 

   if(Number(totalleave) == Number(takenleave)){
        $('#submit').removeAttr('disabled');
   }else{
        $('#submit').attr('disabled', 'true');
   }
   }
      
}
function calleave()
{
    let takenleave = $('#takenleave').val();
    let casualleave = $('#casualleave').val();
    let medicalleave = $('#medicalleave').val();
    let paidleave = $('#paidleave').val();
    let totalleave = Number(casualleave) + Number(medicalleave) + Number(paidleave);

    if(takenleave < totalleave )
    {
        alert('Please enetr valid leave');
        $('#casualleave').val('');
        $('#medicalleave').val('');
        $('#paidleave').val('');
    }
}

</script>
@endpush