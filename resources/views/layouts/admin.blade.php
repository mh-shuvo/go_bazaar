<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="path" content="{{ url('/') }}">
    <title>GoBazaar | @yield("title")</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Go Bazzar" name="description" />
    <meta content="innovation it" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    {{-- <link rel="shortcut icon" href="assets/images/favicon.ico"> --}}
    <link rel="icon" type="image/ico" href="{{ asset('/public/web/img/favicon.ico') }}">
    <link href="{{ asset('public/admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"
        type="text/css" />
    <!-- App css -->
    <link href="{{ asset('public/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
        id="bootstrap-stylesheet" />
    <link href="{{ asset('public/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/assets/css/app.min.css') }}" rel="stylesheet" type="text/css"
        id="app-stylesheet" />
    {{-- for data table --}}
    <link href="{{ asset('public/admin/assets/libs/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"
        type="text/css" id="app-stylesheet" />
    <link href="{{ asset('public/admin/assets/libs/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" id="app-stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('public/admin/assets/css/dataTables.fontAwesome.css') }}" rel="stylesheet">
    <link href="{{ asset('public/admin/assets/css/responsive.bootstrap.min.css') }}" rel="stylesheet">

    {{-- toastr css --}}
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/toastr.min.css') }}" />

    <!-- Datetime picker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />

    <!-- Vendor js -->
    <script src="{{ asset('public/admin/assets/js/vendor.min.js') }}"></script>


    <style>
        .cancel {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .cancel:hover {
            color: white;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child,
        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child {
            position: absolute;
        }

        .nb-spinner {
            width: 75px;
            height: 75px;
            margin: 0;
            background: transparent;
            border-top: 4px solid #009688;
            border-right: 4px solid transparent;
            border-radius: 50%;
            -webkit-animation: 1s spin linear infinite;
            animation: 1s spin linear infinite;
        }
    </style>
    @yield("css")

</head>

<body>



    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        @include("layouts.includes.admin.topbar")

        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        @include("layouts.includes.admin.left_sidebar")
        <!-- ========== Left Sidebar End ========== -->



        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                @yield("content")

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                2020 &copy; Developed by <a href="https:\\www.innovationit.com.bd">Innovation IT</a>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>
            <!-- end content -->

        </div>
        <!-- END content-page -->

    </div>
    <!-- END wrapper -->

    <div id="LoaderModal" class="modal fade" style="width: auto; left: 50%; top: 50%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="nb-spinner"></div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <script src="{{ asset('public/admin/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/libs/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/libs/morris-js/morris.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/libs/raphael/raphael.min.js') }}"></script>

    <script src="{{ asset('public/admin/assets/js/pages/dashboard.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('public/admin/assets/js/app.min.js') }}"></script>
    {{-- for data table js --}}
    <script src="{{ asset('public/admin/assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/libs/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/custom.js') }}"></script>

    <script src="{{ asset('public/admin/assets/js/toastr.min.js') }}"></script>

    <!-- add sweet alert js & css in footer -->
    {{-- <script src="{{ asset('public/admin/assets/sweetalert/sweetalert.min.js')}}"></script> --}}
    {{-- Date Picker --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js">
    </script>
    <script>
        $(document).ready(function(){

    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    var month = new Date(date.getMonth(), date.getDate());


    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    });

    $('#from_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    });

    $('#to_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    });

    $(".monthpicker").datepicker( {
        format: "mm-yyyy",
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true,
    });

    $('.datepicker').datepicker( 'setDate', today );
    $('.monthpicker').datepicker( 'setDate', today );
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

    });
    //get all_location
function get_location(parent_id, type, target_id) {

$.ajax({

    url: "{{route('get_location')}}",
    type: "POST",
    dataType: "JSON",
    data: {
        parent_id: parent_id,
        type: type,
    },
    success: function (response) {

        if (parent_id != '') {

            if (response.status == 'success') {

                var list = "<option value=''>সিলেক্ট করুন</option>";

                response.data.forEach(function (item) {

                    list += "<option value='" + item.id + "'>" + item.bn_name + "</option>";

                });

                $("#" + target_id).html(list);

            } else {

                $("#" + target_id).html("<option value=''>Not Found</option>");
            }

        } else {

            $("#" + target_id).html("<option value=''>সিলেক্ট</option>");
        }

    }

});

}


    </script>

    <script type="text/javascript">
        $(document).on('submit','#PasswordChangeForm',function(e){
             e.preventDefault();
             var formData = new FormData(this);

            var parameter = {
                'old_password'     : 'required',
                'password'         : 'required',
                'confirm_password' : 'required',
            };
            var validate = validation(parameter);
            if(validate == false){
                    $.ajax({
                        url: url+'/admin/password_changes' ,
                        type: "POST",
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function( response ) {
                           Swal.fire(response.statusText,response.message,response.status);
                           if(response.status == 'success'){
                            $("#password_modal").modal('hide');
                           }
                        }
                    });
            }
            else{
                return false;
            }
        });

    </script>

    {{-- @php
        dd();
    @endphp --}}

    @if (App::environment() == 'production')
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/5f3373981e6c072525607d61/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    @endif

    <script src="{{ asset('public/admin/assets/js/global.js') }}"></script>
    @yield("js")

</html>