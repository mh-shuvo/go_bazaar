  <div class="modal fade login-modal-main" id="authentication_modal">
         <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-body">
                  <div class="login-modal">
                     <div class="row">
                        <div class="col-lg-6 pad-right-0 d-flex justify-content-center align-items-center">
                           <div class="login-left">
                                 <img src="{{ asset('public/logo/logo_black.png') }}" alt="">
                           </div>
                        </div>
                        <div class="col-lg-6 pad-left-0">
                           <button type="button" class="close close-top-right" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true"><i class="mdi mdi-close"></i></span>
                           <span class="sr-only">Close</span>
                           </button>

                              <div class="login-modal-right">
                                 <!-- Tab panes -->
                                 <div class="tab-content">
                                  <div class="tab-pane active" id="login" role="tabpanel">
                                    <form action="{{ route('customer.login') }}" method="post" enctype="multipart/form-data">
                                          @csrf
                                       <h5 class="heading-design-h5"> লগইন করুন</h5>
                                       <fieldset class="form-group">
                                          <label>মোবাইল</label>
                                          <input type="text" name="username" id="username" required="" class="form-control" placeholder="মোবাইল">
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <label>পাসওয়ার্ড</label>
                                          <input type="password" name="password" id="password" class="form-control" placeholder="********">
                                          <p><a href="{{route('customer.forgot_password')}}"><i class="mdi mdi-lock"></i>পাসওয়ার্ড ভুলে গিয়েছেন?</a></p>
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <button type="submit" class="btn btn-lg btn-secondary btn-block"> লগইন</button>
                                       </fieldset>
                                    </form>
                                    </div>
                                    <div class="tab-pane" id="register" role="tabpanel">
                                       <form action="{{ route('customer.customer_save') }}" method="post" enctype="multipart/form-data"  onsubmit=" return client_validation()">
                                        @csrf
                                       <h5 class="heading-design-h5">রেজিস্ট্রেশন করুন !</h5>
                                        <fieldset class="form-group">
                                          <label> নাম  </label>
                                          <input name="name" id="customer_name" type="text" class="form-control" placeholder="নাম" >
                                          <span class="text-danger" id="customer_name_error"></span>
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <label>মোবাইল </label>
                                          <input  name="mobile" id="customer_mobile" type="text" class="form-control" placeholder="মোবাইল" >
                                          <span class="text-danger" id="customer_mobile_error"></span>
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <label>পাসওয়ার্ড  </label>
                                          <input  name="password"  id="customer_password" type="password" class="form-control" placeholder="********" >
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <label> কনফার্ম পাসওয়ার্ড</label>
                                          <input name="confirm_password" id="customer_confirm_password" type="password" class="form-control" placeholder="********" >
                                          <span class="text-danger" id="customer_password_error"></span>
                                       </fieldset>
                                       <fieldset class="form-group">
                                          <button type="submit" class="btn btn-lg btn-secondary btn-block">অ্যাকাউন্ট তৈরি করুন </button>
                                       </fieldset>
                                        </form>
                                    </div>
                                 </div>
                                 <div class="clearfix"></div>
                                 <div class="text-center login-footer-tab">
                                    <ul class="nav nav-tabs" role="tablist">
                                       <li class="nav-item">
                                          <a class="nav-link active" data-toggle="tab" href="javascript:void(0)" role="tab"><i class="mdi mdi-lock"></i> লগইন</a>
                                       </li>
                                       <li class="nav-item">
                                          <a class="nav-link" data-toggle="tab" href="#register" role="tab"><i class="mdi mdi-pencil"></i> রেজিস্ট্রেশন</a>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="clearfix"></div>
                              </div>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <script type="text/javascript">


function client_validation(){

      var name = $('#customer_name').val();
      var mobile = $('#customer_mobile').val();
      var password = $('#customer_password').val();
      var confirm_password = $('#customer_confirm_password').val();

      var error_status = true;

      if(name == ''){

          $('#customer_name_error').html('নাম প্রদান করুন');
          error_status = false;

      }else{

         $('#customer_name_error').html('');


      }

      if(password !== confirm_password){

         $('#customer_password_error').html('পাসওয়ার্ড মিল নেই।');
         error_status = false;

      }else{

         $('#customer_password_error').html(' ');
      }

      if(mobile == ''){

          $('#customer_mobile_error').html('মোবাইল নাম্বার দিন');
          error_status = false;

      }else{

          if(mobile.length == 11){

              if(customer_number_validate(mobile)){

                $('#customer_mobile_error').html('');

              }else{

                $('#customer_mobile_error').html('সঠিক নাম্বার দিন');
                error_status = false;
              }

          }else{

              $('#customer_mobile_error').html('সঠিক নাম্বার দিন');
              error_status = false;
          }

      }


      return error_status;
}

//bd phone number validation
function customer_number_validate(mobile) {

  var bd_rgx = /\+?(88)?0?1[3256789][0-9]{8}\b/;

  if (mobile.match(bd_rgx)) {

    return true;

  }else {

    return false;
  }

}


</script>