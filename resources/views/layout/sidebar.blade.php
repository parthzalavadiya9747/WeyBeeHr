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
          <a href="{{ route('dashboard') }}">
            <i class="fa fa-tachometer" aria-hidden="true"></i> <span>Dashboard</span>
          </a>
        </li>
         <li class="active treeview menu-open">
            <a href="#">
            <img src="{{ asset('public/img/icon/employee.png') }}" style="height: 18px; width: 18px;margin-left: -3px;">&nbsp;&nbsp; <span>Employee</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="{{ route('employee') }}"><i class="fa fa-plus"></i> Add Employee</a></li>
               <li><a href="{{ route('viewemployee') }}"><i class="fa fa-eye"></i> View Employee</a></li>
            </ul>
         </li>
         <li class="active treeview menu-open">
            <a href="#">
            <img src="{{ asset('public/img/icon/department.png') }}" style="height: 18px; width: 18px;margin-left: -3px;">&nbsp;&nbsp; <span>Department</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="{{ route('department') }}"><i class="fa fa-plus"></i> Add Department</a></li>
               <li><a href="{{ route('viewdepartment') }}"><i class="fa fa-eye"></i> View Department</a></li>
            </ul>
         </li>
         <li class="treeview">
            <a href="#">
             <img src="{{ asset('public/img/icon/device.png') }}" style="height: 18px; width: 18px;margin-left: -3px;">&nbsp;&nbsp; <span>Device</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li class="treeview">
                  <a href="#"><i class="fa fa-circle-o"></i> Device
                  <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                  </span>
                  </a>
                  <ul class="treeview-menu">
                     <li><a href="{{ route('adddevice') }}"><i class="fa fa-plus"></i> Add Device</a></li>
                     <li><a href="{{ route('viewdevice') }}"><i class="fa fa-eye"></i> View Device</a></li>
                  </ul>
               </li>
               <li><a href="{{ route('enrolldevice') }}"><i class="fa fa-circle-o"></i> Enroll Device</a></li>
               <li><a href="{{ route('emplog') }}"><i class="fa fa-circle-o"></i> Employee Log</a></li>
            </ul>
         </li>
         <li class="treeview">
            <a href="#">
             <img src="{{ asset('public/img/icon/hr.png') }}" style="height: 22px; width: 22px;margin-left: -3px;">&nbsp;&nbsp; <span>HR</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li class="treeview">
                  <a href="#"><img src="{{ asset('public/img/icon/workinghour.png') }}" style="height: 18px; width: 18px;margin-left: -3px;">&nbsp;&nbsp; Working Days
                  <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                  </span>
                  </a>
                  <ul class="treeview-menu">
                     <li><a href="{{ route('workingdays') }}"><i class="fa fa-plus"></i> Add Working Days</a></li>
                     <li><a href="{{ route('viewworkingdays') }}"><i class="fa fa-eye"></i> View Working Days</a></li>
                  </ul>
               </li>
            
               <li class="treeview">
                  <a href="#"><img src="{{ asset('public/img/icon/leave.png') }}" style="height: 18px; width: 18px;margin-left: -3px;">&nbsp;&nbsp; Leave
                  <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                  </span>
                  </a>
                  <ul class="treeview-menu">
                     <li><a href="{{ route('leave') }}"><i class="fa fa-plus"></i> Add Leave</a></li>
                     <li><a href="{{ route('viewleave') }}"><i class="fa fa-eye"></i> View Leave</a></li>
                  </ul>
               </li>
               <li class="treeview">
               <a href="#">
               <img src="{{ asset('public/img/icon/employeeleave.png') }}" style="height: 23px; width: 23px;margin-left: -3px;">&nbsp;&nbsp;
               <span>
               Employee Leave
               </span>
               <span class="pull-right-container">
               <i class="fa fa-angle-left pull-right"></i>
               </span>
               </a>
               <ul class="treeview-menu ">
                  <li>
                     <a href="{{ route('employeeleave') }}">
                     <i class="fa fa-plus"></i>Add Employee Leave
                     </a>
                  </li>
                  <li>
                     <a href="{{ route('viewemployeeleave') }}">
                     <i class="fa fa-eye"></i>View Employee Leave
                     </a>
                  </li>
               </ul>
            </li>

               <li class="treeview">
                  <a href="#"> <img src="{{ asset('public/img/icon/account.png') }}" style="height: 18px; width: 18px;margin-left: -3px;">&nbsp;&nbsp; Employee Account
                  <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                  </span>
                  </a>
                  <ul class="treeview-menu">
                     <li><a href="{{ route('employeeaccount') }}"><i class="fa fa-plus"></i> Add Amount</a></li>
                     <li><a href="{{ route('viewemployeeaccount') }}"><i class="fa fa-eye"></i> View Amount</a></li>
                  </ul>
               </li>

                <li class="treeview">
                  <a href="#"><img src="{{ asset('public/img/icon/employeelog.png') }}" style="height: 23px; width: 23px;margin-left: -3px;">&nbsp;&nbsp; Employee Log
                  <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                  </span>
                  </a>
                  <ul class="treeview-menu">
                     <li><a href="{{ route('employeelog') }}"><i class="fa fa-plus"></i> View Log</a></li>
                  </ul>
               </li>

               <li class="treeview">
                  <a href="#"><img src="{{ asset('public/img/icon/salary.png') }}" style="height: 18px; width: 18px;margin-left: -3px;">&nbsp;&nbsp; Employee Salary
                  <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                  </span>
                  </a>
                  <ul class="treeview-menu">
                     <li><a href="{{ route('salary') }}"><i class="fa fa-plus"></i> Salary</a></li>
                     <li><a href="{{ route('viewsalary') }}"><i class="fa fa-eye"></i> View Salary</a></li>
                     <li><a href="{{ route('viewlockedsalary') }}"><i class="fa fa-eye"></i> View Locked Salary</a></li>
                  </ul>
               </li>

            </ul>
         </li>
         {{-- <li class="treeview">
            <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Device</span>
            <span class="pull-right-container">
            <span class="label label-primary pull-right">4</span>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
               <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
               <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
               <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
            </ul>
         </li>
         <li>
            <a href="pages/widgets.html">
            <i class="fa fa-th"></i> <span>Widgets</span>
            <span class="pull-right-container">
            <small class="label pull-right bg-green">new</small>
            </span>
            </a>
         </li>
         <li class="treeview">
            <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Charts</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
               <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
               <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
               <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
            </ul>
         </li>
         <li class="treeview">
            <a href="#">
            <i class="fa fa-laptop"></i>
            <span>UI Elements</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
               <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
               <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
               <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
               <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
               <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
            </ul>
         </li>
         <li class="treeview">
            <a href="#">
            <i class="fa fa-edit"></i> <span>Forms</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
               <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
               <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
            </ul>
         </li>
         <li class="treeview">
            <a href="#">
            <i class="fa fa-table"></i> <span>Tables</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
               <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
            </ul>
         </li>
         <li>
            <a href="pages/calendar.html">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <span class="pull-right-container">
            <small class="label pull-right bg-red">3</small>
            <small class="label pull-right bg-blue">17</small>
            </span>
            </a>
         </li>
         <li>
            <a href="pages/mailbox/mailbox.html">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <span class="pull-right-container">
            <small class="label pull-right bg-yellow">12</small>
            <small class="label pull-right bg-green">16</small>
            <small class="label pull-right bg-red">5</small>
            </span>
            </a>
         </li>
         <li class="treeview">
            <a href="#">
            <i class="fa fa-folder"></i> <span>Examples</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
               <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
               <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
               <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
               <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
               <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
               <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
               <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
               <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
            </ul>
         </li>
         <li class="treeview">
            <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
               <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
               <li class="treeview">
                  <a href="#"><i class="fa fa-circle-o"></i> Level One
                  <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                  </span>
                  </a>
                  <ul class="treeview-menu">
                     <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                     <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Level Two
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                           <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                           <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                        </ul>
                     </li>
                  </ul>
               </li>
               <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
            </ul>
         </li>
         <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
         <li class="header">LABELS</li>
         <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
         <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
         <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li> --}}
      </ul>
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