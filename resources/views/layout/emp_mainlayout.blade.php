<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" href="{{ asset('public/img/Hygiene_Luzon_logo.png') }}" type="image/x-icon" />
    <!-- include css -->
  @include('layout.css')
</head>
<body class="hold-transition skin-blue sidebar-mini">


<div class="wrapper">



  <!-- include header -->
  @include('layout.header')

  <!-- include sidebar -->
  @include('layout.emp_sidebar')

  <div class="content-wrapper">
    @yield('content')
  </div>

   <!-- include sidebar -->
  @include('layout.footer')

   <!-- include sidebar -->
  @include('layout.script')

  @include('common.notification')


</div>
</body>
</html>