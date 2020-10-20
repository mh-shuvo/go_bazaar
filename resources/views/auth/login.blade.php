<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>GoBazaar | ঘরে বসে সবই পাই,বাজারে যাওয়ার দরকার নাই</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Go Bazzar" name="description" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="innovation it" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- Favicon Icon -->
        <link rel="icon" type="image/ico" href="{{ asset('/public/logo/favicon.ico') }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('/public/logo/favicon.ico') }}">
        <!-- App css -->
        <link href="{{ asset('public/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="{{ asset('public/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/admin/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="{{ asset('public/web/css/sweetalert.css') }}" rel="stylesheet" />

    </head>

    <body>
        <div class="account-pages my-5 pt-5">
            <div class="container" id="login_container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center mb-4 mt-3">
                                    <a href="index.html">
                                        <span><img src="{{asset('public/logo/logo_black.png')}}" alt="" height="70"></span>
                                    </a>

                                </div>
                                <form method="POST" action="{{ route('login') }}" class="p-2">
                                @csrf
                                    <div class="form-group">
                                        <label for="username">ইউজারনেম</label>
                                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">পাসওয়ার্ড</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-4 pb-3">
                                        <p id="forget_password" style="cursor: pointer;"> <i class="fa fa-lock"></i> Forget Password ?</p>
                                    </div> 
                                    <div class="mb-3 text-center">
                                        <button class="btn btn-primary btn-block" type="submit"> লগইন </button>
                                    </div>
                                </form>
                            </div>
                            <!-- end card-body -->
                        </div>
                        
                       
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <div class="container forget_password_container d-none">
                <div class="row">
                    <div class="col-sm-8 offset-sm-2 border border-info rounded mt-5">
                        <div class="login-modal">
                            <div class="row">
                                <div class="col-lg-6 pad-right-0 d-flex justify-content-center align-items-center">
                                    <div class="login-left">
                                        <img src="{{ asset('public/logo/logo_black.png') }}" alt="" style="width: 270px;" />
                                    </div>
                                </div>
                                <div class="col-lg-6 pad-left-0">

                                    <div class="login-modal-right">
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="login" role="tabpanel">
                                                <form action="javascript:void(0)" method="post" enctype="multipart/form-data">
                                                    <h5 class="heading-design-h5">পাসওয়ার্ড পরিবর্তন</h5>
                                                    <fieldset class="form-group">
                                                        <label>মোবাইল</label>
                                                        <input type="text" name="mobile" id="mobile" required="" class="form-control" placeholder="মোবাইল" autocomplete="off" />
                                                    </fieldset>
                                                    <fieldset class="form-group verify_section d-none">
                                                        <label>ভেরিফিকেশন কোড</label>
                                                        <input type="text" name="otp_code" id="otp_code" class="form-control" placeholder="ভেরিফিকেশন কোড" autocomplete="off" />
                                                        <input type="hidden" name="record_id" id="record_id">
                                                    </fieldset>
                                                    <fieldset class="form-group password_section d-none">
                                                      <label>পাসওয়ার্ড  </label>
                                                      <input  name="password"  id="s_password" type="password" class="form-control" placeholder="********" autocomplete="off" >
                                                   </fieldset>
                                                   <fieldset class="form-group password_section d-none">
                                                      <label> কনফার্ম পাসওয়ার্ড</label>
                                                      <input name="confirm_password" id="confirm_password" type="password" class="form-control" placeholder="********" autocomplete="off">
                                                      <span class="text-danger" id="supplier_password_error"></span>
                                                   </fieldset>
                                                    <fieldset class="form-group">
                                                        <button type="button" id="submit_btn" class="btn btn-info btn-block">সাবমিট</button>
                                                        <button type="button" id="verify_btn" class="btn btn-success btn-block d-none">ভেরিফাই করুন <span id="count_down"></span> </button>
                                                         <button type="button" id="password_change_btn" class="btn btn-primary btn-block d-none">পাসওয়ার্ড পরিবর্তন করুন</button>
                                                    </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <!-- Vendor js -->
        <script src="{{ asset('public/admin/assets/js/vendor.min.js') }}"></script>

        <!-- App js -->
       <script src="{{ asset('public/admin/assets/js/app.min.js') }}"></script>
         <!-- Bootstrap core JavaScript -->
        <script src="{{ asset('public/web/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('public/web/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('public/web/js/core.js') }}"></script>
        <script src="{{ asset('public/web/js/sweetalert.min.js') }}"></script>

        
        <script type="text/javascript">
        $(document).ready(function(){
             $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                });

            });

          var x;

          $(document).on('click','#forget_password',function(){
            $("#login_container").addClass('d-none');
            $(".forget_password_container").removeClass('d-none');
          });

          $(document).on('click','#submit_btn',function(){
              let mobile = $("#mobile").val() || 0;
              if(mobile != 0){
                  let mobile_validate = mobile_number_validate(mobile);
                  if(mobile_validate){
                    $.ajax({
                      url:"{{route('supplier.forgot_password.otp_send')}}",
                      type: "POST",
                      dataType:"JSON",
                      data:{
                        mobile: mobile
                      },
                      success:function(res){
                        if(res.status == 'success'){
                          $("#submit_btn").addClass('d-none');
                          $(".verify_section").removeClass('d-none');
                          $("#verify_btn").removeClass('d-none');
                          $("#record_id").val(res.record_id);

                          let exipired_time = 60;
                           x = setInterval(function(){
                            $("#count_down").html(exipired_time);
                            exipired_time--;
                            if(exipired_time == 0){
                              clearInterval(x);
                               $("#submit_btn").removeClass('d-none');
                               $(".verify_section").addClass('d-none');
                               $("#verify_btn").addClass('d-none');
                               $("#record_id").val('');
                            }
                          }, 1000);
                        }

                        else{
                           custom_alert('ভুল নম্বর',res.message,'error');
                        }

                      }
                    });
                  }
                  else{
                    custom_alert('ভুল নম্বর','মোবাইল নম্বর সঠিক নয়','error');
                  }
              }
              else{
                custom_alert('আবশ্যক','মোবাইল নম্বর দিন','error');
              }
          });

          
          $(document).on("click","#verify_btn",function(){
              let otp = $("#otp_code").val() || 0;

              if(otp != 0){
                $.ajax({
                      url:'{{ route('supplier.forgot_password.otp_verify') }}',
                      method: "POST",
                      data:{
                        otp: otp
                      },
                      success:function(res){
                        if(res.status == 'success'){
                          $(".verify_section").addClass('d-none');
                          $("#verify_btn").addClass('d-none');

                          $(".password_section").removeClass('d-none');
                          $("#password_change_btn").removeClass('d-none');

                          clearInterval(x);

                        }
                        else{
                          custom_alert('OTP',res.message,res.status);
                        }
                      }
                  });
              }
              else{
                custom_alert('আবশ্যক','OTP কোড দিন','error');
              }
          });

          $(document).on("click","#password_change_btn",function(){
            let password = $("#s_password").val() || 0;
            let c_password = $("#confirm_password").val() || 0;
            let record_id = $("#record_id").val() || 0;
            if(password != 0 && c_password !=0){
              if(password != c_password && record_id !=0){
                alert("পাসওয়ার্ড মিলে নাই"+password+'-'+c_password);
              }
              else{
                $.ajax({
                   url:'{{ route('supplier.forgot_password.password_change') }}',
                    method: "POST",
                    data:{
                      password: password,
                      record_id: record_id
                    },
                    success:function(res){
                      if(res.status == 'success'){
                        swal("সফল",res.message,res.status);
                        location.href="{{route('login')}}";
                      }
                      else{
                        swal("ভুল হয়েছে",res.message,res.status);
                      }
                    }
                });
              }
            }
            else{
               custom_alert('আবশ্যক','পাসওয়ার্ড এবং কনফার্ম পাসওয়ার্ড দিন','error');
            }
          });

          function custom_alert(title,message,status){
             swal(title,message,status);
          }
             function mobile_number_validate(mobile) {
                      // var bd_rgx = /\+?(88)?0?1[56789][0-9]{8}\b/;
                      var bd_rgx = /\+?(88)?0?1[3256789][0-9]{8}\b/;


                      if (mobile.match(bd_rgx)) {

                          return true;

                      } else {

                          return false;
                      }

                  }
        </script>



    </body>

</html>