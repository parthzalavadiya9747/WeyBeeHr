@extends('layout.mainlayout') 

@section('title', 'View Account')

@section('content')



        @php


        $employeeid = !empty($empid) ? $empid : '';
      

        @endphp

         <section class="content-header">
           <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('viewemployeeaccount') }}">View Account</a></li>
                <li class="active">View Account</li>
              </ol>
            </div>
            </div>
        </section>

  
        <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Account Detail</h3>
                                    <div class="" style="float: right;"><a href="{{ route('employeeaccount') }}" class="btn btn-primary bg-orange" title="Add Working days">Add Amount</a></div>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row" style="margin-left: 0px !important;">
                                    <form method="post" class="form-inline" action="{{ route('searchemployeeaccount') }}">
                                    @csrf
                                        <div class="form-group">
                                            
                                                <select  class="form-control span11 select2"title="Select Employee" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Employee" required="" name="employeeid" id="employeeid" data-sear>
                                                 @if(!empty($employee))
                                                 <option value="">--Select Employee--</option>
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
                                        <div class="form-group" >
                                            <button type="submit" class="btn btn-primary bg-orange">Submit</button>
                                        </div>
                                    </form>
                                </div><br/>
                                    <div class="row" style="margin-left: 0px !important;margin-right: 0px !important;">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($account))
                                                @foreach($account as $accountdata)
                                                    <tr>
                                                        
                                                        <td><?php echo (!empty($accountdata->employeename->first_name) ? ucfirst($accountdata->employeename->first_name) : '') ?> <?php echo (!empty($accountdata->employeename->last_name)) ? ucfirst($accountdata->employeename->last_name) : '' ?></td>
                                                        <td>{{ ucfirst($accountdata->type) }}</td>
                                                        <td>{{ $accountdata->amount }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($accountdata->empaccountdate)) }}</td>
                                                        <td>
                                                            {{-- <a href="{{ route('editleave' , $accountdata->empaccountid) }}" title="edit"><i class="fa fa-edit"></i></a> --}}
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
                                    @if(!empty($account))
                                        <center>{{ $account->render() }}</center>
                                    @endif
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
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
</script>
@endpush

