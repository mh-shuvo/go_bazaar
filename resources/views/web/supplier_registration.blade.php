@extends("layouts.web")
@section("title","বিক্রেতা রেজিস্ট্রেশন")
@section("content")
<!-- Inner Header -->
<section class="section-padding bg-dark inner-header">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="mt-0 mb-3 text-white">বিক্রেতা রেজিস্ট্রেশন</h1>
                <div class="breadcrumbs">
                    <p class="mb-0 text-white"><a class="text-white" href="javascript:void(0)">হোম</a> / <span class="text-success">বিক্রেতা রেজিস্ট্রেশন</span></p>
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
                <img src="{{ asset('public/web/img/cart.jpg') }}" style="height:100%; width: 100%;" class="img-responsive">
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 section-title text-left mb-4">
                        <h2>বিক্রেতা রেজিস্ট্রেশন</h2>
                    </div>
                <form class="col-lg-12 col-md-12" action="javascript:void(0)" method="POST" id="supplierRegistrationForm" >
                  {{-- onsubmit="form_validation()" --}}
                   <div class="row">
                      <div class="control-group form-group col-md-6">
                         <label>উপজেলা <span class="text-danger">*</span></label>
                         <div class="controls">
                            <select class="form-control" id="upazila_id" name="upazila_id"  onchange="get_location(this.value, 2, 'union_id')">
                              <option value="">উপজেলা নির্বাচন করুন</option>

                              @foreach($upazila_list as $item)
                              <option value="{{ $item->id }}">{{ $item->name }}</option>
                              @endforeach

                            </select>
                            <span class="text-danger" id="upazila_id_error"></span>
                         </div>
                      </div>
                      <div class="control-group form-group col-md-6">
                         <div class="controls">
                            <label>ইউনিয়ন <span class="text-danger">*</span></label>
                            <select class="form-control" id="union_id" name="union_id" >
                               <option value="">ইউনিয়ন নির্বাচন করুন</option>
                            </select>
                            <span class="text-danger" id="union_id_error"></span>
                         </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="control-group form-group col-md-6">
                         <label>ব্যবসায়ের প্রতিষ্ঠানের ধরণ <span class="text-danger">*</span></label>
                         <div class="controls">
                            <select class="form-control" id="supplier_type" name="supplier_type" >
                              <option value="">ব্যবসায়ের প্রতিষ্ঠানের ধরণ নির্বাচন করুন</option>

                              @foreach($supplier_types as $item)
                              <option value="{{ $item->id }}">{{ $item->name }}</option>
                              @endforeach
                            </select>
                            <span class="text-danger" id="supplier_type_error"></span>
                         </div>
                      </div>
                      <div class="control-group form-group col-md-6">
                         <div class="controls">
                            <label>দোকানের নাম<span class="text-danger">*</span></label>
                            <input type="text" placeholder="দোকানের নাম" class="form-control" id="shop_name" name="shop_name" >
                            <span class="text-danger" id="shop_name_error"></span>
                         </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="control-group form-group col-md-6">
                         <label>বিক্রেতার নাম <span class="text-danger">*</span></label>
                         <div class="controls">
                            <input type="text" placeholder="বিক্রেতার নাম" class="form-control" id="supplier_name" name="supplier_name" >
                            <span class="text-danger" id="supplier_name_error"></span>
                         </div>
                      </div>
                      <div class="control-group form-group col-md-6">
                         <div class="controls">
                            <label>ফোন নম্বর <span class="text-danger">*</span></label>
                            <input type="text" placeholder="ফোন নম্বর" class="form-control" id="mobiles" name="mobile" >
                            <span class="text-danger" id="mobile_error"></span>
                         </div>
                      </div>
                   </div>
                    <div class="row">
                      <div class="control-group form-group col-md-6">
                        <div class="controls">
                           <label>জাতীয় পরিচয় পত্র নম্বর <span class="text-danger">*</span></label>
                           <input type="text" placeholder="জাতীয় পরিচয় পত্র নম্বর" class="form-control" id="nid" name="nid">
                           <p class="help-block"></p>
                           <span class="text-danger" id="nid_error"></span>
                        </div>
                      </div>
                      <div class="control-group form-group col-md-6">
                        <div class="controls">
                           <label>ট্রেড লাইসেন্স এর নম্বর:<span class="text-danger">*</span></label>
                           <input type="text" placeholder="ট্রেড লাইসেন্স এর নম্বর দিন" class="form-control" id="trade_id" name="trade_id">
                           <p class="help-block"></p>
                           <span class="text-danger" id="trade_id_error"></span>
                        </div>
                      </div>
                    </div>        
                   <div class="control-group form-group">
                      <div class="controls">
                         <label>ইমেইল </label>
                         <input type="email" placeholder="ইমেইল" class="form-control" id="email" name="email" >
                         <p class="help-block"></p>
                         <span class="text-danger" id="email_error"></span>
                      </div>
                   </div>
                   <div class="control-group form-group">
                      <div class="controls">
                         <label>ঠিকানা <span class="text-danger">*</span></label>
                         <textarea rows="4" cols="100" placeholder="দোকানের ঠিকানা লিখুন"  class="form-control" id="address" name="address"  maxlength="999" style="resize:none"></textarea>
                         <span class="text-danger" id="address_error"></span>
                      </div>
                   </div>
                   <div class="control-group form-group">
                      <div class="controls">
                         <label>দোকানের ছবি </label>
                       <input id="shop_image" class="form-control" type="file" name="shop_image" />
                        <img src="{{ asset('public/logo-black.png') }}" class="d-none" id="image_preview" style="height:70px; width:100px; border-radious: 5px;">
                         <p class="help-block"></p>
                         <span class="text-danger" id="email_error"></span>
                      </div>
                   </div>
                   
                    <div class="control-group form-group">
                      <div class="controls">
                         <label>ট্রেড লাইসেন্স এর ছবি </label>
                       <input id="trade_photo" class="form-control" type="file" name="trade_photo" />
                        <img src="{{ asset('public/logo-black.png') }}" class="d-none" id="trade_image_preview" style="height:70px; width:100px; border-radious: 5px;">
                         <p class="help-block"></p>
                         <span class="text-danger" id="trade_photo_error"></span>
                      </div>
                   </div>
                   <div id="success"></div>
                   <!-- For success/fail messages -->
                   <button type="submit" class="btn btn-success">সাবমিট</button>
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
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('submit', "#supplierRegistrationForm", function(e) {


            var status = form_validation();
            // var status = false;


            if (status == false) {

                e.preventDefault();
                $.ajax({
                    url: "{{ route('web.supplier.registration') }}"
                    , type: "POST"
                    , data: new FormData(this)
                    , processData: false
                    , contentType: false
                    , success: function(response) {

                        // console.log(response);



                        //if laravel validation error
                        if (response.errors) {

                            if (response.errors.upazila_id) {
                                $('#upazila_id_error').html(response.errors.upazila_id[0]);
                            } else {
                                $('#upazila_id_error').html('');
                            }

                            if (response.errors.union_id) {
                                $('#union_id_error').html(response.errors.union_id[0]);
                            } else {
                                $('#union_id_error').html('');
                            }

                            if (response.errors.supplier_type) {
                                $('#supplier_type_error').html(response.errors.supplier_type[0]);
                            } else {
                                $('#supplier_type_error').html('');
                            }

                            if (response.errors.mobile) {
                                $('#mobile_error').html(response.errors.mobile[0]);
                            } else {
                                $('#mobile_error').html('');
                            }

                            if (response.errors.address) {
                                $('#address_error').html(response.errors.address[0]);
                            } else {
                                $('#address_error').html('');
                            }

                            if (response.errors.shop_name) {
                                $('#shop_name_error').html(response.errors.shop_name[0]);
                            } else {
                                $('#shop_name_error').html('');
                            }
                    if(response.errors.nid){
                        $( '#nid_error').html( response.errors.nid[0] );
                    }else{
                        $( '#nid_error').html('');
                    }

                            if (response.errors.supplier_name) {
                                $('#supplier_name_error').html(response.errors.supplier_name[0]);
                            } else {
                                $('#supplier_name_error').html('');
                            }



                        }

                        if (response.status == 'success') {
                            reset();
                            swal(response.status, response.msg, response.status)
                        }


                        // swal(response.status,response.msg,response.status)
                        // reset();
                    }
                });

            } else {

                return false;
            }

        });

        function reset() {
            $('#supplierRegistrationForm')[0].reset();
            $("#upazila_id").val('');
            $("#union_id").val('');
            $("#supplier_type").val('');

            // error reset
            $("#upazila_id_error").html('');
            $("#union_id_error").html('');
            $("#supplier_type_error").html('');
            $("#supplier_name_error").html('');
            $("#shop_name_error").html('');
            $("#mobile_error").html('');
            $("#address_error").html('');
            $("#image_preview").addClass('d-none');
            $("#trade_image_preview").addClass('d-none');

        }


    });


    //form validation
    function form_validation() {

        var error_status = false;
      var upazila_id = $('#upazila_id').val();
      var union_id = $('#union_id').val();
      var supplier_type = $('#supplier_type').val();
      var shop_name = $('#shop_name').val();
      var supplier_name = $('#supplier_name').val();
      var mobile = $('#mobiles').val();
      var email = $('#email').val();
      var address = $('#address').val();
      var nid = $('#nid').val();
      var trade_id = $("#trade_id").val();

        if (upazila_id == '') {
            $('#upazila_id_error').html('উপজেলা সিলেক্ট করুন');
            error_status = true;
        } else {

            $('#upazila_id_error').html('');


        }

        if (union_id == '') {

            $('#union_id_error').html('ইউনিয়ন সিলেক্ট করুন');
            error_status = true;

        } else {

            $('#union_id_error').html('');


        }

        if (supplier_type == '') {

            $('#supplier_type_error').html('ব্যবসায়ের প্রতিষ্ঠানের ধরন দিন.');
            error_status = true;

        } else {

            $('#supplier_type_error').html('');


        }



        if (shop_name == '') {

            $('#shop_name_error').html('দোকানের নাম দিন');
            error_status = true;

        } else {

            $('#shop_name_error').html('');
        }

        if (trade_id == '') {

            $('#trade_id_error').html('ট্রেড লাইসেন্স এর নম্বর দিন');
            error_status = true;

        } else {

            $('#trade_id_error').html('');
        }


        if (supplier_name == '') {

            $('#supplier_name_error').html('বিক্রেতার নাম দিন');
            error_status = true;

        } else {

            $('#supplier_name_error').html('');


        }

        if (address == '') {
            $('#address_error').html('ঠিকানা দিন');
            error_status = true;

        } else {

            $('#address_error').html('');


        }  
      if(nid == ''){

          $('#nid_error').html('জাতীয় পরিচয় পত্র নম্বর দিন');
          error_status = true;

      }else{

         $('#nid_error').html('');
       

      }

        if (mobile == '') {

            $('#mobile_error').html('মোবাইল নাম্বার দিন');
            error_status = true;


        } else {

            if (mobile.length == 11) {

                if (mobile_number_validate(mobile)) {

                    $('#mobile_error').html('');


                } else {

                    $('#mobile_error').html('সঠিক নাম্বার দিন');

                    error_status = true;

                }

            } else {

                $('#mobile_error').html('সঠিক নাম্বার দিন');

                error_status = true;

            }


        }



        return error_status;

    }


    //bd phone number validation
    function mobile_number_validate(mobile) {


        // var bd_rgx = /\+?(88)?0?1[56789][0-9]{8}\b/;
        var bd_rgx = /\+?(88)?0?1[3256789][0-9]{8}\b/;


        if (mobile.match(bd_rgx)) {

            return true;

        } else {

            return false;
        }

    }

    $(document).on('change', '#shop_image', function() {
        $('#image_preview').addClass('d-none');
        readURL(this, '#image_preview');
        $('#image_preview').removeClass('d-none');
    });

    $(document).on('change','#trade_photo',function(){
    $('#trade_image_preview').addClass('d-none');
     readURL(this,'#trade_image_preview');
     $('#trade_image_preview').removeClass('d-none');
});


</script>
@endsection
