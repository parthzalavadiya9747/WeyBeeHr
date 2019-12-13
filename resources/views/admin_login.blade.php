<!DOCTYPE html>
<html lang="en">
<head>
  <title>WeyBee Solution</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
 

<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="{{asset('public/css/util.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('public/css/main.css')}}">
  <link rel="stylesheet" href="{{ asset('public/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->




<style type="text/css">
  .alert{
    color: red;
  }
</style>
</head>
<body>

    <!-- /.login-logo -->
  <div class="login-box-body">
    <!-- <p class="login-box-msg">Sign in to start your session</p> -->

  
  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100" style="height: 600px;">



        <form class="login100-form validate-form" action="{{url('loginprocess')}}" method="POST">
          {{csrf_field()}}
          <span class="login100-form-title p-b-26">
            Welcome To 
          </span>
          <span class="login100-form-title p-b-48">
            <img src="{{ asset('public/img/weybeelogo.png') }}" style="height: -1px; width: 230px; "> </span>
          

          <div class="wrap-input100 validate-input" data-validate = "" >
            <input class="input100" type="text" id="username" name="username" placeholder="Username" required="required">
            <span class="focus-input100"></span>
          </div>

          <div class="wrap-input100 validate-input" data-validate="Enter password">
            <span class="btn-show-pass">
              <i class="zmdi zmdi-eye"></i>
            </span>
            <input class="input100" type="password" name="password" placeholder="Password" required="required">
            <span class="focus-input100"></span>
          </div>
          <div class="container-login100-form-btn">
            <div class="wrap-login100-form-btn">
              <div class="login100-form-bgbtn"></div>
              <button class="login100-form-btn" id="login" >
                Login
              </button>
            </div>
          </div>
          @if ($message = Session::get('message'))
        <div class="alert alert-danger alert-block">
          <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
        </div>
        @endif 
          </div>

         <!--  <div class="text-center p-t-115">
            <span class="txt1">
              Don’t have an account?
            </span>

            <a class="txt2" href="#">
              Sign Up
            </a>
          </div> -->
        </form>
      </div>
    </div>
  </div>
</body>
</html>
