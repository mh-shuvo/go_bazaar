<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="author" content="gobazaar.com.bd" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="path" content="{{ url('/') }}" />
        <title>GoBazaar | ঘরে বসে সবই পাই,বাজারে যাওয়ার দরকার নাই</title>

        <meta name="description" content="Bangladesh’s best online shopping destination for grocery, gadgets, electronics, appliances and more." />

        <link rel="canonical" href="{{ url('/') }}" />

        <meta property="og:type" content="Product" />

        <meta property="og:title" content="GoBazaar | ঘরে বসে সবই পাই,বাজারে যাওয়ার দরকার নাই" />

        <meta property="og:description" content="Bangladesh’s best online shopping destination for grocery, gadgets, electronics, appliances and more." />

        <meta property="og:image" content="{{ asset('public/web/img/logo_white.png') }}" />

        <meta property="fb:app_id" content="" />
        <meta
            name="keywords"
            content="buy accessories, buy grocery item, grocery, eletronics, offer, deals, best price in bangladesh, online, shopping ,shop, bangladesh, buy online, store, gobazaar, gobazaar.com.bd, gobazaar shop, brands, cash on delivery, home delivery, price in bangladesh, price in bd, গোবাজার.কম.বিডি"
        />

        <meta property="og:url" content="{{ url()->current() }}" />

        <meta property="og:site_name" content="GOBAZAAR.COM.BD" />

        <!-- Favicon Icon -->
        <link rel="icon" type="image/ico" href="{{ asset('/public/web/img/favicon.ico') }}" />
        <!-- Bootstrap core CSS -->
        <link href="{{ asset('public/web/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
        <!-- Material Design Icons -->
        <link href="{{ asset('public/web/vendor/icons/css/materialdesignicons.min.css') }}" media="all" rel="stylesheet" type="text/css" />

        <!-- Custom styles for this template -->
        <link href="{{ asset('public/web/css/osahan.css') }}" rel="stylesheet" />
        <link href="{{ asset('public/web/css/custom.css') }}" rel="stylesheet" />
        <link href="{{ asset('public/web/css/sweetalert.css') }}" rel="stylesheet" />
        <!-- Owl Carousel -->
        <link rel="stylesheet" href="{{ asset('public/web/vendor/owl-carousel/owl.carousel.css') }}" />
        <link rel="stylesheet" href="{{ asset('public/web/vendor/owl-carousel/owl.theme.css') }}" />

        {{-- for data table --}}
        <link href="{{ asset('public/admin/assets/libs/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" id="app-stylesheet" />
        <link href="{{ asset('public/admin/assets/libs/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <link href="//fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet" />

        @toastr_css
    </head>
    <body>
        <style type="text/css">
            .offer-price {
                font-size: 15px;
            }
            body {
                font-family: "SolaimanLipi", Roboto, sans-serif !important;
            }
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            .h1,
            .h2,
            .h3,
            .h4,
            .h5,
            .h6 {
                font-family: "SolaimanLipi", Roboto, sans-serif !important;
            }
        </style>

        <div class="container">
            <div class="row">
                <div class="col-sm-8 offset-sm-2 border border-info rounded mt-5">
                    <div class="login-modal">
                        <div class="row">
                            <div class="col-lg-6 pad-right-0 d-flex justify-content-center align-items-center">
                                <div class="login-left">
                                    <img src="{{ asset('public/web/img/logo_black.png') }}" alt="" />
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
                                                  <input  name="password"  id="password" type="password" class="form-control" placeholder="********" autocomplete="off" >
                                               </fieldset>
                                               <fieldset class="form-group password_section d-none">
                                                  <label> কনফার্ম পাসওয়ার্ড</label>
                                                  <input name="confirm_password" id="confirm_password" type="password" class="form-control" placeholder="********" autocomplete="off">
                                                  <span class="text-danger" id="customer_password_error"></span>
                                               </fieldset>
                                                <fieldset class="form-group">
                                                    <button type="button" id="submit_btn" class="btn btn-lg btn-secondary btn-block">সাবমিট</button>
                                                    <button type="button" id="verify_btn" class="btn btn-lg btn-secondary btn-block d-none">ভেরিফাই করুন <span id="count_down"></span> </button>
                                                     <button type="button" id="password_change_btn" class="btn btn-lg btn-secondary btn-block d-none">পাসওয়ার্ড পরিবর্তন করুন</button>
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

        <!-- Bootstrap core JavaScript -->
        <script src="{{ asset('public/web/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('public/web/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('public/web/js/custom.js') }}"></script>
        <script src="{{ asset('public/web/js/core.js') }}"></script>
        <script src="{{ asset('public/web/js/sweetalert.min.js') }}"></script>
        
        <script type="text/javascript">
          var x;

          $(document).on('click','#submit_btn',function(){
              let mobile = $("#mobile").val() || 0;
              if(mobile != 0){
                  let mobile_validate = mobile_number_validate(mobile);
                  if(mobile_validate){
                    $.ajax({
                      url:"{{route('customer.forgot_password.otp_send')}}",
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
                      url:'{{ route('customer.forgot_password.otp_verify') }}',
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
            let password = $("#password").val() || 0;
            let c_password = $("#confirm_password").val() || 0;
            let record_id = $("#record_id").val() || 0;
            if(password!=0 && c_password !=0){
              if(password != c_password && record_id !=0){
                alert("পাসওয়ার্ড মিলে নাই"+password+'-'+c_password);
              }
              else{
                $.ajax({
                   url:'{{ route('customer.forgot_password.password_change') }}',
                    method: "POST",
                    data:{
                      password: password,
                      record_id: record_id
                    },
                    success:function(res){
                      if(res.status == 'success'){
                        swal("সফল",res.message,res.status);
                        location.href="{{route('index')}}";
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


        @toastr_js @toastr_render

    </body>
</html>
