<aside class="main-sidebar">
   <!-- sidebar: style can be found in sidebar.less -->
   <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
         <div class="pull-left image">
            @php

            $photo = session()->get('photo');
            if(!empty($photo)){
               $img = asset('public/userupload').'/'.$photo;
            }else{
               $img = asset('public/img/avatarplaceholder.png');
            }
            @endphp
               <img src="{{ $img }}" class="img-circle" alt="User Image">
         </div>
         <div class="pull-left info">

            <p>{{ ucfirst(session()->get('logged_firstname')) }} {{ ucfirst(session()->get('logged_lastname')) }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
         </div>
      </div>
      <!-- search form -->
    {{--   <form action="#" method="get" class="sidebar-form">
         <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat">
            <i class="fa fa-search"></i>
            </button>
            </span>
         </div>
      </form> --}}
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
         <li class="header">MAIN NAVIGATION</li>
         <li class="active">
          <a href="{{ route('empdashboard') }}">
            <i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span>
          </a>
        </li>
         <li>
            <a href="{{ route('empprofile') }}">
               <img src="{{ asset('public/img/icon/employee.png') }}" style="height: 18px; width: 18px;margin-left: -3px;">&nbsp;&nbsp;
               <span>View Profile</span>
            </a>
         </li>
         <li>
            <a href="{{ route('emplogemp') }}">
               <img src="{{ asset('public/img/icon/employeelog.png') }}" style="height: 18px; width: 18px;margin-left: -3px;">&nbsp;&nbsp;
               <span>View Log</span>
            </a>
         </li>
   </section>
   <!-- /.sidebar -->
</aside>

@push('script')

<script type="text/javascript">
   var url = window.location;
   // for sidebar menu but not for treeview submenu
   $('ul.sidebar-menu a').filter(function() {
   return this.href == url;
   }).parent().siblings().removeClass('active').end().addClass('active');
   // for treeview which is like a submenu
   $('ul.treeview-menu a').filter(function() {
   return this.href == url;
   }).parentsUntil(".sidebar-menu > .treeview-menu").siblings().removeClass('active menu-open').end().addClass('active menu-open');
</script>

@endpush