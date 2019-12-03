@extends('layout.mainlayout')
@section('title', 'Add Device')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/validate.css') }}">
@endpush

@section('content')

   <section class="content-header">
       <div class="row">
          <div class="col-md-12">
          <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('viewdevice') }}">Device</a></li>
            <li class="active">Add Device</li>
          </ol>
        </div>
      </div>
    </section>
          <!-- general form elements -->
           <section class="content">
 

  <div class="box">
    <div class="box-header">
      <!-- <a href="{{ url('addterms') }}" class="btn add-new bg-navy"><i class="fa fa-plus"></i>Add New</a> -->

    <?php 
      //$ipaddres = split('.', $device->ipaddress); 
      $ipaddress = explode('.', $device->ipaddress);
     
    ?>
    <h3 class="box-title">Add Device</h3>
    </div>
       <div class="box-body"> <div class="col-lg-3"></div><div class="col-lg-6">
              <form role="form" action="{{url('updatedevice', $device->deviceinfoid)}}" method="post" id="device_form">
                 {{ csrf_field() }}
                <!-- text input -->
                <div class="form-group">

                  <div class="form-group">  
                  <label>Device Ip<span style="color: red;">*</span></label>
                </div>
                <div style="margin-top: -14px;">
                  <div class="form-group col-md-3" style="margin-left: -14px;">
                    <input type="text" class="form-control number" maxlength="3" value="{{ $ipaddress[0] }}" name="deviceip_1" required="">
                    @if($errors->has('deviceip_1'))
                    <span class="help-block">
                      <strong>{{ $errors->first('deviceip_1') }}</strong>
                    </span>
                    @endif
                  </div>
​
                  <div class="form-group col-md-3">
                    <input type="text" class="form-control number" maxlength="3" value="{{ $ipaddress[1] }}" name="deviceip_2" required="">
                    @if($errors->has('deviceip_2'))
                    <span class="help-block">
                      <strong>{{ $errors->first('deviceip_2') }}</strong>
                    </span>
                    @endif
                  </div>
​
                  <div class="form-group col-md-3">
                    <input type="text" class="form-control number" maxlength="3" value="{{ $ipaddress[2] }}" name="deviceip_3" required="">
                    @if($errors->has('deviceip_3'))
                    <span class="help-block">
                      <strong>{{ $errors->first('deviceip_3') }}</strong>
                    </span>
                    @endif
                  </div>
​
                  <div class="form-group col-md-3">
                    <input type="text" class="form-control number" maxlength="3" value="{{ $ipaddress[3] }}" name="deviceip_4" required="">
                    @if($errors->has('deviceip_4'))
                    <span class="help-block">
                      <strong>{{ $errors->first('deviceip_4') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>
​
                <div class="form-group">
                  <label>Device Port<span style="color: red;">*</span></label>
                  <input type="text" class="form-control number" maxlength="4" value="{{ $device->portno }}" required="" name="device_port" placeholder="Enter device port" >
                  @if($errors->has('device_port'))
                  <span class="help-block">
                    <strong>{{ $errors->first('device_port') }}</strong>
                  </span>
                  @endif
                </div>

                 <div class="form-group">
                  <label>Type</label>
                  <select class="form-control" id="dtype" name="dtype">
                    <option value="independent" @if($device->dtype == 'independent') selected="" @endif>Independent</option>
                    <option value="panellitev2" @if($device->dtype == 'panellitev2') selected="" @endif>Panel Lite V2</option>
                  </select>
                </div>
              
                <div class="form-group">
                  <label>Location</label>
                  <textarea rows="2" class="form-control" name="location">{{ $device->location }}</textarea>
                </div>
​
                <div class="form-group">
                  <label>Device Name<span style="color: red;">*</span></label>
                  <input type="text" class="form-control" value="{{ $device->devicename }}" maxlength="200" name="devicename"  placeholder="Enter Device Name" required="">
                  @if($errors->has('devicename'))
                    <span class="help-block">
                      <strong>{{ $errors->first('devicename') }}</strong>
                    </span>
                  @endif
                </div>
                
                
                 <div class="form-group">  
                  <label>Username<span style="color: red;">*</span></label>
                  <input type="text" name="username"  value="{{ $device->username }}" class="form-control" placeholder="Enter Device Username" required="">
                  @if($errors->has('username'))
                    <span class="help-block">
                      <strong>{{ $errors->first('username') }}</strong>
                    </span>
                  @endif
                </div>

                <div class="form-group">  
                  <label>Password<span style="color: red;">*</span></label>
                  <input type="password" name="password" value="{{ $device->password }}" class="form-control" placeholder="Enter Device Password" required="">
                  @if($errors->has('password'))
                    <span class="help-block">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
                </div>
                
                <!-- <div id="reader">
                 <div class="form-group">
                  <label>Reader</label>
                  <select class="form-control" id="readerval" name="reader">
                    <option value="no">No</option>
                    <option value="yes">Yes</option>
                  </select>
                </div>
              </div> -->


​               <div class="row col-md-offset-4">
                <button type="submit" class="btn bg-orange">Submit</button>&nbsp;
                <a href="{{ route('viewdevice') }}" class="btn btn-danger">Cancel</a>
​              </div>

              </form>
            </div>
          </div>
    </div>

    </div>
  </div>

</div></div>
</div>
@endsection