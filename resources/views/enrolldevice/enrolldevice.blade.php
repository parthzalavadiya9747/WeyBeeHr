@extends('layout.mainlayout') 

@section('title', 'Enroll Device')

@section('content')


        @php

            $year = !empty($year) ? $year : '';

        @endphp
        <div id="loader" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8;display: none;">
            <p style="position: absolute; color: White; top: 50%; left: 45%;">
                <img id="img" src="{{ asset('public/img/signal.gif') }}" style="height: 150px; width: 150px;margin-top: -140px;margin-left: -40px;">
            </p>
        </div>
         <section class="content-header">
           <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('enrolldevice') }}">Enroll Device</a></li>
                <li class="active">Enroll Employee Device</li>
              </ol>
            </div>
            </div>
        </section>
      
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Enroll in Device</h3>
                                </div>

                                <!-- /.box-header -->
                               <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form method="post" class="form-inline">
                                                @csrf
                                                <div class="form-row">
                                                    <div class="col-md-3">
                                                        <select  class="form-control span11 select2"title="Select Employee" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  placeholder="Select Employee" required="" name="employeeid" id="employeeid" data-search="true">
                                                           @if(!empty($employee))
                                                            <option value="">--Please Select Employee--</option>
                                                           @foreach($employee as $emp)
                                                                <option value="{{ $emp->employeeid }}" @if(old('employeeid') == $emp->employeeid) selected="" @endif>{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
                                                           @endforeach
                                                           @else
                                                            <option value="">--No Employee available--</option>
                                                           @endif
                                                        </select>
                                                        <span id="empid_error" class="ajaxerror">Please select Employee</span>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="col-md-3">
                                                        <select  class="form-control" name="mobile" id="mobileno" placeholder="Mobileno" disabled="" style="width: 240px !important;">
                                                            <option value="">--Select Mobileno--</option>
                                                           @if(!empty($employee))
                                                           @foreach($employee as $emp)
                                                                <option value="{{ $emp->employeeid }}" @if(old('employeeid') == $emp->employeeid) selected="" @endif>{{ $emp->mobileno }}</option>
                                                           @endforeach
                                                           @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-row" style="margin-top: 45px; margin-left: 15px;">
                                                    <a  id="submit" class="btn btn-primary">Submit</a>
                                                </div>
                                            </form>
                                        </div>
                                        
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                            <div id="second_stage" style="display: none;">
                                <div class="box">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6"><h4>Employee Contract Term : </h4></div>
                                                        <div class="col-md-6"><h4><span id="contractterm"></span></h4></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>

                                <div class="box" style="">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-12">
                                                    <div class="col-md-3"><a class="btn btn-warning btn btn-block" id="enroll" title="Enroll in Device" data-toggle="modal" data-target="#modal-enroll">Enroll in device</a></div>

                                                    <div class="col-md-3"><a class="btn btn-warning btn btn-block" id="fingerprint" title="Set FingerPrint" disabled>Set FingerPrint</a></div>

                                                    <div class="col-md-3"><a class="btn btn-warning btn btn-block"  title="Extend Contract Term" id="setfingerprint" disabled>Set FingerPrint in Device</a></div>

                                                    <div class="col-md-3"><a class="btn btn-warning btn btn-block" id="extend" title="Extend Contract Term" disabled>Extend Contract Term</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>



                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                

        <!-- enroll model userupload start-->
        <div class="modal fade" id="modal-enroll">
            <div class="modal-dialog">
                <div class="modal-content">
                   <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Enroll Empolyee In Device</h4>
                    </div>
                    <div class="modal-body" style="display:none; text-align:center" id="">
                     <img src="{{ asset('public/img/signal.gif') }}" alt="Loading..." / style="height: 150px; width: 150px;">
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                         <label>Contract Term*</label>
                         <?php 
                         $date = date('Y-m-d'); 
                         $oneyeardate = date('Y-m-d', strtotime($date.' +365 days'));
                         ?>
                         <input type="date" name="contract_term" id="contract_term" class="form-control" value="{{ $oneyeardate }}">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" id="uploadusertbl">
                                    <thead>
                                        <tr>
                                            <th>Device Name</th>
                                            <th>Location</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="close" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- enroll model userupload end-->

        <!-- fingertemplate modal start-->
        <div class="modal fade" id="modal-fingertemplate">
            <div class="modal-dialog">
                <div class="modal-content">
                   <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Set Fingerprint In Device</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            @if(!empty($device))
                                @foreach($device as $key => $device_data)
                                    @if($device_data->main == 1)
                                        <div class="form-group">
                                            <label>Device Name</label>
                                            <input type="text" name="" class="form-control" readonly="" value="{{ $device_data->devicename }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Device Location</label>
                                            <input type="text" name="" class="form-control" readonly="" value="{{ $device_data->location }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="color: red;">Fingertemplate Will be enrolled in above device. Make sure device is live</label>
                                        </div>
                                    @endif
                                    
                                @endforeach
                            @else
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="fingertemplate_save">Set Fingerprint</button>
                        <button type="button" class="btn btn-danger" id="close" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- fingertemplate modal end-->

        <!-- Setfingertemplate modal start-->
        <div class="modal fade" id="modal-setfingertemplate">
            <div class="modal-dialog">
                <div class="modal-content">
                   <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Set Fingerprint in device</h4>
                    </div>
                    <div class="modal-body">
                        
                       
                            <table class="table" id="tbl">
                                <thead>
                                    <tr>
                                        <th>Device Name</th>
                                        <th>Enroll</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                 
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="close" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- Setfingertemplate modal end-->

        <!-- extend contract term modal start-->
        <div class="modal fade" id="modal-extend">
            <div class="modal-dialog">
                <div class="modal-content">
                   <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Extend contract term In Device</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                         <label>Contract Term*</label>
                         <?php 
                         
                         ?>

                        <input type="date" name="contract_term" id="contract_term_disp" class="form-control" required="required">
                        
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="extend_save_device">Save changes</button>
                        <button type="button" class="btn btn-danger" id="close" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- extend contract term modal end-->
    </section>

            
@endsection
@push('script')
<script type="text/javascript">
  
    $(document).ready(function(){

        let device1name = '';
        let device2name = '';
        let device3name = '';
        let device4name = '';

        $('#employeeid').change(function(){
            $('#empid_error').hide();
            $('#second_stage').hide();
            $('#contractterm').text('');
            $('#devicename').text('');
            let empid = $(this).val();
            if(empid){
               $('#mobileno option[value='+empid+']').prop('selected', true);
           }
       });

        $('#submit').click(function(){

            $('#empid_error').hide();
            let empid = $('#employeeid').val();
      
            if(empid){
                $('#empid_error').hide();
            }else{
                $('#empid_error').show();
            }

            if(empid){
                $.ajax({
                    type : 'POST',
                    url : '{{ route('employeedeviceinfo') }}',
                    data : {empid:empid, _token:'{{ csrf_token() }}'},
                    success: function(data){
                        if(data.length != 0){
                            console.log(data);
                            $.ajax({
                                type: 'post',
                                url : '{{ route('devicelist') }}',
                                data : {_token : '{{ csrf_token() }}'},
                                success :  function(devicedata){

                                    $(devicedata).each(function( i ,value){

                                        var length=Object.keys(devicedata).length;
                                        
                                        for(var key=1;key<=length;key++)
                                        {
                                            var vbl='device'+ key;                                    
                                            $('#devicename').append('<li>'+value[vbl]+'</li>');
                                        }
                                       
                                    });
                                },

                            })

                            $('#second_stage').show();

                            let contractterm = data.contract_term;
                            let device1 = data.device1setuser;
                            let device2 = data.device2setuser;
                            let device3 = data.device3setuser;
                            let device4 = data.device4setuser;
                            let enroll = data.enroll;
                            let fingertemplate = data.fingertemplate;

                            if(enroll == 1){
                                
                                $('#fingerprint').removeAttr('disabled');
                                $('#extend').removeAttr('disabled');
                            }

                            if(fingertemplate){
                                $('#fingerprint').attr('disabled', 'true');
                                $('#fingerprint').removeAttr('data-toggle', 'data-target');
                                $('#setfingerprint').removeAttr('disabled');
                            }

                            $('#contractterm').text(contractterm);  
                             
                        }else{
                            $('#second_stage').show();
                            $('#contractterm').text('Not Enrolled Yet');
                        }
                    }
                });
            }
        });

        /////////////////////////////////////////////// enroll_save //////////////////////////////////////////////////////

        $('#enroll_save').click(function(){

            let empid = $('#employeeid').val();
            let contract_term = $('#contract_term').val();
            let enrollflag = 1;
            if(empid && contract_term){
                $.ajax({
                    type : 'POST', 
                    url : '{{ route('empindevice') }}',
                    data : {empid:empid,contract_term:contract_term, enrollflag:enrollflag, _token : '{{ csrf_token() }}'},
                    success : function(data){
                        if(data == 'devicenotfound'){
                            alert('Please add or active device');
                        }else{
                            alert('Employee is uploaded in device.');
                            $('#enroll').attr('disabled', 'true');
                            $('#fingerprint').removeAttr('disabled');
                            $('#extend').removeAttr('disabled');
                            $('#modal-enroll').modal('hide');

                            let date    = new Date(contract_term);
                            let year      = date.getFullYear();
                            let month   = date.getMonth()+1;
                            let day     = date.getDate();

                            let newDate = day + '-' + month + '-' + year;
                            $('#contractterm').text(newDate);

                        }
                    }
                });
            }else{
                alert('Please Select Coontract Term');
            }
        });

        $('#fingertemplate_save').click(function(){

            let empid = $('#employeeid').val();
            /*$('#img').attr('src', '{{ asset('public/img/fingerprocessing.gif') }}');
            $('#img').css('height', '1000px');
            $('#img').css('weight', '1000px');*/
            $('#loader').show();

           
                $.ajax({
                    type : 'POST', 
                    url : '{{ route('enrollfingertemplate') }}',
                    data : {empid:empid, _token : '{{ csrf_token() }}'},
                    success : function(data){
                        $('#loader').hide();
                        if(data == 201){
                            $('#loader').show();
                            setTimeout(function(){
                                $.ajax({
                                type : 'POST',
                                url : '{{ route('getfingertemplate') }}',
                                data : {empid:empid, _token:'{{ csrf_token() }}'},
                                success : function(data1){
                                    $('#loader').hide();
                                    if(data1 == 201){
                                        alert('fingerprint is uploaded');
                                        $('#fingerprint').attr('disabled', 'true');
                                        $('#setfingerprint').removeAttr('disabled');
                                        $('#modal-fingertemplate').modal('hide');
                                        $('#fingerprint').attr('disabled', 'true');
                                        $('#fingerprint').removeAttr('data-toggle', 'data-target');

                                    }else if(data1 == 202){
                                        alert('Fingertemplate not found');
                                        $('#modal-fingertemplate').modal('hide');
                                    }else{
                                        $('#modal-fingertemplate').modal('hide');
                                        alert('Ther is something wrong');
                                    }
                                }
                                });
                            }, 60000);
                        }else if(data == 203){
                            $('#modal-fingertemplate').modal('hide');
                            alert('Please add main device for enroll');
                        }else{
                            $('#modal-fingertemplate').modal('hide');
                            alert('There is something wrong');
                        }
                    }
                });
          
            
        });

       /* $('#getfingerprint').click(function(){
            let empid = $('#employeeid').val();
            $.ajax({
                type : 'POST',
                url : '{{ route('checkfingerprint') }}',
                data : {empid:empid, _token : '{{ csrf_token() }}'},
                success : function(data){
                    if(data == 'upload'){
                        alert('Uploaded');
                    }else if(data == 'notupload'){

                    }else{
                        alert('Device not found');
                    }
                }
            });

        });*/

        $('#setfingerprint').click(function(){

            let empid = $('#employeeid').val();
            $('#modal-setfingertemplate #tbl').find('tbody').html('');

            $.ajax({
                type : 'POST',
                url : '{{ route('fetchdeviceenroll') }}',
                data : {empid:empid, _token : '{{ csrf_token() }}'}, 
                success : function(result){
                    if(result == 211){
                        alert('Please set fingerprint');
                        $('#modal-setfingertemplate').modal('hide');
                    }else{
                        $('#modal-setfingertemplate #tbl').find('tbody').append(result);
                        $('#modal-setfingertemplate').modal('show');
                    }
                }
            });
        });

        $('#enroll').click(function(){

            let empid = $('#employeeid').val();
            $('#modal-enroll #uploadusertbl').find('tbody').html('');

            $.ajax({

                type : 'POST',
                url : '{{ route('getuserdevicelist') }}',
                data : {empid:empid, _token : '{{ csrf_token() }}'},
                success : function(device){
                    $('#modal-enroll #uploadusertbl').find('tbody').append(device);
                }

            });

        });

        $('#fingerprint').click(function(){

            let empid = $('#employeeid').val();

            $.ajax({
                type : 'POST',
                url : '{{ route('checksetuser') }}',
                data : {empid:empid, _token : '{{ csrf_token() }}'}, 
                success : function(checkresult){
                    if(checkresult == 201){
                        $('#modal-fingertemplate').modal('show');
                    }else if(checkresult == 203){
                        $('#modal-fingertemplate').modal('hide');
                        alert('Fingertemplate is already set');
                    }else{
                        $('#modal-fingertemplate').modal('hide');
                        alert('Please enroll user into main device');
                    }
                }
            });


        });

        $('#extend').click(function(){

            let empid = $('#employeeid').val();

            $.ajax({
                type : 'POST',
                url : '{{ route('getcontractdate') }}',
                data : { empid:empid, _token : '{{ csrf_token() }}'},
                success : function(contractdate){
                    //alert(contractdate);
                    if(contractdate){
                        if(contractdate == 203){
                            alert('Please Set user into device');
                        }else{

                            let con = contractdate;
                            let result = con.replace(' ','');
                            $('#contract_term_disp').attr('value',result);
                            $('#contract_term_disp').attr('min',result);
                            $('#modal-extend').modal('show');

                        }
                        
                    }else{
                        alert('Something is Wrong occure');
                    }
                }
            });
        
        });

        $('#extend_save_device').click(function(){
            let empid = $('#employeeid').val();
            let contractdate = $('#contract_term_disp').val();
            if(contractdate){
                $.ajax({
                    type : 'POST',
                    url : '{{ route('setcontractdate') }}',
                    data : { empid:empid, contractdate:contractdate, _token : '{{ csrf_token() }}'},
                    success : function(contractdatesuccess){
                        if(contractdatesuccess == 201){
                            alert('Contract Term is extended');
                            $('#modal-extend').modal('hide');

                            let date    = new Date(contractdate);
                            let year      = date.getFullYear();
                            let month   = date.getMonth()+1;
                            let day     = date.getDate();

                            let newDate = day + '-' + month + '-' + year;
                            $('#contractterm').text(newDate);

                        }else{
                            alert('There is something wrong!');
                        }
                    }
                });
            }else{
                alert('Please enter contractterm');
            }
        });








        /////////////////////////////////////////////// enroll_save //////////////////////////////////////////////////////

    });

    function enroll(deviceid){

        let empid = $('#employeeid').val();

        $.ajax({
            type : 'POST',
            url : '{{ route('setfingerprinteachdevice') }}',
            data : { empid:empid, deviceid:deviceid, _token : '{{ csrf_token() }}'},
            success : function(data2){
                alert(data2);
                if(data2 == 201){
                    alert('Uploaded');
                    $('#enrollfingertemplate'+deviceid).attr('disabled', 'true');
                    $('#enrollfingertemplate'+deviceid).removeAttr('data-toggle', 'data-target');

                    $('#deactive'+deviceid).show();
                    $('#active'+deviceid).hide();
                }else if(data2 == 202){
                    alert('There is something wrong');
                }else{
                    alert('Device not found');
                }
            }
        });
    }

    function setuserintodevice(deviceid){

        let empid = $('#employeeid').val();
        let contract_term = $('#contract_term').val();

        $('#loader').show();

        $.ajax({
            type : 'POST',
            url : '{{ route('empindevice') }}',
            data : { empid:empid, deviceid:deviceid,contract_term:contract_term, _token : '{{ csrf_token() }}'},
            success : function(deviceres){
                $('#loader').hide();
                if(deviceres == 201){
                    alert('enrolled');
                    $('#enroll'+deviceid).attr('disabled', 'true');
                    $('#enroll'+deviceid).text('Enrolled');
                    $('#extend').removeAttr('disabled');

                    $.ajax({
                        type : 'POST',
                        url : '{{ route('checkdevicecount') }}',
                        data : { empid:empid, _token : '{{ csrf_token() }}'},
                        success : function(devicecount){
                            if(devicecount == 1){
                                $('#fingerprint').attr('disabled', 'true');
                                $('#fingerprint').removeAttr('data-toggle', 'data-target');
                            }else{
                                $('#fingerprint').removeAttr('disabled');

                            }
                        }
                    });


                    let date    = new Date(contract_term);
                    let year      = date.getFullYear();
                    let month   = date.getMonth()+1;
                    let day     = date.getDate();
                   

                    let newDate = day + '-' + month + '-' + year;
                    $('#contractterm').text(newDate);
                }else if(deviceres == 202){
                    alert('There is something wrong');
                }else if(deviceres == 203){
                    alert('Please check your device');
                }else{
                    alert('Device not found or not active');
                }
            }
        });

    }

    function uploadfingertemplate(deviceid){

        let empid = $('#employeeid').val();
        $('#loader').show();

        $.ajax({
            type : 'POST',
            url : '{{ route('uploadfingerprint') }}',
            data : { empid:empid, deviceid:deviceid, _token : '{{ csrf_token() }}'},
            success : function(template){
                $('#loader').hide();
                if(template == 201){
                    alert('Fingettemplate is uploaded');
                    $('#enrollintodevice'+deviceid).attr('disabled','true');
                    $('#enrollintodevice'+deviceid).text('Uploaded');
                    $('#active'+deviceid).closest('td').hide();
                    $('#deactive'+deviceid).show();
                }else if(template == 202){
                    alert('There is something wrong.');
                }else if(template == 203){
                    alert('Please check device');
                }else{
                    alert('device not found');
                }
            }
        });

    }

    function deactive(deviceid){

        let empid = $('#employeeid').val();
        $('#loader').show();

        $.ajax({
            type : 'POST',
            url : '{{ route('deactiveuser') }}',
            data : { empid:empid, deviceid:deviceid, _token : '{{ csrf_token() }}'},
            success : function(template){
                $('#loader').hide();
                if(template == 201){
                    alert('Employee is deactivated');
                    $('#deactive'+deviceid).closest('td').hide();
                    $('#active'+deviceid).show();
                    $('#active'+deviceid).closest('td').show();
                }else if(template == 202){
                    alert('There is something wrong.');
                }else if(template == 203){
                    alert('Please check device');
                }else{
                    alert('device not found');
                }
            }
        });

    }

    function active(deviceid){

        let empid = $('#employeeid').val();
        $('#loader').show();

        $.ajax({
            type : 'POST',
            url : '{{ route('activeuser') }}',
            data : { empid:empid, deviceid:deviceid, _token : '{{ csrf_token() }}'},
            success : function(template){
                $('#loader').hide();
                if(template == 201){
                    alert('Employee is activated');
                    $('#active'+deviceid).closest('td').hide();
                    $('#deactive'+deviceid).show();
                    $('#deactive'+deviceid).closest('td').show();
                }else if(template == 202){
                    alert('There is something wrong.');
                }else if(template == 203){
                    alert('Please check device');
                }else{
                    alert('device not found');
                }
            }
        });

    }

</script>
@endpush