@extends("layouts.web")
@section("title","যোগাযোগ")
@section("content")
<!-- Inner Header -->
<section class="section-padding bg-dark inner-header">
    <div class="container">
       <div class="row">
          <div class="col-md-12 text-center">
             <h1 class="mt-0 mb-3 text-white">যোগাযোগ</h1>
             <div class="breadcrumbs">
                <p class="mb-0 text-white"><a class="text-white" href="javascript:void(0)">হোম</a>  /  <span class="text-success">যোগাযোগ</span></p>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- End Inner Header -->
 <!-- Contact Us -->
 <section class="section-padding">
    <div class="container">
       <div class="row">
          <div class="col-lg-4 col-md-4">
             {{-- <h3 class="mt-1 mb-5">Get In Touch</h3> --}}
             <h6 class="text-dark"><i class="mdi mdi-home-map-marker"></i> ঠিকানা :</h6>
             
             <p><b>Head Office :</b> House# 1/10, Mayer anchal Building, Block-A, Lalmatia, Dhaka - 1207, Bangladesh</p>

             <p><b>Branch Office :</b> Niloy Society, West Bagichagaon, Station Road, Cumilla-3500, Bangladesh</p>

             <h6 class="text-dark"><i class="mdi mdi-phone"></i> মোবাইল :</h6>
             
             <p>+880 1714-049013</p>
             <p>+880 1714-049026</p>
             <p>+880 1310-027292</p>
             <p>+880 1319-081656</p>

             <h6 class="text-dark"><i class="mdi mdi-email"></i> ইমেইল :</h6>
             <p>support@gobazar.com.bd</p>
             <p>info@gobazar.com.bd</p>

             <h6 class="text-dark"><i class="mdi mdi-link"></i> ওয়েবসাইট :</h6>
             <p>http://narsingdi.gobazaar.com.bd</p>
             <div class="footer-social">
                <a href="javascript:void(0)"><i class="mdi mdi-facebook"></i></a>
                <a href="javascript:void(0)"><i class="mdi mdi-twitter"></i></a>
                <a href="javascript:void(0)"><i class="mdi mdi-instagram"></i></a>
                <a href="javascript:void(0)"><i class="mdi mdi-google"></i></a>
             </div>
          </div>
          <div class="col-lg-8 col-md-8">
            <div class="row">
                {{-- <div class="col-lg-12 col-md-12 section-title text-left mb-4">
                   <h2>Contact Us</h2>
                </div> --}}
                <form class="col-lg-12 col-md-12" name="sentMessage" id="contactForm" action="javascript:void(0)" method="POST">

                   <div class="control-group form-group">
                      <div class="controls">
                         <label>নাম <span class="text-danger">*</span></label>
                         <input type="text" placeholder="নাম" class="form-control" id="full_name" name="name">
                         <p class="help-block"></p>
                      </div>
                   </div>
                   <div class="row">
                      <div class="control-group form-group col-md-6">
                         <label>মোবাইল নম্বর <span class="text-danger">*</span></label>
                         <div class="controls">
                            <input type="tel" placeholder="মোবাইল নম্বর" class="form-control" id="phone" name="phone">
                         </div>
                      </div>
                      <div class="control-group form-group col-md-6">
                         <div class="controls">
                            <label>ইমেইল <span class="text-danger">*</span></label>
                            <input type="email" placeholder="ইমেইল" class="form-control" id="email" name="email">
                         </div>
                      </div>
                   </div>
                  
                   <div class="control-group form-group">
                      <div class="controls">
                         <label>মেসেজ <span class="text-danger">*</span></label>
                         <textarea rows="4" cols="100" placeholder="মেসেজ"  class="form-control" id="message" name="message" style="resize:none"></textarea>
                      </div>
                   </div>
                   <div id="success"></div>
                   <!-- For success/fail messages -->
                   <button type="submit" class="btn btn-success" id="submit_btn">সাবমিট</button>
                </form>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- End Contact Us -->
@endsection

@section('js')
<script type="text/javascript">
  
$(document).ready(function(){
    $(document).on('submit','#contactForm',function(e){
         e.preventDefault();
        var parameter = {
            'full_name'     : 'required',
            'phone' : 'required',
            'email'      : 'required',
            'message' : 'required',
        };
        var validate = validation(parameter);
        if(validate == false){
              $('#submit_btn').html('Submitting your message');
                $.ajax({
                    url: "{{route('contact.store')}}" ,
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function( response ) {
                       if(response.status = 'success'){
                        $('#submit_btn').html('Send Message');
                         swal({
                          title: "Success",
                              text: response.message,
                              type: response.status,
                              showConfirmButton: true,
                              confirmButtonText: 'OK',
                              closeOnConfirm: true,
                          });
                         document.getElementById("contactForm").reset();
                       }
                    }
                });
        }
        else{
            return false;
        }
    });
});

</script>
@endsection
