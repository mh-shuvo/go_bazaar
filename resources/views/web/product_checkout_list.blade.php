@extends("layouts.web")
@section("title","পণ্যের তালিকা")
@section("content")

      <section class="checkout-page section-padding">
        <form action="javascript:void(0)" id="OrderForm" method="post" enctype="multipart/form-data">
        <div class="container">
           <div class="row">
              <div class="col-md-8">
                 <div class="checkout-step">
                    <div class="accordion" id="accordionExample">
                       <div class="card checkout-step-one">
                          <div class="card-header" id="headingOne">
                             <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <span class="number">1</span> ফোন নাম্বার ভেরিফিকেশন
                                </button>
                             </h5>
                          </div>
                          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                             <div class="card-body">
                                <p>আপনার মোবাইলে আপনার অর্ডার সম্পর্কে আপডেট জানানো হবে।</p>
                              {{--   <form> --}}
                                   <div class="form-row align-items-center">
                                      <div class="col-auto" id="sms_send_section">
                                         <label class="sr-only">মোবাইল</label>
                                         <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                               <div class="input-group-text"><span class="mdi mdi-cellphone-iphone"></span></div>
                                            </div>
                                            <input type="text" id="verification_phone_number" class="form-control" placeholder="Enter phone number" value="{{ session('mobile')}}" readonly=""> <br>
                                         </div>
                                      </div>
                                       <div class="col-auto d-none" id="sms_verify_section">
                                         <label class="sr-only">OTP Code</label>
                                         <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                               <div class="input-group-text"><span class="mdi mdi-cellphone-iphone"></span></div>
                                            </div>
                                            <input type="text" id="otp_code" class="form-control" placeholder="ভেরিফিকেশন কোড প্রদান করুন"> <br>
                                         </div>
                                      </div>
                                      <div class="col-auto">
                                         {{-- <button type="button" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="btn btn-secondary mb-2 btn-lg">Verification</button> --}}
                                         <button type="button" type="button" id="verified_btn" class="btn btn-primary mb-2 btn-lg d-none">ভেরিফাই</button>

                                         <button type="button" type="button" id="verification_btn" class="btn btn-secondary mb-2 btn-lg">ভেরিফিকেশন</button>

                                         <button type="button" type="button" id="resend_btn" class="btn btn-secondary mb-2 btn-lg d-none">60</button>
                                      </div>
                                   </div>
                               {{--  </form> --}}
                             </div>
                          </div>
                       </div>
                       <div class="card checkout-step-two">
                          <div class="card-header" id="headingTwo">
                             <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseTwo" id="delivery_address_collapse_btn">
                                <span class="number">2</span> ডেলিভারি ঠিকানা
                                </button>
                             </h5>
                          </div>
                          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                             <div class="card-body">
                               {{--  <form> --}}
                                   <div class="row">
                                      <div class="col-sm-6">
                                         <div class="form-group">
                                            <label class="control-label"> নাম <span class="required">*</span></label>
                                            <p class="form-control"> {{ session('name')}} </p>
                                         </div>
                                      </div>
                                      <div class="col-sm-6">
                                         <div class="form-group">
                                            <label class="control-label">মোবাইল <span class="required">*</span></label>
                                            <p class="form-control">{{ session('mobile')}} </p>
                                            <input type="hidden" name="mobile" value="{{ session('mobile')}}">
                                         </div>
                                      </div>
                                   </div>

                                   <div class="row">
                                      <div class="col-sm-6">
                                         <div class="form-group">
                                            <label class="control-label">উপজেলা <span class="required">*</span></label>
                                           <select type='text' class="form-control" id="district_id" name="district_id" onchange="get_location(this.value, 3, 'upazila_id')"  parsley-trigger="change" required >
                                              <option value="">সিলেক্ট </option>

                                              @foreach($upazila_list as $item)
                                              <option value="{{ $item->id }}" {{ (old('upazila_id') == $item->id) ? 'selected="selected"' : '' }}>{{ $item->en_name }}</option>
                                              @endforeach

                                          </select>
                                                   </div>
                                      </div>
                                      <div class="col-sm-6">
                                         <div class="form-group">
                                            <label class="control-label">ইউনিয়ন <span class="required">*</span></label>
                                            <select type='text'  class="form-control" id="upazila_id" name="upazila_id"  parsley-trigger="change" required >
                                                <option value=""> সিলেক্ট </option>
                                            </select>
                                         </div>
                                      </div>
                                   </div>

                                   <div class="row">
                                      <div class="col-sm-12">
                                         <div class="form-group">
                                            <label class="control-label">শিপিং এড্রেস <span class="required">*</span></label>
                                            <textarea name="shipping_address" class="form-control border-form-control"></textarea>
                                            <small class="text-danger">আপনার নাম্বার এবং ঠিকানা দিন</small>
                                         </div>
                                      </div>
                                   </div>

                                  <button type="button" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg">পরবর্তী</button>
                                {{-- </form>
 --}}                             </div>
                          </div>
                       </div>

                       <div class="card">
                          <div class="card-header" id="headingThree">
                             <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapseThree" id="payment_collapse_btn">
                                <span class="number">3</span> পেমেন্ট
                                </button>
                             </h5>
                          </div>
                          
                          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                             <div class="card-body">

                               {{--  <form class="col-lg-12 col-md-12 mx-auto"> --}}

                                   <div class="row">
                                      <div class="col-sm-2">
                                      <div class="custom-control custom-radio">
                                        <input type="radio" id="payment_method1" name="payment_method" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="payment_method1"> ক্যাশ </label>

                                       </div>
                                      </div>
                                      <div class="col-sm-2">
                                    <div class="custom-control custom-radio">

                                      <input type="radio" id="payment_method2" name="payment_method" class="custom-control-input" value="2">
                                      <label class="custom-control-label" for="payment_method2">বিকাশ</label>
                                   </div>
                                    </div>

                                   </div><br>
                                     <button type="submit" id="form_submit_btn" class="btn btn-secondary mb-2 btn-lg">পরবর্তী</button>
                                 {{--   <button type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour" class="btn btn-secondary mb-2 btn-lg">NEXT</button> --}}
                               {{--  </form>--}}
                             </div>
                          </div>
                       </div>
                       <div class="card">
                          <div class="card-header" id="headingThree">
                             <h5 class="mb-0">
                                <button class="btn btn-link collapsed" id="order_complete_collapse_btn" type="button" data-toggle="collapse" data-target="" aria-expanded="false" aria-controls="collapsefour">
                                <span class="number">4</span> অর্ডার সম্পন্ন
                                </button>
                             </h5>
                          </div>
                          <div id="collapsefour" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                             <div class="card-body">
                                <div class="text-center">
                                   <div class="col-lg-10 col-md-10 mx-auto order-done">
                                      <i class="mdi mdi-check-circle-outline text-secondary"></i>
                                      <h4 class="text-success">ধন্যবাদ ! আপনার অর্ডারটি সম্পন্ন হয়ছে।</h4>
                                     
                                   </div>
                                   <div class="text-center">
                                      <a href="{{ route('index') }}" class="btn btn-secondary mb-2 btn-lg">আরো ক্রয় করুন</a>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
              <div class="col-md-4 ">
                 <div class="card">
                    <h5 class="card-header">কার্ট লিষ্ট <span class="text-secondary float-right total_product">({{ converter::en2bn(count($products_data)) }} টি)</span></h5>
                    <div class="card-body pt-0 pr-0 pl-0 pb-0 ">
                      @php $subtotal = 0;$net_amount = 0;$discount = 0; @endphp
                      @foreach($products_data as $item)

                       <div class="cart-list-product">
                          <img style="height: 100px !important;" class="checkout_product_img" src="{{ asset('public/upload/product')}}/{{ $item['picture']}}" alt="">

                          <h5><a style="color: #69bf53" href="javascript:void(0)">{{$item['product_name']}}</a></h5>

                          <h6 style="font-size: 13px; padding-top: 5px">
                            <a href="javascript:void(0)">{{ $item['shop_name'] }}<br>
                            <span style="font-size: 11px; color: #888"> {{ $item['supplier_address'] }}</span>
                          </a>
                          </h6>

                          <p class="offer-price mb-0">৳ 
                            <!-- offer calculation -->
                            @php 
                             $price = 0;
                              $total_product_price = 0;
                                            if($item['offer_id']!=null){
                                                if($item['offer_type'] == 1){
                                                    $price = $item['rate'] - $item['offer_amount'];
                                                }
                                                else{
                                                    $price = $item['rate'] - (($item['rate'] * $item['offer_amount'])/100);
                                                }
                                            }
                                            else{
                                                $price = $item['rate'];
                                            }

                                            $total_product_price = $price * $item['quantity'];
                                            $subtotal+= $item['rate']* $item['quantity'];
                                            $net_amount+=$total_product_price;
                                            $discount+=$subtotal - $net_amount;

                                            echo converter::en2bn(number_format($total_product_price,2));
                            @endphp
                          </p>
                       </div>

                       @endforeach

                       <div class="cart-list-product">
                       
                          <h5><a href="javascript:void(0)"> মোট &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; : <span id="calculation">৳ {{ converter::en2bn(number_format($subtotal,2)) }}</span></a></h5>
                          <h5><a href="javascript:void(0)"> ছাড় &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; : <span id="calculation">৳ {{ converter::en2bn(number_format($discount,2)) }}</span> </a></h5>

                          <p class="offer-price mb-0"> সর্বমোট &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; : <span id="calculation">৳ {{ converter::en2bn(number_format($net_amount,2)) }}</span>  </p>
                       </div>

                    </div>
                 </div>
              </div>
           </div>
        </div>
      </form>
     </section>
@endsection

@section('js')

<script type="text/javascript">

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

$(document).on('click','#verification_btn',function(){
    let phone = $("#verification_phone_number").val() || 0;
    if(phone != 0){
        $.ajax({
          url:'{{ route('web.sms_send') }}',
          method: "POST",
          data:{
            phone: phone
          },
          success:function(res){
            if(res.status == 'success'){

              $("#verification_btn").addClass('d-none');
              $("#verification_btn").attr('disable',true);
              $("#sms_send_section").addClass('d-none');
              $("#sms_verify_section").removeClass('d-none');
              $("#resend_btn").removeClass('d-none');
              $("#verified_btn").removeClass('d-none');
              let exipired_time = 60;

            var x = setInterval(function(){
                $("#resend_btn").html(exipired_time);
                exipired_time--;
                if(exipired_time == 0){
                  clearInterval(x);
                  $("#verification_btn").removeClass('d-none');
                  $("#sms_send_section").removeClass('d-none');
                  $("#sms_verify_section").addClass('d-none');
                  $("#resend_btn").addClass('d-none');
                  $("#verified_btn").addClass('d-none');
                }
              }, 1000);
            }
          }
        });
    }
    else{
      alert("Fill out Phone number");
    }
 });

$(document).on("click","#verified_btn",function(){
  let otp = $("#otp_code").val() || 0;

  if(otp != 0){
    $.ajax({
          url:'{{ route('web.check_otp') }}',
          method: "POST",
          data:{
            otp: otp
          },
          success:function(res){
            if(res.status == 'success'){
                $("#verification_btn").addClass('d-none');
                $("#sms_send_section").removeClass('d-none');
                $("#sms_verify_section").addClass('d-none');
                $("#resend_btn").addClass('d-none');
                $("#verified_btn").addClass('d-none');

                 // Show Delivery Address
                $("#delivery_address_collapse_btn").attr('data-target','#collapseTwo');
                $("#delivery_address_collapse_btn").slideDown().removeClass('collapsed');
                $('#collapseTwo').addClass('show');
            }
            else{
              alert(res.message);
              $("#verification_btn").removeClass('d-none');
                $("#sms_send_section").removeClass('d-none');
                $("#sms_verify_section").addClass('d-none');
                $("#resend_btn").addClass('d-none');
                $("#verified_btn").addClass('d-none');


            }
          }
      });
  }
  else{
    alert("Fill OTP Code");
  }
});

$(document).on('submit','#OrderForm',function(e){
     e.preventDefault();
     var formData = new FormData(this);
            $.ajax({
                url: "{{ route('product.order') }}",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function( response ) {

                  $("#cart_product_total_sidebar").html('0');
                  $("#sub_total").html('0');
                  $("#net_amount").html('0');
                  $("#cart_product_total").html('0');


                  $(".total_product").html('0');
                  $(".cart-list-product").html('');
                  $("#calculation").html('0');
                  $("#form_submit_btn").attr('disabled',true);
                  $('#OrderForm input').val('');

                $("#delivery_address_collapse_btn").attr('data-target','#collapseTwo');
                $("#payment_collapse_btn").attr('data-target','#collapseThree');
                $("#order_complete_collapse_btn").attr('data-target','#collapsefour');
                $("#order_complete_collapse_btn").slideDown().removeClass('collapsed');
                $('#collapseThree').removeClass('show');
                $('#collapsefour').addClass('show');

                }
            });
});
</script>
@endsection
