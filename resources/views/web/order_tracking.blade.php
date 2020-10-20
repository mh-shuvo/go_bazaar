@extends("layouts.web")
@section("title","Order Tracking")
@section("content")

      <section class="shop-single section-padding pt-3">
         <div class="container">
           <div class="row">
              <div class="col-lg-11 mx-auto">
                 <div class="row no-gutters">
                    @include("web.customer_menu")
                     <div class="col-md-9">
                        <div class="card card-body account-right">
                            <div class="widget">
                               <div class="section-header">
                                  <h5 class="heading-design-h5">
                                     অর্ডার ট্র্যাকিং
                                  </h5>
                               </div>

                               <hr class="mt-3 mb-3">

                               <div class="row">
                                 <div class="col-sm-6 offset-sm-3">
                                    <div class="input-group">
                                      <div class="custom-file">
                                        <input type="text" name="order_id" id="order_id" placeholder="আপনার অর্ডার আইডি লিখুন" class="form-control">
                                      </div>
                                      <div class="input-group-append">
                                        <button class="btn btn-info search_order" type="button">খুজুন</button>
                                      </div>
                                    </div>
                                 </div>
                               </div>

                               <div class="row mt-5">
                                 <div class="col-sm-12 table-responsive">
                                   <table class="table table-bordered order-table d-none">
                                    <thead>
                                      <tr>
                                        <th width="60%">সরবরাহকারীর নাম ও পন্যসমূহ</th>
                                        <th width="40%">স্ট্যাটাস</th>
                                      </tr>
                                    </thead>
                                    <tbody></tbody>
                                   </table>
                                 </div>
                               </div>

                            </div>
                         </div>
                    </div>
                   
                 </div>
              </div>
           </div>
        </div>
      </section>

@endsection

@section('js')
<script type="text/javascript">
  $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

  $(document).on('click','.search_order',function(){    
    let id = $("#order_id").val() || 0;
    if(id != 0){
      $.ajax({
        url:"{{route('order_tracking_data')}}",
        dataType:"JSON",
        data:{
          order_id: id
        },
        method:"POST",
        success:function(res){
          if(res.status == 'success'){
            $('.order-table tbody').html('');
            let htmldata = htmlGenerate(res.data);
             $('.order-table tbody').html(htmldata);   
            $('.order-table').removeClass('d-none');   
          }else{
            swal('কিছু ভুল হয়েছে',res.message,res.status);   
            $('.order-table tbody').html('');   
            $('.order-table').addClass('d-none');   
          }
        }
      });
    }else{
      swal('আবশ্যক','অর্ডার আইডি আবশ্যক','error');
    }
  });

  function htmlGenerate(data){
    var html='';
    data.forEach(function(item){
       html+='<tr>';
       let product = '';
       item.products.forEach(function(obj){
        product+='<label class="badge badge-info">'+obj+'</label> ';
       });
       html+='<td>';
       html+='<h5>'+item.supplier+'</h5>'+product;
       html+='</td>';

       let status='';
       if(item.status == 1){
        status+='<label class="text-primary">আপনার অর্ডারটি এখনো গৃহীত হয়নি।</label>';
       }
       else if(item.status == 2){
        status+='<label class="text-success">আপনার অর্ডারটি গৃহীত হয়েছে, ডেলিভারির জন্য অপেক্ষমান আছে।</label>';
       }
       else if(item.status == 3){
        status+='<label class="text-danger">আপনার অর্ডারটি সরবরাহকারীর দ্বারা রিজেক্ট হয়েছে।</label>';
       }
       else if(item.status == 4){
        status+='<label class="text-danger">আপনার অর্ডারটি প্রক্রিয়াধীন রয়েছে।</label>';
       }
       else{
        status+='<label class="text-info">আপনার অর্ডারটি আপনার ঠিকানায় পাঠিয়ে দেয়া হয়েছে। আগামি ২৪ ঘন্টার মধ্যে পেয়ে যাবেন।</label>';
       }
       html+='<td>'+status;
       html+='</td>';

       html+='</tr>';
    });
    return html;
  }
</script>
@endsection

