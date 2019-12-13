<!-- jQuery 3 -->
<script src="{{ asset('public/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('public/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('public/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('public/js/adminlte.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('public/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('public/js/demo.js') }}"></script>
<!-- toastr js -->
<script src="{{ asset('public/js/toastr.js') }}"></script>
<!-- common js -->
<script src="{{ asset('public/js/common.js') }}"></script>
<!-- select2 js -->
<script src="{{ asset('public/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

 <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
	@if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    switch(type){
        case 'info':
            toastr.info("{{ Session::get('message') }}");
            break;

        case 'warning':
            toastr.warning("{{ Session::get('message') }}");
            break;

        case 'success':
            toastr.success("{{ Session::get('message') }}");
            break;

        case 'error':
            toastr.error("{{ Session::get('message') }}");
            break;
    }
  @endif
@php
        
    Session::forget('message');



@endphp 

$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

   
  })
    
$('#sync').click(function(){
      $(this).attr('src', '{{ asset('public/img/sync.gif') }}');
      $.ajax({
        type : 'GET',
        url : '{{ url('cronjob') }}',
        success : function(data){
            if(data == 'success'){
              setInterval(function(){
                  $('#sync').attr('src', '{{ asset('public/img/sync.png') }}');
              },10000);
            }
          
        }
      });
    });




 
</script>

@stack('script')
