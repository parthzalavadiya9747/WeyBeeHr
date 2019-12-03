@extends('layout.mainlayout') 

@section('title', 'View Leave')

@section('content')

    <section class="content-header">
           <div class="row">
            <div class="col-md-12">
              <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="{{ route('viewleave') }}">Leave</a></li>
                <li class="active">View Leave</li>
              </ol>
            </div>
            </div>
        </section>

    <section class="content">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Leave Detail</h3>
                                    <div class="" style="float: right;"><a href="{{ route('leave') }}" class="btn btn-primary bg-orange" title="Add Working days">Add Leave</a></div>
                                </div>

                                <!-- /.box-header -->
                                <div class="box-body">
                                     <div class="row" style="margin-left: 0px !important; ">
                                      
                                          <form method="post" class="form-inline" action="{{ route('searcheleave') }}">
                                            @csrf
                                            <div class="form-group">
                                              {{-- <label>Employee<span style="color: red;">*</span></label> --}}
                                              <select  class="form-control span11 select2"title="Select Employee" data-live-search="true" data-selected-text-format="count"  data-actions-box="true"  data-header="Select Employee" required="" placeholder="Please Select Employee" name="employeeid" id="employeeid" data-sear>
                                               @if(!empty($employee))
                                               <option value="">--Please Select Employee--</option>
                                               @foreach($employee as $emp)
                                               <option value="{{ $emp->employeeid }}">{{ ucfirst($emp->first_name) }} {{ ucfirst($emp->last_name) }}</option>
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
                                             <option value="{{ $emp->employeeid }}"  >{{ $emp->mobileno }}</option>
                                             @endforeach
                                             @endif
                                           </select>
                                           <span id="leave_error" style="color: red;display: none;">Please add employee leave</span>
                                         </div>
                                         <div class="form-group">
                                          <button type="submit" class="btn btn-primary bg-orange">Submit</button>
                                        </div>
                                      </form>
                                      <br/>
                                    <div class="box-body table-responsive no-padding">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>No Of Leave</th>
                                                <th>Expiry Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($Leave))
                                                @foreach($Leave as $Leaves)
                                                    <tr>
                                                        
                                                        <td><?php echo (!empty($Leaves->employeename->first_name) ? ucfirst($Leaves->employeename->first_name) : '') ?> <?php echo (!empty($Leaves->employeename->last_name)) ? ucfirst($Leaves->employeename->last_name) : '' ?></td>
                                                        <td>{{ $Leaves->noofleave }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($Leaves->expirydate)) }}</td>
                                                        <td>
                                                            <a href="{{ route('editleave' , $Leaves->leaveid) }}" title="edit"><i class="fa fa-edit"></i></a>
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
                                    @if(!empty($Leave))
                                    <center>{{ $Leave->render() }}</center>
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



    });
</script>
@endpush
